<?php

require_once dirname(__FILE__).'/vendor/autoload.php';

class HttpServer
{
    public static $instance;
    public static $http;
    public static $get;
    public static $post;
    public static $header;
    public static $server;
    public static $cookies;
    public static $rawContent;
    private $application;

    public function __construct()
    {
        $config = new Yaf_Config_Ini(dirname(__FILE__) . '/conf/swoole.ini');
        $config = $config->get('swoole');

        $http = new swoole_http_server($config->host, $config->port);
        $http->set(array(
            'worker_num'                => $config->worker_num,
            'task_worker_num'           => $config->task_worker_num,
            'daemonize'                 => $config->daemonize,
            'dispatch_mode'             => $config->dispatch_mode,
            'open_tcp_nodelay'          => $config->open_tcp_nodelay,
            'log_file'                  => $config->log_file,
            'heartbeat_check_interval'  => $config->heartbeat_check_interval,
            'heartbeat_idle_time'       => $config->heartbeat_idle_time,
        ));

        $http->on('WorkerStart', array($this, 'onWorkerStart'));
        $http->on('task', array($this, 'onTask'));
        $http->on('finish', array($this, 'onFinish'));

        $http->on('request', function ($request, $response) {
            //请求过滤,会请求2次
            if ($request->server['path_info'] == '/favicon.ico' || $request->server['request_uri'] == '/favicon.ico') {
                return $response->end();
            }

            HttpServer::$header = $request->header;
            HttpServer::$get = $request->get;
            HttpServer::$post = $request->post;
            HttpServer::$cookies = $request->cookie;
            HttpServer::$rawContent = $request->rawContent;

            ob_start();
            try {
                $yaf_request = new Yaf_Request_Http(HttpServer::$server['request_uri']);
                $this->application->getDispatcher()->dispatch($yaf_request);
            } catch (Yaf_Exception $e) {
                jsonReturn(404,'not found');
            }

            $result = ob_get_contents();
            ob_end_clean();
            $response->header('Content-Type', 'application/json; charset=utf-8');
            $response->end($result);
        });
        $http->start();

        self::$http = $http;
    }

    public function onWorkerStart($serv, $worker_id)
    {
        define('APPLICATION_PATH', dirname(__FILE__) . '/..');
        define('APP_PATH', APPLICATION_PATH . '/application/');

        $this->application = new Yaf_Application (APPLICATION_PATH . "/conf/application.ini");
        $this->application->bootstrap();
        
        if ($worker_id >= $serv->setting['worker_num']) {
            cli_set_process_title("swoolehttp:task_worker");
        } else {
            cli_set_process_title("swoolehttp:worker");
        }
    }

    public function onTask($serv, $taskId, $fromId, $data)
    {
        # code...
        echo $data."\n";
    }

    public function onFinish($serv, $taskId, $data)
    {
        # code...
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