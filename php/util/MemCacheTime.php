<?php

/**
 *
 * @author 疯牛 liu1s0404@outlook.com
 * @package: broker
 */
class Util_MemCacheTime
{

    //套餐点击量
    public static function get_broker_combo_click()
    {
        return Const_MemCache::TIME_BROKER_COMBO_CLICK;
    }

    public static function get_property_list_image()
    {
        return Const_MemCache::TIME_PROPERTY_LIST_IMG;
    }

    /**
     * ppc底价配置
     *
     * @return mixed
     */
    public static function getPriceInterval()
    {
        return Const_MemCache::TIME_PRICE_INTERVAL;
    }
}