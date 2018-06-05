<?php

if (!function_exists('p')) {
    function p($arr)
    {
        echo '<pre>', print_r($arr), '</pre>';
    }
}


/**
 * get获取参数 为了代码能兼容swwole模式和fpm模式
 * @param $name string  get参数名字
 * @param $default 参数若不存在赋予一个默认值
 * @param $func 过滤函数
 */
if (!function_exists('get')) {
    function get($name = '', $default = '', $func = '')
    {
        $gets = common_response_values('get', $name, $default, $func);
        return $gets;
    }
}

if (!function_exists('post')) {
    function post($name = '', $default = '', $func = '')
    {
        $posts = common_response_values('post', $name, $default, $func);
        return $posts;
    }
}

/**
 *获取用户输入的公共函数
 */
if (!function_exists('common_response_values')) {
    function common_response_values($type = 'get', $name = '', $default = '', $func = '')
    {
        $requestObj = Yaf_Dispatcher::getInstance()->getRequest();
        $server = defined('SWOOLE_SERVER') ? SWOOLE_SERVER : '';
        switch (strtolower($type)) {
            case 'get':
                $values = defined('SWOOLE_SERVER') ? $server::$get : $requestObj->getQuery();
                break;
            case 'post':
                $values = defined('SWOOLE_SERVER') ? $server::$post : $requestObj->getPost();
                break;
            default:
                return false;
                break;
        }

        if (!empty($name)) {
            $value = array_key_exists($name, $values) ? $values[$name] : $default;
            return empty($func) ? $value : call_user_func($func, $value);
        }
        return $values;
    }
}
