<?php

/**
 * @name IndexController
 * @author vagrant
 * @desc 默认控制器
 */
class IndexController extends Yaf_Controller_Abstract
{


    public function indexAction()
    {
        //1.json格式化输出
        json_success(0,'success'); //继续执行
        //return json_fail(1,'fail');//停止继续执行

        // //2.异步任务
        // $taskdata = array(
        //     'event' => 'email',
        //     'data' => array(
        //         'id' => 1,
        //         'to' => 'aaaa'
        //         )
        //     );
        // //2.1 php-fpm模式下调用异步任务
        // TaskLibrary::createTask($taskdata);
        // //2.2 swoole调用异步任务
        // HttpServer::$http->task($taskdata);
    }
}
