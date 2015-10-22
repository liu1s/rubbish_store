<?php

class Util_Map
{
    // 地球半径
    const EARTH_RADIUS = 6371000;

    /**
     * 火星坐标 转 百度坐标
     */
    public static function gcj02ToBd09($lat, $lng)
    {
        $converter = new latlng_conv_gcj02_2_bd09();
        return $converter->conv($lat, $lng);
    }

    /**
     * 百度坐标 转 火星坐标
     */
    public static function bd09ToGcj02($lat, $lng)
    {
        $converter = new latlng_conv_bd09_2_gcj02();
        return $converter->conv($lat, $lng);
    }

    /**
     * 获取某点周边的经纬度范围
     *
     * @param float $lat 纬度
     * @param float $lng 经度
     * @param int $radius 半径（单位：米）
     *
     * @return array
     */
    public static function round($lat, $lng, $radius)
    {
        $latRange = 180 / pi() * $radius / self::EARTH_RADIUS;
        $lngRange = $latRange / cos($lat * pi() / 180.0);

        $maxLat = $lat + $latRange;
        $minLat = $lat - $latRange;
        $maxLng = $lng + $lngRange;
        $minLng = $lng - $lngRange;

        return array(
            'maxLat' => $maxLat,
            'minLat' => $minLat,
            'maxLng' => $maxLng,
            'minLng' => $minLng,
        );
    }

    /**
     * 计算两点的距离（近似），经纬度建议使用百度地图坐标版（BD09）
     *
     * @link http://tieba.baidu.com/p/2004450642
     *
     * @param float $lat1 点1纬度
     * @param float $lng1 点1经度
     * @param float $lat2 点2纬度
     * @param float $lng2 点2经度
     *
     * @return float
     */
    public static function distance($lat1, $lng1, $lat2, $lng2)
    {
        $distance = self::EARTH_RADIUS * acos(cos($lat1 * pi() / 180) * cos($lat2 * pi() / 180) * cos($lng1 * pi() / 180 - $lng2 * pi() / 180) + sin($lat1 * pi() / 180) * sin($lat2 * pi() / 180));
        if (is_nan($distance)) {
            $distance = 0;
            trigger_error('Util_Map::distance($lat1, $lng1, $lat2, $lng2) got a NaN with: ' . json_encode(func_get_args()), E_USER_WARNING);
        }

        return $distance;
    }

}

/**
 * latlng转换器：从gcj02转换到bd09
 *
 * 验证方法可以从百度中验证。验证url：
 * http://api.map.baidu.com/marker?location=纬度,经度&title=baidu_poi&output=html
 *
 * 也有在线转换方法，文档见：document/latlng_convert_online_any_2_bd09.txt
 *
 * @link http://blog.csdn.net/coolypf/article/details/8569813
 * @author coolypf
 * @author hl
 * @version 0.1
 *
 */
class latlng_conv_gcj02_2_bd09
{

    /**
     * 转换接口
     * @param float|array $lat gcj02坐标的纬度、或者array('lat'=>lat, 'lng'=>lng)
     * @param float $lng gcj02坐标的经度
     * @return array 转换后的百度坐标系。格式array('lat'=>lat, 'lng'=>lng)
     */
    static public function conv($lat, $lng = 0)
    {
        if (is_array($lat)) {
            $lng = isset($lat['lng']) ? $lat['lng'] : 0;
            $lat = isset($lat['lat']) ? $lat['lat'] : 0;
        }

        $z = sqrt($lng * $lng + $lat * $lat) + 0.00002 * sin($lat * self::const_x_pi());
        $theta = atan2($lat, $lng) + 0.000003 * cos($lng * self::const_x_pi());

        return array(
            'lat' => $z * sin($theta) + 0.006,
            'lng' => $z * cos($theta) + 0.0065,
        );

    }

    /**
     * const_x_pi
     * @return float
     */
    static public function const_x_pi()
    {
        static $x_pi = 0;
        if (0 == $x_pi) {
            $x_pi = M_PI * 3000.0 / 180.0;
        }
        return $x_pi;
    }


}


/**
 * latlng转换器：从bd09转换到gcj02
 *
 * 验证方法可以从高德地图中验证。验证url：
 * http://mo.amap.com/?q=纬度,经度&name=park&dev=0#thirdpoi
 *
 * @link http://blog.csdn.net/coolypf/article/details/8569813
 * @author coolypf
 * @author hl
 * @version 0.1
 *
 */
class latlng_conv_bd09_2_gcj02
{

    /**
     * 转换接口
     * @param float|array $lat 百度坐标系的纬度、或者array('lat'=>lat, 'lng'=>lng)
     * @param float $lng 百度坐标系的经度
     * @return array 转换后的gcj02坐标(高德坐标系)。格式array('lat'=>lat, 'lng'=>lng)
     */
    static public function conv($lat, $lng = 0)
    {
        if (is_array($lat)) {
            $lng = isset($lat['lng']) ? $lat['lng'] : 0;
            $lat = isset($lat['lat']) ? $lat['lat'] : 0;
        }

        $x = $lng - 0.0065;
        $y = $lat - 0.006;
        $z = sqrt($x * $x + $y * $y) - 0.00002 * sin($y * self::const_x_pi());
        $theta = atan2($y, $x) - 0.000003 * cos($x * self::const_x_pi());
        return array(
            'lat' => $z * sin($theta),
            'lng' => $z * cos($theta),
        );

    }

    /**
     * const_x_pi
     * @return float
     */
    static public function const_x_pi()
    {
        static $x_pi = 0;
        if (0 == $x_pi) {
            $x_pi = M_PI * 3000.0 / 180.0;
        }
        return $x_pi;
    }


}
