<?php
/**
 * Created by PhpStorm.
 * User: Nicky
 * Date: 2017/7/16
 * Time: 23:32
 */

namespace Core\Lib;

use core\lib\conf;

class Route{
    public $ctrl;
    public $action;

    public function __construct(){
        /**
         * 1.隐藏index.php
         * 2.获取URL参数部分
         * 3.返回对应控制器的方法
         */
//        $uri = urldecode(
//            parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
//        );
//        p($_SERVER['REQUEST_URI']);

        $path = $_SERVER['REQUEST_URI'];
//        var_dump($_GET);
        if( $path != '/' && isset($path) ){
            $patharr = explode('/',trim($path,'/'));
            if(isset($patharr[0])){
                $this->ctrl = $patharr[0];
                unset($patharr[0]);
            }
            if(isset($patharr[1])){
                $paramPos = strpos($patharr[1],'?');

                if($paramPos){
                    $this->action = substr($patharr[1],0,$paramPos);
                }else{
                    $this->action = $patharr[1];
                }

                unset($patharr[1]);
            }else{
                $this->action = conf::get('ACTION','route');
            }

//            $count = count($patharr);
//            $i = 2;
//            while ($i<=$count){
//                if(isset($patharr[$i + 1])){
//                    $_GET[$patharr[$i]] = $patharr[$i+1];
//                }
//                $i = $i+2;
//            }
//            p($_GET);

//            p($_SERVER['PATH_INFO']);
//            p($_SERVER['QUERY_STRING']);
        }else{
            $this->ctrl = conf::get('CTRL','route');
            $this->action = conf::get('ACTION','route');
        }

//        if($_SERVER['REQUEST_METHOD'] == 'POST'){
//            $_POST = $_REQUEST;
//        }

//        p($_SERVER);
    }

    public function assign($data){

    }

    public function display($file){

    }
}