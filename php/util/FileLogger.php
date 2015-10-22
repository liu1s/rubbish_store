<?php
/**
 * job本地文件记录的简单封装
 * 采用php的file_put_contents相关函数
 *
 * @author 疯牛
 * @package: broker
 */
class Util_FileLogger{
    private $file;
    private $errorInfo = "";

    public function __construct($file)
    {
        if (!$this->filePathInit($file)) {
            throw new Exception($this->errorInfo());
        }

        $this->file = $file;
    }

    /**
     * 文件路径判断
     *
     * @param $file
     * @param int $mode
     * @return bool
     */
    public static function filePathInit($file, $mode = 0777)
    {
        $dirPath = dirname($file);
        if (is_dir($dirPath)) {
            return true;
        }else{
            if (mkdir($dirPath,$mode,true) ){
                return true;
            }else{
                self::addError("dir " . $dirPath . " create fail");
                return false;
            }
        }
    }

    /**
     * 覆盖记录文件
     *
     * @param $msg
     */
    public function coverLog($msg)
    {
        file_put_contents($this->file, $msg);
    }

    /**
     * 追加记录文件
     *
     * @param $msg
     */
    public function appendLog($msg)
    {
        file_put_contents($this->file, $msg, FILE_APPEND);
    }

    /**
     * 获取文件内容
     *
     */
    public function getLog()
    {
        if(!file_exists($this->file)){
            return '';
        }

        return file_get_contents($this->file);
    }

    /**
     * 添加错误信息
     *
     * @param $msg
     */
    public function addError($msg){
        $this->errorInfo .= $msg;
    }

    /**
     * 返回错误信息
     *
     * @return string
     */
    public function errorInfo(){
        return $this->errorInfo;
    }
}