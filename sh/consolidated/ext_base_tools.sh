#!/bin/bash

#
#找到可用的数据库配置
# $1 对应配置路径
# $2 查找的库的名字
#错误返回 1-配置没找到 2-文件不存在
#外部使用$mysql_db_command保存转换后的mysql命令行
function get_avaliable_db_conf()
{
    if [ ! -f $1 ];then
        echo 'db conf file not exist';
        return 2;
    fi

    bash -c "cd $(dirname $1);svn up $1 1>/dev/null 2>/dev/null"

    while read LINE
    do
        res=`echo $LINE | grep "=$2"`;
        if [ "$res" != "" ];then
            parse_db_conf_to_command "$LINE";
            echo "show databases" | $mysql_db_command --connect_timeout=1 2> /dev/null 1>/dev/null
            if [ $? -eq 0 ];then
                return 0;
            fi
        fi
    done < $1

    echo "no avaliable $2 db conf can be used in $1";
    return 1;
}

# 转换配置行为mysql命令行
# $1 conf串 示例 db=bat host=xxxxx port=xxxx weight=1 user=xxxxxxx pass=xxxx master=0
# 外部使用$mysql_db_command保存转换后的mysql命令行
function parse_db_conf_to_command()
{
    IFS=" "
    conf_arry=($1)
    for row in ${conf_arry[@]}
    do
        test "$(echo $row | grep 'db=')" != '' && local db=$(echo $row |cut -c 4-)
        test "$(echo $row | grep 'host=')" != '' && local host=$(echo $row |cut -c 6-)
        test "$(echo $row | grep 'port=')" != '' && local port=$(echo $row |cut -c 6-)
        test "$(echo $row | grep 'user=')" != '' && local user=$(echo $row |cut -c 6-)
        test "$(echo $row | grep 'pass=')" != '' && local pass=$(echo $row |cut -c 6-)
    done
    mysql_db_command="mysql -N -h$host -u$user -p$pass -P$port -A $db"
}

