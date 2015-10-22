<?php
/**
 * 闭包函数测试
 *
 * Author: 疯牛
 * Time: 15/5/29 下午4:20
 * Email: liu1s0404@outlook.com
 */

function criteria_greater_than($min)
{
    return function($item) use ($min) {
        return $item > $min;
    };
}

$input = array(1, 2, 3, 4, 5, 6);

// Use array_filter on a input with a selected filter function
$output = array_filter($input, criteria_greater_than(3));

print_r($output); // items > 3


function callback($callback) {
    $callback();
}

$msg = "Hello, everyone";
$callback = function () use ($msg){
    print "This is a closure use string value, msg is: $msg. <br />\n";
};
$msg ="Hello, everybody";
callback($callback);