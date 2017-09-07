<?php
/**
 * Created by PhpStorm.
 * User: Nicky
 * Date: 2017/7/17
 * Time: 1:16
 */

namespace app\Controllers;

class IndexController extends Controller{
    public function index(){
        //test code
        $model = new \app\Model\cModel();
//        $sql = 'select * from table1';
//        $ret = $model->query($sql);
//        p($ret->fetchAll());
//        p($model->select(23));
//        p($model->delete(4));
//        p($model->add(['name'=>'framework','sort'=>5]));
//        $model->name = 'laravel';
//        $model->sort = '8';
//        echo $model->add();
//        $model->name = "Yii";
//        $model->sort = 6;
//        echo $model->update(['name'=>'Ci','sort'=>'7','id'=>7]);
//        $model->name = "Ci";
//        $model->sort = "7";
//        $model->id = "7";
//        echo $model->update();
//        var_dump($_REQUEST);
//        var_dump($_SERVER['REQUEST_METHOD']);
//        var_dump($_POST);
//        var_dump($model->field('id,name,sort')->where(['id'=>'8'])->select());
//        echo memory_get_usage().'bytes';
//        exit();

//        echo \core\lib\conf::get('CTRL','route');
//        echo \core\lib\conf::get('ACTION','route');
        $data = 'helloworld';
        $this->assign('data',$data);
        $this->display('index.html');
    }

    public function action(){
        p(__CLASS__.'\\'.__FUNCTION__);
    }
}