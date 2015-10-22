<?php
/**
 * User: 疯牛
 * Time: 15/1/20 下午12:12
 * Email: liu1s0404@outlook.com
 */
require_once __DIR__ . '/../../vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPConnection;

$connection = new AMQPConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();


$channel->queue_declare('hello', false, false, false, false);



$message = $channel->basic_get('hello', false); //的二个参数为true时，自动提交
//yewuchuli

//$channel->basic_ack();
var_dump($message->body);


//echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";
//
//$callback = function($msg) {
//    echo " [x] Received ", $msg->body, "\n";
//};
//$channel->basic_consume('hello', '', false, true, false, false, $callback);
//
//while(count($channel->callbacks)) {
//    $channel->wait();
//}

$channel->close();
$connection->close();