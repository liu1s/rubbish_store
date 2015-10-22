<?php

/**
 * 字符串工具类
 */
class Util_String
{

    /**
     * 判断字符串是否是UTF-8编码
     *
     * @param $string
     *
     * @return bool
     */
    public static function isUtf8($string)
    {
        return (bool)preg_match('//u', $string); // Since PHP 5.2.5, this also excludes invalid five and six bytes sequences
    }

    /**
     * Return the length of the given string.
     *
     * @param  string $string
     *
     * @return int
     */
    public static function length($string)
    {
        return mb_strlen($string);
    }

    /**
     * Limit the number of characters in a string.
     *
     * @param  string $string
     * @param  int $limit
     * @param  string $end
     *
     * @return string
     */
    public static function limit($string, $limit = 100, $end = '...')
    {
        if (mb_strlen($string) <= $limit) return $string;

        return rtrim(mb_substr($string, 0, $limit, 'UTF-8')) . $end;
    }

    /**
     * 按视觉长度截取字符串，长度单位为：半角字符的宽度，如：a。
     *
     * @param $string
     * @param int $limit
     * @param string $end
     *
     * @return string
     */
    public static function truncate($string, $limit = 100, $end = '...')
    {
        $length = strlen($string);

        $charAtIndex = 0;
        $i = 0;
        $j = 0;

        while ($j < $length && $i < $limit) {
            $char = mb_substr($string, $charAtIndex, 1, 'UTF-8');
            $charLength = strlen($char);

            if ($charLength == 1) {
                $i += 1;
            } else {
                $i += 2;
            }

            $j += $charLength;
            $charAtIndex++;
        }

        return $j < $length
            ? substr($string, 0, $j) . $end
            : substr($string, 0, $j);
    }

    /**
     * Determine if a given string matches a given pattern.
     *
     * @param  string $pattern
     * @param  string $value
     * @return bool
     */
    public static function is($pattern, $value)
    {
        if ($pattern == $value) return true;

        $pattern = preg_quote($pattern, '#');

        // Asterisks are translated into zero-or-more regular expression wildcards
        // to make it convenient to check if the strings starts with the given
        // pattern such as "library/*", making any string check convenient.
        $pattern = str_replace('\*', '.*', $pattern) . '\z';

        return (bool)preg_match('#^' . $pattern . '#', $value);
    }

    /**
     * Determine if a given string contains a given substring.
     *
     * @param  string $haystack
     * @param  string|array $needles
     * @return bool
     */
    public static function contains($haystack, $needles)
    {
        foreach ((array)$needles as $needle) {
            if ($needle != '' && strpos($haystack, $needle) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     *
     * @param $haystack
     * @param $needles
     *
     * @return bool
     */
    public static function startsWith($haystack, $needles)
    {
        foreach ((array)$needles as $needle) {
            if ($needle != '' && strpos($haystack, $needle) === 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine if a given string ends with a given substring.
     *
     * @param string $haystack
     * @param string|array $needles
     *
     * @return bool
     */
    public static function endsWith($haystack, $needles)
    {
        foreach ((array)$needles as $needle) {
            if ($needle == substr($haystack, -strlen($needle))) {
                return true;
            }
        }

        return false;
    }

    /**
     * Convert a value to camel case.
     *
     * @param  string $string
     * @return string
     */
    public static function camel($string)
    {
        return lcfirst(static::studly($string));
    }

    /**
     * Convert a value to studly caps case.
     *
     * @param  string $string
     * @return string
     */
    public static function studly($string)
    {
        $string = ucwords(str_replace(array('-', '_'), ' ', $string));

        return str_replace(' ', '', $string);
    }

    /**
     * Convert a string to snake case.
     *
     * @param  string $string
     * @param  string $delimiter
     * @return string
     */
    public static function snake($string, $delimiter = '_')
    {
        $replace = '$1' . $delimiter . '$2';

        return ctype_lower($string) ? $string : strtolower(preg_replace('/(.)([A-Z])/', $replace, $string));
    }

    /**
     * Generate a more truly "random" alpha-numeric string.
     *
     * @param  int $length
     * @return string
     *
     * @throws \RuntimeException
     */
    public static function random($length = 16)
    {
        if (function_exists('openssl_random_pseudo_bytes')) {
            $bytes = openssl_random_pseudo_bytes($length * 2);

            if ($bytes === false) {
                throw new \RuntimeException('Unable to generate random string.');
            }

            return substr(str_replace(array('/', '+', '='), '', base64_encode($bytes)), 0, $length);
        }

        return static::quickRandom($length);
    }

    /**
     * Generate a "random" alpha-numeric string.
     *
     * Should not be considered sufficient for cryptography, etc.
     *
     * @param  int $length
     * @return string
     */
    public static function quickRandom($length = 16)
    {
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        return substr(str_shuffle(str_repeat($pool, 5)), 0, $length);
    }

    public static function uuid()
    {
        $chars = md5(uniqid(mt_rand(), true));

        $uuid = substr($chars,0,8) . '-';
        $uuid .= substr($chars,8,4) . '-';
        $uuid .= substr($chars,12,4) . '-';
        $uuid .= substr($chars,16,4) . '-';
        $uuid .= substr($chars,20,12);

        return $uuid;
    }

    public static function isJson($str)
    {
        return (!is_null(json_decode($str)));
    }
}
