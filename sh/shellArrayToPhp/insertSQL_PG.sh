#!/bin/sh
#author 疯牛

#PG 配置
ppcDbWritehost=xxx
ppcDbWriteUser=xxx
ppcDbWritePsw=xxx
dbname=ajk_dw_stats
#插入变量配置
cityId=11
commid=`php -r 'echo rand(1,10000000);'`
pricerank=`php -r 'echo rand(1,14);'`
scorerank=`php -r 'echo rand(1,3);'`
marketScore=`php -r 'echo rand(1,10000);'`
type=`php -r 'echo rand(1,2);'`
isY=`php -r 'echo rand(0,1);'`

for((i=1;i<1000;i++));do
cityId=11
commid=`php -r 'echo rand(1,10000000);'`
pricerank=`php -r 'echo rand(1,14);'`
scorerank=`php -r 'echo rand(1,3);'`
marketScore=`php -r 'echo rand(1,10000);'`
type=`php -r 'echo rand(1,2);'`
isY=`php -r 'echo rand(0,1);'`

(mysql -A $dbname -h$ppcDbWritehost -u$ppcDbWriteUser -p$ppcDbWritePsw<<EOF
INSERT INTO ajk_market_analysis(daydate,cityid,blockid,commid,pricerank,averageVPPV,marketScore,scorerank,TYPE,totalvppv,spreadPropNum,isY) values (20131011,$cityId,1,$commid,$pricerank,10000,$marketScore,$scorerank,$type,10000,1000,$isY);
EOF
)

done



#`echo "INSERT INTO ajk_ppccoin_city_sale(cityid,coin,isopen) VALUES ($cityId,$coin,$isOpen)" | mysql -A ppc_db -h$ppcDbWritehost -u$ppcDbWriteUser -p$ppcDbWritePsw` >> 1.txt

#(mysql -A $dbname -h$ppcDbWritehost -u$ppcDbWriteUser -p$ppcDbWritePsw<<EOF
#INSERT INTO ajk_market_analysis(daydate,cityid,blockid,commid,pricerank,averageVPPV,marketScore,scorerank,TYPE,totalvppv,spreadPropNum,isY) values (20131011,$cityId,1,$commid,$pricerank,10000,$marketScore,$scorerank,$type,10000,1000,$isY);
#EOF
#)

echo exit
