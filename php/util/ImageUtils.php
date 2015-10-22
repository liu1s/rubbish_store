<?php
class Util_ImageUtils {
    public static function format_url_to_objective_size($url, $width, $height, $multiplier=0, $default_width=240, $default_height=180, $broker_id=0, $upload=0){
        if(preg_match('/images|pic[0-9]+/i',$url)){
            $result = preg_replace('/[0-9]+x[0-9]+/i',$width."x".$height,$url);
            if(preg_match('/pic[0-9]+/i', $url)){
                //$enabled = $multiplier%5;
                if($multiplier == 14){
                    if(!preg_match("/display\/anjuke/", $result)){
                        $result = preg_replace("/display/", "display/anjuke", $result);
                        $result = preg_replace('/\/([0-9]+x[0-9]+)/i',"_$broker_id/$1",$result);
                    }
                }else{
                    if(!preg_match("/display\/anjuke/", $result)){
                        $result = preg_replace("/display/", "display/anjuke", $result);
                        $result = preg_replace('/\/([0-9]+x[0-9]+)/i',"_$broker_id/$1",$result);
                    }
                }
            }
            return $result;

        }else{
            return preg_replace('/^(http.*[commimages|attachments|attachments_comm].*)(\/[0-9\/A-z]+)\.([A-z]{3,4})/','$1$2.$3',$url);
        }
    }

    public static function get_property_images_by_type ($images,$type) {
        $list = array();
        foreach ($images as $image) {
            if ($image['DataType'] == $type) {
                $list[] = $image;
            }
        }
        return $list;
    }

    public static function get_property_room_images ($images) {
        return self::get_property_images_by_type($images,2);
    }

    public static function get_property_comm_images ($images) {
        return self::get_property_images_by_type($images,1);
    }

    public static function get_property_model_images ($images) {
        return self::get_property_images_by_type($images,3);
    }

    public static function get_new_image_url ($file_path , $host_id) {
        $img_domain = APF::get_instance()->get_config("image_server_domain");
        $base_domain = APF::get_instance()->get_config("image_server_base_domain");
        $photourl = "http://".$img_domain . $host_id . "." . $base_domain . "/" . $file_path;
        return $photourl;
    }
//http://images.anjukestatic.com/attachments_comm/e/7/f/e7fb58dae7d04f6db7acac503820a692.jpg
    public static function get_old_property_image_url ($file_path) {
        $url_prefix = APF::get_instance()->get_config("imageprefix");
        return $url_prefix . $file_path;
    }

    public static function get_old_image_url ($file_path) {
        return self::get_old_property_image_url($file_path);
    }

    public static function get_old_property_image_path ($file) {
        return "attachments/" . $file;
    }

    public static function get_old_community_image_path ($file) {
        if (preg_match("/commimage/i",$file)) {
            return $file;
        } else {
            return "attachments_comm/" . $file;
        }
    }
    public static function convert_community_image2url ($file, $host_id = 0) {
        if ($host_id > 0) {
            return self::get_new_image_url($file,$host_id);
        } else {
            return self::get_old_image_url(self::get_old_community_image_path($file));
        }
    }
    public static function convert_property_room_image2url ($file,$host_id) {
        if ($host_id > 0) {
            return self::get_new_image_url($file,$host_id);
        } else {
            return self::get_old_property_image_url(self::get_old_property_image_path($file));
        }
    }

    public static function community_default_photo_to_url ($str) {
        if (preg_match("/,/",$str)) {
            $arr = explode(",",$str);
            if ($arr[0] > 0) {
                return self::convert_community_image2url($arr[1],$arr[0]);
            } else {
                return self::get_old_image_url($arr[1]);
            }
        } else {
            //return self::convert_community_image2url($str,0);
            return self::get_old_image_url($str);
        }
    }

    public static function get_image_host_id ($str) {
        if (preg_match('/,/',$str)) {
            $arr = explode(",",$str);
            return intval($arr[0]);
        } else {
            return 0;
        }
    }

