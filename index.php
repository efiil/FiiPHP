<?php
/**
 * 公共入口文件
 * 1.定义常量
 * 2.加载函数库
 * 3.启动框架(错误收集待定)
 */

define('ROOT',__DIR__);
define('CORE',ROOT.'/core');
define('APP',ROOT.'/app');
define('MODULE','app');

define('DEBUG',true);

date_default_timezone_set('PRC');

//记录开始运行时间
$GLOBALS['_beginTime'] = microtime(true);

//set_error_handler("core/Error.php");
//set_exception_handler("core/Error.php");

include "core/Error.php";
//new \core\Error();
//引入composer
//include "vendor/autoload.php";
//var_dump($_POST);
//var_dump($_SERVER);
if(DEBUG){
    //选择报错使用的类*
//    $whoops = new \Whoops\Run();
//    $title = "ERROR";
//    $option = new \Whoops\Handler\PrettyPageHandler();
//    $option->setPageTitle($title);
//    $whoops->pushHandler($option);
//    $whoops->register();

    ini_set('display_errors','On');
}else{
    ini_set('display_errors','Off');
}

require CORE.'/common/function.php';

include CORE . '/FiiPHP.php';

//自动加载类
spl_autoload_register('\core\FiiPHP::load');

//启动框架核心
\core\FiiPHP::run();