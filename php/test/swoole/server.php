<?php
/**
 * User: 疯牛
 * Time: 15/2/28 下午3:59
 * Email: liu1s0404@outlook.com
 */
if (!extension_loaded('swoole')) {
    die('swoole extension not found');
}

define('ROOT', __DIR__);

require_once(ROOT . '/config.ini');

$serv = new swoole_server("127.0.0.1", 9501, SWOOLE_BASE, SWOOLE_SOCK_TCP);

$serv->set(array(
    //'tcp_defer_accept' => 5,
    //'ipc_mode' => 2,
    'worker_num' => 4,
    'task_worker_num' => 2,
    //'max_request' => 1000,
    //'daemonize' => true,
    //'log_file' => '/tmp/swoole.log'
));
$serv->on('timer', function($serv, $interval) {
    echo "onTimer: $interval\n";
});

$serv->on('start', function($serv) {
    //$serv->addtimer(1000);
});

$serv->on('workerStart', function($serv, $worker_id) {
    echo "server start\n";
    //if($worker_id == 0) $serv->addtimer(1000);
});

$serv->on('connect', function ($serv, $fd, $from_id){
    //echo "[#".posix_getpid()."]\tClient@[$fd:$from_id]: Connect.\n";
});

$serv->on('task', function ($serv, $task_id, $from_id, $data){
    //var_dump($task_id, $from_id, $data);
    $fd = $data;
    $serv->send($fd, str_repeat('B', 1024*rand(40, 60)).rand(10000, 99999)."\n");
});

$serv->on('finish', function ($serv, $fd, $from_id){

});

$serv->on('receive', function (swoole_server $serv, $fd, $from_id, $data) {
    echo "[#".$serv->worker_pid."]\tClient[$fd]: $data\n";
    //$info = $serv->connection_info($fd);
    //$t = microtime(true);
    //trigger_error(E_WARNING, "Test warning");
    //$serv->task($fd);
    //$serv->send($fd, str_repeat('B', 1024*rand(4, 6)).rand(10000, 99999)."\n");
    //echo "use. ".((microtime(true) - $t)*1000)."ms\n";
    $serv->send($fd, json_encode(array("hello" => '1213', "bat" => "ab")).PHP_EOL);
    //$serv->close($fd);
});

$serv->on('close', function ($serv, $fd, $from_id) {
    echo "[#".posix_getpid()."]\tClient@[$fd:$from_id]: Close.\n";
});

$serv->start();
