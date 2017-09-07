<?php
/**
 * Created by PhpStorm.
 * User: Nicky
 * Date: 2017/7/17
 * Time: 10:21
 */

namespace core\lib;

use core\lib\conf;
use core\lib\drive\database\Mysql;

class Model extends Mysql {
    protected $db;
    protected $table;
    protected $field = [];
    protected $primaryKey;
    protected $data = [];

    protected $option = [
        'field'=>'*',
        'where'=>'1',
        'where_val'=>[],
        'limit'=>''
    ];

    public function __construct(){
        //连接数据库
        if($this->db == null){
            $this->connectDB();
        }
        return $this->db;

        //自动识别表名称 表名默认与model名一致
        $this->getTable();
        $this->getInfo();
    }

    public function __set($name, $value){
        $this->data[$name] = $value;
    }

    public function connectDB(){
        $DBconf = conf::all('database');
        try{
            $this->db = new \PDO($DBconf['DSN'],$DBconf['USERNAME'],$DBconf['PASSWD'], $options=array() );
        }catch (\PDOException $e){
            var_dump($e->getMessage());
//            throw new \Exception($e->getMessage(),$e->getCode());
        }
    }

    public function getTable(){
        if(empty($this->table)){
            $arr = explode('\\',get_called_class());
            $this->table = strtolower( rtrim(end($arr),'Model') );
        }
    }

    public function getInfo(){
        $sql = 'desc '.$this->table;

        $tableInfo = $this->db->query($sql);
        if(!$tableInfo){
            throw new \Exception('数据表'.$this->table.'不存在',888);
        }

        $data = $tableInfo->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($data as $k=>$v){
            if($v['Key'] == 'PRI'){
                $this->primaryKey = $v['Field'];
                continue;   //$this->field 字段没有主键
            }
            $this->field[] = $v['Field'];
        }
    }


    //sql数据操作

    public function getRow($sql, $data, $row = 'row'){
        $source = $this->db->prepare($sql);
        is_array($data)? $data : [$data];
        if($source->execute($data)){
            if($row == "row"){
                $data = $source->fetch(\PDO::FETCH_ASSOC);
            }elseif($row == "rows"){
                $data = $source->fetchAll(\PDO::FETCH_ASSOC);
            }
        }else{
            $data = $this->db->errorInfo();
        }
        return $data;
    }

    public function remove($sql,$id){
        $source = $this->db->prepare($sql);
        if($source->execute([$id])){
            $data = $source->rowCount();
        }else{
            $data = $this->db->errorInfo();
        }
        return $data;
    }

    public function insert($sql,$data){
        $source = $this->db->prepare($sql);
        if($source->execute($data)){
            $data = $this->db->lastInsertId();
        }else{
            $data = $this->db->errorInfo();
        }
        return $data;
    }

    public function save($sql,$data=[]){
        $source = $this->db->prepare($sql);
        if($source->execute($data)){
            $count = $source->rowCount();
            return $count;
        }else{
            return $this->db->errorInfo();
        }
    }

    public function find($data){
        $sql  = "SELECT " .$this->option['field']. " FROM ".$this->table . " where ". $this->primaryKey."=?";
        return $this->getRow($sql,$data);
    }

    public function select($data=[]){
        if(empty($data)){
            $data = $this->option['where_val'];
        }

        //select * from table where xxx group by ?? having ??? order ??? limit ??? pagination ???

        $sql = "SELECT ".$this->option['field']. " FROM ".$this->table. " where ".$this->option['where'];
        return $this->getRow($sql,$data,'rows');
    }

    public function delete($id){
        $sql = "DELETE FROM ".$this->table. " where ".$this->primaryKey."=?";
        return $this->remove($sql,$id);
    }

    public function add($data = []){
        if(empty($data)){
            $data = $this->data;
            $this->data = [];
        }

        $sql = "INSERT INTO ".$this->table.' (';
        $sql .= implode(',', array_keys($data));
        $sql .= " ) VALUES ( ";
        $sql .= rtrim( str_repeat('?,', count($data)),',');
        $sql .= ")";

        $data = array_values($data);
        return $this->insert($sql,$data);
    }

    public function update($data=[]){
        if(empty($data)){
            $data = $this->data;
            $this->data = [];
        }

        if(!isset($data[$this->primaryKey])){
            throw new \Exception('缺少主键','800');
        }
        $data_tmp = $data;
        unset($data_tmp[$this->primaryKey]);
        $sql = "UPDATE ".$this->table. " SET ";
        foreach($data_tmp as $k=>$v){
            $sql .= $k.'=?,';
        };
        $sql = rtrim($sql,',');
        $sql .= " WHERE ".$this->primaryKey. "=?";
        $data_tmp[] = $data[$this->primaryKey];

        return $this->save($sql,array_values($data_tmp));
    }

//    public function get(){
//
//    }

    //链式操作

    public function field($field){
        if(!empty($field)){
            if(is_array($field)){
                $field = implode(',', array_values($field));
            }
            $this->option['field'] = $field;
        }

        return $this;
    }

    public function where($data){
        // where name=?,age=?
        if(!empty($data)){
            $data_o = '';
            foreach ($data as $k=>$v) {
                $data_o .= $k.'=? and ';
                $this->option['where_val'][] = $v;
            }
            $data_o = rtrim($data_o,'and ');
            $this->option['where'] = $data_o;
        }else{
            $this->option['where'] = 1;
        }

        return $this;
    }

    public function group($data){
        if(!empty($data)){

        }

        return $this;
    }

    public function having(){

        return $this;
    }

    public function order(){

        return $this;
    }

    public function limit($num){
        if(!empty($num)){
//            $this->option['']
        }

        return $this;
    }

    public function pagination($data){

        return $this;
    }

//    public function id(){
//
//        return $this;
//    }

//    protected static function arrToStr($data){
//        if(is_array($data)){
//            $data = implode(',',array_keys($data));
//        }
//        return $data;
//    }

}