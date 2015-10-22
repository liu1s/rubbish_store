<?php
/**
 * @file
 *
 *
 * Created by 疯牛
 * project: ajk_v3
 * Date: 14-1-6
 * Time: 17:17
 */
abstract class Dao_Common_PdoCommonUse{
    protected $_tableName;
    protected $_fields;
    protected $_masterPdoName;
    protected $_slavePdoName;

    protected function __construct(){
        $this->_fields = "*";
        $this->_masterPdoName = "master";
        $this->_slavePdoName = "slave";
        $this->setTableName();
    }

    abstract protected function setTableName();

    public function setFields($fields){
        if(is_array($fields)){
            $fields = implode(",",$fields);
        }
        $this->_fields = $fields;
    }

    /**
     * 获取属性值
     *
     * @param $propertyName
     * @return bool
     */
    public function __get($propertyName){
        if(isset($this->$propertyName)){
            return $this->$propertyName;
        }else{
            return false;
        }
    }

    /**
     * 获取一条信息
     *
     * @param $data 数组 array(array(条件字段名,条件值,条件值类型,条件判断符号),array(),.....)
     */
    public function fetchOne($data, $order = false){
        try{
            if(empty($this->_slavePdoName)){
                throw new Exception('pdo name error');
            }
            if(empty($this->_tableName)){
                throw new Exception('table name error');
            }
            $pdo = APF_DB_Factory::get_instance()->get_pdo($this->_slavePdoName);
            //var_dump($pdo);
            //拼接sql
//            $sql = "SELECT ". $this->_fields ." FROM " . $this->_tableName . " WHERE cityid = :cityid limit 1";
            $sql = "SELECT ". $this->_fields ." FROM " . $this->_tableName;
            if(!empty($data)){
                $binParamNum = $i = 0;
                foreach($data as $row){
                    if($i == 0){
                        $sql .= " WHERE ";
                    }else{
                        $sql .= " AND ";
                    }

                    if(is_array($row[1])){
                        //in 拼接
                        $j = 0;
                        $sql .= $row[0] . " IN ( ";
                        foreach($row[1] as $subRow){
                            if($j == 0){
                                $sql .= "?";
                            }else{
                                $sql .= ",?";
                            }
                            $j++;
                        }
                        $sql .= ")";
                    }else{
                        //非in 拼接
                        if( isset($row[3]) ){ //判断是否是特殊判断符
                            $sql .= $row[0] . $row[3] . "?";
                        }
                        $sql .= $row[0]."=?";
                    }
                    $i++;
                }
            }
            if($order != false){
                $sql .= " ".trim($order);
            }
            $sql .= " LIMIT 1";
            $stmt = $pdo->prepare($sql);
            //绑定参数
            if(!empty($data)){
                foreach($data as $row){
                    if(is_array($row[1])){
                        foreach($row[1] as $subRow){
                            $binParamNum++;
                            $value{$binParamNum} = $subRow;
                            $stmt->bindParam($binParamNum,$value{$binParamNum},$row[2]);
                        }
                    }else{
                        $binParamNum++;
                        $value{$binParamNum} = $row[1];
                        $stmt->bindParam($binParamNum,$value{$binParamNum},$row[2]);
                    }
                }
            }

            if($stmt->execute()){
                return $stmt->fetch();
            }else{
                return array(
                    -9999 => "SQL ERROR",
                    "info" => array(
                        "PDO ERROR" => $pdo->errorInfo(),
                        "STMT ERROR" => $stmt->errorInfo()
                    )
                );
            }
        }catch (Exception $e){
            return array(
                -9999 => "SQL ERROR",
                "info" => $e->getMessage()
            );
        }
    }

    /**
     * 获取一条信息
     *
     * @param $data 数组 array(array(条件字段名,条件值,条件值类型,条件判断符号),array(),.....)
     */
    public function fetchMultiple($data,$limit = false,$order = false){
        try{
            if(empty($this->_slavePdoName)){
                throw new Exception('pdo name error');
            }
            if(empty($this->_tableName)){
                throw new Exception('table name error');
            }
            $pdo = APF_DB_Factory::get_instance()->get_pdo($this->_slavePdoName);
            //拼接sql
            $sql = "SELECT ". $this->_fields ." FROM " . $this->_tableName;
            if(!empty($data)){
                $binParamNum = $i = 0;
                foreach($data as $row){
                    if($i == 0){
                        $sql .= " WHERE ";
                    }else{
                        $sql .= " AND ";
                    }

                    if(is_array($row[1])){
                        //in 拼接
                        $j = 0;
                        $sql .= $row[0] . " IN ( ";
                        foreach($row[1] as $subRow){
                            if($j == 0){
                                $sql .= "?";
                            }else{
                                $sql .= ",?";
                            }
                            $j++;
                        }
                        $sql .= ")";
                    }else{
                        //非in 拼接
                        if( isset($row[3]) ){ //判断是否是特殊判断符
                            $sql .= $row[0] . $row[3] . "?";
                        }
                        $sql .= $row[0]."=?";
                    }
                    $i++;
                }
            }
            if($order != false){
                $sql .= " ".trim($order);
            }
            if($limit != false){
                $sql .= " LIMIT $limit[0],$limit[1]";
            }
            $stmt = $pdo->prepare($sql);
            //绑定参数
            if(!empty($data)){
                foreach($data as $row){
                    if(is_array($row[1])){
                        foreach($row[1] as $subRow){
                            $binParamNum++;
                            $value{$binParamNum} = $subRow;
                            $stmt->bindParam($binParamNum,$value{$binParamNum},$row[2]);
                        }
                    }else{
                        $binParamNum++;
                        $value{$binParamNum} = $row[1];
                        $stmt->bindParam($binParamNum,$value{$binParamNum},$row[2]);
                    }
                }
            }

            if($stmt->execute()){
                return $stmt->fetchAll();
            }else{
                return array(
                    -9999 => "SQL ERROR",
                    "info" => array(
                        "PDO ERROR" => $pdo->errorInfo(),
                        "STMT ERROR" => $stmt->errorInfo()
                    )
                );
            }
        }catch (Exception $e){
            return array(
                -9999 => "SQL ERROR",
                "info" => $e->getMessage()
            );
        }
    }
}