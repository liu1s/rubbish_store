<?php
/**
 * #desc html tags filter bll
 * @author leoma
 */

class Util_StrFilter{
    public static function xss_filter($str){
        return preg_replace('/\<script\>.+\<\/script\>/i', '', $str);
    }

    public static function http_filter($str){
        return preg_replace('/(http:\/\/)|(https:\/\/)/i', '', $str);
    }

    public static function tag_filter($tags=array(), $str){
        if($tags){
            foreach ($tags as $tag){
                $str = preg_replace("/(<|&lt;)(\/?\s*".$tag.".*?)(>|&gt;)/i","",$str);
            }
        }
        return $str;
    }

    public static function tags_filter($str){
        return preg_replace("/(<|&lt;)\/?.+?(>|&gt;)/i","",$str);
    }

    public static function id_class_filter($str){
        return preg_replace("/(class|id)=('|\").*('|\")/i", "", $str);
    }

    /**
     * @author leoma
     * @param
     * array(
    'html' => html code,
    'options' => array(
    'tagArray' => tags in close mode when type is nest,
    'type' => mode:'NEST', 'CLOSE',
    'length' => cut length (null when not necessary),
    'lowerTag' => (str to lower, default TRUE),
    'XHtmlFix' => (format standard xhtml tags, default TRUE),
    )
    );
     */
    public static function autoCompleteUnclosedTag($param = array()){
        //参数的默认值
        $html = '';
        $tagArray = array();
        $type = 'NEST';
        $length = null;
        $lowerTag = TRUE;
        $XHtmlFix = TRUE;

        //首先获取一维数组，即 $html 和 $options （如果提供了参数）
        extract($param);

        //如果存在 options，提取相关变量
        if (isset($options)) {
            extract($options);
        }

        $result = ''; //最终要返回的 html 代码
        $tagStack = array(); //标签栈，用 array_push() 和 array_pop() 模拟实现
        $contents = array(); //用来存放 html 标签
        $len = 0; //字符串的初始长度

        //设置闭合标记 $isClosed，默认为 TRUE, 如果需要就近闭合，成功匹配开始标签后其值为 false,成功闭合后为 true
        $isClosed = true;

        //将要处理的标签全部转为小写
        $tagArray = array_map('strtolower', $tagArray);

        //“合法”的单闭合标签
        $singleTagArray = array(
            '<meta', '<link', '<base', '<br', '<hr', '<input', '<img', '<area'
        );

        //校验匹配模式 $type，默认为 NEST 模式
        $type = strtoupper($type);
        if (!in_array($type, array('NEST', 'CLOSE'))) {
            $type = 'NEST';
        }

        //以一对 < 和 > 为分隔符，将原 html 标签和标签内的字符串放到数组中
        $contents = preg_split("/(<[^>]+?>)/si", $html, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

        foreach ($contents as $tag) {
            if ('' == trim($tag)) {
                $result .= $tag;
                continue;
            }

            //匹配标准的单闭合标签，如<br />
            if (preg_match("/<(\w+)[^\/>]*?\/>/si", $tag)) {
                $result .= $tag;
                continue;
            }

            //匹配开始标签，如果是单标签则出栈
            else if (preg_match("/<(\w+)[^\/>]*?>/si", $tag, $match)) {
                //如果上一个标签没有闭合，并且上一个标签属于就近闭合类型
                //则闭合之，上一个标签出栈

                //如果标签未闭合
                if (false === $isClosed) {
                    //就近闭合模式，直接就近闭合所有的标签
                    if ('CLOSE' == $type) {
                        $result .= '</' . end($tagStack) . '>';
                        array_pop($tagStack);
                    }
                    //默认的嵌套模式，就近闭合参数提供的标签
                    else {
                        if (in_array(end($tagStack), $tagArray)) {
                            $result .= '</' . end($tagStack) . '>';
                            array_pop($tagStack);
                        }
                    }
                }

                //如果参数 $lowerTag 为 TRUE 则将标签名转为小写
                $matchLower = $lowerTag == TRUE ? strtolower($match[1]) : $match[1];

                $tag = str_replace('<' . $match[1], '<' . $matchLower, $tag);
                //开始新的标签组合
                $result .= $tag;
                array_push($tagStack, $matchLower);

                //如果属于约定的的单标签，则闭合之并出栈
                foreach ($singleTagArray as $singleTag) {
                    if (stripos($tag, $singleTag) !== false) {
                        if ($XHtmlFix == TRUE) {
                            $tag = str_replace('>', ' />', $tag);
                        }
                        array_pop($tagStack);
                    }
                }

                //就近闭合模式，状态变为未闭合
                if ('CLOSE' == $type) {
                    $isClosed = false;
                }
                //默认的嵌套模式，如果标签位于提供的 $tagArray 里，状态改为未闭合
                else {
                    if (in_array($matchLower, $tagArray)) {
                        $isClosed = false;
                    }
                }
                unset($matchLower);
            }

            //匹配闭合标签，如果合适则出栈
            else if (preg_match("/<\/(\w+)[^\/>]*?>/si", $tag, $match)) {

                //如果参数 $lowerTag 为 TRUE 则将标签名转为小写
                $matchLower = $lowerTag == TRUE ? strtolower($match[1]) : $match[1];

                if (end($tagStack) == $matchLower) {
                    $isClosed = true; //匹配完成，标签闭合
                    $tag = str_replace('</' . $match[1], '</' . $matchLower, $tag);
                    $result .= $tag;
                    array_pop($tagStack);
                }
                unset($matchLower);
            }

            //匹配注释，直接连接 $result
            else if (preg_match("/<!--.*?-->/si", $tag)) {
                $result .= $tag;
            }

            //将字符串放入 $result ，顺便做下截断操作
            else {
                if (is_null($length) || $len + mb_strlen($tag) < $length) {
                    $result .= $tag;
                    $len += mb_strlen($tag);
                } else {
                    $str = mb_substr($tag, 0, $length - $len + 1);
                    $result .= $str;
                    break;
                }
            }
        }

        //如果还有将栈内的未闭合的标签连接到 $result
        while (!empty($tagStack)) {
            $result .= '</' . array_pop($tagStack) . '>';
        }
        return $result;
    }

    /**
     * 房源描述显示过滤
     * @params p_strHtml
     * @return string
     */
    public static function house_content_filter($p_strHtml){

        //去除截掉的无用信息
        $arrHtmlTags = array('strong', 'p', 'a','div','tr','td','tbody','table','span');
        $intLen = strlen($p_strHtml);
        if($intLen > 65530 && $p_strHtml[$intLen-1] != '>'){
            $intCloseTagPos = 1;
            foreach($arrHtmlTags as $strTag){
                $strTag = '</'.$strTag.'>';
                $intPos = strrpos($p_strHtml,$strTag)+strlen($strTag);
                if($intPos){
                    if($intPos > $intCloseTagPos){
                        $intCloseTagPos = $intPos;
                    }
                }
            }
            $p_strHtml =  substr($p_strHtml,0,$intCloseTagPos);
        }

        #put all opened tags into an array
        preg_match_all('#<([a-z]+)(?: .*)?(?<![/|/ ])[ ]*>#iU', $p_strHtml, $arrResult);
        $arrOpenedTags = $arrResult[1];

        #put all closed tags into an array
        preg_match_all('#</([a-z]+)>#iU', $p_strHtml, $arrResult);
        $arrClosedTags = $arrResult[1];
        $intLenOpened = count($arrOpenedTags);

        # all tags are closed
        if (count($arrClosedTags) == $intLenOpened) {
            return $p_strHtml;
        }
        $arrOpenedTags = array_reverse($arrOpenedTags);

        # close tags
        for ($i=0; $i < $intLenOpened; $i++) {
            if (!in_array($arrOpenedTags[$i], $arrClosedTags)){
                $p_strHtml .= '</'.$arrOpenedTags[$i].'>';
            } else {
                unset($arrClosedTags[array_search($arrOpenedTags[$i], $arrClosedTags)]);
            }
        }

        return $p_strHtml;
    }

    public static function str_ch($str){
        $str = trim($str);
        $str = preg_replace('/<!--(.*)-->/i', '',$str);
        $str = preg_replace('/(<(.[^>]*)>)|(&nbsp;)/i', '',$str);
        $str = preg_replace('/\s*/i', '',$str);
        return $str;
    }

    /**
     * 查询违规词
     *
     * @param string $str
     * @return array
     */
    public static function checkIllegalWord($str)
    {
        $query = array('dicname' => 'banwords-dict', 'text' => $str);
        $host = APF::get_instance()->get_config('mss_host');
        $url = sprintf('%s/mss/match?%s', $host, http_build_query($query));
        $result = Util_Curl::service_curl($url);
        if (false === $result) {
            return array('status' => 'error');
        }
        return $result;
    }
}