    public static function community_image_to_thumb_url ($file,$host_id) {
        if ($host_id > 0) {
            return self::convert_community_image2url(self::convert_newimg_larger_to_thumb_file_path($file),$host_id);
        } else {
            return self::convert_community_image2url(self::convert_oldimg_larger_to_thumb_file_path($file));
        }
    }

    public static function convert_community_default_photo_thumb_path ($str) {
        if (preg_match('/,/',$str)) {
            $arr = explode (",",$str);
            if ($arr[0] > 0) {
                return self::convert_newimg_larger_to_thumb_file_path($arr[1]);
            } else {
                return self::convert_oldimg_larger_to_thumb_file_path($arr[1]);
            }
        } else {
            return self::convert_oldimg_larger_to_thumb_file_path($str);
        }
    }

    public static function convert_oldimg_larger_to_thumb_file_path ($file) {
        $ext = self::get_filename_extension($file);
        if (preg_match('/commimage/',$file)) {
            return str_replace(".".$ext,"_s.".strtolower($ext),$file);
        } else {
            return str_replace(".".$ext,"_s.".$ext,$file);
        }
    }

    public static function convert_newimg_larger_to_thumb_file_path ($file) {
        $thumb = self::get_new_image_thumb_file_name();
        $larger = self::get_new_image_larger_file_name();
        return str_replace($larger,$thumb,$file);
    }

    public static function get_new_image_thumb_file_name () {
        $thumb = APF::get_instance()->get_config("size_thumbnail");
        return $thumb['width'] . "x" . $thumb['height'] . ".jpg";
    }

    public static function get_new_image_larger_file_name () {
        $larger = APF::get_instance()->get_config("size_larger");
        return $larger['width'] . "x" . $larger['height'] . ".jpg";
    }

    protected function get_filename_extension ($file_name) {
        return preg_replace('/.*\.(\w+)$/i','\\1',$file_name);
    }

    public static function format_url_to_medium ($url) {
        //return $url; // medium image
        if (preg_match('/images[0-9]+/i',$url)) {
            return preg_replace('/[0-9]+x[0-9]+/i',"420x420",$url);
        } else {
            return preg_replace('/^(http.*[commimages|attachments|attachments_comm].*)(\/[0-9a-z]+)_?.*\.([A-z]{3,4})/','$1$2_md.$3',$url);
        }
    }

    public static function format_url_to_small ($url) {
        //return $url; // small image
        if (preg_match('/images[0-9]+/i',$url)) {
            return preg_replace('/[0-9]+x[0-9]+/i',"100x75",$url);
        } else {
            return preg_replace('/^(http.*[commimages|attachments|attachments_comm].*)(\/[0-9A-z]+)\.([A-z]{3,4})/','$1$2_s.$3',$url);
        }
    }

    public static function format_url_to_bigger ($url) {
        //return $url; // small image
        if (preg_match('/images[0-9]+/i',$url)) {
            return preg_replace('/[0-9]+x[0-9]+/i',"200x200",$url);
        } else {
            return preg_replace('/^(http.*[commimages|attachments|attachments_comm].*)(\/[0-9A-z]+)\.([A-z]{3,4})/','$1$2_sm.$3',$url);
        }
    }

    public static function format_url_to_larger ($url) {
        if (preg_match('/images[0-9]+/i',$url)) {
            return preg_replace('/[0-9]+x[0-9]+/i',"600x600",$url);
        } else {
            $url = str_replace("_md","",$url);
            $url = str_replace("_s","",$url);  //qli added @2009-8-28
            return preg_replace('/^(http.*[commimages|attachments|attachments_comm].*)(\/[0-9A-z]+)\.([A-z]{3,4})/','$1$2_la.$3',$url);
        }
    }

    public static function format_url_to_original ($url) {
        if (preg_match('/images[0-9]+/i',$url)) {
            return preg_replace('/[0-9]+x[0-9]+/i',"o",$url);
        } else {
            return preg_replace('/^(http.*[commimages|attachments|attachments_comm].*)(\/[0-9A-z]+)\.([A-z]{3,4})/','$1$2_o.$3',$url);
        }
    }

