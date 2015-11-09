<?php
/**
 * Author: 疯牛
 * Time: 15/3/25 下午7:07
 * Email: liu1s0404@outlook.com
 */

//初始化
$ch = curl_init();
//设置选项，包括URL



$condition =
    json_encode(
        array(
            'shop_id' => 151940596501054964,
            'goods_status' => 1
        )
    )
;


//curl_setopt($ch, CURLOPT_URL, "http://api.local.goodsservice.higo.meilishuo.com/goods/GetLists?from=1&condition=" . $condition);

curl_setopt($ch, CURLOPT_URL, "http://goodsservice.qa.higo.meilishuo.com/goods/GetLists?from=1&debug=1&condition=" . $condition);

// post数据
curl_setopt($ch, CURLOPT_POST, 1);
// post的变量
#curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
//curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
//curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);

$headers[] = 'Content-type: application/json; charset=utf-8';
$headers[] = 'Appkey:hO3GO4atae';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

//执行并获取HTML文档内容
$output = curl_exec($ch);

//var_dump(curl_getinfo($ch));
//释放curl句柄
curl_close($ch);
//打印获得的数据
var_dump($output);
$output = json_decode($output, true);
