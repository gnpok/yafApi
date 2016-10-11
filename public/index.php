<?php

define('APPLICATION_PATH', dirname(__FILE__).'/..');
define('APP_PATH', APPLICATION_PATH.'/application/');

$application = new Yaf_Application( APPLICATION_PATH . "/conf/application.ini");


$application->bootstrap()->run();
