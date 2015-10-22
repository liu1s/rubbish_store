<?php
/**
 * 请求API的工具类。
 *
 * @author liuxd
 */
apf_require_class('APF_Http_Client_Curl');

class Util_CallAPI {

    /**
     * 从url获得数据。
     * @param string $url
     * @param string $type 可能值：json, xml, html
     * @param array $post 需要post到目标的数据。
     * @param array $headers 需要加入的头信息
     * @return array
     * eg: array(
     *          'http_code' => 200,
     *          'data' => array('brokername' => '张三')
     * )
     */
    public static function get_data_from_url($url, $type = 'json', $post = array(), $headers = array()) {
        if ($post) {
            $p_strMethod = "post";
        } else {
            $p_strMethod = "get";
        }

        $result = self::_callAPI($url, $post, $p_strMethod, $type, $headers);

        return $result;
    }

    /**
     * 从拱平内部获得数据
     * @param string $url
     * @return array
     */
    public static function get_data_from_java_api($url, $post = array(), $needfrom = true) {
        $hosts = APF::get_instance()->get_config('back_api_host');
        $host = $hosts['java_internal'];
        $from = APF::get_instance()->get_config('java_api_from');

        if (strpos($url, '?') !== FALSE) {
            $mark = '&';
        } else {
            $mark = '?';
        }

        if ($needfrom) {
            $real_url = $host . $url . $mark . 'from=' . $from . '&json';
        } else {
            $real_url = $host . $url . '&json';
        }


        $real_url = $host . $url . ($needfrom ? ($mark . 'from=' . $from . '&json') : '&json');
        $data = self::get_data_from_url($real_url, 'json', $post);
        return $data;
    }

    /**
     * 从拱平OpenAPI
     * @param type $url
     * @param type $post
     * @param type $headers
     */
    public static function get_data_from_java_v3($url, $post = array(), $needfrom = true) {
        $hosts = APF::get_instance()->get_config('back_api_host');
        $host = $hosts['java_v3'];
        $from = APF::get_instance()->get_config('java_api_from');

        if (strpos($url, '?') !== FALSE) {
            $mark = '&';
        } else {
            $mark = '?';
        }

        if ($needfrom) {
            $real_url = $host . $url . $mark . 'from=' . $from;
        } else {
            $real_url = $host . $url;
        }
        $key = APF::get_instance()->get_config('java_v3_key');
        $headers = array(
            'key: ' . $key['shared_key'],
            'sig: ' . self::signature($real_url, $post, $key['private_key']),
            'Content-type: application/json'
        );
        $data = self::get_data_from_url($real_url, 'json', $post, $headers);
        return $data;
    }


    /**
     * 从宋API获得数据
     * @param string $url
     * @return array
     */
    public static function get_data_from_chat_api($url, $post = array(), $headers = array()) {
        $hosts = APF::get_instance()->get_config('chat_api_host');
        $host = $hosts['host'];

        $real_url = $host . $url . '?from_idc=1&from=mobile-ajk-broker&skip_auth=1';
        $defaultHeaders = array(
            'Content-type: application/json'
        );
        $headers = array_merge($defaultHeaders, $headers);
        $data = self::get_data_from_url($real_url, 'json', $post, $headers);
        return $data;
    }

    /**
     * 计算 java 3.0 的签名
     *
     * @param string $request_uri
     * @param array $params
     * @param string $request_body
     * @param string $shared_key
     * @param bool newmethod
     * @return string
     */
    public static function signature($url, $post, $shared_key) {
        $url_info = parse_url($url);
        $request_uri = str_replace("/broker/3.0/", "/3.0/", $url_info['path']);

        if ($url_info['query']) {
            $params = explode('&', $url_info['query']);
        }

        $_params = array();
        $_params_sort = array();
        if (!empty($params)) {
            foreach ($params as $v) {
                $tmp = explode('=', $v);
                $_params[$tmp[0]] = urldecode($tmp[1]);
            }


            foreach ($_params as $key => $value) {
                $_params_sort[] = "$key=$value";
            }

            sort($_params_sort);
        }

        if ($post) {
            if (is_array($post)) {
                foreach ($post as $key => $value) {
                    if (is_array($value) || is_object($value)) {
                        $value = json_encode($value);
                    }

                    $_post_sort[] = "$key=$value";
                }
                sort($_post_sort);
                $post_json = implode('&', $_post_sort);
            } else {
                $post_json = $post;
            }
        }

        $finalstr = $request_uri . implode('&', $_params_sort) . $post_json . $shared_key;
        $sig = md5($finalstr);
        return $sig;
    }

