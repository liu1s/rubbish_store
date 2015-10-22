<?php

/**
 *
 * @author 疯牛 liu1s0404@outlook.com
 * @package: broker
 */
class Util_MemCacheKey
{

    //竞价点击量（租房）
    public static function get_broker_bid_click_hz($brokerId, $date)
    {
        return Const_MemCache::KEY_BROKER_BID_CLICK_HZ . $brokerId . "_" . $date;
    }

    //套餐点击量
    public static function get_broker_combo_click($brokerId, $date)
    {
        return Const_MemCache::KEY_BROKER_COMBO_CLICK . $brokerId . "_" . $date;
    }

    public static function get_property_list_image($proid, $commid)
    {
        return Const_MemCache::KEY_PROPERTY_LIST_IMG . $proid . "," . $commid;
    }

    /**
     * ppc底价配置
     *
     * @return string
     */
    public static function getPriceInterval()
    {
        return Const_MemCache::KEY_PRICE_INTERVAL;
    }

    /**
     * 取某一城市下的所有区域
     *
     * @param int $cityid 城市id
     * @return String memcache key
     */
    public static function get_area_list($cityid) {
        return Const_MemCache::KEY_AREA_LIST . $cityid;
    }

    public static function get_area_value($id) {
        return Const_MemCache::KEY_AREA_VALUE . $id;
    }

    public static function get_metro_value($id) {
        return Const_MemCache::KEY_METRO_VALUE . $id;
    }

    public static function get_metro_station_value($id) {
        return Const_MemCache::KEY_METRO_STATION_VALUE . $id;
    }
    /**
     * 取某一个区域一下的板块
     *
     * @param int $areaid 区域id
     * @return string memcahce key
     */
    public static function get_block_list($areaid) {
        return Const_MemCache::KEY_SUB_AREA_LIST . $areaid;
    }
    public static function get_block_value($id) {
        return Const_MemCache::KEY_SUB_AREA_VALUE . $id;
    }
    /**
     * 某一城市的价格范围字典
     *
     * @param int $cityid
     * @return key
     */
    public static function get_sale_price_range_list($cityid) {
        return Const_MemCache::KEY_SALE_PRICE_RANGE_LIST . $cityid;
    }
    public static function get_rent_price_range_list($cityid) {
        return Const_MemCache::KEY_RENT_PRICE_RANGE_LIST . $cityid;
    }
    /**
     * 某一城市的面积范围字典
     *
     * @param int $cityid
     * @return memcache key
     */
    public static function get_area_range_list($cityid) {
        return Const_MemCache::KEY_AREA_RANGE_LIST . $cityid;
    }
    /**
     * 某一城市的房型字典
     *
     * @param int $cityid
     * @return memcache key
     */
    public static function get_house_model_list($cityid) {
        return Const_MemCache::KEY_HOUSE_MODEL_LIST . $cityid;
    }

    /**
     * 某一城市的小区单位均价字典
     *
     * @param int $cityid
     * @return memcache key
     */
    public static function get_unit_price_list($cityid) {
        return Const_MemCache::KEY_UNIT_PRICE_LIST . $cityid;
    }

    /**
     * 某一城市的房源类型字典
     *
     * @param int $cityid
     * @return memcache key
     */
    public static function get_use_type_list($cityid) {
        return Const_MemCache::KEY_USE_TYPE_LIST . $cityid;
    }
    /**
     * 某城市的房源装修字典列表
     *
     * @param int $cityid
     * @return unknown
     */
    public static function get_fitment_list($cityid) {
        return Const_MemCache::KEY_FITMENT_LIST . $cityid;
    }
    /**
     * 房源列表中单个经纪人信息
     *
     * @param int $brokerid
     * @return key
     */
    public static function get_property_list_broker($brokerid) {
        return Const_MemCache::KEY_PROPERTY_LIST_BROKERINFO . $brokerid;
    }
    public static function get_property_list_broker_userid($userid) {
        return Const_MemCache::KEY_PROPERTY_LIST_BROKERINFO_USERID . $userid;
    }
    public static function get_is_agree_protocol($brokerid) {
        return Const_MemCache::KEY_IS_AGREE_PROTOCOL . $brokerid;
    }
    public static function get_property_list_sw_broker($proid, $act) {
        return Const_MemCache::KEY_PROPERTY_LIST_SW_BROKERINFO . $act . "_" . $proid;
    }

    public static function get_area_range($rangeid) {
        return Const_MemCache::KEY_AREA_RANGE . $rangeid;
    }
    public static function get_house_model($modelid) {
        return Const_MemCache::KEY_HOUSE_MODEL . $modelid;
    }
    public static function get_sale_price_range($rangeid) {
        return Const_MemCache::KEY_SALE_PRICE_RANGE . $rangeid;
    }
    public static function get_rent_price_range($rangeid) {
        return Const_MemCache::KEY_RENT_PRICE_RANGE . $rangeid;
    }
    public static function get_area_code($areaid) {
        return Const_MemCache::KEY_AREA_CODE . $areaid;
    }
    public static function get_fitment_value($fitmentid) {
        return Const_MemCache::KEY_FITMENT_VALUE . $fitmentid;
    }

    public static function get_property_list($hashcode) {
        return Const_MemCache::KEY_PROPERTY_LIST . $hashcode;
    }
    public static function get_property_list_login_broker($hashcode) {
        return self::get_property_list ( $hashcode );
    }
    public static function get_property_list_count($hashcode) {
        return Const_MemCache::KEY_PROPERTY_COUNT . $hashcode;
    }

    public static function get_advertise_list($hashcode) {
        return Const_MemCache::KEY_ADVERTISE_LIST . $hashcode;
    }

    public static function get_choose_broker_info($bid) {
        return Const_MemCache::KEY_CHOOSE_BROKER_INFO . $bid;
    }

    public static function get_community_name_by_id($commid) {
        return Const_MemCache::KEY_COMMUNITY_GET_NAME . $commid;
    }

    public static function get_community_info_by_id($commid) {
        return Const_MemCache::KEY_COMMUNITY_GET_INFO_BY_ID . $commid;
    }

    public static function get_broker_name_by_id($bid) {
        return Const_MemCache::KEY_BROKER_NAME_BY_ID . $bid;
    }

    public static function get_free_broker_reg_date($uid) {
        return Const_MemCache::KEY_FREE_BROKER_REG_DATE . $uid;
    }

