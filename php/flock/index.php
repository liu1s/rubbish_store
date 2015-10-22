<?php
/**
 * @file 
 * 调试php文件锁
 *
 * Created by Wallace
 * project: 个人测试
 * Date: 13-7-10
 * Time: 18:04
 */
$filePath = 'temp.txt';

@$fileHandle = fopen('temp.txt','w');


// 排它性的锁定
if (flock($fileHandle,LOCK_EX))
{
    fwrite($fileHandle,"Write something");
    // release lock
    //flock($fileHandle,LOCK_UN);
}
else
{
    echo "Error locking file!";
}

for($i=0;$i<20;$i++){
    echo 1;
    sleep(1);
}


fclose($fileHandle);

