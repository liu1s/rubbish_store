#!/bin/bash

#帮助内容输出
function thisHelp()
{
    echo "可选参数 -i 保存忽略文件列表的文件路径的文件路径，该文件中每个忽略文件一行代表一个文件"
}


ignore=""
#参数解析
while [ $# -gt 0 ];do
    case $1 in 
        '-h')
        thisHelp
        exit
        ;;
        '-i')
            shift
            if [ ! -f $1 ];then
                "$1 不是一个文件"
                exit
            fi
            while read Line
            do
                tmpFile=`echo $Line | awk '{print $1}'`
                ignore="$ignore | grep -v \"$tmpFile\""
            done < $1
        ;;
        *)
        thisHelp
        ;;
        esac
    shift
done
cmd="svn status $ignore"
#进入执行目录
cd $PWD
#查看哪些文件已变更
eval $cmd | while read line
do
echo $line
    fixCmd=`echo $line | awk '{ if($1=="M"){print "svn revert "$2}else if($1=="?" ){print "rm -r "$2} else if ($1=="A" || $1=="!"){print "svn delete "$2} }' `
    set -x
    eval $fixCmd
    set +x
done
