<?php
//$str 待截取的字符串
//$len 截取的字符个数
//$chars 已经截取的字符数
//$res   保存的字符串
//$chars 保存已经截取的字符串个数
//$offset 截取的偏移量
//$length 字符串的字节数
//若$len>$str的字符个数，造成无谓的while循环，($offset<$length限定)
function utf8sub($str,$len){
        if($len<=0){
                return ;
        }
        $res="";
        $offset=0;
        $chars=0;
        $length=strlen($str);
        while($chars<$len && $offset<$length){

                $hign=decbin(ord(substr($str,$offset,1)));
                if(strlen($hign)<8){
                        $count=1;
                }elseif(substr($hign,0,3)=="110"){
                        $count=2;
                }elseif(substr($hign,0,4)=="1110"){
                        $count=3;
                }elseif(substr($hign,0,5)=="11110"){
                        $count=4;
                }elseif(substr($hign,0,6)=="111110"){
                        $count=5;
                }elseif(substr($hign,0,7)=="1111110"){
                        $count=6;
                }

                $res.=substr($str,$offset,$count);
                $offset+=$count;
                $chars+=1;

        }
        return $res;
}
