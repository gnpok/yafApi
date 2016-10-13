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
        $conf = parse_ini_file(dirname(__FILE__) . '/../conf/swoole.ini');
        $httpcConf = $conf['httpserver'];
        $http = new swoole_http_server($httpcConf['host'], $httpcConf['port']);
        $http->set(array(
            'worker_num' => $httpcConf['worker_num'],
            'daemonize' => $httpcConf['daemonize'],
            'dispatch_mode' => $httpcConf['dispatch_mode'],
            'heartbeat_check_interval' => $httpcConf['heartbeat_check_interval'],
            'heartbeat_idle_time' => $httpcConf['heartbeat_idle_time'],
            'open_tcp_nodelay' => $httpcConf['open_tcp_nodelay'],
            'log_file' => $httpcConf['log_file'],
        ));

        $http->on('WorkerStart', array($this, 'onWorkerStart'));
        $http->on('request', function ($request, $response) {
            //请求过滤,会请求2次
            if ($request->server['path_info'] == '/favicon.ico' || $request->server['request_uri'] == '/favicon.ico') {
                return $response->end();
            }
            HttpServer::$server = isset($request->server) ? $request->server : [];
            HttpServer::$header = isset($request->header) ? $request->header : [];
            HttpServer::$get = isset($request->get) ? $request->get : [];
            HttpServer::$post = isset($request->post) ? $request->post : [];

            // TODO handle img
            ob_start();
            try {
                $yaf_request = new Yaf_Request_Http(HttpServer::$server['request_uri']);
                $this->application->getDispatcher()->dispatch($yaf_request);
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
        define('APPLICATION_PATH', dirname(__FILE__) . '/..');
        define('APP_PATH', APPLICATION_PATH . '/application/');
        $this->application = new Yaf_Application(APPLICATION_PATH . "/conf/application.ini");
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