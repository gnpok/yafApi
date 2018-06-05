<?php

/**
 * swoole http_server时候走这边
 */
define ('DS', DIRECTORY_SEPARATOR);
define ('ROOT_PATH', realpath(dirname(__FILE__) . '/../'));
define ('CONF_PATH', ROOT_PATH . DS . 'conf' . DS);

class HttpServer
{
    public static $instance;
    public static $httpServer;
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
        $config = new Yaf_Config_Ini(CONF_PATH . 'application.ini');
        $configArr = $config->toArray();
        $config = $configArr[$this->environment]['swoole'];
        extract($config);

        self::$httpServer = new swoole_http_server($host, $port);
        self::$httpServer->set(array(
            'worker_num' => $worker_num,         //worker进程数
            'task_worker_num' => $task_worker_num,    //task_worker进程数
            'daemonize' => $daemonize,
            'dispatch_mode' => $dispatch_mode,
            'open_tcp_nodelay' => $open_tcp_nodelay,
            'open_tcp_keepalive' => '',
            'tcp_defer_accept' => '',
            //'log_file' => ROOT_PATH . '/logs/swoole_http_server.log',
        ));
        self::$httpServer->on('WorkerStart', array($this, 'onWorkerStart'));
        self::$httpServer->on('request', array($this, 'onRequest'));
        self::$httpServer->on('task', array($this, 'onTask'));
        self::$httpServer->on('finish', array($this, 'onFinish'));
        self::$httpServer->start();
    }

    /**
     * woker进程启动
     */
    public function onWorkerStart($serv, $worker_id)
    {
        define('SWOOLE_SERVER', __CLASS__);
        require_once dirname(__FILE__) . '/../vendor/autoload.php';

        define('APPLICATION_PATH', dirname(__FILE__).'/..');
        $environment = $this->environment;
        $this->application = new Yaf_Application(APPLICATION_PATH . "/conf/application.ini", $environment);
        $this->application->bootstrap();

        if ($worker_id >= $serv->setting['worker_num']) {
            cli_set_process_title("swoolehttp:task_worker");
        } else {
            cli_set_process_title("swoolehttp:worker");
        }
    }

    /**
     * 处理http请求
     * @param $request
     * @param $response
     * @return mixed
     */
    public function onRequest($request, $response)
    {
        //请求过滤,会请求2次
        if (in_array('/favicon.ico', [$request->server['path_info'], $request->server['request_uri']])) {
            return $response->end();
        }

        self::$header = isset($request->header) ? $request->header : [];
        self::$get = isset($request->get) ? $request->get : [];
        self::$post = isset($request->post) ? $request->post : [];
        self::$cookies = isset($request->cookies) ? $request->cookies : [];
        self::$server = isset($request->server) ? $request->server : [];
        self::$rawContent = $request->rawContent();

        ob_start();
        try {
            $yafRequest = new Yaf_Request_Http(self::$server['request_uri']);
            $this->application->getDispatcher()->dispatch($yafRequest);
        } catch (Yaf_Exception $e) {
            var_dump($e->getMessage());
        }
        $result = ob_get_contents();
        ob_end_clean();
        $response->header('content-type', 'application/json;charset=utf8',true);
        $response->end($result);
    }

    /**
     * 异步任务
     */
    public function onTask($serv, $taskId, $fromId, array $taskdata)
    {
        echo "新的异步任务[来自进程 {$fromId}，当前进程 {$taskId}],data:" . json_encode($taskdata) . PHP_EOL;
        //在异步任务内需要调用swoole_server的话，需在createTask中将$serv参数传递过去
        TaskFactory::createTask($taskdata);
    }

    /**
     * 异步任务后续处理
     */
    public function onFinish($serv, $taskId, $data)
    {
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