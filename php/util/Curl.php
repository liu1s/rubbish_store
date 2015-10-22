<?php
class Util_Curl
{
    public static function service_curl($service_url, $post_data = array())
    {
        APF::get_instance()->debug($service_url);
        $curl = curl_init();
        $options = array(
            CURLOPT_URL => $service_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 1,
            CURLOPT_TIMEOUT => 3
        );
        curl_setopt_array($curl, $options);

        if (!empty($post_data)) {
            $post_fields = http_build_query($post_data);
            APF::get_instance()->debug($service_url);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $post_fields);
        }

        $result = curl_exec($curl);
        $info = curl_getinfo($curl);

        curl_close($curl);
        if ($info["http_code"] == 200) {
            APF::get_instance()->debug(json_decode($result, true));
            return json_decode($result, true);
        } else {
            APF::get_instance()->get_logger()->error('API->Call[' . json_encode($info) . ']: ' . $service_url);
            APF::get_instance()->get_logger()->error('API->Call[' . json_encode($post_data) . ']: ' . $service_url);
            return false;
        }
    }
}
