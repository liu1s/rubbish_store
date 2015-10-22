#!/bin/bash
source_path='/home/work';
app_name='tms'
svn_path='xxxx'

source $(dirname $0)"/baseTools.sh"

user=`whoami`
if [ "$user" != "work" ];then
    echo "Please User work";
    exit 0;
fi

if [ -d "$source_path/.$app_name/" ];then
    cd $source_path/.$app_name;
    svn update
else
    svn export --force $svn_path $source_path/.$app_name
fi

action_check "svn update";

#rm -rf  $source_path/.$app_name/config/*
#rm -rf  $source_path/.$app_name/transport/host/*

rsync -av --exclude "config/*" --exclude "transport/host/*" --exclude "public/*" --exclude "bin/*" $source_path/.$app_name/* $source_path/$app_name/

action_check "rsync";

echo "Down!!"