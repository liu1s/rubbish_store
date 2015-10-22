
<?php
/**
 * Class APF_DB_MysqlAccessor
 */
class APF_DB_MysqlAccessor  {
    const OP_GREATER_THAN = '>';
    const OP_LESS_THAN = '<';
    const OP_EQUAL = '=';
    const OP_IN = 'in';

    public static $del_cache = false;
    public static $auto_commit = true;
    public static $last_sql = null;
    public static $splitSuffix = '';

    public static $connections = array();
    public $connection = null;

    public $read_only = false;
    public $force_master = false;

    private $model_name;
    private $cache_enable;
    private $sql;
    private $mapping;
    private $filter_names = array();
    private $filter_ops = array();
    private $filter_values = array();
    private $distinct = false;
    private $load_fields = array();
    private $sorts = array();
    private $set_fields = array();
    private $pdo_values = array();
    private $limit = null;
    private $offset = null;
    private $user_define_where = '';
    private $user_define_values = array();
    private $user_define_flag = false;

    public static function get_last_sql()
    {
        return self::$last_sql;
    }

    public static function get_instance()
    {
        return new self();
    }

    public static function use_model($className, $splitSuffix = '')
    {
        self::$splitSuffix = $splitSuffix;
        $dataAccessor = static::get_instance();
        $dataAccessor->model_name = $className;
        $dataAccessor->cache_enable = $className::cache_enable();
        $dataAccessor->mapping = $className::get_mapping(self::$splitSuffix);
        if (!isset($dataAccessor->mapping['key'])) throw new Exception('no primary key', '001');
        return $dataAccessor;
    }

    public static function begin_transaction()
    {
        self::$auto_commit = false;
    }

    public static function commit()
    {
        foreach (self::$connections as $con_name => $con)
            $con->commit();
    }

    public static function roll_back()
    {
        foreach (self::$connections as $con_name => $con)
            $con->rollBack();
    }

    public function force_master()
    {
        $this->force_master = true;
        return $this;
    }

    public function native_sql($sql, $values, $read_only = true)
    {
        $this->read_only = $read_only;
        $className = $this->model_name;
        $this->connection = $this->get_connection($className::get_pdo_name($this));
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($values);
        if ($read_only)
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $stmt->rowCount();
    }

    public function filter($name, $value)
    {
        $op = self::OP_EQUAL;
        if (is_array($value)) {
            if (empty($value))
                throw new Exception('values can not be empty array', '005');
            $op = self::OP_IN;
        }
        return $this->filter_by_op($name, $op, $value);
    }

    public function filter_by_op($name, $op, $value)
    {
        $this->filter_names[] = $name;
        $this->filter_ops[] = $op;
        $this->filter_values[] = $value;
        return $this;
    }

    public function distinct()
    {
        $this->distinct = true;

        return $this;
    }

    public function load_field($fieldNames)
    {
        if (!is_array($fieldNames))
            $fieldNames = array($fieldNames);
        $this->load_fields = array_merge($this->load_fields, $fieldNames);
        return $this;
    }

    public function user_define_where($sqlWhere, $values)
    {
        $this->user_define_where = $sqlWhere;
        $this->user_define_values = $values;
        $this->user_define_flag = true;
        return $this;
    }

    public function sort($name, $type = 'desc')
    {
        $this->sorts[$name] = $type;
        return $this;
    }

    public function offset($offset)
    {
        $this->offset = $offset;
        return $this;
    }

    public function limit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    public function set_field($name, $value)
    {
        $this->set_fields[$name] = $value;
        return $this;
    }

    public function find()
    {
        return $this->find_all();
    }

    public function find_all()
    {
        $this->read_only = true;
        $fields = '*';
        if (count($this->load_fields)) {
            $fields = '';
            foreach ($this->load_fields as $field)
                $fields .= " `{$this->mapping_to_db($field)}` ,";
            $fields = rtrim($fields, ',');
        }

        // Distinct
        if ($this->distinct) {
            $fields = 'distinct ' . $fields;
        }

        $this->sql = "select {$fields} from `{$this->mapping['table']}` ";
        $this->append_where();
        $this->append_order();
        $this->append_limit();
        return $this->init_objs($this->execute_read()->fetchAll(PDO::FETCH_ASSOC));
    }

    /**
     * @deprecated
     */
    public function find_one()
    {
        $rs = $this->limit(1)->find();
        if (empty($rs)) {
            throw new Exception('no obj found', '003');
        }
        return array_pop($rs);
    }

    /**
     * 获取单条记录（取代find_one()）
     */
    public function find_only()
    {
        $result = null;

        $rs = $this->limit(1)->find();
        if (!empty($rs)) {
            $result = array_pop($rs);
        }

        return $result;
    }

