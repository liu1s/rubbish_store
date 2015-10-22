<?php

define('DB_HOST', 'xxx');
define('DB_USER', 'xxx');
define('DB_PWD', 'xxx');

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

            $sql1[] = "alter table queue_task_flow_log_$dateSuffix add column `mark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注';";
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