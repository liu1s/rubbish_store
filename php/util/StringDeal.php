<?php
/**
 *
 * @author 疯牛 liu1s0404@outlook.com
 * @package: broker
 */
class Util_StringDeal{
    /**
     * 根据str获取一个固定长度的数字
     */
    public static function convertStrToConstLenthInt($str,$lenth)
    {
        if(strlen($str) > $lenth){
            $str = substr($str,0,$lenth);
        }

        $strArray = str_split($str);
        $int = 0;
        foreach($strArray as $key=>$row){
            $pre = 1;
            for($i=$key;$i>0;$i--){
                $pre *= 10;
            }
            $int += $pre*ord($row);
        }

        if($int > 1000){
            $int = substr($int,0,$lenth);
        }else{
            for($i=strlen($int);$i<$lenth;$i++){
                $int .= '0';
            }
        }

        return $int;
    }
}