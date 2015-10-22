<?php
/**
 * 通用函数库
 * @author jxu
 * @package Util
 */

class Util_PublicMethods
{

    /**
     * 检查传入值是否是数据字典中的值
     * 如果存在则返回数据字典中的值,否则返回默认值
     * @param int $p_intChkValue
     * @param array $p_arrAllConditions
     * @param int $p_intDefaultValue
     * @param string $p_strIndex
     * @return string
     */
    static function chkCondition($p_intChkValue, $p_arrAllConditions, $p_intDefaultValue = '', $p_strIndex = 'CODE')
    {
        $intCnt = count($p_arrAllConditions);
        for ($i = 0; $i < $intCnt; ++$i) {
            if ($p_intChkValue == $p_arrAllConditions[$i][$p_strIndex]) {
                return $p_intChkValue;
            }
        }
        return $p_intDefaultValue;
    }

    /**
     * 获取选中的数据字典中的值
     * @param int $p_intSelectedValue
     * @param array $p_arrAllConditions
     * @param string $p_strConditionIndex
     * @param int $p_intDefaultValue
     * @param string $p_strIndex
     * @return string
     */
    static function getCondition($p_intSelectedValue, $p_arrAllConditions, $p_strConditionIndex, $p_intDefaultValue = '', $p_strIndex = 'CODE')
    {
        $intCnt = count($p_arrAllConditions);
        for ($i = 0; $i < $intCnt; ++$i) {
            if ($p_intSelectedValue == $p_arrAllConditions[$i][$p_strIndex]) {
                return $p_arrAllConditions[$i][$p_strConditionIndex];
            }
        }
        return $p_intDefaultValue;
    }

    /**
     * 校验数据格式
     * @param mix $p_mixData
     * @param string $p_strDataType
     * @return boolean
     */
    static function chkDataType($p_mixData, $p_strDataType)
    {
        if ('' == $p_mixData) {
            return false;
        }
        switch ($p_strDataType) {
            case 'int':
                return 0 < preg_match('/^-?[1-9]?[0-9]*$/', $p_mixData) ? true : false;
            case 'url':
                return 0 < preg_match('/^https?:\/\/([a-z0-9-]+\.)+[a-z0-9]{2,4}.*$/', $p_mixData) ? true : false;
            case 'email':
                return 0 < preg_match('/^[a-z0-9_+.-]+\@([a-z0-9-]+\.)+[a-z0-9]{2,4}$/i', $p_mixData) ? true : false;
            case 'idcard':
                return 0 < preg_match('/^[0-9]{15}$|^[0-9]{17}[a-zA-Z0-9]/', $p_mixData) ? true : false;
            case 'area':
                return 0 < preg_match('/^\d+(\.\d{1,2})?$/', $p_mixData) ? true : false;
            case 'money':
                return 0 < preg_match('/^\d+(\.\d{1,2})?$/', $p_mixData) ? true : false;
            case 'year':
                return 0 < preg_match('/^(19|20)\d\d$/', $p_mixData) ? true : false;
            case 'md5_32':
                return 0 < preg_match('/^(\d|[a-z]|[A-Z]){32}$/', $p_mixData) ? true : false;
            case 'mobile':
                return 0 < preg_match('/(^1(5\d|3\d|8\d|4\d)\d{8}$)|(^[1-9][0-9]{7}$)/', $p_mixData) ? $p_mixData : null;
        }
        return false;
    }

    /**
     * 检验数据是否包含某种格式
     * @param mix $p_mixData
     * @param string $p_strDataType
     * @return boolean
     */
    static function containDataType($p_mixData, $p_strDataType)
    {
        if ('' == $p_mixData) {
            return false;
        }
        switch ($p_strDataType) {
            case 'mobile':
                return 0 < preg_match('/[0-9]{13}/', $p_mixData) ? true : false;
        }
        return false;
    }

    /**
     * 检查字符串长度
     * @param string $p_strData
     * @param int $p_intMinLength
     * @param int $p_intMaxLength
     * @param boolean $p_bolMultiByte 是否区分中英文字符串
     * @return boolean
     */
    static function chkStrLength($p_strData, $p_intMinLength = 0, $p_intMaxLength = 0, $p_bolMultiByte = true)
    {
        if ($p_bolMultiByte) {
            $intLen = strlen($p_strData);
        } else {
            $intLen = mb_strlen($p_strData, 'utf-8');
        }
        if ($p_intMinLength > 0) {
            if ($p_intMinLength > $intLen) {
                return false;
            }
        }
        if ($p_intMaxLength > 0) {
            if ($p_intMaxLength < $intLen) {
                return false;
            }
        }
        return true;
    }

