#!/bin/bash
SERVICE_PATH=/home/service
BACKUP_PATH=/data/backup
MYSQL_BACKUP_PATH=$BACKUP_PATH/mysql
MYSQL_PATH=$SERVICE_PATH/mysql5.6
MYSQL_BIN_PATH=$MYSQL_PATH/bin/
MYSQL_USERNAME=mua
MYSQL_PASSWORD=""
MYSQL_HOST="127.0.0.1"
MYSQL_PORT=3306
NOW_BACKUP_PATH=$MYSQL_BACKUP_PATH/`date '+%Y%m%d'`
mkdir -p  $NOW_BACKUP_PATH
$MYSQL_BIN_PATH/mysql -N -e "show databases" -u$MYSQL_USERNAME -p$MYSQL_PORT -h$MYSQL_HOST -p$MYSQL_PASSWORD | grep -vE 'information|performance_schema|mysql|test' | while read db_name
do
            $MYSQL_BIN_PATH/mysqldump -u$MYSQL_USERNAME -p$MYSQL_PORT -h$MYSQL_HOST -p$MYSQL_PASSWORD -B $db_name > $NOW_BACKUP_PATH/$db_name".sql"
done
cd $MYSQL_BACKUP_PATH
tar zcvf `date '+%Y%m%d'`".tgz"  $NOW_BACKUP_PATH
cd ~
rm -fr $NOW_BACKUP_PATH