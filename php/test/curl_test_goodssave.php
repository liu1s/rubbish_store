<?php
/**
 * Author: 疯牛
 * Time: 15/3/25 下午7:07
 * Email: liu1s0404@outlook.com
 */



$a = 'O:8:"stdClass":40:{s:8:"goods_id";s:18:"184196457610019014";s:7:"shop_id";s:18:"167571839284588965";s:11:"main_img_id";s:18:"184196074417013012";s:10:"goods_type";s:1:"0";s:10:"goods_name";s:18:"110203商品名称";s:13:"use_transport";s:1:"1";s:10:"goods_desc";s:18:"110203商品描述";s:11:"category_id";s:1:"0";s:13:"category_name";s:0:"";s:10:"subcate_id";s:1:"0";s:12:"subcate_name";s:0:"";s:8:"brand_id";s:4:"7048";s:10:"brand_name";s:6:"测试";s:11:"material_id";s:1:"0";s:13:"material_name";s:0:"";s:7:"quality";s:5:"0.000";s:12:"quality_unit";s:6:"千克";s:14:"producing_area";s:0:"";s:10:"store_type";s:1:"3";s:12:"shipping_fee";s:5:"0.000";s:11:"goods_price";s:1:"0";s:19:"goods_price_presale";s:1:"0";s:15:"goods_repertory";s:3:"110";s:11:"goods_sales";s:1:"0";s:11:"goods_image";s:75:"184196082607014083,184196082750015017,184196082890016040,184196083948017005";s:19:"goods_express_delay";s:0:"";s:12:"goods_status";s:1:"1";s:12:"goods_origin";s:1:"1";s:9:"favorites";s:1:"0";s:13:"limitation_id";s:1:"0";s:12:"thirdcate_id";s:1:"0";s:14:"thirdcate_name";s:0:"";s:16:"root_category_id";s:1:"0";s:18:"root_category_name";s:0:"";s:13:"n_category_id";s:5:"11977";s:15:"n_category_name";s:10:"连衣裙=";s:13:"qrcode_img_id";s:1:"0";s:7:"maidian";s:0:"";s:17:"goods_instruction";s:0:"";s:12:"goods_art_no";s:18:"184199568716117033";}';
var_dump(unserialize($a));exit;



//初始化
$ch = curl_init();
//设置选项，包括URL



$data = '{"id":"","main_image_id":"184193330694986046","detail_image_ids":"184193434423988069","maidian":"1231","characteristic":"","name":"higo_test_2","desc":"13131","category_id":"11949","category_name":"\u98ce\u8863=","brand_id":"28629","brand_name":"text000","art_no":"","area":"Andorra","quality":"1000","quality_unit":"\u5343\u514b","repertory":"99","store_type":"2","transport_type":"1","shipping_fee":"","goods_status":"-3","instruction":"","ext_meta":"","is_use_idcard":"0","shop_id":"151940596501054964","sku":"589:33655;159:33687","sku_str":"\u989c\u8272:\u9ed1\u8272;\u5c3a\u7801:\u5747\u7801","sku_prices":"99","sku_sale_prices":"99","sku_quantities":"99","sku_artnos":"99","props":"591:80010;585:30039;481:30009;479:30015;467:;465:30001;379:30062;357:;319:60003;81:110345;55:;3:","props_str":"\u98ce\u683c:\u751c\u7f8e\u97e9\u8303;\u9886\u578b:V\u9886;\u8896\u957f:\u4e94\u5206\u8896;\u8896\u578b:\u5305\u8896;\u8863\u95e8\u895f:\u8bf7\u9009\u62e9;\u8863\u957f:\u77ed\u6b3e;\u7248\u578b:\u5bbd\u677e;\u6b3e\u5f0f:\u8bf7\u9009\u62e9;\u6750\u8d28:\u68c9;\u529f\u80fd:\u6237\u5916;\u5143\u7d20:\u8bf7\u9009\u62e9;\u4e0a\u8863\u539a\u5ea6:\u8bf7\u9009\u62e9","is_higo_group":1}';


$post_data =json_decode($data, true);
var_dump($post_data);exit;


//curl_setopt($ch, CURLOPT_URL, "http://api.local.goodsservice.higo.meilishuo.com/goods/GetLists?from=1&condition=" . $condition);

curl_setopt($ch, CURLOPT_URL, "http://api.local.goodsservice.higo.meilishuo.com/goods/save");

// post数据
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
// post的变量
#curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
//curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));

curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//curl_setopt($ch, CURLOPT_HEADER, 0);

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
