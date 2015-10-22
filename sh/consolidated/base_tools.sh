#!/bin/bash
#一些常用函数的封装

#错误退出并打印错误信息
#$1 信息
function error_return()
{
    error_print "$1 fail";
    exit;
}

#格式化输出错误信息
#$1 信息
function error_print()
{
    echo -e "\033[1;31;1m $1 \033[1;31;0m \r\n";
}

#格式化输出正确信息
#$1 信息
function success_print()
{
    echo -e "\033[0;32;1m $1 \033[0;32;0m \r\n";
}

#检查动作成功与否
#$1 动作名称
function action_check()
{
    if [ $? -ne 0 ];then
        error_return "$1";
    else
        success_print "$1 success";
    fi
}

#获取脚本所在位置
function get_shell_path()
{
    shell_path=$(dirname $0)
}

# 检查目录是否存在
# $1 目录路径
function path_check()
{
    if [ ! -d $1 ];then
        error_print "directory ${1} not exist"
        exit
    fi
}