    public function find_by_pk($id, $throw_exception = true)
    {
        //read cache
        if ($this->cache_enable && !self::$del_cache) {
            $obj = $this->get_cached_objs($id);
            if ($obj) {
                //APF::get_instance()->debug("load from row cache");
                return $obj;
            }
        }
        $rs = $this->limit(1)->find_by_pk_internal($id);
        if (!count($rs)) {
            if ($throw_exception === true) {
                throw new Exception('no obj loaded', '002');
            } else {
                return NULL;
            }
        }

        $rs = array_pop($rs);

        //write cache
        if ($this->cache_enable) {
            APF::get_instance()->debug("write row cache");
            $this->set_cached_objs($rs);
        }
        return $rs;
    }

    /**
     * 获取一条数组结果
     */
    public function get_row()
    {
        $result = $this->find_only();

        if (!is_null($result)) {
            $array_result = (array) $result;
            unset($array_result['splitSuffix']);
            unset($array_result['cache_expire']);
            unset($array_result['isLoaded']);

            $result = $array_result;
        }

        return $result;
    }

    /**
     * 获取全部数组结果
     */
    public function get_all()
    {
        $result = $this->find_all();

        if (!empty($result)) {
            $array_result = array();

            foreach ($result as $row) {
                $array_row = (array) $row;
                unset($array_row['splitSuffix']);
                unset($array_row['cache_expire']);
                unset($array_row['isLoaded']);

                $array_result[] = $array_row;
            }

            $result = $array_result;
        }

        return $result;
    }

    protected function set_cached_objs($objs)
    {
        $key = $this->get_primary_key_name();
        if (!is_array($objs)) $objs = array($objs);
        foreach ($objs as $obj) {
            $this->get_cache()->set(
                $this->get_cache()->get_obj_key($this->model_name, $obj->$key), $obj, 0, $obj->cache_expire
            );
        }
    }

    protected function get_cached_objs($ids)
    {
        return $this->get_cache()->get($this->add_prefix($ids));
    }

    protected function add_prefix($ids)
    {
        if (is_array($ids)) {
            foreach ($ids as $key => $id) $ids[$key] = $this->get_cache()->get_obj_key($this->model_name, $id);
        } else {
            $ids = $this->get_cache()->get_obj_key($this->model_name, $ids);
        }
        return $ids;
    }

    protected function get_cache()
    {
        return APF_Cache_Factory::get_instance()->get_memcache();
    }

    public function find_by_pks($ids)
    {
        $key = $this->get_primary_key_name();
        $mem_ids = $mem_objs = $mem_arr = $db_arr = $return = array();
        //read cache
        if ($this->cache_enable && !self::$del_cache) {
            $mem_objs = $this->get_cached_objs($ids);
            if ($mem_objs && (count($mem_objs) == count($ids))) {
                return $mem_objs;
            }
            foreach ($mem_objs as $mem_obj) {
                $mem_ids[] = $mem_obj->$key;
                $mem_arr[$mem_obj->$key] = $mem_obj;
            }
        }

        //read db
        $db_objs = $this->find_by_pk_internal(array_diff($ids, $mem_ids));
        foreach ($db_objs as $db_obj) {
            $db_arr[$db_obj->$key] = $db_obj;
        }

        //write cache
        if ($this->cache_enable) {
            $this->set_cached_objs($db_objs);
            APF::get_instance()->debug("write rows cache");
        }

        foreach ($ids as $id) {
            $return[] = isset($mem_arr[$id])
                ? $mem_arr[$id]
                : (isset($db_arr[$id]) ? $db_arr[$id] : null);
        }
        return $return;
    }

    private function find_by_pk_internal($ids)
    {
        $this->filter($this->mapping['key'], $ids);
        $rs = $this->find();
        return $rs;
    }

    public function update()
    {
        $this->sql = "update `{$this->mapping['table']}` ";
        $this->append_update();
        $this->append_where();
        return $this->execute_write();
    }

    public function insert($type = 'insert')
    {
        $this->sql = "{$type} into `{$this->mapping['table']}` ";
        $this->append_insert();
        return $this->execute_write();
    }

    public function replace()
    {
        return $this->insert('replace');
    }

    public function delete()
    {
        $this->sql = "delete from `{$this->mapping['table']}` ";
        $this->append_where();
        $this->append_limit();
        return $this->execute_write();
    }

    public function count()
    {
        $this->sql = "select count(1) from `{$this->mapping['table']}` ";
        $this->append_where();
        return $this->execute_read()->fetchColumn();
    }

    public function sum($field)
    {
        $this->sql = "select sum(`{$this->mapping_to_db($field)}`) from `{$this->mapping['table']}` ";
        $this->append_where();
        return $this->execute_read()->fetchColumn();
    }

    public function max($field)
    {
        $this->sql = "select max(`{$this->mapping_to_db($field)}`) from `{$this->mapping['table']}` ";
        $this->append_where();
        return $this->execute_read()->fetchColumn();
    }

