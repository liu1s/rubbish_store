#!/bin/bash
echo "请输入密码";
read -s password;


#启动服务函数
#$1 对应服务启动命令
#$2 密码
function server_start()
{
    cd /Users/mls/server_start_path;
    grepResult=$(ps -ef | grep "$1" | grep -v "grep");
    if [ "$grepResult" = "" ];then
        $(echo $2 | sudo -S $1 1>/dev/null 2>&1) > /dev/null &
    if [ $? -eq 0 ];then
        echo -e "\033[0;32;1m $1 start success \033[0;32;0m \r\n";
    else
        echo -e "\033[1;31;1m $1 start fail \033[1;31;1m \r\n";
    fi
    else
        echo -e "\033[1;34;1m $1 has start \033[0;32;0m \r\n";
    fi
}

#start nginx
server_start /usr/local/nginx/sbin/nginx $password

#start php-fpm
server_start ~/.phpbrew/php/php-5.6.3/sbin/php-fpm $password

#start redis
server_start /usr/local/redis/bin/redis-server $password

#start memcache
server_start "memcached -d -u root -m 1024 -p 12121 -c 1024 -v" $password
#/home/service/memcached-1.4.20/bin/memcached -d -u root -m 1024 -p 12221 -c 1024 -v
#/home/service/memcached-1.4.20/bin/memcached -d -u root -m 1024 -p 12224 -c 1024 -v
#/home/service/memcached-1.4.20/bin/memcached -d -u root -m 1024 -p 12225 -c 1024 -v
#/home/service/memcached-1.4.20/bin/memcached -d -u root -m 1024 -p 12125 -c 1024 -v
#/home/service/memcached-1.4.20/bin/memcached -d -u root -m 1024 -p 12124 -c 1024 -v

#start sshd
server_start /usr/sbin/sshd $password

#start rabbit
server_start /usr/local/Cellar/rabbitmq/3.4.1/sbin/rabbitmq-server $password
