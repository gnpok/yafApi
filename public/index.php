<?php
/**
 * 使用php-fpm时候走这边，此模式下不可以调用task异步任务
 */

define('APPLICATION_PATH', dirname(__FILE__).'/..');
define('APP_PATH', APPLICATION_PATH.'/application/');

$application = new Yaf_Application( APPLICATION_PATH . "/conf/application.ini");

$application->bootstrap()->run();
