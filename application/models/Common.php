<?php

/**
 * 公用model类
 */
class CommonModel
{

    static protected $db;

    public function __construct()
    {
        $objConfig = Yaf_Registry::get('config');
        self::$db = Yaf_Registry::get($objConfig->set['database']);
    }

}