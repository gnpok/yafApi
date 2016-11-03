
# yafApi
参照 [LinkedDestiny](https://github.com/LinkedDestiny/swoole-yaf)  
参照 [xuebingwang](https://github.com/xuebingwang/xbw-swoole-yaf)

##**项目描述**
> 使用swoole_http_server + yaf 专注于网站api接口  
可以使用异步task任务,如异步发送邮件，发送短信验证码等

注意:  
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
**这边我没有能理解为什么，还请求大神解答**

##**使用说明**
```
打开终端
cd yafApi
php server.php
```

##**swoole版本**
swoole-1.7.8+版本

##**yaf版本**
任意stable版本
