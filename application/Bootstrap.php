<?php

/**
 * @name Bootstrap
 * @author vagrant
 * @desc 所有在Bootstrap类中, 以_init开头的方法, 都会被Yaf调用,
 * @see http://www.php.net/manual/en/class.yaf-bootstrap-abstract.php
 * 这些方法, 都接受一个参数:Yaf_Dispatcher $dispatcher
 * 调用的次序, 和申明的次序相同
 */
class Bootstrap extends Yaf_Bootstrap_Abstract
{
    protected $arrConfig;

    public function _initConfig()
    {
        $objConfig = Yaf_Application::app()->getConfig();
        Yaf_Registry::set('config', $objConfig);
        $this->arrConfig = $objConfig->toArray();
    }

    /**
     * 其他 如公共方法
     */
    public function _initOthers(Yaf_Dispatcher $dispatcher)
    {
        Yaf_Loader::import(APP_PATH.DIRECTORY_SEPARATOR.'helper'.DIRECTORY_SEPARATOR.'functions.php');
    }

    /**
     *初始化服务，如mysql,redis等 
     */
    public function _initServices(Yaf_Dispatcher $dispatcher)
    {
        //mysql-orm操作类可以使用medoo
    }

    public function _initPlugin(Yaf_Dispatcher $dispatcher)
    {
        $dispatcher->registerPlugin(new SafePlugin());//注册一个安全过滤插件
    }

    /**
     * 路由协议
     */
    public function _initRoute(Yaf_Dispatcher $dispatcher)
    {
        //在这里注册自己的路由协议,默认使用简单路由
        /*
        $router = Yaf_Dispatcher::getInstance()->getRouter();
        $routeConfig = array(
            "item" => array(
                "type"  => "regex",
                "match" => "#^/(software|game)/(.*).html$#",
                "route" => array('action' => 'item'),
                "map" => array( 1 => 'data_type', 2 => 'docid' ),
            ),
            //正则匹配
            "category" => array(
                "type"  => "regex",
                "match" => "#^/(software|game|video)/(.*)/(list_(.*).html)?$#",
                "route" => array('action' => 'list' ),
                "map" => array( 1 => 'data_type', 2 => 'cid',4 => 'page_num' ),
            ),
            //使用动态结果 :a 表示action
            "name" => array(
               "type"  => "rewrite",        //Yaf_Route_Rewrite route
               "match" => "/user-list/:a/:id", //match only /user-list/开头的
               "route" => array(
                   'controller' => "user",  //route to user controller,
                   'action'     => ":a",  //使用动态的action
               ),
            ),
        );
        $test = new Yaf_Config_Simple($routeConfig);
        $router->addConfig(new Yaf_Config_Simple($routeConfig));*/
    }

    public function _initView(Yaf_Dispatcher $dispatcher)
    {
        //在这里注册自己的view控制器，例如smarty,firekylin
        $dispatcher->disableView();//关闭view输出
        if(defined('IS_SWOOLE')){
            //若是swoole模式下，关闭默认输出，程序调用
            $dispatcher->returnResponse(true);
        }
    }
}