    /**
     * 转义HTML敏感字符
     * @param mix $p_mixData
     * @return mix
     */
    static function chgHtmlSpecialChar($p_mixData)
    {
        if (is_array($p_mixData)) {
            foreach ($p_mixData as $skey => $value) {
                $p_mixData[$skey] = self::chgHtmlSpecialChar($value);
            }
        } else {
            $p_mixData = htmlspecialchars($p_mixData);
        }
        return $p_mixData;
    }

    /**
     * 删除字符串两端字符
     * @param mix $p_mixData
     * @param string $p_strCharList
     * @return mix
     */
    static function trim($p_mixData, $p_strCharList = '')
    {
        if (is_array($p_mixData)) {
            foreach ($p_mixData as $skey => $value) {
                $p_mixData[$skey] = self::trim($value, $p_strCharList);
            }
        } else {
            if ('' == $p_strCharList) {
                $p_mixData = trim($p_mixData);
            } else {
                $p_mixData = trim($p_mixData, $p_strCharList);
            }
        }
        return $p_mixData;
    }

    /**
     * 反转义HTML敏感字符
     * @param mix $p_mixData
     * @return mix
     */
    static function chgHtmlSpecialChar_decode($p_mixData)
    {
        if (is_array($p_mixData)) {
            foreach ($p_mixData as $skey => $value) {
                $p_mixData[$skey] = self::chgHtmlSpecialChar($value);
            }
        } else {
            $p_mixData = htmlspecialchars_decode($p_mixData);
        }
        return $p_mixData;
    }

    /**
     * 切断字符串(长度包含最后字符)
     * @param mix $p_mixData
     * @param int $p_intLength
     * @param string $p_strLast
     * @return mix
     */
    static function cutStr($p_mixData, $p_intLength, $p_strLast = '...')
    {
        if (is_array($p_mixData)) {
            foreach ($p_mixData as $key => $value) {
                $p_mixData[$key] = self::cutStr($value, $p_intLength, $p_strLast);
            }
        } else {
            if (mb_strlen($p_mixData, 'utf8') <= $p_intLength) {
                return $p_mixData;
            } else {
                $strCut = mb_substr($p_mixData, 0, $p_intLength - strlen($p_strLast), 'utf8');
                return $strCut . $p_strLast;
            }
        }
        return $p_mixData;
    }

    /**
     * 切断字符串(1个字节字的算半个字)
     * @param mix $str
     * @param int $lenth
     * @param string $dot
     * @return mix
     */
    static function specialCutStr($str, $lenth, $dot = "...")
    {
        $str = str_replace("&nbsp;", "", $str);
        $str = trim($str);
        $strlen = mb_strlen($str, 'utf8');
        $charlen = 0;
        $curLen = 0;
        $returnData = '';

        for ($i = 0; $i < $strlen; $i++) {
            $oneStr = '';
            $oneStr = mb_substr($str, $i, 1, 'utf-8');
            $charAt = ord($oneStr);
            if ($charAt < 128) {
                $curLen = 0.5;
            } else {
                $curLen = 1;
            }

            if (($charlen + $curLen) >= $lenth) {
                return $returnData . $oneStr . $dot;
            } else {
                $charlen += $curLen;
                $returnData .= $oneStr;
            }
        }
        return $returnData;

    }