    public static function get_broker_finished_prop_num($bid, $type) {
        return Const_MemCache::KEY_BROKER_CHOOSE_FINISH_COUNT . $bid . "_" . $type;
    }

    public static function get_broker_finished_props($bid, $num) {
        return Const_MemCache::KEY_BROKER_CHOOSE_FINISH_PROPS . $bid . "_" . $num;
    }

    public static function get_broker_comments_num($bid) {
        return Const_MemCache::KEY_BROKER_COMMENTS_COUNT . $bid;
    }
    public static function get_broker_finished_comments_list($bid, $num) {
        return Const_MemCache::KEY_BROKER_CHOOSE_COMMENTS_LIST . $bid . "_" . $num;
    }

    public static function get_usr_brokerextend($bid) {
        return Const_MemCache::KEY_USR_BROKEREXTEND . $bid;
    }

    // property
    public static function get_property($propertyId) {
        return Const_MemCache::KEY_PROPERTY . $propertyId;
    }

    public static function get_propertysale($propertyId) {
        return Const_MemCache::KEY_PROPERTYSALE . $propertyId;
    }

    public static function get_propertyrent($propertyId) {
        return Const_MemCache::KEY_PROPERTYRENT . $propertyId;
    }

    public static function get_comm_prop_recent_sale($commId) {
        return Const_MemCache::KEY_COMM_OTHER_PROP_SALE . $commId;
    }

    public static function get_comm_prop_recent_rent($commId) {
        return Const_MemCache::KEY_COMM_OTHER_PROP_RENT . $commId;
    }

    public static function get_comm_other_prop_sale($commId, $propId) {
        return Const_MemCache::KEY_COMM_OTHER_PROP_SALE . $commId . '_' . $propId;
    }

    public static function get_comm_other_prop_rent($commId, $propId) {
        return Const_MemCache::KEY_COMM_OTHER_PROP_RENT . $commId . '_' . $propId;
    }

    public static function get_user_prop_sale($brokerId, $areaCode, $percentCost, $propPriceInt) {
        return Const_MemCache::KEY_USER_PROP_SALE . $brokerId . '_' . $areaCode . '_' . $percentCost . '_' . $propPriceInt;
    }

    public static function get_user_prop_rent($brokerId, $areaCode, $percentCost, $propPriceInt) {
        //return Const_MemCache::KEY_USER_PROP_RENT . $brokerId . '_' . $areaCode . '_' . $percentCost . '_' . $propPriceInt;
        return Const_MemCache::KEY_USER_PROP_RENT . $brokerId;
    }

    public static function get_similar_prop_sale($areaCode, $percentCost, $propPriceInt, $propId) {
        return Const_MemCache::KEY_SIMILAR_PROP_SALE . $areaCode . '_' . $percentCost . '_' . $propPriceInt . '_' . $propId;
    }

    public static function get_similar_prop_RENT($areaCode, $percentCost, $propPriceInt, $propId) {
        return Const_MemCache::KEY_SIMILAR_PROP_RENT . $areaCode . '_' . $percentCost . '_' . $propPriceInt . '_' . $propId;
    }

    public static function get_prop_commission_feeling($propId) {
        return Const_MemCache::KEY_PROP_COMMISSION_FEELING . $propId;
    }

    public static function get_prop_commission_comment($propId) {
        return Const_MemCache::KEY_PROP_COMMISSION_COMMENT . $propId;
    }

    public static function get_prop_commission_prop($propId) {
        return Const_MemCache::KEY_PROP_COMMISSION_PROP . $propId;
    }

    // community
    public static function get_comm_intro($commId) {
        return Const_MemCache::KEY_COMM_INTRO . $commId;
    }

    public static function get_comm_intro_other($commId) {
        return Const_MemCache::KEY_COMM_INTRO_OTHER . $commId;
    }

    public static function get_comm_pic($commId) {
        return Const_MemCache::KEY_COMM_PIC . $commId;
    }

    public static function get_default_comm_pic($commId) {
        return Const_MemCache::KEY_COMM_DEFAULT_PIC . $commId;
    }

    public static function get_def_comm_photo($comm_id) {
        return Const_MemCache::KEY_COMM_DEF_PHOTO . $comm_id;
    }

    public static function get_comm_room_pic($commId) {
        return Const_MemCache::KEY_COMM_ROOM_PIC . $commId;
    }

    public static function get_comm_hot($cityid, $w) {
        return Const_MemCache::KEY_COMMUNITY_GET_HOT . $cityid . $w;
    }

    public static function get_expert_recomprops($commId) {
        return Const_MemCache::KEY_EXPERT_RECOMPROPS . $commId;
    }

    public static function get_city_type($cityId,$load) {
        return Const_MemCache::KEY_CITY_TYPE . $cityId.$load;
    }

    // area
    public static function getBlockName($typeCode) {
        return Const_MemCache::KEY_BLOCK_NAME . $typeCode;
    }

    public static function getAreaName($typeCode) {
        return Const_MemCache::KEY_AREA_NAME . $typeCode;
    }

    public static function getBlockByCode($typeCode) {
        return Const_MemCache::KEY_BLOCK . $typeCode;
    }

    public static function getAreaByCode($typeCode) {
        return Const_MemCache::KEY_AREA . $typeCode;
    }

    public static function getAreacodeByTypeId($typeId) {
        return Const_MemCache::KEY_AREA_TYPEID . $typeId;
    }

    public static function getSalePriceRangeById($id) {
        return Const_MemCache::KEY_SALEPRIC_TYPEID . $id;
    }

    public static function getAreaRangeById($areaid) {
        return Const_MemCache::KEY_AREA_RANGE_ID . $areaid;
    }

    public static function getHouseModelById($id) {
        return Const_MemCache::KEY_HOUSE_MODEL_ID . $id;
    }

    public static function getUseType($id) {
        return Const_MemCache::KEY_USE_TYPE_ID . $id;
    }

    public static function getAreaListGmap($cityId) {
        return Const_MemCache::KEY_AREA_LIST_GMAP . $cityId;
    }

    public static function getBlockListGmap($parentId) {
        return Const_MemCache::KEY_BLOCK_LIST_GMAP . $parentId;
    }

    public static function getAreaGmap($id) {
        return Const_MemCache::KEY_AREA_GMAP . $id;
    }

