<?php
class Util_DisplayImages {
    public $db;
    public $thumb;
    public $larger;
    public static $nonethum = "no-photo.gif";
    function DisplayImages($db) {
        $this->db = $db;
    }
	
	/*@新图片读取
     *名片：display/080917d1fc6a950bd159f7777ea62b5989402b_1
     *身份证：display/080917d1fc6a950bd159f7777ea62b5989402b_1
     *头像：display/080917d1fc6a950bd159f7777ea62b5989402b_1
     *小区图片：display/0e98a730f1387d4b53a8177dafdf2b68/420x315.jpg
     *
     *解释：1.前面的display表示第三代图片存储;
     *      2. "_"后面的数字为host_id;
     *      3.小区图片的host_id 在数据库有字段存储;
     *
     *生成图片url示例：http://pic1.ajkimg.com/display/anjuke/84df4e4ec4a9bc20e93c5cc336e31e90/420x315.jpg
     * */
    public static function get_img_url($filename,$host_id = -1,$size = ''){
    	$PicDisplayURLPrefixDFS = APF::get_instance()->get_config('ImageDisplayURLPrefixDFS','image');
    	$PicDisplayURLBaseDomainDFS = APF::get_instance()->get_config('ImageDisplayURLBaseDomainDFS','image');
        if(empty($filename) || strpos($filename, 'http://') !== false){
            return $filename;
        }
        $strURLPrefix=APF::get_instance()->get_config('imageprefix');
        //判断图片是否为第三代图片
        if (strpos($filename, 'display') === false){//非第三代图片
            //职业证书审核
            if(strpos($filename,'broker')){
                return $strURLPrefix.$filename;
            }
            if(strpos($filename, '_') === false){
                return $strURLPrefix."attachments/".$filename;
            }
            if(strpos($filename,'/')){//头像特殊情况:有"-"和"/"
                return  $strURLPrefix."attachments/".$filename;
            }
            $tmpArray = explode('_', $filename);
            $filename = $tmpArray[0];
            $host_id = $tmpArray[1];
            return "http://".$PicDisplayURLPrefixDFS.$host_id.$PicDisplayURLBaseDomainDFS."/display/anjuke/".$filename."/".$size.".jpg";
        }
        //第三代图片
        $cut = '';
        if($host_id < 1){//没有传递参数host_id,从图片名称中切割host_id
            $tmpArray = explode('_',$filename);
            $filename = $tmpArray[0];
            $host_id = $tmpArray[1];
            if($tmpArray[2]) $cut = '/'.$tmpArray[2];
        }
        
        return "http://".$PicDisplayURLPrefixDFS.$host_id.$PicDisplayURLBaseDomainDFS."/".$filename.$cut."/".$size.".jpg";
    }
}