<?php
/**
 * json返回输出
 */

class Response
{
    const ERROR_CODE = 1;
    const SUCCESS_CODE = 0;

    public static function error($msg = '', $data = [])
    {
        $code = self::ERROR_CODE;
        $response = compact('code', 'msg', 'data');
        return self::_showCommon($response, $code);
    }


    public static function success($msg = '', $data = [])
    {
        $code = self::SUCCESS_CODE;
        $response = compact('code', 'msg', 'data');
        return self::_showCommon($response, $code);
    }

    private static function _showCommon($response = [], $type = 0)
    {
        if (!defined('SWOOLE_SERVER')) {
            header('content-type:application/json;charset=utf8');
        }
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        return $type == 1 ? false : true;
    }

}