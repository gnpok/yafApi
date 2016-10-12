<?php


use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;


class LogLibrary
{
    /**
     * 记录日志
     * @param string $logPath 日志文件
     * @param string $msg 文件消息
     * @param array $array 消息体数组格式
     * @param string $name 日志信息分类[自定义]
     * @param string $level 消息级别 [DEBUG|INFO|NOTICE|WARNING|ERROR|CRITICAL|ALERT]
     */
    static function writeLog($logPath, $msg, $array = [], $name = 'attack')
    {

        $logger = new Logger($name);
        $logger->pushHandler(new StreamHandler($logPath, Logger::INFO));
        $logger->pushHandler(new FirePHPHandler());
        //记录额外信息
        $logger->pushProcessor(function ($record) {
            $record['extra']['post'] = $_POST;
            return $record;
        });
        $logger->addInfo($msg, $array);
    }

    /**
     * 日志路径
     * @param string $type
     * @return string
     */
    static function logPath($type = '')
    {
        switch ($type) {
            case 'attack':
                return APP_PATH . 'logs/attack/attack_' . date('Ymd') . '.log';
                break;
        }

    }

}