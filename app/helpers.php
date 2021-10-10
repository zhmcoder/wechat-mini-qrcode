<?php

use Illuminate\Support\Facades\Log;

if (!function_exists('http_path')) {

    function http_path($url = '')
    {
        // 空地址
        if (empty($url)) {
            return '';
        }

        // 远程地址
        if (strpos($url, 'http') !== false) {
            return $url;
        }

        // 本机地址
        if ((\Illuminate\Support\Facades\Storage::exists('public/' . $url))) {
            return config('filesystems.disks.public.url') . '/' . $url;
        }

        // 七牛地址
        return config('filesystems.disks.qiniu.domain') . '/' . $url;
    }
}

if (!function_exists('stringToText')) {

    function stringToText($string, $num)
    {
        if ($string) {
            //把一些预定义的 HTML 实体转换为字符
            $html_string = htmlspecialchars_decode($string);
            //将空格替换成空
            $content = str_replace(" ", "", $html_string);
            $content = str_replace("&nbsp;", "", $content);
            //函数剥去字符串中的 HTML、XML 以及 PHP 的标签,获取纯文本内容
            $contents = strip_tags($content);
            //返回字符串中的前$num字符串长度的字符
            return mb_strlen($contents, 'utf-8') > $num ? mb_substr($contents, 0, $num, "utf-8") . '...' : mb_substr($contents, 0, $num, "utf-8");
        } else {
            return $string;
        }
    }
}

if (!function_exists('getFileTime')) {

    function getFileTime($path)
    {
        if (!empty($path)) {
            $mediaTool = new \getID3();

            $mediaInfo = $mediaTool->analyze($path);
            $time = isset($mediaInfo['playtime_seconds']) ? $mediaInfo['playtime_seconds'] : 0;

            return $time;
        } else {
            return 0;
        }
    }
}


if (!function_exists('get_client_ip')) {
    function get_client_ip($type = 0)
    {
        $type = $type ? 1 : 0;
        static $ip = NULL;
        if ($ip !== NULL) return $ip[$type];
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $pos = array_search('unknown', $arr);
            if (false !== $pos) unset($arr[$pos]);
            $ip = trim($arr[0]);
        } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        // IP地址合法验证
        $long = sprintf("%u", ip2long($ip));
        $ip = $long ? array($ip, $long) : array('0.0.0.0', 0);
        return $ip[$type];
    }
}

if (!function_exists('error_log_info')) {

    function error_log_info($message, array $context = array())
    {
        Log::channel('error_log')->info($message, $context);
    }
}

if (!function_exists('result_log_info')) {

    function result_log_info($message, array $context = array())
    {
        Log::channel('result_log')->info($message, $context);
    }
}

function to_hour_minute($second = 0)
{
    // 小时
    $hour = floor($second / 3600);
    if ($hour < 10) {
        $hour = '0' . $hour;
    }

    $middle = $second % 3600;

    // 分钟
    $minute = floor($middle / 60);
    if ($minute < 10) {
        $minute = '0' . $minute;
    }

    // 秒
    $second = $second % 60;
    if ($second < 10) {
        $second = '0' . $second;
    }

    if ($hour <= 0) {
        return $minute . ':' . $second;
    }
    return $hour . ':' . $minute . ':' . $second;
}