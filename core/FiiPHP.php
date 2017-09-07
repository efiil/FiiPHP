<?php
/**
 * Created by PhpStorm.
 * User: Nicky
 * Date: 2017/7/16
 * Time: 23:09
*/

namespace Core;

use core\lib\Model;

class FiiPHP{
    public static $classMap = array();
    public $assign;

    public static function run(){
        //引入日志类
        \core\lib\Log::init();
        \core\lib\Log::log($_SERVER,'server');

        //引入路由类
        $route = new \core\lib\Route();
        $ctrlClass = $route->ctrl;
        $action = $route->action;
        $ctrlfile = APP.'/Controllers/'.$ctrlClass.'Controller.php';
        $ctrlClassPath = '\\'.MODULE.'\Controllers\\'.ucfirst($ctrlClass).'Controller';

        if(is_file($ctrlfile)){
            include $ctrlfile;
            // new \app\Controllers\IndexController()
            $ctrl = new $ctrlClassPath();
            $ctrl->$action();
            \core\lib\Log::log('ctrl:'.$ctrlClass.'    '.'action:'.$action);
        }else{
            throw new \Exception('找不到控制器'.$ctrlClass);
        }
    }

    //自动加载类函数
    public static function load($class){
        //自动加载类库
        if (isset(self::$classMap[$class])){
            return true;
        }else{
            $class = str_replace('\\','/',$class);
            $file = ROOT.'/'.$class.'.php';
            if(is_file($file)){
                include $file;
                self::$classMap[$class] = $class;
            }else{
                return false;
            }
        }

    }

    public function assign($name,$value){
        $this->assign[$name] = $value;
    }

    public function display($file){
        $file = APP.'/views/'.$file;

        if(is_file($file)){
            extract($this->assign);
            include $file;
        }else{
            p('找不到视图文件'.$file);
        }
    }
}