<?php

/**
 * api公共类，目的为了格式化输出数据
 */
class ApiBaseController extends Yaf_Controller_Abstract
{
    const CODE_ERROR = 1;
    const CODE_SUCCESS = 0;//0表示无错误

    /**
     * json格式化输出数据,可以放在library中当方法调用
     * @param  integer $code [状态码 自行定义]
     * @param  string  $msg  [消息提示]
     * @param  string  $data [返回信息，可以为array]
     * @return [type]        [description]
     */
    protected function jsonReturn($code = 0,$msg = '' ,$data = ''){

        if(!defined('IS_SWOOLE')){
            //是php-fpm格式时候，设置header头部，异常让程序内捕获
            header('content-type:application/json;charset=utf8');
            Yaf_Dispatcher::getInstance()->catchException(TRUE);
        }

        $response = compact('code','msg','data');
        $json = json_encode($response);
        $this->getResponse()->setBody($json);
        if($code != self::CODE_SUCCESS){
            throw new RuntimeException($msg,$code); 
        }else{
            return TRUE;
        }                                
    }
}

