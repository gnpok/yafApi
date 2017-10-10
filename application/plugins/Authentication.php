<?php
/**
 * 权限接口验证
 */

class AuthenticationPlugin extends Yaf_Plugin_Abstract
{


    public function routerStartup(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response)
    {
        //验证token是否合法
        $token = get('token', '', 'trim');
        $auth_msg = TokenLibrary::decode($token);
        if ($auth_msg !== true && is_string($auth_msg)) {
            json_fail(401,$auth_msg);
        }
    }

    public function routerShutdown(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response)
    {
    }

    public function dispatchLoopStartup(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response)
    {
    }

    public function preDispatch(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response)
    {
    }

    public function postDispatch(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response)
    {
    }

    public function dispatchLoopShutdown(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response)
    {
    }
}