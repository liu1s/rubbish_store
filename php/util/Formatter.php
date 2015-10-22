<?php

class Util_Formatter
{

    /**
     * 格式化今日花费（包括定价、竞价和精选）
     *
     * @param float $todayConsume 单位：元
     *
     * @return int|float
     */
    public static function formatTodayConsume($todayConsume)
    {
        $todayConsume = round($todayConsume, 2);

        if ($todayConsume >= 100) {
            $todayConsume = round($todayConsume);
        }

        return $todayConsume;
    }

}
