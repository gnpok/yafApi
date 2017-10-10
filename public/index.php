<?php
/**
 * 使用php-fpm时候走这边，此模式下不可以调用task异步任务
 */

//使用composer
require_once dirname(__FILE__).'/../vendor/autoload.php';

define('APPLICATION_PATH', dirname(__FILE__).'/..');
define('APP_PATH', APPLICATION_PATH.'/application/');

ini_set("yaf.environ",'development');

$application = new Yaf_Application( APPLICATION_PATH . "/conf/application.ini");

$application->bootstrap()->run();
