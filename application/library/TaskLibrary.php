<?php

class TaskLibrary
{

    /**
     * 异步任务分发中心！
     * 为了统一管理异步任务，使用简单工厂模式
     * @param array $taskdata 必须包含event和data两个Key
     */
    public static function createTask(array $taskdata)
    {
        if (isset($taskdata['event']) && isset($taskdata['data'])) {
            $event = strtolower(trim($taskdata['event']));
            $event = 'Task'.ucfirst($event);
            $taskPath = dirname(__FILE__) . '/task/' . $event . '.php';
            if (is_file($taskPath)) {
                Yaf_Loader::import('task/' . $event . '.php');
                $event::doTask($taskdata['data']);
            }
        }
    }
}