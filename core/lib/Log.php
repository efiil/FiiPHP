<?php
/**
 * Created by PhpStorm.
 * User: Nicky
 * Date: 2017/7/17
 * Time: 13:16
 */

namespace core\lib;

use core\lib\conf;

class Log{
    static $class;
    /**
     * 1.确定日志存储方式
     *
     *
     * 2.写日志
     */

    public static function init(){
        //确定log存储方式 file mysql等
        $drive = conf::get('DRIVER','log');

        $class = '\core\lib\drive\log\\'.$drive;
        self::$class = new $class;
    }

    public static function log($name,$file='log'){
        self::$class->log($name,$file);
    }
}