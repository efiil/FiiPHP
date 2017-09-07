<?php
/**
 * Created by PhpStorm.
 * User: Nicky
 * Date: 2017/7/17
 * Time: 0:22
 */

namespace core;

/**
 * Class Error
 * @package core
 * 错误自动捕捉记录类
 */
class Error{
    public function __construct(){
        $this->isError();
    }

    public function isError(){
        set_exception_handler([$this,'exception']);
        set_error_handler([$this,'errorhandler']);
        register_shutdown_function([$this,'registerSF']);
    }

    public function exception($ex){
        $code = $ex->getCode();
        $message = $ex->getMessage();
        $file = $ex->getFile();
        $line = $ex->getLine();

        $this->errlog($code,$message,$file,$line);
    }

    public function errorhandler($errcode,$errmsg,$errfile,$errline){
        $this->errlog($errcode,$errmsg,$errfile,$errline);
    }

    public function registerSF(){
        $err = error_get_last();

        $code = $err['type'];
        $message = $err['message'];
        $file = $err['file'];
        $line = $err['line'];

        $canDo = array(1,4,16,32,64,128);

        if(in_array($err['type'],$canDo)){
            $this->errlog($code,$message,$file,$line);
        }
    }

    public function errlog($code,$message,$file,$line){
        $err_msg = date('Y-m-d H:i:s',time())."\r\n";
        $err_msg .= '错误级别: '.$code."\r\n";
        $err_msg .= '错误信息: '.$message."\r\n";
        $err_msg .= '错误文件: '.$file."\r\n";
        $err_msg .= '错误行号: '.$line."\r\n";
        $err_msg .= "\r\n";

        $path = ROOT."/storage/logs/";
        $logName = "errors.log";
        error_log($err_msg,3,$path.$logName);
    }
}