    public static function convert_community_image2urlLarge($file,$host_id=0){
        if($host_id>0){
            if (preg_match('/^display/', $file)) {
                $file=preg_replace('/display/i', 'display/anjuke', $file);
                return self::get_dfs_image_url($file,$host_id);
            }
            return self::get_new_image_url($file,$host_id);
        }else{
            return self::get_old_image_url(self::get_old_community_image_path($file));
        }
    }

    public static function format_commpicurl_to_larger ($url) {
        if (preg_match('/pic[0-9]+/i',$url)) {
            if(!preg_match('/\/anjuke\//i',  $url)){
                $url=preg_replace('/\/display\//', '/display/anjuke/', $url);
            }
            return preg_replace('/[0-9]+x[0-9]+/i',"820x615",$url);
        }elseif(preg_match('/images\d/i', $url)){
            return preg_replace('/[0-9]+x[0-9]+/i',"820x615",$url);
        } else {
            $url = str_replace("_md","",$url);
            $url = str_replace("_s","",$url);

            return preg_replace('/^(http.*[commimages|attachments|attachments_comm].*)(\/[0-9\/A-z]+)\.([A-z]{3,4})/','$1$2_la.$3',$url);
        }
    }

    public static function get_tmp_image_url ($sub_path,$host_id,$file_name) {
        $img_domain = APF::get_instance()->get_config("image_server_domain");
        $base_domain = APF::get_instance()->get_config("image_server_base_domain");
        $src = "http://" . $img_domain . $host_id . "." . $base_domain ."/tmp/" . $sub_path . "" .$file_name;
        return $src;
    }

    public static function get_image_url ($sub_path,$host_id,$file_name) {
        $img_domain = APF::get_instance()->get_config("image_server_domain");
        $base_domain = APF::get_instance()->get_config("image_server_base_domain");
        $src = "http://" . $img_domain . $host_id . "." . $base_domain ."/" . $sub_path . "" .$file_name;
        return $src;
    }

	/*
     * 获取经纪人头像URL
     */
    public static function get_broker_photo_url($url,$size = '100x133'){
        if(empty($url) || preg_match("/^http:/", $url)){
              return $url;
        }

       $isz  = in_array($size,array('s','m'));
       $zarr = array('s'=>'75x100','m'=>'100x133');

        //DFS
        if (preg_match("/^display/", $url)){
        	$size = $isz ? $zarr[$size] : $size;
            $tmpArray = explode('_',$url);
            $uri = $tmpArray[0];
            $host_id = $tmpArray[1];
            //$cut = isset($tmpArray[2]) ? '/'.$tmpArray[2] : '';
            //return self::get_dfs_image_url($uri.$cut."/".$size.".jpg",$host_id);
            return self::get_dfs_image_url($uri."/".$size.".jpg",$host_id);
        }

        //OLD
        $strURLPrefix=APF::get_instance()->get_config('imageprefix');
        if($isz){
	        $url = str_replace("_1.","_".$size.".",$url);
	    	if(!preg_match("/_".$size."/i",$url)){
	    		$rpos=strrpos($url,'.');
	    		$url=substr($url,0,$rpos).'_'.$size.substr($url,$rpos);
	    	}
	    	return $strURLPrefix."broker/icon/v1/".$url;
        }

        return $strURLPrefix."attachments/".$url;
    }

	public static function get_dfs_image_url($file_path,$host_id){
        $pic_domain=APF::get_instance()->get_config("pic_server_domain");
        $base_domain=APF::get_instance()->get_config("pic_server_base_domain");
        $photourl="http://".$pic_domain.$host_id.".".$base_domain."/".$file_path;
        return $photourl;
    }

