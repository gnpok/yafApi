<?php

class TaskLibrary
{

    /**
     * 异步任务分发中心
     * @param array $data
     */
    public static function createTask($data = array())
    {
        if (is_array($data) && isset($data['event'])) {
            $event = strtolower(trim($data['event']));
            $event = ucfirst($event);
            $taskPath = dirname(__FILE__) . '/task/' . $event . '.php';
            if (is_file($taskPath)) {
                Yaf_Loader::import('task/' . $event . '.php');
                $event::doTask($data);
            }
        }
    }
}