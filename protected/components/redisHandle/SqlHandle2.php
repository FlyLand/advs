<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/5/20
 * Time: 16:17
 */
class SqlHandle2
{
    public $redis;
    public $cache_name;
    public $db_name;
    public function __construct($redis,$cache_name,$db_name)
    {
        $this->redis = $redis;
        $this->cache_name = $cache_name;
        $this->db_name = $db_name;
    }
    public function save_to_redis_one($sql){
        return $this->redis->lpush($this->db_name . ':' . $this->cache_name, $sql);
    }

    public function save_to_redis($sql){
        //放入五个redis队列中
        $rand = rand(0, 100);
        if ($rand <= 20) {
            $redis_prefix = $this->db_name . ':' . $this->cache_name . "_01";
        } elseif ($rand <= 40) {
            $redis_prefix = $this->db_name . ':' . $this->cache_name . "_02";
        } elseif ($rand <= 60) {
            $redis_prefix = $this->db_name . ':' . $this->cache_name . "_03";
        } elseif ($rand <= 80) {
            $redis_prefix = $this->db_name . ':' . $this->cache_name . "_04";
        } else {
            $redis_prefix = $this->db_name . ':' . $this->cache_name . "_05";
        }
        return $this->redis->lpush($redis_prefix, $sql);
    }

    public static function createSql($sql = null,$table_name,$params,$type){
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
}
