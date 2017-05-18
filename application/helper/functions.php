<?php

if(!function_exists('p')){
	function p($arr){
		echo '<pre>',print_r($arr),'</pre>';
	}
}


/**
 * get获取参数 为了代码能兼容swwole模式和fpm模式
 * @param $name string  get参数名字
 * @param $default 参数若不存在赋予一个默认值
 * @param 
 */
if(!function_exists('get')){
	function get($name = '',$default='',$func = ''){
		$gets = defined('IS_SWOOLE') ? HttpServer::$get : Yaf_Dispatcher::getInstance()->getRequest()->getQuery();
		if(!empty($name)){
			$get_value = array_key_exists($name, $gets) ? $gets[$name] : $default;
			return empty($func) ? $get_value : call_user_func($func,$get_value);
		}
		return $gets;
	}
}

if(!function_exists('post')){
	function post($name = '',$default='',$func = ''){
		$posts = defined('IS_SWOOLE') ? HttpServer::$post : Yaf_Dispatcher::getInstance()->getRequest()->getPost();
		if(!empty($name)){
			$post_value = array_key_exists($name, $posts) ? $posts[$name] : $default;
			return empty($func) ? $post_value : call_user_func($func,$post_value);
		}
		return $posts;
	}
}