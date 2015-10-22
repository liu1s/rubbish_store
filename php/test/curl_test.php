<?php
/**
 * Author: 疯牛
 * Time: 15/3/25 下午7:07
 * Email: liu1s0404@outlook.com
 */

//初始化
$ch = curl_init();
//设置选项，包括URL

curl_setopt($ch, CURLOPT_URL, "http://xxxx/wmsapi/Inbound/CreatePurchaseAsn");

//$post_data = array(
//    array(
//        'code' => 'liu1s',
//        'name' => 'liu2s',
//        'status' => 'true',
//        'platform' => 'mls',
//        'qrurl' => '',
//        'qrimg' => ''
//    )
//);

//$post_data = array(
//    array(
//        'owcode' => 1,
//        'owname' => 2,
//        'detail' => array(
//            array(
//                'itemno' => 1,
//                'stcodeo' => 2,
//                'stname' => 'aaa',
//            )
//        ),
//    )
//);

$post_data = '[{"referNo":"10423","omsNo":"10005001","erpNo":"7028","tradeNos":"","relateNo":"1225","type":1,"whNo":"001","expresscode":"YTO","expressnumber":"111111111","shopcode":"Meilishuo","crtime":"2015-05-09 17:27:01","apTime":"2015-05-20 19:34:14","apUser":"awms","consigneename":"tanfy","mobile":"18300000000","consigneeaddress":"\u5a04\u5c71\u5173\u8def523\u53f7","note":"318:,\u9000\u8d27,,;","clientno":"oms","detail":[{"sku":"1002","qty":"1","price":0,"inventoryStatusCode":"Good"}]}]';

// post数据
curl_setopt($ch, CURLOPT_POST, 1);
// post的变量
#curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
//curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);

$headers[] = 'Content-type: application/json; charset=utf-8';
$headers[] = 'Appkey:UU2PbycYC9';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

//执行并获取HTML文档内容
$output = curl_exec($ch);

//var_dump(curl_getinfo($ch));
//释放curl句柄
curl_close($ch);
//打印获得的数据
var_dump($output);
$output = json_decode($output, true);



var_dump($output['success']);