    /**
     * 组合字符串截取
     * @param string $p_strVal1
     * @param string $p_strVal2
     * @param int $p_intLength
     * @param string $p_strLast
     * @return array
     */
    static function cbcutStr($p_strVal1, $p_strVal2, $p_intLength, $p_strLast = '...')
    {
        $intVal1Length = mb_strlen($p_strVal1, 'utf8');
        $intVal2Length = mb_strlen($p_strVal2, 'utf8');
        $arrData = array();
        if ($intVal1Length + $intVal2Length <= $p_intLength) {
            $arrData = array(
                $p_strVal1,
                $p_strVal2
            );
        }
        if ($intVal1Length > $p_intLength) { //第一个就超长
            $arrData[0] = self::cutStr($p_strVal1, $p_intLength, $p_strLast);
            $arrData[1] = '';
        } else { //第一个不超长
            $arrData[0] = $p_strVal1;
            $intSecLength = $p_intLength - $intVal1Length;
            if ($intVal2Length > $intSecLength) { //需要截取第2个
                $arrData[1] = self::cutStr($p_strVal2, $intSecLength, $p_strLast);
            } else {
                $arrData[1] = $p_strVal2;
            }
        }
        return $arrData;
    }

    /**
     * 切断带有格式的字符,长度不包含HTML代码
     * @param mix $p_mixData
     * @param int $p_intLength
     * @param string $p_strLast
     * @return mix
     */
    static function cutHTML($p_mixData, $p_intLength, $p_strLast = '...')
    {
        if (is_array($p_mixData)) {
            foreach ($p_mixData as $key => $value) {
                $p_mixData[$key] = self::cutHTML($value, $p_intLength, $p_strLast);
            }
        } else {
            if (strlen(strip_tags($p_mixData)) <= $p_intLength) {
                return $p_mixData;
            } else {
                $strCut = '';
                $bolInHTML = false;
                $bolInTag = false;
                $bolRecordTag = false;
                $bolNeedMinus = false;
                $arrTags = array();
                $arrSelfCloseTag = array(
                    'br',
                    'hr',
                    'input'
                );
                $strSingleTag = '';
                $intLength = 0;
                for ($i = 0; $intLength < $p_intLength - strlen($p_strLast); ++$i) {
                    if ('<' == $p_mixData[$i]) {
                        $bolInTag = true;
                    } elseif ('&' == $p_mixData[$i]) {
                        $bolInHTML = true;
                    } elseif ('>' == $p_mixData[$i] and $bolInTag) {
                        $bolInTag = false;
                        $bolRecordTag = false;
                        $bolNeedMinus = true;
                        if ('' != $strSingleTag) {
                            if ('/' == substr($strSingleTag, 0, 1)) {
                                array_pop($arrTags);
                            } else {
                                if (!in_array($strSingleTag, $arrSelfCloseTag)) {
                                    $arrTags[] = $strSingleTag;
                                }
                            }
                        }
                        $strSingleTag = '';
                    } elseif (';' == $p_mixData[$i] and $bolInHTML) {
                        $bolInHTML = false;
                    } elseif (' ' == $p_mixData[$i] and $bolRecordTag) {
                        $bolRecordTag = false;
                        if ('' != $strSingleTag) {
                            if (!in_array($strSingleTag, $arrSelfCloseTag)) {
                                $arrTags[] = $strSingleTag;
                            }
                        }
                        $strSingleTag = '';
                    }
                    if (ord($p_mixData[$i]) > 127) {
                        $strCut .= $p_mixData[$i] . $p_mixData[++$i] . $p_mixData[++$i];
                        if (!$bolInTag and !$bolInHTML) {
                            $intLength = $intLength + 3;
                        }
                    } else {
                        $strCut .= $p_mixData[$i];
                        if (!$bolInTag and !$bolInHTML) {
                            ++$intLength;
                        } elseif ($bolRecordTag) {
                            $strSingleTag .= $p_mixData[$i];
                        }
                    }
                    if ('<' == $p_mixData[$i]) {
                        $bolRecordTag = true;
                    } elseif ($bolNeedMinus) {
                        $intLength--;
                        $bolNeedMinus = false;
                    }
                }
                $arrTags = array_reverse($arrTags);
                $p_mixData = $strCut . '</' . implode('></', $arrTags) . '>' . $p_strLast;
                unset($strCut);
            }
        }
        return $p_mixData;
    }

    /**
     * 将areacode转换为各类ID
     * @param string $p_strAreaCode
     * @return array
     */
    static function transferAreaCode2TypeID($p_strAreaCode)
    {
        $arrTmp = array();
        if (strlen($p_strAreaCode) > 3) {
            $arrTmp['CITYTYPEID'] = ltrim(substr($p_strAreaCode, 0, 4), 0);
        }
        if (strlen($p_strAreaCode) > 7) {
            $arrTmp['AREATYPEID'] = ltrim(substr($p_strAreaCode, 4, 4), 0);
        }
        if (strlen($p_strAreaCode) > 11) {
            $arrTmp['BLOCKTYPEID'] = ltrim(substr($p_strAreaCode, 8, 4), 0);
        }
        return $arrTmp;
    }

