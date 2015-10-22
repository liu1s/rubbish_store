<?php
if (!function_exists('cal_days_in_month'))
{
    function cal_days_in_month($calendar, $month, $year)
    {
        return date('t', mktime(0, 0, 0, $month, 1, $year));
    }
}

if (!defined('CAL_GREGORIAN'))
    define('CAL_GREGORIAN', 1);



define('DB_HOST', 'xxxx');
define('DB_USER', 'xxxx');
define('DB_PWD', 'xxxx');

create_table();

for($month = 1; $month<=12; $month++) {
    $dayCount = cal_days_in_month(CAL_GREGORIAN, $month, date('Y'));
    for($day = 1; $day <= $dayCount; $day++) {
        if ($month >= 10) {
            $dateSuffix = date('Y') . $month . ($day >= 10 ? $day : '0' . $day);
        } else {
            $dateSuffix = date('Y') . '0' . $month . ($day >= 10 ? $day : '0' . $day);
        }
        echo $dateSuffix . PHP_EOL;
    }
}


function init() {
    $db_host =DB_HOST;
    $db_user =DB_USER;
    $db_pwd = DB_PWD;
    $conn = mysql_connect($db_host, $db_user, $db_pwd);
    if (!$conn) {
        echo mysql_error();
        exit();
    }
    mysql_query('set names utf8');
    return $conn;
}

function create_table(){
	$conn = init();
    $sql1[] = 'use db_service';


    for($month = 1; $month<=12; $month++) {
        $dayCount = cal_days_in_month(CAL_GREGORIAN, $month, date('Y'));
        for($day = 1; $day <= $dayCount; $day++) {
            if ($month >= 10) {
                $dateSuffix = date('Y') . $month . ($day >= 10 ? $day : '0' . $day);
            } else {
                $dateSuffix = date('Y') . '0' . $month . ($day >= 10 ? $day : '0' . $day);
            }

            $sql1[] = "CREATE TABLE IF NOT EXISTS `queue_error_log_{$dateSuffix}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `origin_data` text NOT NULL COMMENT '出错队列数据',
  `error` text NOT NULL COMMENT '错误信息',
  `type` smallint(2) NOT NULL DEFAULT '1' COMMENT '类型，1-致命错误 2-return false达到最大次数',
  `queue_name` varchar(25) NOT NULL DEFAULT '' COMMENT '出错队列名称',
  `task_id` int(11) NOT NULL DEFAULT '0' COMMENT '任务id',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `task_id` (`task_id`),
  KEY `name_type` (`queue_name`,`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='队列错误记录日志表'";
        }
    }


    foreach ($sql1 as $s) {
        $result = mysql_query($s, $conn);
        if (!$result) {
            echo ('Invalid query: ' . mysql_error());
            exit();
        }
    }
}