    // area range
    public static function getAreaRangeList($cityId) {
        return Const_MemCache::KEY_AREA_RANGE_LIST_1 . $cityId;
    }

    public static function getAreaRange($id) {
        return Const_MemCache::KEY_AREA_RANGE_1 . $id;
    }

    public static function getAreaRangeID($id) {
        return Const_MemCache::KEY_AREA_RANGEID_1 . $id;
    }

    // house model
    public static function getHouseModelList($cityId, $hmFlag) {
        return Const_MemCache::KEY_HOUSE_MODEL_LIST_1 . $cityId . '_' . $hmFlag;
    }

    public static function getHouseModel($id) {
        return Const_MemCache::KEY_HOUSE_MODEL_1 . $id;
    }

    // use type
    public static function getUseTypeList($cityId) {
        return Const_MemCache::KEY_USE_TYPE_LIST_1 . $cityId;
    }

    // prop price
    public static function getPriceRange($act, $id) {
        return Const_MemCache::KEY_PRICE_RANGE . $act . '_' . $id;
    }

    // prop price cityid
    public static function getPriceId($act, $cityid) {
        return Const_MemCache::KEY_PRICE_ID . $act . '_' . $cityid;
    }

    public static function getPriceList($cityId, $tradeType) {
        return Const_MemCache::KEY_PRICE_LIST . $cityId . '_' . $tradeType;
    }

    public static function getPriceUplow($id, $tradeType) {
        return Const_MemCache::KEY_PRICE_UPLOW . $id . '_' . $tradeType;
    }

    // attachment
    public static function get_indoor_pic($sourceId) {
        return Const_MemCache::KEY_INDOOR_PIC . $sourceId;
    }

    // fitment
    public static function get_fitment_name($fitmentId) {
        return Const_MemCache::KEY_FITMENT_NAME . $fitmentId;
    }

    public static function get_fitment_list2($cityId, $flag) {
        return Const_MemCache::KEY_FITMENT_LIST2 . $cityId . '_' . $flag;
    }

    // user
    public static function get_user_name_by_user_id($userId) {
        return Const_MemCache::KEY_USER_NAME . $userId;
    }
    public static function get_usermail_by_id($userId) {
        return Const_MemCache::KEY_USER_MAIL . $userId;
    }
    /**
     * 手机号码取回密码
     * @param int $mobile
     * @return unknown
     */
    public static function get_password_mobile($mobile) {
        return Const_MemCache::KEY_PASSWORD_MOBILE . $mobile;
    }

    public static function get_reportbyuserip($userip) {
        return Const_MemCache::KEY_REPORT_USERIP . $userip;
    }
    public static function get_report_times_by_city($cityid,$date) {
        return Const_MemCache::KEY_REPORT_TIMES_BY_CITY . $cityid . "_" . $date;
    }

    /**
     * 经纪人预约刷新
     * @param int $mobile
     * @return unknown
     */
    public static function get_booking_brokerid($brokerid) {
        return Const_MemCache::KEY_BOOKING_BROKERID . $brokerid;
    }

    public static function get_booking_propid($propid) {
        return Const_MemCache::KEY_BOOKING_PROPID . $propid;
    }

    public static function get_booking_time($time) {
        return Const_MemCache::KEY_BOOKING_TIME . $time;
    }

    public static function get_community_latlng($commid) {
        return MemCacheHelper::get_key ( Const_MemCache::KEY_COMMUNITY_LAT_LNG, $commid );
        // return Const_MemCache::KEY_COMMUNITY_LAT_LNG . $commid;
    }

    public static function get_community_latlng_baidu($commid) {
        return MemCacheHelper::get_key ( Const_MemCache::KEY_COMMUNITY_LAT_LNG_BAIDU, $commid );
    }

    /*
     * 经纪人/房源查询道具标签信息
     * */

    public static function get_taginfo_brokerid($brokerid) {
        return Const_MemCache::KEY_TAGINFO_BROKERID . $brokerid;
    }

    public static function get_taginfo_propid($propid) {
        return Const_MemCache::KEY_TAGINFO_PROPID . $propid;
    }

    public static function get_taginfo_propid_icon($propid) {
        return Const_MemCache::KEY_TAGINFO_PROPID . $propid . "_icon";
    }

    /*
     * 对比房源
     * */
    public static function get_comparebyuserid($userid) {
        return Const_MemCache::KEY_COMPARE_USERID . $userid;
    }

    // home
    public static function get_TodayShowPro_by_cityid($cityid) {
        return Const_MemCache::KEY_HOME_TodayShowPro . $cityid;
    }
    public static function getBargainOnProList($cityid) {
        return Const_MemCache::KEY_HOME_BargainOnProList . $cityid;
    }
    public static function get_broker_excellence($cityid) {
        return Const_MemCache::KEY_HOME_broker_excellence . $cityid;
    }
    public static function get_general_statistics($cityid) {
        return Const_MemCache::KEY_HOME_general_statistics . $cityid;
    }

    public static function get_community_side_other($hash_str) {
        return Const_MemCache::KEY_COMMUNITY_SIDE_ITEM . $hash_str;
    }

    public static function get_community_unitprice($id) {
        return Const_MemCache::KEY_UNITPRICE_VALUE . $id;
    }

    public static function get_list_price_trend_useful($type) {
        return Const_MemCache::KEY_LIST_PRICE_TREND_USEFULL.$type;
    }

    public static function get_white_list($commid) {
        return Const_MemCache::KEY_WHITE_LIST . $commid;
    }

    public static function get_comm_wiki_info($commid) {
        return Const_MemCache::KEY_COMM_WIKI_INFO . $commid;
    }

    public static function get_usetype_name($TypeId) {
        return Const_MemCache::KEY_USETYPE_NAME . $TypeId;
    }

    public static function get_commexpert_mycomm($brokerId) {
        return Const_MemCache::KEY_COMMEXPERT_MYCOMM . $brokerId;
    }

    public static function get_vip_broker($id) {
        return Const_MemCache::KEY_VIP_BROKER_INFO . $id;
    }

    public static function get_html_content($flag, $cityid) {
        return Const_MemCache::KEY_HTML_CONTENT . $flag . $cityid;
    }

    public static function get_broker_companydify($cid) {
        return Const_MemCache::KEY_BROKER_COMPANY . $cid;
    }

