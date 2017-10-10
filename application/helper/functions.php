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
        switch (strtolower($type)) {
            case 'get':
                $values = defined('IS_SWOOLE') ? HttpServer::$get : $requestObj->getQuery();
                break;
            case 'post':
                $values = defined('IS_SWOOLE') ? HttpServer::$post : $requestObj->getPost();
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

/**
 * 成功返回json数据
 * @param int $code 状态0为成功
 * @param string $msg 提示消息
 * @param array $data 数据
 * @return bool
 */
if (!function_exists('json_success')) {
    function json_success($code = 0, $msg = '', $data = array())
    {
        if ($code == 0) {
            header('content-type:application/json;charset=utf8');
            $response = compact('code', 'msg', 'data');
            echo json_encode($response);
            return true;
        }
        return false;
    }
}


/**
 * 失败时候返回json数据
 * @param int $code 状态不为0
 * @param string $msg
 * @param array $data
 * @return bool
 */
if (!function_exists('json_fail')) {
    function json_fail($code = 1, $msg = '', $data = array())
    {
        if ($code != 0) {
            header('content-type:application/json;charset=utf8');
            $response = compact('code', 'msg', 'data');
            echo json_encode($response);
        }
        return false;
    }
}
