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
        $db = Yaf_Registry::get('db');
        $res = $db->select('admin_user','*');
        return Response::success('success',$res);
    }

    public function taskAction()
    {
        //异步任务
        $taskData = array(
            'event' => 'email',
            'data' => array(
                'id' => 1,
                'to' => 'aaaa'
            )
        );
        //1 php-fpm模式下调用异步任务
        //TaskFactory::createTask($taskData);
        //2 swoole调用异步任务
        $server = SWOOLE_SERVER;
        $server::$httpServer->task($taskData);
    }

}
