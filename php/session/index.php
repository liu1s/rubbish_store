<?php  

session_start();  
if (!isset($_SESSION['TEST'])) {  
        $_SESSION['TEST'] = time();  
}  

$_SESSION['TEST3'] = time();  

print $_SESSION['TEST'];  
print "<br><br>";  
print $_SESSION['TEST3'];  
print "<br><br>";  
print session_id();  

/*
 * 测试结果
 * 用户请求服务器时如果，没有包含session id,那么服务器针对这次会话，会生成一个新的session id
 * 用户保存session id有两种方式:一.保存在cookie  二.在URL中直接传送
 */
?>  
