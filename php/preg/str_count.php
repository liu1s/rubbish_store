<?php
/**
 * 统计一个字符串中出现数字和字符的数组
 *
 * @author 疯牛 liu1s0404@outlook.com
 * @package: personal
 */

$str = 'a23。/啊b4';

echo countIntDisplayNum($str);
echo PHP_EOL;
echo countCDisplayNum($str);

function countIntDisplayNum($str)
{
    if (preg_match_all('/[0-9]{1,1}/i', $str, $match)) {
        return count($match[0]);
    }
    return 0;
}

function countCDisplayNum($str)
{
    if (preg_match_all('/[a-zA-Z]{1,1}/i', $str, $match)) {
        return count($match[0]);
    }
    return 0;
}