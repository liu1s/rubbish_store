<?php
/**
 * 获得不同分表策略下的分表后缀
 */
class Util_DbSplitSuffix {

    /**
     * 获得城市分表后缀
     *
     * TODO 确认该方法是否正确
     *
     * @param int $cityId 城市ID
     *
     * @return string
     */
    public static function city($cityId) {
        if ($cityId == 11) {
            $suffix = 'sh';
        } elseif ($cityId == 14) {
            $suffix = 'bj';
        } elseif ($cityId <= 41) {
            $suffix = '04';
        } else { // $cityId >= 42
            $suffix = 'other';
        }

        return $suffix;
    }

    /**
     * 获得年分表后缀
     *
     * @param string|null $date
     *
     * @return bool|string
     */
    public static function year($date = null) {
        if (is_null($date)) {
            $date = date('Y-m-d');
        }

        return date('Y', strtotime($date));
    }

    /**
     * 获得年月分表后缀
     *
     * @param string|null $date
     *
     * @return bool|string
     */
    public static function yearMonth($date = null) {
        if (is_null($date)) {
            $date = date('Y-m-d');
        }

        return date('Ym', strtotime($date));
    }

    /**
     * 获得年月日分表后缀
     *
     * @param string|null $date
     *
     * @return bool|string
     */
    public static function yearMonthDay($date = null) {
        if (is_null($date)) {
            $date = date('Y-m-d');
        }

        return date('Ymd', strtotime($date));
    }

}