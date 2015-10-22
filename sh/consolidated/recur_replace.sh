#!/usr/bin/bash

shell_path=$(dirname $0)
ab_shell_path="$PWD/$shell_path"

if [ -z $1 ];then
    echo "请输入替换目录绝对地址，不要包含~"
    exit
fi

function replace()
{
    cd $1;
    items=`ls`
    arr=($items)
    for item in ${arr[@]}
    do
        if [ -d $item ];then
            echo $1/$item
            bash $ab_shell_path/recur_replace.sh $1/$item
        else
            #ls $item
            if [ "$item" != "recur_replace.sh" ];then
                sed -i "" "s/Higoservice/Goodsservice/g" $item
            fi
        fi
    done
}

replace_dir=$1

replace $replace_dir
