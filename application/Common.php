<?php

###########公共函数部分###########
if (!function_exists('p')) {
    function p($arr)
    {
        echo '<pre>', print_r($arr), '</pre>';
    }
}

if (!function_exists('get')) {
    function get($name = '', $default = '', $func = '')
    {
        if (empty($name)) {

        } else {

        }
    }
}

if (!function_exists('curlGet')) {
    /**
     * 基础get请求
     * @param string $url 请求网址
     * @param array $data 请求的get数据，写入数组
     * @param int $timeout 请求超时
     * @return bool|string  请求失败则返回false，请求成功 则返回页面内容
     */
    function curlGet($url = '', $data = [], $timeout = 6)
    {
        if (!empty($data)) {
            $urlData = http_build_query($data);
            $sign = strpos($url, '?') === false ? '?' : '&';
            $url = $url . $sign . $urlData;
        }
        $ch = new CurlLibrary();
        $ch->include_response_headers(0);
        $response = $ch->get($url, null, $timeout);
        $ch->close();
        return $response;
    }
}

if (!function_exists('curlPost')) {
    /**
     * 基础post方法
     * @param $url
     * @param array $postData
     * @param int $timeout
     * @return mixed|string
     */
    function curlPost($url, $postData = [], $timeout = 10)
    {
        $ch = new CurlLibrary();
        $ch->include_response_headers(0);
        $response = $ch->post($url, $postData, '', $timeout);
        $ch->close();
        return $response;
    }
}