    /**
     * 将各类ID转换为areacode
     * @param int $p_intCityID
     * @param int $p_intAreaID
     * @param int $p_intBlockID
     * @return string
     */
    static function transferTypeID2AreaCode($p_intCityID = '', $p_intAreaID = '', $p_intBlockID = '')
    {
        $strTmp = '';
        if ('' != $p_intCityID) {
            $strTmp .= sprintf('%04s', $p_intCityID);
            if ('' != $p_intAreaID) {
                $strTmp .= sprintf('%04s', $p_intAreaID);
                if ('' != $p_intBlockID) {
                    $strTmp .= sprintf('%04s', $p_intBlockID);
                }
            }
        }
        return $strTmp;
    }

    /**
     * 解析翻页参数
     * @param int $p_intPageNum 当前页码
     * @param int $p_intPageSize 每页数量
     * @param int $p_intTotalCnt 总数量
     * @return array
     */
    static function getPage($p_intPageNum, $p_intPageSize, $p_intTotalCnt)
    {
        $arrPage = self::getSearchPage($p_intPageNum, $p_intPageSize);
        $arrPage['TOTALNUM'] = $p_intTotalCnt;
        if (0 == $arrPage['TOTALNUM'] % $arrPage['PAGESIZE']) {
            $arrPage['TOTALPAGE'] = $arrPage['TOTALNUM'] / $arrPage['PAGESIZE'];
        } else {
            $arrPage['TOTALPAGE'] = floor($arrPage['TOTALNUM'] / $arrPage['PAGESIZE']) + 1;
        }
        if ($arrPage['PAGENUM'] > $arrPage['TOTALPAGE']) {
            $arrPage['PAGENUM'] = $arrPage['TOTALPAGE'];
        }
        return $arrPage;
    }

    /**
     * 解析搜索翻页参数
     * @param int $p_intPageNum
     * @param int $p_intPageSize
     * @return array
     */
    static function getSearchPage($p_intPageNum, $p_intPageSize)
    {
        $arrPage = array(
            'PAGENUM' => $p_intPageNum,
            'PAGESIZE' => $p_intPageSize
        );
        if (is_numeric($p_intPageNum)) {
            if ($p_intPageNum < 1) {
                $arrPage['PAGENUM'] = 1;
            }
        } else {
            $arrPage['PAGENUM'] = 1;
        }
        if (is_numeric($p_intPageSize)) {
            if ($p_intPageSize < 1) {
                $arrPage['PAGESIZE'] = 20;
            }
        } else {
            $arrPage['PAGESIZE'] = 20;
        }
        return $arrPage;
    }

    /**
     * 加密一个字符串
     * @param string $p_strVal
     * @param string $p_strKeyType
     * @param string $p_strStaticKey
     * @return string
     */
    static function cryptString($p_strVal, $p_strKeyType = 'dynamic', $p_strStaticKey = '')
    {
        if (is_array($p_strVal)) {
            $p_strVal = implode("", $p_strVal);
        }
        switch ($p_strKeyType) {
            case 'static':
                if ('' == $p_strStaticKey) {
                    $p_strStaticKey = 'pandajingjing'; //嘿嘿
                }
                break;
            case 'dynamic':
            default:
                $p_strStaticKey = date('Ymd', time());
                break;
        }
        return md5(strrev($p_strVal) . $p_strStaticKey);
    }

    /**
     * 过滤制定的HTML标签
     * @param string $p_strValue
     * @return string
     */
    static function filterHTML($p_strValue)
    {
        $p_strValue = preg_replace('/[\n\r\t]/', ' ', trim($p_strValue)); //去掉非space 的空白，用一个空格代替
        if ('' == $p_strValue) {
            return $p_strValue;
        } else {
            return preg_replace('/\\<(?!p|\/p|span|\/span|font|\/font|br|strong|\/strong|em|\/em|b|\/b|U|\/U|table|\/table|tbody|\/tbody|thead|\/thead|tr|\/tr|td|\/td|ul|\/ul|ol|\/ol|li|\/li|u|\/u)(.*?)\\>/is', '', $p_strValue); //替换所有非p,span,font,br,strong,em,b,ol,li,ul,u 的html-tag
        }
    }

