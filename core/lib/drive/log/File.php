<?php

/**
 * Created by PhpStorm.
 * User: Nicky
 * Date: 2017/7/17
 * Time: 13:26
 */

namespace core\lib\drive\log;

use core\lib\conf;

class File{
    public static $path;//日志存储位置

    public function __construct(){
        $path = conf::get('OPTION','log');
        self::$path = $path['PATH'];
    }

    public function log($message,$file='log'){
        /**
         * 1.确定文件存储位置是否存在
         *      新建目录
         * 2.写入日志
         */

        $filePath = self::$path.'/'.date('Ymd');
        if(!is_dir($filePath)){
            mkdir($filePath,0777,true);
        }

        $date = date('Y-m-d H:i:s',time())."\r\n";

        return file_put_contents($filePath.'/'.$file.'.php',$date.json_encode($message).PHP_EOL,FILE_APPEND);
    }
}