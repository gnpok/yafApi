<?php

/**
 * @name IndexController
 * @author vagrant
 * @desc 默认控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
 */
class IndexController extends ApiBaseController
{


    public function indexAction($name = "Stranger")
    {

        $data = array('name' => 'Hello World');
        $this->jsonReturn(0,'wow',$data);
        echo 'has next?';
        //调用异步任务
        // HttpServer::$http->task(time());
    }
}
