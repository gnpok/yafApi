<?php


/**
 * 使用jwt生成token和解密token
 */

use Firebase\JWT\JWT;

class TokenLibrary
{
    protected static $leeway = 2000;//token的生存周期
    protected static $key = '12345';//TODO,login操作和已login操作时候的key是不一样的


    /**
     * 加密生成token
     * @param array $data
     * @return string
     */
    public static function encode($data = array())
    {
        $key = self::$key;
        $data['exp'] = time();
        return JWT::encode($data, $key);
    }


    /**
     * 验证token合法性
     * @param string $jwt_token
     * @return bool|string
     */
    public static function decode($jwt_token = '')
    {
        if(!empty($jwt_token)){
            $key = self::$key;
            JWT::$leeway = self::$leeway; //token的有效期
            try {
                $data = JWT::decode($jwt_token, $key, array('HS256'));
                //TODO 这边要对data中的元素进行判断是否是自己规定的格式

                return true;
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        }
        return 'empty token';
    }
}