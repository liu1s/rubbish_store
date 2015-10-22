#!/usr/bin/env bash

shell_path=$(dirname $0)
grep_path=$shell_path/../..
ab_shell_path="$PWD/$shell_path"

#if [ -z $1 ];then
#    echo "请输入检查目录绝对地址，不要包含~"
#    exit
#fi
#
#if [ ! -z $2 ];then
#    ab_shell_path=$2
#fi

##ip地址检查
grep -r -E "[0-9]+\.[0-9]+\.[0-9]+\.[0-9]+" $grep_path | grep -v 127.0.0.1 | grep -v 192.168. | grep -v 0.0.0.0 | grep -v /c/ | grep -v /python/scrapy_study


##域名检查
grep -r -E "[a-z]+\.com" $grep_path | grep -v .git | grep meilishuo

####
# mac grep有时候莫名其妙的卡在某个未知不动，单linux不会。。。
# 所有采用循环方式处理
####

#function recur_grep()
#{
#    cd $1;
#    items=`ls`
#    arr=($items)
#    for item in ${arr[@]}
#    do
#        if [ -d $item ];then
#            bash $ab_shell_path/recur_check.sh $1/$item $ab_shell_path
#        else
#            #ls $item
#            if [ "$item" != "recur_ckeck.sh" ];then
#                grep -r -E "[0-9]+\.[0-9]+\.[0-9]+\.[0-9]+" $item
#            fi
#        fi
#    done
#}
#
#recur_grep $1