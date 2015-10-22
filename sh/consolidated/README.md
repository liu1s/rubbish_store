#日常常用shell#

##常用函数##
base_tools.sh

1. error_return 错误退出并打印错误信息
2. success_print 格式化输出正确信息
3. error_print 格式化输出错误信息
4. action_check 检查动作成功与否

mls_base_tools.sh

1. get_avaliable_db_conf 找到可用的数据库配置
2. parse_db_conf_to_command 转换配置行为mysql命令行

##本地服务启动##
local_server_start.sh

##svn同步代码脚本##
svn_rsync.sh

##递归替换目下文件##
recur_replace.sh

##检测文件目录是否包含特殊字符##
recur_check_works.sh

##backup##
###mysql同步脚本###
mysql_back_up.sh
###gistore同步脚本###