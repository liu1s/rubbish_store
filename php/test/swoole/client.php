<?php
/**
 * User: 疯牛
 * Time: 15/2/28 下午5:14
 * Email: liu1s0404@outlook.com
 */
define('ROOT', __DIR__);
require_once(ROOT . '/config.ini');
$client = new swoole_client(SWOOLE_SOCK_TCP, SWOOLE_SOCK_ASYNC); //异步非阻塞

$client->on("connect", function($cli) {
    $cli->send("hello world\n");
});

$client->on("receive", function($cli, $data = ""){
    //$data = $cli->recv(); //1.6.10+ 不需要
    if(empty($data)){
        $cli->close();
        echo "closed\n";
    } else {
        echo "received: $data\n";
        sleep(1);
        $cli->send("hello\n");
    }
});

$client->on("close", function($cli){
    $cli->close(); // 1.6.10+ 不需要
    echo "close\n";
});

$client->on("error", function($cli){
    exit("error\n");
});

$client->connect($serverHost, $serverPort, 0.5);