    public function min($field)
    {
        $this->sql = "select min(`{$this->mapping_to_db($field)}`) from `{$this->mapping['table']}` ";
        $this->append_where();
        return $this->execute_read()->fetchColumn();
    }

    protected function init_objs($rs)
    {
        $objs = array();
        foreach ($rs as $r) {
            $obj = new $this->model_name(self::$splitSuffix);
            foreach ($this->mapping['columns'] as $objColumn => $dbColumn)
                $obj->$objColumn = isset($r[$dbColumn]) ? $r[$dbColumn] : null;
            $obj->isLoaded = true;
            $objs[] = $obj;
        }
        return $objs;
    }

    protected function execute_internal()
    {
        APF::get_instance()->debug("MysqlAccessor execute internal:" . $this->sql);
        $className = $this->model_name;
        $this->connection = $this->get_connection($className::get_pdo_name($this));
        $stmt = $this->connection->prepare($this->sql);
        self::$last_sql = $this->sql;
        APF::get_instance()->debug("MysqlAccessor pdo_values:" . print_r($this->pdo_values, true));
        $stmt->execute($this->pdo_values);
//        var_dump($this->sql, $this->pdo_values);
        return $stmt;
    }

    protected function get_connection($con_name)
    {
        if (array_key_exists($con_name, self::$connections)) {
            return self::$connections[$con_name];
        }
        apf_require_class("APF_DB_Factory");
        $con = APF_DB_Factory::get_instance()->get_pdo($con_name);
        if (self::$auto_commit === false) {
            $con->beginTransaction();
        }
        self::$connections[$con_name] = $con;
        return $con;
    }

    protected function execute_write()
    {
        return $this->execute_internal()->rowCount();
    }

    protected function execute_read()
    {
        return $this->execute_internal();
    }

    protected function append_where()
    {
        if ((!count($this->filter_names) || !count($this->filter_ops) || !count($this->filter_values)) && !$this->user_define_flag)
            return false;
        $this->sql .= 'where ';
        $tmp = array();
        foreach ($this->filter_names as $k => $name) {
            if (is_array($this->filter_values[$k])) {
                $valueString = implode(' , ', array_fill(0, count($this->filter_values[$k]), '?'));
                $tmp[] = "`{$this->mapping_to_db($name)}` {$this->filter_ops[$k]} ( {$valueString} ) ";
                $this->pdo_values = array_merge($this->pdo_values, $this->filter_values[$k]);
            } else {
                $tmp[] = "`{$this->mapping_to_db($name)}` {$this->filter_ops[$k]} ? ";
                $this->pdo_values[] = $this->filter_values[$k];
            }
        }
        //user define where
        if ($this->user_define_flag) {
            $tmp[] = '(' . $this->user_define_where . ')';
            $this->pdo_values = array_merge($this->pdo_values, $this->user_define_values);
        }
        $this->sql .= implode(' and ', $tmp) . ' ';
    }

    protected function append_order()
    {
        if (!count($this->sorts))
            return false;
        $this->sql .= 'order by ';
        $tmp = array();
        foreach ($this->sorts as $k => $t)
            $tmp[] = "`{$this->mapping_to_db($k)}` {$t}";
        // mysql关键字导致sql执行出错 fixed bug by Lukin
        $this->sql .= implode(' , ', $tmp) . ' ';
    }

    protected function append_limit()
    {
        if (is_null($this->limit))
            return false;
        $offset = is_null($this->offset) ? '' : $this->offset . ',';
        $this->sql .= "limit {$offset}{$this->limit}";
    }

    protected function append_update()
    {
        $this->sql .= 'set ';
        $tmp = array();
        foreach ($this->set_fields as $k => $v)
            $tmp[] = "`{$this->mapping_to_db($k)}` = ?";
        $this->sql .= implode(' , ', $tmp) . ' ';
        $this->pdo_values = array_merge($this->pdo_values, array_values($this->set_fields));
    }

    protected function append_insert()
    {
        $columns = array();
        $values = array();
        foreach ($this->set_fields as $k => $v) {
            $columns[] = "`{$this->mapping_to_db($k)}`";
            $values[] = '?';
        }
        $this->sql .= '( ' . implode(' , ', $columns) . ' ) values ( ' . implode(' , ', $values) . ' ) ';
        $this->pdo_values = array_merge($this->pdo_values, array_values($this->set_fields));
    }

    protected function mapping_to_db($objColumn)
    {
        return $this->mapping['columns'][$objColumn];
    }

    protected function mapping_to_obj($dbColumn)
    {
        return array_search($dbColumn, $this->mapping['columns']);
    }

    protected function get_primary_key_name()
    {
        return $this->mapping['key'];
    }

}

