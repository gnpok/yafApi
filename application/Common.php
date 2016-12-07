<?php

###########公共函数部分###########
if (!function_exists('p')) {
    function p($arr)
    {
        echo '<pre>', print_r($arr), '</pre>';
    }
}


if (!function_exists('jsonReturn')) {
    /**
     * json返回数据
     * @param int $code 状态码 0:执行或验证成功  不为0：执行或验证失败
     * @param string|array $value 信息 或数组数据
     * @return bool $exit 是否要退出程序
     */
    function jsonReturn($code, $value, $exit = false)
    {
        $key = is_array($value) ? 'data' : 'msg';
        $response = [
            'code' => $code,
            $key => $value
        ];
        echo json_encode($response);

        if ($code != 0 || $exit) {
            return false;
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

if (!function_exists('get')) {
    /**
     * 获取get参数
     * @param $name get参数名
     * @param string $default 默认数值
     * @param string $func 调用函数
     * @return mixed
     */
    function get($name, $default = '', $func = 'htmlspecialchars')
    {
        if (isset($_SERVER['is_swoole']) && $_SERVER['is_swoole']) {
            $get = isset(HttpServer::$get[$name]) ? HttpServer::$get[$name] : $default;
        } else {
            $request = Yaf_Dispatcher::getInstance()->getRequest();
            $get = $request->getQuery($name, $default);
        }
        return call_user_func($func, trim($get));
    }

}

if (!function_exists('post')) {
    function post($name, $default = '', $func = 'htmlspecialchars')
    {
        if (isset($_SERVER['is_swoole']) && $_SERVER['is_swoole']) {
            $post = isset(HttpServer::$post[$name]) ? HttpServer::$post[$name] : $default;
        } else {
            $request = Yaf_Dispatcher::getInstance()->getRequest();
            $post = $request->getPost($name, $default);
        }
        return call_user_func($func, trim($post));
    }
}