    /**
     * 返回时间范围信息
     * @param int $p_intSecond
     */
    static function updateStr($p_intSecond)
    {
        if ($p_intSecond < 0) {
            return '1秒';
        } elseif ($p_intSecond < 60) {
            return $p_intSecond . '秒';
        } elseif ($p_intSecond < 3600) {
            return round($p_intSecond / 60) . '分';
        } elseif ($p_intSecond < 86400) {
            return round($p_intSecond / 3600) . '小时';
        } else {
            return round($p_intSecond / 86400) . '天';
        }
    }

    /**
     * 获取最终级域名
     *
     * @return string
     */
    static function getSecondDomain()
    {
        $current_url = $_SERVER['HTTP_HOST'];
        $domain = explode(".", $current_url);
        return $domain[0];
    }

    /**
     * 生成密码
     * @param $p_strMobile
     */
    public static function buildPassword($p_strMobile)
    {

        $strPrivateKey = 'AnJuke2011@StoreKey.com';
        $strMixKey = $strPrivateKey . '_' . $p_strMobile . '_' . $strPrivateKey;
        $strMd5Key = md5($strMixKey);

        return substr($strMd5Key, 5, 10);
    }

    public static function escapeLuceneChars($p_origstr)
    {
        //    	+ - && || ! ( ) { } [ ] ^ ” ~ * ? : \
        if (empty($p_origstr))
            return $p_origstr;
        $rep_src = array(
            '(',
            ')',
            ':',
            '^',
            '{',
            '}',
            '[',
            ']',
            '?',
            '*',
            '~',
            '!',
            '&&',
            '||',
            '+',
            '-',
            '"'
        );
        return str_replace($rep_src, '', $p_origstr);
    }

    public static function convPeriod($time, $day = 2, $default = '', $timestamp = false)
    {
        $from1st = APF::get_instance()->get_config('price_trend_from_1st', 'community');
        if ($from1st) {
            if ($timestamp) {
                return $time - $day * 86400;
            } else {
                return date('Ym', $time);
            }
        } else {
            if ($timestamp) {
                return $time - $day * 86400;
            } else {
                return date('Ym', $time - $day * 86400);
            }
        }
    }

    /**
     * 苏州站的昆山区域的所有页面做临时跳转
     */
    public static function tempRedirect($cityid, $blockid, $redUrl)
    {
        if ($cityid == 19 && $blockid == '2109') {
            $base_domain = APF::get_instance()->get_config("base_domain");
            apf_require_class("Uri_Http");
            Uri_Http::redirect_header("http://ks.$base_domain$redUrl");
        }
    }

    /**
     * 获取时间间隔描述字符串
     * @param int $p_intStartTime
     * @param int $p_intEndTime
     * @return array
     */
    static function getTimeLength($p_intStartTime, $p_intEndTime = 0)
    {
        if (0 == $p_intEndTime) {
            $p_intEndTime = time();
        }
        $intInterval = $p_intEndTime - $p_intStartTime;
        if ($intInterval > 0) {
            $intYear = floor($intInterval / 31536000);
            $intInterval = $intInterval % 31536000;
            $intMonth = floor($intInterval / 2592000);
            $intInterval = $intInterval % 2592000;
            $intDays = floor($intInterval / 86400);
            $intInterval = $intInterval % 86400;
            $intHours = floor($intInterval / 3600);
            $intInterval = $intInterval % 3600;
            $intMinutes = floor($intInterval / 60);
            $intSeconds = $intInterval % 60;
            return array($intYear, $intMonth, $intDays, $intHours, $intMinutes, $intSeconds);
        } else {
            return array();
        }
    }

    /**
     * 根据身份证号计算年龄
     * @param string $p_strUserCard
     * @return array
     */
    static function getAgeByUserCard($p_strUserCard)
    {
        if ('' == $p_strUserCard) {
            return 0;
        }
        $strBirth = self::getBirthByUserCard($p_strUserCard);
        return self::getTimeLength(strtotime($strBirth));
    }