    /**
     * 调用API
     * @param string $p_strURL
     * @param mix $p_mixData
     * @param string $p_strMethod
     * @param string $p_strResultType
     * @return mix
     */
    private static function _callAPI($p_strURL, $p_mixData, $p_strMethod, $p_strResultType = 'json', $headers = array()) {
        $p_strData = '';

        if (is_array($p_mixData)) {
            foreach ($p_mixData as $strKey => $strValue) {
                if (is_array($strValue) || is_object($strValue)) {
                    $strValue = json_encode($strValue);
                }
                $p_strData.=('&' . $strKey . '=' . urlencode($strValue));
            }
            if (isset($p_strData[0])) {
                $p_strData = substr($p_strData, 1);
            }
        } else {
            $p_strData = $p_mixData;
        }

        apf_require_class('APF_Http_Client_Factory');
        $objCURL = APF_Http_Client_Factory::get_instance()->get_curl($p_strMethod);


        if ($headers) {
            $entire_headers = array_merge(array("Content-type:application/x-www-form-urlencoded;charset=UTF-8"), $headers);
        } else {
            $entire_headers = array("Content-type:application/x-www-form-urlencoded;charset=UTF-8");
        }

        if (!is_array($p_mixData)) {
            $entire_headers = array_merge(array("Content-type:application/json;charset=UTF-8"), $headers);
        }

        $timeout = APF::get_instance()->get_config('back_api_timeout');

        $objCURL->set_attribute(CURLOPT_HTTPHEADER, $entire_headers);
        $objCURL->set_attribute(CURLOPT_CONNECTTIMEOUT, $timeout['connect_timeout']);
        $objCURL->set_attribute(CURLOPT_TIMEOUT, $timeout['curl_timeout']);
        $objCURL->set_url($p_strURL);

        if ('post' == $p_strMethod) {

            $objCURL->set_url($p_strURL);
            $objCURL->set_attribute(CURLOPT_POSTFIELDS, $p_strData);
            if (isset($p_strData[0])) {
                $p_strURL.='?' . $p_strData;
            }
        } else {
            if (isset($p_strData[0])) {
                $p_strURL.='?' . $p_strData;
            }


            $objCURL->set_url($p_strURL);
        }

        $strCookies = '';

        foreach ($_COOKIE as $strKey => $mixValue) {
            $strCookies.=(';' . $strKey . '=' . urlencode($mixValue));
        }

        $strCookies = substr($strCookies, 1);
        $objCURL->set_attribute(CURLOPT_COOKIE, $strCookies);
        $objCURL->set_attribute(CURLOPT_USERAGENT, (isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'Anjuke API'));

        if (APF::get_instance()->is_debug_enabled()) {
            APF::get_instance()->debug('API->Call[' . $p_strMethod . ']: ' . $p_strURL);
        }

        $bolResult = $objCURL->execute();

        if ($bolResult) {
            $result['http_code'] = 200;
            $strResource_json = $objCURL->get_response_text();

            switch ($p_strResultType) {
                case 'json':
                    $strResource = json_decode($strResource_json, true);
                    break;
                case 'string':
                    break;
                default:
                    break;
            }

            $result['data'] = $strResource;
            return $result;
        } else {
            APF::get_instance()->get_logger()->error('API->Call[' . $p_strMethod . ']: ' . $p_strURL . ' failed.');
            return false;
        }
    }

    /**
     * Get获取数据
     * @param string $p_strURL
     * @param string $p_strResultType
     * @return mix
     */
    static function getData($p_strURL, $p_mixData, $p_strResultType = 'json') {
        return self::_callAPI($p_strURL, $p_mixData, 'get', $p_strResultType);
    }

    /**
     * Post获取数据
     * @param string $p_strURL
     * @param mix $p_strData
     * @param string $p_strResultType
     * @return mix
     */
    static function postData($p_strURL, $p_mixData, $p_strResultType = 'json') {
        return self::_callAPI($p_strURL, $p_mixData, 'post', $p_strResultType);
    }

    /**
     * 从拱平OpenAPI
     * @param type $url
     * @param type $post
     * @param type $headers
     */
    public static function get_data_from_java_v2($url, $post = array(), $needfrom = true) {
        $hosts = APF::get_instance()->get_config('back_api_host');
        $host = $hosts['java_v2_pg'];
        $from = APF::get_instance()->get_config('java_api_from');

        if (strpos($url, '?') !== FALSE) {
            $mark = '&';
        } else {
            $mark = '?';
        }

        if ($needfrom) {
            $real_url = $host . $url . $mark . 'from=' . $from;
        } else {
            $real_url = $host . $url;
        }

        $key = APF::get_instance()->get_config('java_v3_key_pg');
        $headers = array(
            'key: ' . $key['shared_key'],
            'sig: ' . self::signature($real_url, $post, $key['private_key']),
            'Content-type: application/json'
        );
        $data = self::get_data_from_url($real_url, 'json', $post, $headers);
        return $data;
    }

    /**
     * 从拱平OpenAPI
     * @param type $url
     * @param type $post
     * @param type $headers
     */
    public static function get_data_from_java_ga($url, $post = array(), $needfrom = true) {
        $hosts = APF::get_instance()->get_config('back_api_host');
        $host = $hosts['java_v3_ga'];
        $from = APF::get_instance()->get_config('java_api_from');

        if (strpos($url, '?') !== FALSE) {
            $mark = '&';
        } else {
            $mark = '?';
        }

        if ($needfrom) {
            $real_url = $host . $url . $mark . 'from=' . $from;
        } else {
            $real_url = $host . $url;
        }

        $key = APF::get_instance()->get_config('java_v3_key_ga');
        $headers = array(
            'key: ' . $key['shared_key'],
            'sig: ' . self::signature($real_url, $post, $key['private_key']),
            'Content-type: application/json'
        );
        $data = self::get_data_from_url($real_url, 'json', $post, $headers);
        return $data;
    }

    /**
     * 请求 http://$host/service-exes/rest/exes/ 的API的封装
     * @param string $url_path url的path
     * @param string $app_cfg_name 使用的支付中心的配置。
     * @param array $api_params api参数。
     * @return array
     */
    public static function call_internal_exes_api($url_path, $app_cfg_name, $api_params) {
        $cfg = APF::get_instance()->get_config($app_cfg_name);

        $app_id = $cfg['paycenter_id'];
        $app_key = $cfg['paycenter_key'];

        $url_params = '?appId=' . $app_id . '&appKey=' . $app_key;

        foreach ($api_params as $k => $v) {
            $url_params .= '&' . $k . '=' . $v;
        }

        $real_url = '/service-exes/rest/exes/' . $url_path . $url_params;
        $ret = self::get_data_from_java_api($real_url);

        return $ret;
    }

}

# end of this file
