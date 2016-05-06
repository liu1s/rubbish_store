#!/bin/bash
#对redis命中率统计

current_shell_path=$(dirname $0)

host="127.0.0.1"
port=6379
redis_cli_path="redis-cli"

info_res=`echo "info" | $redis_cli_path -h $host -p $port > $current_shell_path/tmp_redis_static.log`

awk -F ':' 'BEGIN {hit=0;miss=0} { if($0~"keyspace_hits"){ hit =$2 } if($0~"keyspace_misses") { miss =$2 } } END {print (hit/(hit+miss))*100"%"}' $current_shell_path/tmp_redis_static.log

#删除中间统计文件
if [ -f $current_shell_path/tmp_redis_static.log ];then
    rm $current_shell_path/tmp_redis_static.log
fi