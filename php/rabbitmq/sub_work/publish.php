<?php
/**
 * User: 疯牛
 * Time: 15/1/20 下午4:54
 * Email: liu1s0404@outlook.com
 */
require_once __DIR__ . '/../../vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

$channel->queue_declare('task_queue', false, true, false, false);

for($i=1; $i<5; $i++) {
    $msg = new AMQPMessage($i,
        array('delivery_mode' => 2) # make message persistent
    );

    $channel->basic_publish($msg, '', 'task_queue');

    echo " [x] Sent ", $i, "\n";
}

$channel->close(); //使用delivery_mode=2时，必须调用这个方法，不然数据不会保存
$connection->close();