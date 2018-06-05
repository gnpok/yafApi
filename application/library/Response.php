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
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        return false;
    }


    public static function success($msg = '', $data = [])
    {
        $code = self::SUCCESS_CODE;
        $response = compact('code', 'msg', 'data');
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        return true;
    }
}