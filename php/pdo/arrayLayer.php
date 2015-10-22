<?php
/**
 *
 * module层简单封装，原对象传参改为array传参
 *
 * todo 由于封装的层数过多，建议了解APF_DB_DataObject和APF_DB_MysqlAccessor的关系后直接采用这两个类
 *
 * @author 疯牛 liu1s0404@outlook.com
 * @package: ajk_v3
 */
abstract Class Model_Db_ArrayLayer extends Model_Db_Abstract{
    protected static $dbSplit;

    public static function get_pdo_slave_name()
    {
        return 'slave';
    }

    public static function get_pdo_master_name()
    {
        return 'master';
    }

    /**
     * 分库时依据的条件，子类根据这个字段设置具体的pdo name
     *
     * @param $dbSplitData
     */
    private static function setDbSplit($dbSplitData)
    {
        self::$dbSplit = $dbSplitData;
    }

    /**
     * 获取单行数据封装
     *
     * @param $data array(array1(filterkey,filterSymbols,filtervalue),  array2(filterkey,filterSymbols,filtervalue),.....) 筛选条件数组
     * @param array $order array(array1(orderkey,ordertype), array1(orderkey,ordertype), ....) 排序条件数组
     * @param int $limit
     * @param int $limitStart
     * @param string $prefix 表的分表方式
     * @return mixed|null
     */
    public static function fetchOneLayer($data, $order=array(), $prefix= '', $dbSplit = '')
    {
        $mysqlAccessor = static::baseFetchLayer($data, $order, $prefix, 0,0,$dbSplit);
        return $mysqlAccessor->find_only();
    }

    /**
     * 获取多行数据封装
     *
     * @param $data array(array1(filterkey,filterSymbols,filtervalue),  array2(filterkey,filterSymbols,filtervalue), ...) 筛选条件数组
     * @param array $order array(array1(orderkey,ordertype), array1(orderkey,ordertype), ....) 排序条件数组
     * @param int $limit
     * @param int $limitStart
     * @param string $prefix 表的分表方式
     * @return array
     */
    public static function fetchLayer($data, $order=array(), $prefix= '', $limit=0, $limitStart=0, $dbSplit = '')
    {
        $mysqlAccessor = static::baseFetchLayer($data, $order, $prefix, $limit, $limitStart, $dbSplit);
        return $mysqlAccessor->find();
    }

    /**
     * 基本获取数据数据封装
     *
     * @param $data array(array1(filterkey,filterSymbols,filtervalue),  array2(filterkey,filterSymbols,filtervalue), ...) 筛选条件数组
     * @param array $order array(array1(orderkey,ordertype), array1(orderkey,ordertype), ....) 排序条件数组
     * @param int $limit
     * @param int $limitStart
     * @param string $prefix 表的分表方式
     * @return APF_DB_MysqlAccessor
     */
    protected static function baseFetchLayer($data, $order=array(), $prefix= '', $limit=0, $limitStart=0, $dbSplit = '')
    {
        //设置分库所需的条件
        if (!empty($dbSplit)) {
            self::setDbSplit($dbSplit);
        }

        //设置分表的条件
        if (!empty($prefix)) {
            $mysqlAccessor = static::data_access($prefix);
        }else{
            $mysqlAccessor = static::data_access();
        }

        //组装筛选条件
        foreach ($data as $row) {
            $mysqlAccessor->filter_by_op($row[0], $row[1], $row[2]);
        }

        //设置排序条件
        if (!empty($order)) {
            foreach ($order as $row) {
                $mysqlAccessor->sort($row[0], $row[1]);
            }
        }

        //设置limit开始值
        if (!empty($limitStart)) {
            $mysqlAccessor->offset($limitStart);
        }

        //设置limit值
        if (!empty($limit)) {
            $mysqlAccessor->limit($limit);
        }

        return $mysqlAccessor;
    }

    /**
     * 插入数据封装
     *
     * @param $data array(key1=>value1, key2=>value2, ...)
     * @param string $prefix
     * @return mixed
     * @throws Exception
     */
    public static function insertLayer($data, $prefix= '', $dbSplit = ''){
        //设置分库所需的条件
        if (!empty($dbSplit)) {
            self::setDbSplit($dbSplit);
        }

        if (!empty($prefix)) {
            $model = new static($prefix);
            $mapping = static::get_mapping($prefix);
        }else{
            $model = new static();
            $mapping = static::get_mapping();
        }
        $primaryKey = $mapping['key'];
        foreach ($data as $key=>$row) {
            $model->$key = $row;
        }

        $model->save();

        if (!$model->$primaryKey) {
            throw new Exception("insert fail in file " . get_class($model));
        }else{
            return $model->$primaryKey;
        }
    }

    /**
     * 更新数据封装
     *
     * @param $data
     * @param string $prefix
     * @param $primaryId 主键id
     * @return mixed
     * @throws Exception
     */
    //todo 代码主体和insertLayer很多一样
    public static function updateLayer($filterData, $updateData, $primaryId, $prefix= '', $dbSplit = ''){
        //设置分库所需的条件
        if (!empty($dbSplit)) {
            self::setDbSplit($dbSplit);
        }

        //获取主键key
        if (!empty($prefix)) {
            $mapping = static::get_mapping($prefix);
        }else{
            $mapping = static::get_mapping();
        }
        $primaryKey = $mapping['key'];

        $mysqlAccessor = static::data_access($prefix);
        //设置筛选条件
        foreach($filterData as $key=>$row) {
            $mysqlAccessor->filter($key, $row);
        }
        //设置主键
        $mysqlAccessor->filter($primaryKey, $primaryId);
        //设置更新数据
        foreach($updateData as $key=>$row) {
            $mysqlAccessor->set_field($key, $row);
        }

        $rowCount = $mysqlAccessor->update();
        return $rowCount;
    }

    /**
     * @param $sql sql语句
     * @param $value 变量绑定的变量数组
     * @param bool $read_only 为true时，获取取得的详细数据 false时，返回取得数据条数
     * @param string $prefix 分表的后缀条件
     * @param string $dbSplit  分库的条件
     * @return mixed
     */
    public static function nativeSqlLayer($sql, $value, $read_only = true, $prefix= '', $dbSplit = '')
    {
        //设置分库所需的条件
        if (!empty($dbSplit)) {
            self::setDbSplit($dbSplit);
        }

        $mysqlAccessor = static::data_access($prefix);

        return $mysqlAccessor->native_sql($sql, $value, $read_only);
    }

}
