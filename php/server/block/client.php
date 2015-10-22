<?php
/**
 * User: 疯牛
 * Time: 15/2/27 下午4:56
 * Email: liu1s0404@outlook.com
 */
set_time_limit(0);

$host = "127.0.0.1";
$port = 4412;
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)or die("Could not create  socket\n"); // 创建一个Socket

$connection = socket_connect($socket, $host, $port) or die("Could not connet server\n");    //  连接
socket_write($socket, "hello socket 1111") or die("Write failed\n"); // 数据传送 向服务器发送消息
while ($buff = socket_read($socket, 1024, PHP_NORMAL_READ)) {
    echo("Response was:" . $buff . "\n");
}

socket_close($socket);