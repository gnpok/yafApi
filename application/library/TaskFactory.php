<?php

class TaskFactory
{

    public static function createTask(array $taskData)
    {
        $event = $taskData['event'];
        $class = 'Task_'.ucfirst($event);
        $class::doTask($taskData);
    }
}