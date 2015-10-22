<?php
/**
 * 清除空白
 *
 * @param string $content
 * @return string
 */
function clear_space($content){
    if (strlen($content)==0) return $content; $r = $content;
    $r = str_replace(array(chr(9),chr(10),chr(13)),'',$r);
    while (strpos($r,chr(32).chr(32))!==false || strpos($r,'&nbsp;')!==false) {
        $r = str_replace(chr(32).chr(32),chr(32),str_replace('&nbsp;',chr(32),$r));
    }
    return $r;
}
/**
 * 内容截取，支持正则
 *
 * $start,$end,$clear 支持正则表达式，“/”斜杠开头为正则模式
 * $clear 支持数组
 *
 * @param string $content           内容
 * @param string $start             开始代码
 * @param string $end               结束代码
 * @param string|array $clear      清除内容
 * @return string
 */
function mid($content, $start, $end = null, $clear = null) {
    if (empty($content) || empty($start)) return null;
    if ( strncmp($start, '/', 1) === 0) {
        if (preg_match($start, $content, $args)) {
            $start = $args[0];
        }
    }
    if ( $end && strncmp($end, '/', 1) === 0 ) {
        if (preg_match($end, $content, $args)) {
            $end = $args[0];
        }
    }
    $start_len = strlen($start); $result = null;
    $start_pos = stripos($content,$start); if ($start_pos === false) return null;
    $length    = $end===null ? null : stripos(substr($content,-(strlen($content)-$start_pos-$start_len)),$end);
    if ($start_pos !== false) {
        if ($length === null) {
            $result = trim(substr($content, $start_pos + $start_len));
        } else {
            $result = trim(substr($content, $start_pos + $start_len, $length));
        }
    }
    if ($result && $clear) {
        if (is_array($clear)) {
            foreach ($clear as $v) {
                if ( strncmp($v, '/', 1) === 0 ) {
                    $result = preg_replace($v, '', $result);
                } else {
                    if (strpos($result, $v) !== false) {
                        $result = str_replace($v, '', $result);
                    }
                }
            }
        } else {
            if ( strncmp($clear, '/', 1) === 0 ) {
                $result = preg_replace($clear, '', $result);
            } else {
                if (strpos($result,$clear) !== false) {
                    $result = str_replace($clear, '', $result);
                }
            }
        }
    }
    return $result;
}
/**
 * 格式化URL地址
 *
 * 补全url地址，方便采集
 *
 * @param string $base  页面地址
 * @param string $html  html代码
 * @return string
 */
function format_url($base, $html) {
    if (preg_match_all('/<(img|script)[^>]+src=([^\s]+)[^>]*>|<(a|link)[^>]+href=([^\s]+)[^>]*>/iU', $html, $matchs)) {
        $pase_url  = parse_url($base);
        $base_host = sprintf('%s://%s',   $pase_url['scheme'], $pase_url['host']);
        if (($pos=strpos($pase_url['path'], '#')) !== false) {
            $base_path = rtrim(dirname(substr($pase_url['path'], 0, $pos)), '\\/');
        } else {
            $base_path = rtrim(dirname($pase_url['path']), '\\/');
        }
        $base_url = $base_host.$base_path;
        foreach($matchs[0] as $match) {
            if (preg_match('/^(.+(href|src)=)([^ >]+)(.+?)$/i', $match, $args)) {
                $url = trim(trim($args[3],'"'),"'");
                // http 开头，跳过
                if (preg_match('/^(http|https|ftp)\:\/\//i', $url)) continue;
                // 邮件地址和javascript
                if (strncasecmp($url, 'mailto:', 7)===0 || strncasecmp($url, 'javascript:', 11)===0) continue;
                // 绝对路径
                if (strncmp($url, '/', 1) === 0) {
                    $url = $base_host.$url;
                }
                // 相对路径
                elseif (strncmp($url, '../', 3) === 0) {
                    while (strncmp($url, '../', 3) === 0) {
                        $url = substr($url, -(strlen($url)-3));
                        if(strlen($base_path) > 0){
                            $base_path = dirname($base_path);
                            if ($base_path=='/') $base_path = '';
                        }
                        if ($url == '../') {
                            $url = ''; break;
                        }
                    }
                    $url = $base_host.$base_path.'/'.$url;
                }
                // 当前路径
                elseif (strncmp($url, './', 2) === 0) {
                    $url = $base_url.'/'.substr($url, 2);
                }
                // 其他
                else {
                    $url = $base_url.'/'.$url;
                }
                // 替换标签
                $html = str_replace($match, sprintf('%s"%s"%s', $args[1], $url, $args[4]), $html);
            }
        }
    }
    return $html;
}


$file = array_shift($argv);
$func = array_shift($argv);

array_unshift($argv, file_get_contents('php://stdin'));

if (empty($argv)) {
    echo call_user_func($func);
} else {
    echo call_user_func_array($func, $argv);
}
