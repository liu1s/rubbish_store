<?php
/**
 * @file 
 *
 * Created by 疯牛
 * project: {PROJECT_NAME}
 * Date: 13-7-30
 * Time: 15:20
 */

$filePath = 'temp.txt';

@$fileHandle = fopen('temp.txt','r');

if (flock($fileHandle,LOCK_SH|LOCK_NB))
{
    echo 1233;
    sleep(10);
    // release lock
    //flock($fileHandle,LOCK_UN);
}
else
{
    echo "Error locking file!";
}
fclose($fileHandle);