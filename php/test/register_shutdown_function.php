<?php
/**
 * User: 疯牛
 * Time: 15/3/11 下午5:34
 * Email: liu1s0404@outlook.com
 */

register_shutdown_function('a');
register_shutdown_function('b');

function a()
{
    echo 1;
}

function b()
{
    echo 2;
}