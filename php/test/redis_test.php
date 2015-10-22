<?php
/**
 * User: 疯牛
 * Time: 15/3/11 下午3:11
 * Email: liu1s0404@outlook.com
 */
$redis = new redis();
$res = $redis->connect('127.0.0.1', 6379, 1, 100);


while(true) {
    var_dump($redis->ping());
    sleep(1);
}
