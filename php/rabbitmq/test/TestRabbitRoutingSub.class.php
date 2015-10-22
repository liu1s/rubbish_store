<?php
/**
 * User: 疯牛
 * Time: 15/2/26 下午4:06
 * Email: liu1s0404@outlook.com
 */
namespace Snake\Scripts;

use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPConnection('xxx', 5672, 'guest', 'guest');
#$connection = new AMQPSocketConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

list($queue, ,) = $channel->queue_declare('test', false, true, false, false);
$channel->queue_bind($queue, 'direct_logs', 'test');
var_dump($queue);

$callback = function($msg){
    var_dump($msg);
};

$channel->basic_consume($queue, '', false, true, false, false, $callback);

while(count($channel->callbacks)) {
    $channel->wait();
}
$channel->close();
