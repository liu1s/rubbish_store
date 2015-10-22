<?php
/**
 * 公共方法
 * User: maskwang
 * Date: 14/10/28
 * Time: 上午10:52
 */

class Util_GlobalFunc
{
    const MD5_KEY = "Anjuke";

    /**
     * 函数标准返回格式
     * @param int $status
     * @param string $msg
     * @return array
     */
    public static function buildReturn($status = 1, $msg = 'success')
    {
        return array('status' => $status, 'msg' => $msg);
    }

    /**
     * 过滤html
     * @param $str
     * @return mixed
     */
    public static function strFilter($str)
    {
        $str = Util_StrFilter::id_class_filter($str);
        $str = Util_StrFilter::xss_filter($str);
        $str = Util_StrFilter::http_filter($str);
        $str = Util_StrFilter::tag_filter(array('a', 'img'), $str);
        return $str;
    }

    /**
     * 判断是否是手机号码
     * @param unknown_type $mobile
     * @return string|string
     */
    public static function isMobile($mobile){
        $pattern = "/^(13|15|18|14)\d{9}$/";
        if (preg_match($pattern,$mobile)){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 是否是电子邮件
     * @param $email
     * @return bool
     */
    public static function isEmail($email){
        $pattern = "/^[\w\-\.]+@[\w\-]+(\.\w+)+$/";
        if (strlen($email) > 6 && preg_match($pattern, $email)){
            return true;
        }else {
            return false;
        }
    }

    /**
     * 生成随机字符串
     * @param $numchar
     * @return string
     */
    public static function randomStr($numchar) {
        $str 		= "abcdefghijkmnpqrstuvwxyz23456789";
        $start 		= mt_rand(1, (strlen($str) - $numchar));
        $string 	= str_shuffle($str);
        $rand_str 	= substr($string, $start, $numchar);
        return($rand_str);
    }

    /**
     * 生成随机数字
     * @param $numchar 长度
     * @return string
     * @author junyang
     */
    public static function randomNum($numchar) {
        $str = "123456789";
        $string = str_shuffle($str);
        $rand_str = substr($string, 0, $numchar);

        return $rand_str;
    }

    /**
     * 判断日期是否合格
     * @param $date
     * @param string $format
     * @return bool
     */
    public static function checkDate($date, $format='-'){
        if(!trim($date)) return false;
        $arr_date = explode($format, $date);
        return checkdate($arr_date[1], $arr_date[2], $arr_date[0]);
    }

    /**
     * 日期时间转化为Unix时间
     * larger = true	23：59：59
     * larger = false	0：0：1
     * @param $date date	格式为：Y-m-d
     * @param string $format
     * @param bool $larger
     * @return int
     */
    public static function date2timestamp($date, $format='-', $larger=false){
        $arr_date = explode($format, $date);
        $year	= $arr_date[0];
        $month	= $arr_date[1];
        $day	= $arr_date[2];
        $hour	= $larger ? '23' : '00';
        $minute	= $larger ? '59' : '00';
        $second	= $larger ? '59' : '01';

        return mktime(intval($hour), intval($minute), intval($second), intval($month), intval($day), intval($year));
    }

    /**
     * 字符串的长度，一个汉字算一个长度
     * @param $str
     * @return int
     */
    public static function strlenw($str){
        $str	= trim($str);
        $count 	= 0;
        $len 	= strlen($str);
        for($i=0; $i<$len; $i++,$count++)
            if(ord($str[$i])>=128)
                $i+=2;
        return $count;
    }

    /**
     * 显示剩余天数
     * @param $day
     * @param $unixtime
     * @return int
     */
    public static function showLeaveDay($day, $unixtime){
        $now = time();
        $oldtime	= mktime('12', 0, 0, date('m', $unixtime), date('d', $unixtime), date('Y', $unixtime));
        $newtime	= mktime('12', 0, 1, date('m', $now), date('d', $now), date('Y', $now));
        $leave_unixtime = $newtime - $oldtime;
        $leave_day 		= floor($leave_unixtime / 86400);
        $r_day =  $day - $leave_day;
        if($r_day < 0) $r_day = 0;
        return $r_day;
    }

    /**
     * 字符串加密
     * @param $plain_text 需要加密的明文字符串
     * @param $password 加密密钥
     * @param int $iv_len
     * @return string
     */
    public static function md5e($plain_text, $password, $iv_len = 8){
        $plain_text .= "\x13";
        $n = strlen($plain_text);
        if ($n % 16) $plain_text .= str_repeat("\0", 16 - ($n % 16));
        $i = 0;
        $enc_text = self::get_rnd_iv($iv_len);
        $iv = substr($password ^ $enc_text, 0, 512);
        while ($i < $n) {
            $block = substr($plain_text, $i, 16) ^ pack('H*', md5($iv));
            $enc_text .= $block;
            $iv = substr($block . $iv, 0, 512) ^ $password;
            $i += 16;
        }

        $enc_text = base64_encode($enc_text);
        $enc_text = eregi_replace("/", self::MD5_KEY, $enc_text);
        return $enc_text;
    }

    /**
     * 字符串解密
     * @param $enc_text 需要解密的字符串
     * @param $password 解密密钥
     * @param int $iv_len
     * @return mixed
     */
    public static function md5d($enc_text, $password, $iv_len = 8){
        $enc_text = eregi_replace(self::MD5_KEY, "/", $enc_text);
        $enc_text = base64_decode($enc_text);
        $n = strlen($enc_text);
        $i = $iv_len;
        $plain_text = '';
        $iv = substr($password ^ substr($enc_text, 0, $iv_len), 0, 512);
        while ($i < $n){
            $block = substr($enc_text, $i, 16);
            $plain_text .= $block ^ pack('H*', md5($iv));
            $iv = substr($block . $iv, 0, 512) ^ $password;
            $i += 16;
        }
        return preg_replace('/\\x13\\x00*$/', '', $plain_text);
    }

    /**
     * @param $iv_len
     * @return string
     */
    public static function getRndIv($iv_len){
        $iv = '';
        while ($iv_len-- > 0){
            $iv .= chr(mt_rand() & 0xff);
        }
        return $iv;
    }

    /**
     * 计算添加后的日期时间
     * @param $days
     * @param string $fromtime
     * @param bool $is_gift: 是否赠送，true,是，false：否
     * @return int
     */
    public static function addDays($days, $fromtime='', $is_gift=false){
        $fromtime  	= empty($fromtime) ? time() : $fromtime;

        $oneday		= 0;
        // 如果从24点计算时间，就当一天出来，中间差值为10分钟

        if(!$is_gift && $fromtime && $fromtime > time() && ($fromtime - time()) > 600){
            $oneday = 1;
        }

        $endday		= $fromtime + ($days-$oneday) * 86400;
        $endtime	= mktime(23, 59, 59, date('m', $endday), date('d', $endday), date('Y', $endday));
        return $endtime;
    }

    /**
     * 替换所有的HTML标签
     * @param $str
     * @return mixed|string
     */
    public static function clearHtml($str){
        $str=html_entity_decode($str) ;
        $str=preg_replace("/<.+?>/i","",$str);
        return $str;
    }

    /**
     * @param $str
     * @param $lenth
     * @param string $dot
     * @return string
     * 截取字符串
     */
    public static function cutChar($str,$lenth,$dot = "...")
    {
        $strlen = strlen($str);
        $charlen = 0;
        $cut = false;
        for($i=0;$i<$strlen;)
        {
            $charAt = ord($str[$i]);
            if(($charAt & 0xfe) == 0xfe)//占七个字节的汉字1111 1110
            {
                $i+=7;
                $charlen+=2;
            }
            else if(($charAt & 0xfc) == 0xfc)//占六个字节的汉字1111 1100
            {
                $i+=6;
                $charlen+=2;
            }
            else if(($charAt & 0xf8) == 0xf8)//占五个字节的汉字1111 1000
            {
                $i+=5;
                $charlen+=2;
            }
            else if(($charAt & 0xf0) == 0xf0)//占四个字节的汉字1111 0000
            {
                $i+=4;
                $charlen+=2;
            }
            else if(($charAt & 0xe0) == 0xe0)//占三个字节的汉字1110 0000
            {
                $i+=3;
                $charlen+=2;
            }
            else if(($charAt & 0xc0) == 0xc0)//占两个字节的汉字1100 0000
            {
                $i+=2;
                $charlen+=2;
            }
            else if(($charAt & 0x08) == 0x08)//
            {
                $i++;
                continue;
                //$charlen+=2;
            }
            else//($charAt < 128)//普通字符
            {
                $i++;
                $charlen ++;
                //echo $i." ".decbin($charAt)." | ";
            }

            if(($charlen+2) > $lenth*2)
            {
                $cut = true;
                break;
            }
            //echo "$i,";
        }
        if($cut)
        {
            return substr($str,0,$i).$dot;
        }
        else
        {
            return $str;
        }
    }
}