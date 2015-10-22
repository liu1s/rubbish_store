http://blog.csdn.net/zlcd1988/article/details/25324877

环境：CentOS

鉴于语句描述苍白无力，用例子直接说明。

文件内容：

zilzhang 19881110 jiangxi 18 film
zhagnsan 21321    sichuan 100 card


1. 打印整行

$ awk '{print $0}' mytxt
zilzhang 19881110 jiangxi 18 film
zhagnsan 21321    sichuan 100 card

2. 打印第三列

$ awk '{print $3}' mytxt
jiangxi
sichuan

3. 打印第一列和第三行

awk '{print $1,$3}' mytxt

$ awk '{print $1,$3}' mytxt
zilzhang jiangxi
zhagnsan sichuan

4. 加入头部信息

$ awk 'BEGIN {print "name\thuji"} {print $1,$3}' mytxt
name huji
zilzhang jiangxi
zhagnsan sichuan

5. 加入尾部信息

$ awk 'BEGIN {print "name\thuji\n"} {print $1,$3} END {print "\nended"}' mytxt
name huji

zilzhang jiangxi
zhagnsan sichuan

ended


6. 找到第三列等于”jiangxi“的记录

$ awk '{if($3=="jiangxi") print $0}' mytxt
zilzhang 19881110 jiangxi 18 film


awk使用正则表达式一定要用~ ，显示匹配正则表达式

7. 找到第三列含 an 的记录（有点grep an的意思）

$ awk '{if($3~/an/) print $0}' mytxt
zilzhang 19881110 jiangxi 18 film
zhagnsan 21321    sichuan 100 card

awk命令自带变量


8. 查看文件有多少列

$ awk 'END {print NR}' mytxt
2

9. 查看文件并打印列号

$ awk '{print NR, $0}' mytxt
1 zilzhang 19881110 jiangxi 18 film
2 zhagnsan 21321    sichuan 100 card

10. 文件不为空且第三列含an

$ awk '{if (NR>0 && $3~/an/) print NR, $0}' mytxt
1 zilzhang 19881110 jiangxi 18 film
2 zhagnsan 21321    sichuan 100 card

11. 打印列数

$ awk '{print NF}' mytxt
5
5

12. 使用自定义变量

$ awk '{name=$1; if(name~/ang/) print $0}' mytxt
zilzhang 19881110 jiangxi 18 film

13.打印第10列，第15行

$ awk '{if (NR==10 || NR==15) print $0}' hive_single_table_load_handler.sh
TABLE_NAME=$1
result=$?

14. 变更列值

$ awk '{if($3=="jiangxi") $3="poyang";print $0}' mytxt
zilzhang 19881110 poyang 18 film
zhagnsan 21321    sichuan 100 card

15. 打印当前目录下文件大小总和

$ ls -lrt | awk 'BEGIN {total=0} {total=total+$5} END {print total}'
1364985
