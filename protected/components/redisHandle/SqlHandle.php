<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/3/17
 * Time: 21:39
 */
class SqlHandle
{
    private $redis;
    public $db;
    public static $redis_prefix = 'appdown:sql_cache';
    public function __construct()
    {
        $this->db = Yii::app()->db;
//        $this->redis = new Redis();
        $this->redis = Yii::app()->redis_cache;
    }
    public function pushSql($sql){
        return $this->redis->lpush(self::$redis_prefix,$sql);
    }

    //some error about the rename and renamenx function in Yii redis,but others could do thsi
    public function rename_cache($timestamp){
//        return $this->redis->rename(self::$redis_prefix,self::$redis_prefix.$timestamp);
        if($this->redis->exists(self::$redis_prefix)){
            $command = $this->redis->createCommand('rename',array(self::$redis_prefix,self::$redis_prefix.$timestamp));
            $result = $this->redis->executeCommand($command);
            var_dump($result);
//            var_dump($this->redis->rename(self::$redis_prefix));
            die();
        }else{
            return false;
        }
    }

    public function getSql($timestamp){
        return $this->redis->rpop(self::$redis_prefix.$timestamp);
    }

    public  function createSql($sql = null,$table_name,$params,$type){
        $fields = '';
        $values = '';
        if($type == 'insert'){
            foreach($params as $param=>$value){
                $fields .= " $param,";
                $values .= "'$value',";
            }
            $fields = substr($fields,0,strlen($fields) - 1);
            $values = substr($values,0,strlen($values) - 1);
            $sql = "insert into $table_name($fields) VALUES($values)";
        }elseif('update' == $type){
            foreach($params as $param=>$value){
                $fields .= "$param='$value',";
            }
            $fields = substr($fields,0,strlen($fields));
            $sql = "update $table_name set $fields";
        }
        return $sql;
    }

    public function checkConnect(){
        if(empty($this->redis)){
            return null;
        }
    }
}