    public static function get_filter() {
        return Const_MemCache::KEY_FILTER_WORD;
    }
    public static function get_community_default_image($comm_id) {
        return Const_MemCache::KEY_COMMUNITY_DEFAULT_IMAGE . $comm_id;
    }

    public static function get_broker_punish($bid) {
        return Const_MemCache::KEY_BROKER_PUNISH . $bid;
    }

    public static function get_broker_license($bid) {
        return Const_MemCache::KEY_BROKER_LICENSE . $bid;
    }

    public static function get_all_punish_broker() {
        return Const_MemCache::KEY_ALL_PUNISH_BROKER;
    }

    public static function get_all_doubt_broker() {
        return Const_MemCache::KEY_ALL_DOUBT_BROKER;
    }

    public static function get_newhome_list_ad($id) {
        return Const_MemCache::KEY__NEWHOME_LIST_AD . $id;
    }
    public static function get_newhome_list_hotkw($id) {
        return Const_MemCache::KEY__NEWHOME_LIST_HOTKW . $id;
    }

    //TinyUrl
    public static function get_tinyurl_url($id) {
        return Const_MemCache::KEY_TINYURL_URL . $id;
    }

    public static function get_paypercall_bynum($typeid, $phonenum) {
        return Const_MemCache::KEY_PAYPERCALL . $typeid . "_" . $phonenum;
    }

    public static function get_phonenum_bypcid($pcid, $typeflag) {
        return Const_MemCache::KEY_PAYPERCALL_PHONENUM . $pcid . "_1" . $typeflag;
    }

    public static function get_prop_using_tags($propid) {
        return Const_MemCache::KEY_PROP_USING_TAGS . $propid;
    }

    //今日房价随机数
    public static function get_today_random_price($commid) {
        return Const_MemCache::KEY_TODAY_RANDOM_PRICE . $commid;
    }
    //今日房价页面访问量
    public static function get_today_price_pv($commid) {
        return Const_MemCache::KEY_TODAY_PRICE_PV . $commid;
    }

    //首页小区列表随机页数
    public static function get_comm_radom_page() {
        return Const_MemCache::COMM_RADOM_PAGE;
    }

    //诚信联盟
    public static function get_trust_company() {
        return Const_MemCache::KEY_TRUSTUNION_COMPANY;
    }

    public static function get_trust_status($level) {
        return Const_MemCache::KEY_TRUSTUNION_STATUS . '_' . $level;
    }

    //首页精选房源
    public static function get_home_select_property($cityid, $type) {
        return Const_MemCache::KEY_HOME_SELECT_PROPERTY . '_' . $cityid . $type;
    }

    public static function get_areainfo_bycode($code) {
        return Const_MemCache::KEY_AREA_CODE_INFO . $code;
    }

    public static function get_myanjuke_notice($cityid) {
        return Const_MemCache::KEY_MYANJUKE_NOTICE . $cityid;
    }

    public static function get_myanjuke_agent($cityid) {
        return Const_MemCache::KEY_MYANJUKE_AGENT . $cityid;
    }

    public static function get_myanjuke_help($cityid) {
        return Const_MemCache::KEY_MYANJUKE_HELP . $cityid;
    }

    public static function get_myanjuke_friendinviteview($cityid) {
        return Const_MemCache::KEY_MYANJUKE_FRIENDINVITEVIEW . $cityid;
    }

    public static function get_myanjuke_newfunction($cityid) {
        return Const_MemCache::KEY_MYANJUKE_NEWFUNCTION . $cityid;
    }

    public static function get_myanjuke_mustread($cityid) {
        return Const_MemCache::KEY_MYANJUKE_MUSTREAD . $cityid;
    }

    public static function get_myanjuke_brokerhq($brokerid) {
        return Const_MemCache::KEY_MYANJUKE_BROKERHQ . $brokerid;
    }

    public static function get_myanjuke_newroom($brokerid) {
        return Const_MemCache::KEY_MYANJUKE_NEWROOM . $brokerid;
    }
    public static function get_myanjuke_brokermanager($mid) {
        return Const_MemCache::KEY_MYANJUKE_BROKERMANAGER . $mid;
    }

    public static function get_myanjuke_remind($cityid) {
        return Const_MemCache::KEY_MYANJUKE_REMIND . $cityid;
    }

    public static function get_myanjuke_noreadmess($fuid) {
        return Const_MemCache::KEY_MYANJUKE_NOREADMESS . $fuid;
    }

    public static function get_myanjuke_noreadmess_list($fuid) {
        return Const_MemCache::KEY_MYANJUKE_NOREADMESS_LIST . $fuid;
    }

    public static function get_newhome_recommend_list_key($params_array) {
        $params_str = '';
        foreach ( $params_array as $params ) {
            $params_str .= '_' . $params;
        }
        return Const_MemCache::KEY_SALE_INTRODUCE_NEWHOME . $params_str;
    }

