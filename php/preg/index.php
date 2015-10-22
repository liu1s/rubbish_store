<?php
/**
 * grep例子
 *
 * @author 疯牛 liu1s0404@outlook.com
 * @package: personal
 */

$string1 = "liu1s0404@outlook.com";
$string2 = "liu1s0404@139.com"; //liu1s0404@139.12

$emailPattern = '/(.+)@([0-9a-z]+\.[a-z]+)/';

$pregResult1 = preg_match($emailPattern, $string1, $match1);
$pregResult2 = preg_match($emailPattern, $string2, $match2);

var_dump($pregResult1 . PHP_EOL);
var_dump($match1);
var_dump('--------------');
var_dump($pregResult2 . PHP_EOL);
var_dump($match2);