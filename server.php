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
    private $environment = 'develop'; //product OR develop

    private function __construct()
    {

        $configObj = new Yaf_Config_Ini(dirname(__FILE__) . '/conf/application.ini');
        $configArr = $configObj->toArray();
        $config = $configArr[$this->environment]['swoole'];
        extract($config);

        $http = new swoole_http_server($host, $port);
        $http->set(array(
            'worker_num'                => $worker_num,
            'task_worker_num'           => $task_worker_num,
            'daemonize'                 => $daemonize,
            'dispatch_mode'             => $dispatch_mode,
            'open_tcp_nodelay'          => $open_tcp_nodelay,
            'log_file'                  => $log_file,
            'heartbeat_check_interval'  => $heartbeat_check_interval,
            'heartbeat_idle_time'       => $heartbeat_idle_time,
        ));

        $http->on('WorkerStart', array($this, 'onWorkerStart'));
        $http->on('task', array($this, 'onTask'));
        $http->on('finish', array($this, 'onFinish'));

        $http->on('request', function ($request, $response) use($http) {
            //请求过滤,会请求2次
            if(in_array('/favicon.ico', [$request->server['path_info'],$request->server['request_uri']])){
                return $response->end();
            }

            HttpServer::$header     = isset($request->header)   ? $request->header  : [];
            HttpServer::$get        = isset($request->get)      ? $request->get     : [];
            HttpServer::$post       = isset($request->post)     ? $request->post    : [];
            HttpServer::$cookies    = isset($request->cookies)  ? $request->cookies : [];
            HttpServer::$rawContent = $request->rawContent();
            HttpServer::$http       = $http;

            try {
                ob_start();
                $yaf_request = new Yaf_Request_Http($request->server['request_uri']);
                $this->application->getDispatcher()->dispatch($yaf_request);
                $result = ob_get_contents();
                ob_end_clean();
            } catch (Yaf_Exception $e) {
                $result           = array();
                $result['code']   = $e->getCode();
                $result['msg']    = $e->getMessage();
                $result           = json_encode($result,JSON_UNESCAPED_UNICODE);
            }

            $response->header('Content-Type', 'application/json; charset=utf-8');
            $response->end($result);
        });

        $http->start();
    }

    public function onWorkerStart($serv, $worker_id)
    {
        define('APPLICATION_PATH', dirname(__FILE__));
        define('APP_PATH', APPLICATION_PATH . '/application/');

        switch ($this->environment)
        {
            case 'develop':
                error_reporting(-1);
                ini_set('display_errors', 1);
                $application = new Yaf_Application(APPLICATION_PATH . "/conf/application.ini",'develop');
            break;

            case 'product':
                ini_set('display_errors', 0);
                if (version_compare(PHP_VERSION, '5.3', '>='))
                {
                    error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
                }
                else
                {
                    error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_USER_NOTICE);
                }
                $application = new Yaf_Application(APPLICATION_PATH . "/conf/application.ini");
            break;
        }

        $this->application = $application;
        $this->application->bootstrap()->run();

        if ($worker_id >= $serv->setting['worker_num']) {
            cli_set_process_title("swoolehttp:task_worker");
        } else {
            cli_set_process_title("swoolehttp:worker");
        }
    }

    public function onTask($serv, $taskId, $fromId, $data)
    {
        $task = new TaskLibrary($data);
    }

    public function onFinish($serv, $taskId, $data)
    {
        # code...
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}

HttpServer::getInstance();