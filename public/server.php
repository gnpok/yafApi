<?php

class HttpServer
{
    public static $instance;
    public $http;
    public static $get;
    public static $post;
    public static $header;
    public static $server;
    private $application;

    public function __construct()
    {
        $http = new swoole_http_server("0.0.0.0", 9501);
        $http->set(
            array(
                'worker_num' => 4,
                'daemonize' => false,
                'max_request' => 5000,
                'dispatch_mode' => 1
            )
        );
        $http->on('WorkerStart', array($this, 'onWorkerStart'));
        $http->on('request', function ($request, $response) {
            HttpServer::$server = isset($request->server) ? $request->server : [];
            HttpServer::$header = isset($request->header) ? $request->header : [];
            HttpServer::$get = isset($request->get) ? $request->get : [];
            HttpServer::$post = isset($request->post) ? $request->post : [];
            // TODO handle img
            ob_start();
            try {
                $yaf_request = new Yaf_Request_Http(
                    HttpServer::$server['request_uri']);
                $this->application
                    ->getDispatcher()->dispatch($yaf_request);

                // unset(Yaf_Application::app());
            } catch (Yaf_Exception $e) {
                var_dump($e);
            }

            $result = ob_get_contents();
            ob_end_clean();
            // add Header

            // add cookies

            // set status
            $response->end($result);
        });
        $http->start();
    }

    public function onWorkerStart()
    {
        define('APPLICATION_PATH', dirname(__FILE__).'/..');
        define('APP_PATH', APPLICATION_PATH.'/application/');
        $this->application = new Yaf_Application(APPLICATION_PATH .
            "/conf/application.ini");
        ob_start();
        $this->application->bootstrap()->run();
        ob_end_clean();
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new HttpServer;
        }
        return self::$instance;
    }
}

HttpServer::getInstance();