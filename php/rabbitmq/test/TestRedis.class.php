<?php
/**
 * User: 疯牛
 * Time: 15/2/26 下午5:21
 * Email: liu1s0404@outlook.com
 */
namespace Snake\Scripts;

require_once(ROOT_PATH . '/libs/xhprof/xhprof_lib/utils/xhprof_lib.php');
require_once(ROOT_PATH . '/libs/xhprof/xhprof_lib/utils/xhprof_runs.php');

ini_set('xhprof.output_dir', '/home/work/webdata/logs/xhprof/rabbitmq_test');
xhprof_enable();

$redis = new \redis();
$redis->connect('xxxx', 6379);

for($i=1;$i<=10000;$i++)
{
    var_dump($redis->lpush("test","111"));
}

$xhprof_data = xhprof_disable();
$xhprof_runs = new \Snake\Libs\Xhprof\Xhprof_lib\Utils\XHProfRuns_Default();
$run_id = $xhprof_runs->save_run($xhprof_data, "xhprof_site");
echo "***". $run_id . "***";