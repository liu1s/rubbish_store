<?php
/**
 * User: 疯牛
 * Time: 15/2/25 下午6:03
 * Email: liu1s0404@outlook.com
 */
namespace Snake\Scripts;

use PhpAmqpLib\Connection\AMQPConnection;
#use PhpAmqpLib\Connection\AMQPSocketConnection;
use PhpAmqpLib\Message\AMQPMessage;
require_once(ROOT_PATH . '/libs/xhprof/xhprof_lib/utils/xhprof_lib.php');
require_once(ROOT_PATH . '/libs/xhprof/xhprof_lib/utils/xhprof_runs.php');

ini_set('xhprof.output_dir', '/home/work/webdata/logs/xhprof/rabbitmq_test');
xhprof_enable();


for($i=0;$i<=10000;$i++) {
    $connection = new AMQPConnection('xxxx', 5672, 'guest', 'guest');
    #$connection = new AMQPSocketConnection('localhost', 5672, 'guest', 'guest');
    $channel = $connection->channel();

    #$channel->exchange_declare('direct_logs', 'direct', false, false, false);
    #$channel->exchange_declare('queues', 'direct', false, true, false);

    $data = $i . 'abcdadahdakjdhakjdahkdahkdjahdkjahdkjahdkjahdkahdkahdkajdhk';
    $msg = new AMQPMessage($data);
    $severity = 'test';
    $channel->basic_publish($msg, 'direct_logs', $severity);
    echo " [x] Sent ",$severity,':',$data," \n";

    $channel->close();
    $connection->close();
}



$xhprof_data = xhprof_disable();

$xhprof_runs = new \Snake\Libs\Xhprof\Xhprof_lib\Utils\XHProfRuns_Default();

$run_id = $xhprof_runs->save_run($xhprof_data, "xhprof_site");
echo "***". $run_id . "***";