    public static function get_introduce_newhome($areaid) {
        return Const_MemCache::KEY_SALE_INTRODUCE_NEWHOME . $areaid;
    }
    public static function get_introduce_newhome_time() {
        return Const_MemCache::TIME_SALE_INTRODUCE_NEWHOME;
    }
    public static function get_prop_report_filter($ip) {
        return Const_MemCache::KEY_PROP_REPORT_FILTER . $ip;
    }
    public static function is_ip_in_brokerlist($guid) {
        return Const_MemCache::KEY_IP_IN_BROKERLIST . $guid;
    }
    public static function last_time_by_ip($ip) {
        return Const_MemCache::KEY_LAST_TIME_BY_IP . $ip;
    }
    public static function last_time_by_uid($uid) {
        return Const_MemCache::KEY_LAST_TIME_BY_UID . $uid;
    }
    public static function get_find_prop_cond($uid) {
        return Const_MemCache::KEY_FIND_PROP_COND . $uid;
    }
    public static function get_prop_condition_num($uid) {
        return Const_MemCache::KEY_PROP_CONDITION_NUM . $uid;
    }
    public static function get_procond_total_count($condid, $type) {
        return Const_MemCache::KEY_PROPCOND_TOTAL_COUNT . $condid . "_" . $type;
    }
    public static function get_community_subscribed_user($commid, $limit) {
        return Const_MemCache::KEY_COMM_SUBSCRIBED_USERS . $commid . $limit;
    }
    public static function get_comm_total_prop_rows($commid, $cityid, $tradetype) {
        return Const_MemCache::KEY_COMM_TOTAL_PROP_ROWS . $commid . $cityid . $tradetype;
    }
    public static function get_hot_comm_by_city($cityid) {
        return Const_MemCache::KEY_HOT_COMM_BY_CITY . $cityid;
    }
    //看图找房
    public static function get_gallery_viewlist_by_update($update) {
        return Const_MemCache::KEY_GALLERY_VIEWLIST_BY_UPDATE . $update;
    }
    public static function get_gallery_viewlist($cityid, $tradetype) {
        return Const_MemCache::KEY_GALLERY_VIEWLIST . "_" . $cityid . "_" . $tradetype;
        ;
    }
    public static function get_gallery_view_by_id($picid) {
        return Const_MemCache::KEY_GALLERY_VIEW_BY_ID . $picid;
    }
    public static function get_newest_gallery_id($cityid, $tradetype) {
        return Const_MemCache::KEY_NEWEST_GALLERY_ID . "_" . $cityid . "_" . $tradetype;
        ;
    }
    public static function get_recommend_imglist($cityid, $tradetype) {
        return Const_MemCache::KEY_RECOMMEND_IMGLIST . "_" . $cityid . "_" . $tradetype;
    }
    public static function get_gallery_entra_list($cityid, $tradetype) {
        return Const_MemCache::KEY_GALLERY_ENTRA_LIST . "_" . $cityid . "_" . $tradetype;
    }
    public static function get_comm_entra_imglist($commid) {
        return Const_MemCache::KEY_COMM_ENTRA_IMGLIST . "_" . $commid;
    }

    // 经纪人帐户剩余使用天数
    public static function get_broker_enddate($brokerid) {
        return Const_MemCache::KEY_BROKER_END_DATE . $brokerid;
    }
    public static function check_broker_is_expert($brokerId, $commId) {
        if ($commId) {
            return Const_MemCache::KEY_BROKER_IS_EXPERT . $brokerId . "_" . $commId;
        } else {
            return Const_MemCache::KEY_BROKER_IS_EXPERT . $brokerId;
        }
    }

    public static function get_broker_company($companyId) {
        return Const_MemCache::KEY_BROKER_COMPANY . $companyId;
    }
    public static function get_broker_store($storeid) {
        return Const_MemCache::KEY_BROKER_STORE . $storeid;
    }
    public static function get_broker_propertys($brokerId, $type) {
        return Const_MemCache::KEY_BROKER_PROPERTYS . $type . "_" . $brokerId;
    }
    public static function getBrokerPaytypeInfo($payid) {
        return Const_MemCache::KEY_BROKER_PAYTYPE_INFO . $payid;
    }
    public static function getBrokerBaseInfo($brokerid) {
        return Const_MemCache::KEY_BROKER_BASE_INFO . $brokerid;
    }
    public static function get_broker_clicks($brokerId, $nb) {
        return Const_MemCache::KEY_BROKER_CLICKS . $nb . "_" . $brokerId;
    }
    public static function getRecomCommBrokerList($ctype, $cityid, $commid, $date) {
        return Const_MemCache::KEY_RECOM_COMM_BROKER_LIST.$ctype."_".$cityid."_".$commid."_".$date;
    }
    public static function getBannerImgByCompId($compid, $logotype) {
        return Const_MemCache::KEY_BANNER_IMG_BY_COMPID . $compid . "_" . $logotype;
    }
    public static function getLogoCompanyList($cityid) {
        return Const_MemCache::KEY_LOGO_COMPANY_LIST.$cityid;
    }

    public static function get_solr_list_key($cityid, $tradetype) {
        return Const_MemCache::KEY_SOLR_LIST . $cityid . "_" . $tradetype;
    }
    public static function get_solr_list_time() {
        return Const_MemCache::TIME_SOLR_LIST;
    }
    public static function get_online_status($userid) {
        return Const_MemCache::KEY_USER_ONLINE_STATUS . "_" . $userid;
    }
    public static function get_report_date($proid) {
        return Const_MemCache::KEY_REPORT_DATE . $proid;
    }
    public static function get_false_prop_count($cityid) {
        return Const_MemCache::KEY_FALSE_PROP_COUNT . $cityid;
    }
    public static function get_false_props($cityid, $page, $limit) {
        return Const_MemCache::KEY_FALSE_PROPS . $cityid . "_" . $page . "_" . $limit;
    }

    // chli add
    public static function get_total_bulletinboard($userid) {
        return Const_MemCache::KEY_TOTAL_BULLETINBOARD . $userid;
    }
    public static function get_recentnum_bulletinboard($userid) {
        return Const_MemCache::KEY_RECENTNUM_BULLETINBOARD . $userid;
    }

    //经纪人阅读公告的记录
    public static function get_bulletin_read_info($broker_id){
        return Const_MemCache::KEY_BULLETIN_READ_INFO . $broker_id;
    }

    public static function get_pay_logs($broker_id) {
        return Const_MemCache::KEY_PAY_LOGS . $broker_id;
    }
    public static function get_pay_type($type_id) {
        return Const_MemCache::KEY_PAY_TYPE . $type_id;
    }

    //小区新鲜事总数
    public static function getCommunityActionFeedCount($p_intCommunityID){
        return Const_MemCache::KEY_COMMUNITYACTION_CNT.$p_intCommunityID;
    }

    public static function get_price_byid($id) {
        return Const_MemCache::TIME_PRICE_ID . $id;
    }

    //用户最活跃的小区
    public static function getCommunityHot($p_strAreaCode,$p_intLimitNum){
        return Const_MemCache::KEY_HOTCOMMUNITY_CNT.$p_strAreaCode.'_'.$p_intLimitNum;
    }

    // order
    public static function get_inuse_order_id($broker_id) {
        return Const_MemCache::KEY_INUSE_ORDER_ID . $broker_id;
    }

    // map2 tracker
    public static function get_marker_info($marker_id) {
        return Const_MemCache::KEY_MARKER_INFO . $marker_id;
    }

    public static function get_markers_info($city_id, $type) {
        return Const_MemCache::KEY_MARKERS_INFO . $city_id . '_' . $type;
    }

    public static function get_area_propnum($cityid,$areacode,$paramStr) {
        return Const_MemCache::KEY_AREA_MARKERS_INFO . $cityid . '_' . $areacode. '_' . $paramStr;
    }