    /**
     * 根据身份证号获取生日
     * @param string $p_strUserCard
     * @return string
     */
    static function getBirthByUserCard($p_strUserCard)
    {
        if (15 == strlen($p_strUserCard)) {
            $strBirth = substr($p_strUserCard, 6, 6);
            $strBirth = '19' . $strBirth;
        } else {
            $strBirth = substr($p_strUserCard, 6, 8);
        }
        return $strBirth;
    }

    /**
     * 根据身份证号获取性别
     * @param string $p_strUserCard
     * @return int
     */
    static function getSexByUserCard($p_strUserCard)
    {
        if (15 == strlen($p_strUserCard)) {
            $strSex = substr($p_strUserCard, 14, 1);
        } elseif (18 == strlen($p_strUserCard)) {
            $strSex = substr($p_strUserCard, 16, 1);
        }
        $intSex = intval($strSex, 10);
        $intSex = $intSex % 2;
        if (0 == $intSex) {
            $intSex = 1;
        } else {
            $intSex = 0;
        }
        //$intSex结果为0则性别为男，结果为1则性别为女。
        return $intSex;
    }

    static function is_personal_prop($proid)
    {
        return $proid > 1000000000;
    }

    static function conv_personal_propid($proid, $code = 'decode')
    {
        if ($code == 'decode' && $proid > 1000000000) {
            $proid = $proid - 1000000000;
        } else if ($code == 'encode' && $proid < 1000000000) {
            $proid = $proid + 1000000000;
        } else {
            $proid = intval($proid);
        }
        return $proid;
    }

    static function is_aifang_prop($proid)
    {
        return $proid < 1000000000 && $proid > 500000000;
    }

    static function conv_aifang_propid($proid, $code = 'decode')
    {
        if ($code == 'decode' && $proid > 500000000) {
            $proid = $proid - 500000000;
        } else if ($code == 'encode' && $proid < 500000000) {
            $proid = $proid + 500000000;
        } else {
            $proid = intval($proid);
        }
        return $proid;
    }

    function check_prop_type($proid)
    {
        if ($proid > 1000000000) {
            return 'personal';
        } else if ($proid > 500000000) {
            return 'aifang';
        } else {
            return 'sale';
        }
    }

    function conv_sale_proid($priod, $type = 'personal', $code = 'decode')
    {
        if ($type == 'personal') {
            if ($code == 'decode' && $priod > 1000000000) {
                $proid = $priod - 1000000000;
            } else if ($code == 'encode' && $priod < 1000000000) {
                $proid = $priod + 1000000000;
            }
        } else if ($type == 'aifang') {
            if ($code == 'decode' && $priod > 500000000) {
                $priod = $priod - 500000000;
            } else if ($code == 'encode' && $priod < 500000000) {
                $priod = $priod + 500000000;
            }
        }
    }

    public function groupSubStr($String, $Length, $Append = false)
    {
        if (strlen($String) <= $Length) {
            return $String;
        } else {
            $i = 0;
            while ($i < $Length) {
                $StringTMP = substr($String, $i, 1);
                if (ord($StringTMP) >= 224) //如果字符为三字节字符
                {
                    $StringTMP = substr($String, $i, 3);
                    $i = $i + 3;
                } else if (ord($StringTMP) >= 192) //如果字符为两字节字符
                {
                    $StringTMP = substr($String, $i, 2);
                    $i = $i + 2;
                } else //如果字符为1字节字符
                {
                    $i = $i + 1;
                }
                $StringLast[] = $StringTMP;
            }
            $StringLast = implode("", $StringLast);
            if ($Append) {
                $StringLast .= $Append;
            }
            return $StringLast;
        }
    }

    /**
     * 切割字符串
     * @param str $str
     * @param str $split
     * 曹阳 2013-3-15
     */
    public static function splitStr($str, $split = ",", $removedValue = "")
    {
        if (empty($str)) {
            return array();
        }
        //切割字段串
        $tmpArr = explode($split, $str);
        //剔除指定内容
        $result = array_diff($tmpArr, array($removedValue));
        //返回数组
        return $result;
    }

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
     * 替换所有的HTML标签
     *
     */
    public static function clearHtml($str){
        $str=html_entity_decode($str) ;
        $str=preg_replace("/<.+?>/i","",$str);
        return $str;
    }


}