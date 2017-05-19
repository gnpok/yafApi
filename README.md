
# yaf+swoole_http_server

##**项目描述**
> 使用swoole_http_server + yaf 专注于网站api接口,同时能处理异步任务，如异步发送邮件，发送短信验证码等耗时相对长一点的任务
> 本项目兼容php-fpm模式，不过在异步任务调用处要调整

参照 [LinkedDestiny](https://github.com/LinkedDestiny/swoole-yaf)  
参照 [xuebingwang](https://github.com/xuebingwang/xbw-swoole-yaf)

##**使用说明**
```
//使用Nginx做反方代理，将所有请求代理到swoole端口上，配置如下
location / {
	proxy_pass http://127.0.0.1:9501;
}

//打开终端
cd yafApi/public
php http_server.php
```

##**注意事项**
1.  若你的接口需要多版本控制，需要配置modules，或参考[xuebingwang](https://github.com/xuebingwang/xbw-swoole-yaf)  
2.  调用task时候注意事项  
```
//1.当使用如下代码时候，可以在controller中调用task
$this->application->bootstrap();
//controller中代码
HttpServer::$http->task(time());  


//2.当入口文件使用如下代码时候
$this->application->bootstrap()->run();
//在controller中调用则会报出如下错误
PHP Fatal error:  Call to a member function task()

```
** 不懂C,现阶段专注于应用层，未涉及底层代码，望请大神指教**