    //小区附近房源
    public static function getNearbyProperty($commId,$areaCode,$tradeType,$num){
        return Const_MemCache::KEY_NEARBY_PROPERTY . $commId . "_" . $areaCode . "_" . $tradeType . "." . $num;
    }

    //小区房价首页
    public static function get_community_home_feeds($cityid, $areacode, $lastid, $older, $pagesize, $replylimit, $actionid){
        return Const_MemCache::KEY_COMMUNITY_FEEDS.$cityid."_".$areacode."_".$lastid."_".$older."_".$pagesize."_".$replylimit."_".$actionid;
    }

    public static function get_community_home_feeds_limit($cityid, $areacode, $actionid){
        return Const_MemCache::KEY_COMMUNITY_FEEDS_LIMIT.$cityid."_".$areacode."_".$actionid;
    }

    public static function get_community_home_feeds_count($cityid, $areacode){
        return Const_MemCache::KEY_COMMUNITY_FEEDS_COUNT.$cityid."_".$areacode;

    }

    public static function getMasterStoreInfo($p_intCommunityID){
        return Const_MemCache::KEY_COMMUNITY_MASTERSTORE_INFO.$p_intCommunityID;
    }
    public static function getMasterStoreNewProperty($p_arrGroupId,$p_intPageSize,$p_intTradeType,$p_intCommunityID){
        return Const_MemCache::KEY_COMMUNITY_MASTERSTORE_PROPERTY.join('_',$p_arrGroupId).'_'.$p_intPageSize.'_'.$p_intTradeType.'_'.$p_intCommunityID;
    }

    // 经纪人统计分析
    public static function get_my_reprop_clicks($brokerid, $datestr) {
        return Const_MemCache::KEY_MY_REPROP_CLICKS . $brokerid . "_" . $datestr;
    }

    public static function get_area_reprop_clicks($area, $datestr) {
        return Const_MemCache::KEY_AREA_REPROP_CLICKS . $area . "_" . $datestr;
    }

    public static function get_content($key, $timeout) {
        return Const_MemCache::KEY_CONTENT . $key . "_" . $timeout;
    }

    public static function get_default_price_id($cityid, $pstr, $visittype) {
        return Const_MemCache::KEY_DEFAULT_PRICE_ID . $cityid . "_" . $pstr . "_" . $visittype;
    }

    public static function get_price_line($cityid, $visittype) {
        return Const_MemCache::KEY_PRICE_LINE . $cityid . "_" . $visittype;
    }

    public static function get_broker_servs_by_broker_id($brokerid) {
        return Const_MemCache::KEY_BROKER_SERVS_BY_BROKER_ID . $brokerid;
    }

    public static function get_html_by_city($cityid, $html_id) {
        return Const_MemCache::KEY_HTML_BY_ID . $cityid . "_" . $html_id;
    }

    public static function get_my_data_compare($brokerid, $datestr) {
        return Const_MemCache::KEY_MY_DATA_COMPARE . $brokerid . "_" . $datestr;
    }

    public static function getCityInfo($p_intCityID){
        return Const_MemCache::KEY_AJK_COMMTYPE.$p_intCityID;
    }
    //图片删除原因反馈
    public static function get_unpassed_pics($user_id, $date) {
        return Const_MemCache::KEY_UNPASSED_PICS . $user_id . '_' . $date;
    }


    //图片删除原因反馈第二版
    public static function get_unpassed_picssec($brokerid, $date) {
        return Const_MemCache::KEY_UNPASSED_PICSSEC . $brokerid . '_' . $date;
    }

    public static function is_unpassed_pics($user_id, $date) {
        return Const_MemCache::KEY_IS_UNPASSED_PICS . $user_id . '_' . $date;
    }
    public static function last_unpassed_pics_id($user_id) {
        return Const_MemCache::KEY_LAST_UNPASSED_PICS_ID . $user_id;
    }

    //房源管理小区列表
    public static function get_propmanagement_commids($brokerid){
        return Const_MemCache::KEY_PROPMANAGEMENT_COMMIDS.$brokerid;
    }

    //房源违规原因查询
    public static function get_illegal_prop_reason($prop_id) {
        return Const_MemCache::KEY_ILLEGAL_PROP_REASON . $prop_id;
    }

    public static function get_community_filters($commid,$tradetype,$cityid){
        return Const_MemCache::KEY_COMMUNITY_FILTERS_ARR.$commid."_".$tradetype."_".$cityid;
    }

    //常用网址导航
    public static function get_myanjuke_navigation($cityid) {
        return Const_MemCache::KEY_MYANJUKE_NAVIGATION . $cityid;
    }


    //获取门店经济人
    public static function get_store_brokers($storeid) {
        return Const_MemCache::KEY_STORE_BROKERS . $storeid;
    }
    //根据公司ID获取门店
    public static function get_storeid_by_companyid($companyid) {
        return Const_MemCache::KEY_STOREID_BY_COMPANYID . $companyid;
    }
    //根据公司ID获取合同小区
    public static function get_agent_company_communitys($companyid) {
        return Const_MemCache::KEY_AGENT_COMPANY_COMMUNITYS . $companyid;
    }
    //获取经纪人公司帐号类型
    public static function get_agent_user_type($userid) {
        return Const_MemCache::KEY_AGENT_USER_TYPE . $userid;
    }
    //获取门店账号信息
    public static function get_userinf_byusername($username) {
        return Const_MemCache::KEY_USERINFO_BY_USERNAME . $username;
    }

    //手机号码释放
    public static function get_mobile_release($mobile) {
        return Const_MemCache::KEY_MOBILE_RELEASE . $mobile;
    }

    //业主委托手机验证码
    public static function get_commission_verify_code($mobile) {
        return Const_MemCache::KEY_COMMISSION_VERIFY_CODE . $mobile;
    }

    //ufs手机验证码
    public static function get_ufs_verify_code($mobile){
        return Const_MemCache::KEY_UFS_VERIFY_CODE . $mobile;
    }

    //小区feed数量
    public static function get_feedcount_by_commid($commid){
        return Const_MemCache::KEY_COMMUNITY_FEED_COUNT . $commid;
    }

    //小区评分
    public static function get_community_scores($commid){
        return Const_MemCache::KEY_COMMUNITY_SCORE . $commid;
    }

