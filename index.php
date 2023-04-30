<?php
//引入自动加载文件
require 'Autoload.php';
//————————————————————————————————————————————————————————————
//调试区
//echo phpinfo();exit;
//————————————————————————————————————————————————————————————
//设置中国时区
date_default_timezone_set('PRC');
//处理跨域问题
header("Access-Control-Allow-Origin:*");
//————————————————————————————————————————————————————————————
//定义常量
define('ROOT', $_SERVER['DOCUMENT_ROOT']);
//————————————————————————————————————————————————————————————
//手动加载
Autoload::load();
//自动加载
spl_autoload_register('Autoload::run');
//加载 Env 环境变量
Frame\Env::load();
//————————————————————————————————————————————————————————————
//启用错误处理
Frame\Error::startUsing();
//注册一个会在php中止时执行的函数
register_shutdown_function('Frame\Error::error_end');
//注册用户自定义错误处理方法
set_error_handler('Frame\Error::error_handler');
//————————————————————————————————————————————————————————————
//调试区
//x($_GET,$_POST);
//————————————————————————————————————————————————————————————
//运行
Frame\Api::run();