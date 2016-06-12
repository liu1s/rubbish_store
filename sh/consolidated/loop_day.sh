#!/bin/bash
##展示（$1+1）到($2+1)的时间

date="$1"
enddate=`date -d "+1 day $2" +%Y%m%d`

while [[ $date < $enddate ]]
do
        date=`date -d "+1 day $date" +%Y%m%d`
        echo $date
done
