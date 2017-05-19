<?php

/**
 * 测试异步任务
 */
class TaskEmail implements TaskInterface
{

    public static function doTask(array $data)
    {
    	var_dump($data);
    }
}
