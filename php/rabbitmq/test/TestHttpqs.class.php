<?php
/**
 * User: 疯牛
 * Time: 15/2/26 下午6:34
 * Email: liu1s0404@outlook.com
 */
namespace Snake\Scripts;

require_once(ROOT_PATH . '/libs/xhprof/xhprof_lib/utils/xhprof_lib.php');
require_once(ROOT_PATH . '/libs/xhprof/xhprof_lib/utils/xhprof_runs.php');

ini_set('xhprof.output_dir', '/home/work/webdata/logs/xhprof/rabbitmq_test');
xhprof_enable();

$_request_url = "http://xxx/?name=test&opt=put&auth=mypass123";
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $_request_url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($curl, CURLOPT_HEADER, FALSE);
curl_setopt($curl, CURLOPT_POST, 1);
curl_setopt($curl, CURLOPT_TIMEOUT, 1);

for($i=1;$i<=10000;$i++)
{
    curl_setopt($curl, CURLOPT_POSTFIELDS, $i);
    $resp = curl_exec($curl);
    var_dump($resp . $i);
}

curl_close($curl);

$xhprof_data = xhprof_disable();
$xhprof_runs = new \Snake\Libs\Xhprof\Xhprof_lib\Utils\XHProfRuns_Default();
$run_id = $xhprof_runs->save_run($xhprof_data, "xhprof_site");
echo "***". $run_id . "***";