    /**
     * 得到对应尺寸的URL
     * @param string $p_strFileName
     * @param int $p_intHostID
     * @param int $p_intWidth
     * @param int $p_intHeight
     * @return string
     */
    public static function getResizeURL($p_strFileName,$p_intHostID,$p_intWidth,$p_intHeight,$BrokerId=0){
        //可能的尺寸:100x75,420x315,200x200,600x600,420x420
        //_s69x75,_md388x420
        if(false === strrpos($p_strFileName, 'http')){
            $strURL=self::getOriginalURL($p_strFileName,$p_intHostID,$BrokerId);
        }else{
            $strURL=$p_strFileName;
        }
        //echo $url. '|'.strrpos($url,'.').'<hr />';
        if(preg_match('/\d+x\d+/',$strURL)){
            $strURL=preg_replace('/\d+x\d+\w+\./',$p_intWidth.'x'.$p_intHeight.'.',$strURL);
        }else{
            if($p_intWidth <= 100){
                $strURL=substr_replace($strURL,'_s.',strrpos($strURL,'.'),1);
            }elseif($p_intWidth == 200 && $p_intHeight == 200){
                $strURL=substr_replace($strURL,'_sm.',strrpos($strURL,'.'),1);
            }
        }
        return $strURL;
    }
    /**
     * 得到原图的URL
     * @param string $p_strFileName
     * @param int $p_intHostID
     * @return string URL
     */
    public static function getOriginalURL($p_strFileName, $p_intHostID = 0, $BrokerId = 0)
    {
        if (substr($p_strFileName, 0, 7) == 'http://') {
            return $p_strFileName;
        }
        $config = static::getConfig();
        if (!strpos($p_strFileName, '/')) {
            $p_strFileName = 'display/' . $p_strFileName . "/" . $config['DisplayURLImageSize']['width'] . 'x' . $config['DisplayURLImageSize']['height'] . '.jpg';
        }
        if ($p_intHostID > 0) {
            if (preg_match('/^display/', $p_strFileName) && substr($p_strFileName, 7, 7) != '/anjuke') {
                if ($BrokerId) {
                    $tmp = explode('/', $p_strFileName);
                    $p_strFileName = $tmp[0] . '/anjuke/' . $tmp[1] . '_' . $BrokerId . '/' . $tmp[2];
                }
                return 'http://' . $config['DisplayURLPrefixDFS'] . $p_intHostID . $config['DisplayURLBaseDomainDFS'] . '/' . $p_strFileName;
            } else {
                return 'http://' . $config['DisplayURLPrefixHost'] . $p_intHostID . $config['DisplayURLBaseDomainHost'] . '/' . $p_strFileName;
            }
        } else {
            if (preg_match('/^commimages|attachments_comm/i', $p_strFileName)) {
                return 'http://' . $config['DisplayURLPrefixOld'] . $config['DisplayURLBaseDomainOld'] . '/' . $p_strFileName;
            } else {
                return 'http://' . $config['DisplayURLPrefixOld'] . $config['DisplayURLBaseDomainOld'] . '/' . 'attachments_comm/' . $p_strFileName;
            }
        }
    }

    public static function getConfig()
    {
        static $config = null;
        if (is_null($config)) {
            $objAPF = APF::get_instance();

            $config['DisplayURLPrefixDFS'] = $objAPF->get_config('ImageDisplayURLPrefixDFS', 'image');
            $config['DisplayURLPrefixHost'] = $objAPF->get_config('ImageDisplayURLPrefixHost', 'image');
            $config['DisplayURLPrefixOld'] = $objAPF->get_config('ImageDisplayURLPrefixOld', 'image');
            $config['DisplayURLBaseDomainDFS'] = $objAPF->get_config('ImageDisplayURLBaseDomainDFS', 'image');
            $config['DisplayURLBaseDomainHost'] = $objAPF->get_config('ImageDisplayURLBaseDomainHost', 'image');
            $config['DisplayURLBaseDomainOld'] = $objAPF->get_config('ImageDisplayURLBaseDomainOld', 'image');
            $config['DisplayURLImageSize'] = $objAPF->get_config('size_larger');

            $config['AifangDisplayURLPrefix'] = $objAPF->get_config('AifangImageDisplayURLPrefix', 'image');
            $config['AifangDisplayURLBaseDomainHost'] = $objAPF->get_config('AifangImageDisplayURLBaseDomainHost', 'image');
        }
        return $config;
    }
}
?>
