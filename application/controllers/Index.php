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
        //1.json格式化输出
        $this->jsonReturn(1,'wow',$data);

        //2.异步任务
        $taskdata = array(
            'event' => 'email',
            'data' => array(
                'id' => 1,
                'to' => 'aaaa'
                )
            );
        //2.1 php-fpm模式下调用异步任务
        TaskLibrary::createTask($taskdata);
        //2.2 swoole调用异步任务
        HttpServer::$http->task($taskdata);
    }
}
