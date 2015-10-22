<?php

class Util_Christmas {
    const CONFIG_CHRISTMAS = 'christmas';

    public static function hammerRushDefaultRoundNumber()
    {
        $apf = APF::get_instance();
        $number = $apf->get_config('hammer_rush_default_round_number', self::CONFIG_CHRISTMAS);
        if (!$number) {
            $number = 1000;
        }
        return $number;
    }

    public static function hammerRushStartDate()
    {
        $apf = APF::get_instance();
        $startDate = $apf->get_config('hammer_rush_start_date', self::CONFIG_CHRISTMAS);
        if (!$startDate) {
            $startDate = '2013-12-18';
        }

        return $startDate;
    }

    public static function hammerRushStartTime()
    {
        $apf = APF::get_instance();
        $startDate = $apf->get_config('hammer_rush_start_time', self::CONFIG_CHRISTMAS);
        if (!$startDate) {
            $startDate = '2013-12-18 10:00:00';
        }

        return $startDate;
    }

    public static function hammerRushStarted()
    {
        $startTime = self::hammerRushStartTime();
        return time() > strtotime($startTime);
    }

    public static function hammerRushEndDate()
    {
        $apf = APF::get_instance();
        $endDate = $apf->get_config('hammer_rush_end_date', self::CONFIG_CHRISTMAS);
        if (!$endDate) {
            $endDate = '2013-12-25';
        }
        return $endDate;
    }

    public static function hammerRushEndTime()
    {
        $apf = APF::get_instance();
        $endDate = $apf->get_config('hammer_rush_end_time', self::CONFIG_CHRISTMAS);
        if (!$endDate) {
            $endDate = '2013-12-25 18:30:00';
        }
        return $endDate;
    }

    public static function hammerRushEnded()
    {
        $endTime = self::hammerRushEndTime();
        return time() > strtotime($endTime);
    }

    public static function hammerSeekGuideUrl()
    {
        $apf = APF::get_instance();
        $url = $apf->get_config('hammer_seek_guide_url', self::CONFIG_CHRISTMAS);
        if (!$url) {
            $url = '#';
        }

        return $url;
    }

    public static function messages()
    {
        $messages = APF::get_instance()->get_config('messages', self::CONFIG_CHRISTMAS);
        if (!$messages) {
            $messages = array();
        }
        return $messages;
    }

    public static function hammerIsRushableCacheKey($currentRoundTime)
    {
        return 'hammer_is_rushable_' . date('Ymd_H', strtotime($currentRoundTime));
    }

}