    //同价格区间房源数量
    public static function get_propnum_by_price($price, $cityId, $tradetype, $commId){
        return Const_MemCache::KEY_PROPNUM_WITH_PRICE."_".$price."_".$cityId."_".$tradetype."_".$commId;
    }

    //同价格区间房源数量
    public static function get_propnum_by_area($area, $cityId, $tradetype, $commId){
        return Const_MemCache::KEY_PROPNUM_WITH_AREA."_".$area."_".$cityId."_".$tradetype."_".$commId;
    }

    //房源图片信息
    public static function get_property_attachments($proid){
        return Const_MemCache::KEY_PROPERTY_ATTACHMENTS.$proid;
    }

    //是否是经纪人手机
    public static function get_is_broker_mobile($mobile){
        return Const_MemCache::KEY_IS_BROKER_MOBILE.$mobile;
    }

    //房源价格变动记录
    public static function get_property_all_price($propid, $count){
        return Const_MemCache::KEY_PROPERTY_ALL_PRICE.$propid."_".$count;
    }

    public static function get_broker_company_info($company_id) {
        return Const_MemCache::KEY_BROKER_COMPANY_INFO . $company_id;
    }

    public static function get_commtype_name($type_id) {
        return Const_MemCache::KEY_COMMTYPE_NAME_NAME . $type_id;
    }

    public static function get_worker_info($worker_id) {
        return Const_MemCache::KEY_WORKER_INFO . $worker_id;
    }

    public static function get_similar_keyword($cityid,$kw) {
        return Const_MemCache::KEY_SIMILAR_KEYWORD . "_".$cityid."_".md5($kw);
    }

    public static function is_master_store_db($commid,$storeid) {
        return Const_MemCache::KEY_MASTER_STORE_PROPERTY . date('Ymd') . $commid . "_" . $storeid;
    }

    public static function getConcernedCommunityCount($user_id){
        return Const_MemCache::KEY_CONCERNED_COMMUNITY_NUM.$user_id;
    }

    public static function listSearchDict($type_id){
        return Const_MemCache::KEY_LIST_SEARCH_DICT . $type_id;
    }

    public static function get_prop_image($proid) {
        return "prop_images_" . $proid;
    }

    public static function get_property_normal_image($keystr){
        return "prop_default_img_viewpage_" . $keystr;
    }

    public static function get_master_store_rec_props($ad_id){
        return Const_MemCache::KEY_GET_MASTER_STORE_REC_PROPS."_a".$ad_id;
    }

    public static function getHotCommByAreaCode($areacode,$date,$limit){
        return Const_MemCache::KEY_MARKET_HOT_COMMUNITY.'_'.$areacode.'_'.$date.'_'.$limit.'_';
    }

    public static function getRecommendAreaCode($areacode,$date,$limit,$areacode_length){
        return Const_MemCache::KEY_MARKET_RECOMMEND_AREA.'_'.$areacode.'_'.$date.'_'.$limit.'_'.$areacode_length;
    }

    public static function getRecommendByComm($commid,$date,$limit){
        return Const_MemCache::KEY_MARKET_COMMUNITY_RECOMMEND.'_'.$commid.'_'.$date.'_'.$limit.'_';
    }

    public static function get_baidu_bad_commlist($areacode, $limit){//added by kakie 2011-7-6 baidu最差的16个板块小区排名
        return Const_MemCache::KEY_BAIDU_BAD_COMMUNITY.'_'.$areacode.'_'.$limit.'_';
    }

    public static function get_broker_user_checked($brokerid){
        return Const_MemCache::KEY_BROKER_USER_CHECKED.$brokerid;
    }
    public static function get_broker_user_checked_type($brokerid){
        return Const_MemCache::KEY_BROKER_USER_CHECKED_TYPE.$brokerid;
    }

    public static function get_comm_piclist($commid){
        return Const_MemCache::KEY_COMM_PICLIST.$commid;
    }

    public static function get_model_piclist($commid){
        return Const_MemCache::KEY_MODEL_PICLIST.$commid;
    }

    public static function get_comm_pic_count($CommId){
        return Const_MemCache::KEY_COMM_PIC_COUNT.$CommId;
    }
    public static function getGuessPriceMobileVerifyKey($MobileNum){
        return Const_MemCache::KEY_PRICEGAMBLE_VERIFY.$MobileNum;
    }

    public static function getGuessPriceMobileVerifyNumKey($MobileNum){
        return Const_MemCache::KEY_PRICEGAMBLE_VERIFYNUM.$MobileNum;
    }

    public static function getGuessPriceMobileVerifyNoRepeatKey($MobileNum){
        return Const_MemCache::KEY_PRICEGAMBLE_VERIFYNOREPEAT.$MobileNum;
    }

