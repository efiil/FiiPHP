<?php
/**
 * Created by PhpStorm.
 * User: Nicky
 * Date: 2017/7/17
 * Time: 11:28
 */

namespace core\lib;


use Core\FiiPHP;

class conf{
    public static $conf = array();

    public static function get($name,$file){
        /*
         * 1.判断配置文件是否存在
         * 2.判断配置是否存在
         * 3.缓存配置
         */
        if(isset(self::$conf[$file])){
            try{
                return self::$conf[$file][$name];
            }catch (\Exception $e){
                //可能出问题
                throw new \Exception('找不到配置名:'.$name);
            }
        }else{
            $path = ROOT.'/config/'.$file.'.php';
            if(is_file($path)){
                $conf = include $path;
                if(isset($conf[$name])){
                    self::$conf[$file] = $conf;
                    return $conf[$name];
                }else{
                    throw new \Exception($name.'配置不存在');
                }
            }else{
                throw new \Exception('找不到配置文件'.$path);
            }
        }
    }

    public static function all($file){
        if(isset(self::$conf[$file])){
            return self::$conf[$file];
        }else{
            $path = ROOT.'/config/'.$file.'.php';
            if(is_file($path)){
                $conf = include $path;
                self::$conf[$file] = $conf;
                return $conf;
            }else{
                throw new \Exception('找不到配置文件'.$file);
            }
        }
    }
}