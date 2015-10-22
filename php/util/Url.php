<?php

apf_require_class("APF_Util_StringUtils");

class Util_Url
{
    public static function buildMyBasicUrl()
    {
        $objApf = APF::get_instance();
        $strBaseDomain = $objApf->get_config('base_domain');
        $strPath = BASE_URI;
        return "http://my.{$strBaseDomain}{$strPath}/";
    }

    public static function buildChoiceResultUrl($query)
    {
        $objApf = APF::get_instance();
        $strBaseDomain = $objApf->get_config('base_domain');
        $strPath = BASE_URI;
        return "http://my.{$strBaseDomain}{$strPath}/user/choice/result/?" . $query;
    }

    /**
     * 获取好租房源详情页的URL
     *
     * @param $houseId
     * @param array $params
     * @return string
     */
    public static function hzHouseDetailUrl($houseId, $params = array())
    {
        $request = APF::get_instance()->get_request();
        $citySet = $request->load_city_set();
        $subDomain = 'www';
        if (!empty($citySet) && isset($citySet['py']) && !empty($citySet['py'])) {
            $subDomain = $citySet['py'];
        }
        $domain = APF::get_instance()->get_config('haozu_city_base_domain');
        $proto = is_callable(array($request, 'is_secure')) && $request->is_secure() ? 'https' : 'http';
        $ext = '';
        if (!empty($params)) {
            $ext = APF_Util_StringUtils::encode_seo_parameters($params);
        }
        return "{$proto}://{$subDomain}.{$domain}/fangyuan/{$houseId}{$ext}";
    }

    /**
     * 获取安居客房源详情页
     *
     * @param int $houseId
     * @param int $cityId
     * @return string
     */
    public static function ajkHouseDetailUrl($houseId, $cityId)
    {
        $baseDomain = APF::get_instance()->get_config('base_domain');
        $citySets = APF::get_instance()->get_config('city_set', 'multicity');
        $citySet = $citySets[$cityId];
        $url = Uri_Http::build_uri($baseDomain, $citySet['pinyin']);

        return $url . '/prop/view/' . $houseId;
    }
}