    public static function getPkListKey($brokerid,$usersex){
        return Const_MemCache::KEY_PK_LIST. date('Ymd') . $brokerid.'_'.$usersex;
    }
    public static function getPkRmListKey($brokerid){
        return Const_MemCache::KEY_PK_RM_LIST . date('Ymd') . $brokerid;
    }
    public static function getPkRankListKey($type,$cityid,$usersex){
        return Const_MemCache::KEY_PK_RANK_LIST. date('Ymd') . '_' . $type . '_' . $cityid . '_'.$usersex;
    }
    public static function getPkReportKey($picid, $brokerid, $frombrokerid){
        return Const_MemCache::KEY_PK_REPORT_LIST.$picid. '_' . $brokerid. '_' . $frombrokerid;
    }
    //经纪人好友--我的好友、互为好友、加我为好友的
    public static function getBrokerStoreFriendsByUserID($userid) {
        return Const_MemCache::KEY_GETBROKERSTOREFRIENDSBYUSERID.$userid;
    }
    //经纪人好友--我的好友
    public static function getBrokerMyFriendsByUserID($userid) {
        return Const_MemCache::KEY_GETBROKERMYFRIENDSBYUSERID.$userid;
    }
    //经纪人好友--互为好友
    public static function getBrokerMutualFriendsByUserID($userid) {
        return Const_MemCache::KEY_GETBROKERMUTUALFRIENDSBYUSERID.$userid;
    }
    //经纪人好友--加我为好友的
    public static function getBrokerOtherFriendsByUserID($userid) {
        return Const_MemCache::KEY_GETBROKEROTHERFRIENDSBYUSERID.$userid;
    }
    //经纪人好友--好友数目
    public static function getBrokerFriendsNumByUserID($userid) {
        return Const_MemCache::KEY_GETBROKERFRIENDSNUMBYUSERID.$userid;
    }
    //经纪人-友情店铺
    public static function getBrokerFriendshipStoreByUserID($userid){
        return Const_MemCache::KEY_GETBROKERFRIENDSHIPSTOREBYUSERID.$userid;
    }
    //活动中心
    public static function getBrokerActivityCentreByBrokerID($broker) {
        return Const_MemCache::KEY_GETBROKERACTIVITYCENTREBYBROKERID.$broker;
    }
    //活动中心列表
    public static function getActivityCentre() {
        return Const_MemCache::KEY_GETACTIVITYCENTRE;
    }
    public static function getCommunityGardenersKey($intCommId) {
        return Const_MemCache::KEY_COMMUNITY_GARDENER_IDS . $intCommId;
    }
    //业主委托 周边板块 抢房源列表
    public static function getPublicCommissionByAreacode($areaTypeId,$dateLine) {
        return Const_MemCache::KEY_PUBLICCOMMISSION_AREACODE_DATELINE.$areaTypeId."_".$dateLine;
    }
    public static function getRound($commid){
        return Const_MemCache::KEY_COMMUNITY_ROUND_DETAIL.'_'.$commid;
    }
    //经纪人问答经验值
    public static function getQAGradesLogDesByItemID($p_intItemID){
        return Const_MemCache::KEY_QA_LogDes.'_'.$p_intItemID;
    }
    //小区纠错经纪人当月缓存记录
    public static function getBrokerCommCorrectInfo($p_BrokerId,$date){
        return Const_MemCache::KEY_COMMCORR_BROKER_MONTH_LOGS.'_'.$p_BrokerId.'_'.$date;
    }
    //feeds添加限制时间
    public static function getFeedsAddTime($UserId){
        return Const_MemCache::KEY_FEEDS_ADD_TIME.$UserId;
    }

    //获取经纪人发布房源的小区，各种状态房源数量
    public static function get_broker_pro_status($brokerid){
        return Const_MemCache::KEY_BROKER_PRO_STATUS.$brokerid;
    }

    //修改手机验证码
    public static function get_editmobile_verify_code($mobile) {
        return Const_MemCache::KEY_EDITMOBILE_VERIFY_CODE . $mobile;
    }

    //释放手机验证码
    public static function get_releasemobile_verify_code($mobile){
        return Const_MemCache::KEY_RELEASE_MOBILE_VERIFY_CODE . $mobile;
    }

    //获取经纪人房源点击
    public static function get_property_clicks($brokerid){
        return Const_MemCache::KEY_PROPERTY_CLICKS.$brokerid;
    }

    //获取api错误code信息
    public static function getApiCodeMsg(){
        return Const_MemCache::KEY_API_CODE_MSG;
    }

    //获得房源默认图片key
    public static function getPropDefImg($propId, $commId){
        return Const_MemCache::KEY_PROP_DEF_IMG.$propId.'_'.$commId;
    }

    //获得经纪人UFS信息key
    public static function getBrokerUfs($brokerId){
        return Const_MemCache::KEY_BROKER_UFS_INFO.$brokerId;
    }

    //城市最大的UFS Rank key
    public static function getCityMaxOrMinUfsRank($cityId, $type,$field){
        return Const_MemCache::KEY_CITY_MAX_OR_MIN_UFS_RANK.$cityId.$type.'_'.$field;
    }

    //获得举报房源key
    public static function getPropFalseHouseNum($proId){
        return Const_MemCache::KEY_PROP_FALSE_HOUSE_NUM.$proId;
    }

    //获得经纪人服务等级key
    public static function getBrokerRankInCity($cityId,$brokerId){
        return Const_MemCache::KEY_BROKER_RANK_IN_CITY.$cityId.'_'.$brokerId;
    }

    //获得经纪人feedback key
    public static function getBrokerFeedBackInfo($brokerId){
        return Const_MemCache::KEY_BROKER_FEED_BACK_INFO.$brokerId;
    }

    //获得房源特色标签key
    public static function getPropPrivateTags($propId){
        return Const_MemCache::KEY_PROP_PRIVATE_TAGS.$propId;
    }

    //同区域百度未收录的房源
    public static function getBaiduNoRecordProps($cityId,$regionCode){
        return Const_MemCache::KEY_BAIDU_NO_RECORD_PROPS.$cityId.'_'.$regionCode;
    }

    //房屋建造年代
    public static function getPropHouseAge($proId){
        return Const_MemCache::KEY_PROP_HOUSE_AGE.$proId;
    }

    //区域或版块相关问答
    public static function getRegionOrBlockQa($cityId,$regionId,$blockId = ''){
        if(!empty($blockId)){
            return Const_MemCache::KEY_REGION_OR_BLOCK_QA.$cityId.'_'.$blockId;
        }
        return Const_MemCache::KEY_REGION_OR_BLOCK_QA.$cityId.'_'.$regionId;
    }
    //获得举报房源key
    public static function getMyChooseProIds($userId,$favFlagId){
        return Const_MemCache::KEY_MY_CHOOSE_PRO_IDS.$userId.'_'.$favFlagId;
    }

    //获取小区单页中问答模块的cache
    public static function getcommqablock($commId, $commName, $flg, $blankId, $cityId){
        $key = "{$cityId}_{$commId}_{$commName}_{$flg}_{$blankId}";
        return md5($key);
    }

    //筛选无结果时的推荐
    public static function getSaleHotRecommend($cityId){
        return Const_MemCache::KEY_SALE_HOT_RECOMMEND.$cityId;
    }

    //获得城市房源最小score和最大score
    public static function getCityMinScoreAndMaxScore($cityId,$isNewRank){
        return Const_MemCache::KEY_CITY_MINSCORE_MAXSCORE.($isNewRank ? 'newRank' : 'rank').'_'.$cityId;
    }

    //精选房源方并发key
    public static function getChoiceRequestKey($siteType,$brokerId,$houseId)
    {
        return Const_MemCache::KEY_CHOICE_REQUEST_REJECT.$siteType.'_'.$brokerId.'_'.$houseId;
    }

    //获取金铺城市板块key
    public static function getCityDistrictsKey($cityId)
    {
        return Const_MemCache::KEY_JP_CITY_DISTRICT.$cityId;
    }
}