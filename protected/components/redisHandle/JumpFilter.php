<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/3/23
 * Time: 19:35
 */
class JumpFilter
{
    public static $redis_prefix;
    public static $member_prefix;
    public $parent_filter_prefix_name;
    public $parent_filter_sort_name;
    public $parentHandle;
    public $default_id;
    private $redis;
    public function __construct($selfHandle)
    {
        if(empty($this->redis)){
            $this->redis = Yii::app()->redis_cache;
//            $this->redis = new Redis();
        }
        $parent_class = new ReflectionClass($selfHandle);
        $staticProperties = $parent_class->getStaticProperties();
        $this->parent_filter_prefix_name = $staticProperties['filter_prefix'];
        $this->parent_filter_sort_name = $staticProperties['filter_sort'];
        $this->default_id = $staticProperties['default_id'];
        $this->parentHandle = $selfHandle;
    }

    public function setFilterHash($parent_id,$self_id,$sub_id){
        return $this->redis->hset($this->parent_filter_prefix_name.$parent_id,$self_id,$sub_id);
    }

    public function setFilterList($parent_id,$sub_id,$score){
        return $this->redis->zadd($this->parent_filter_prefix_name . $parent_id,$score,$sub_id);
    }

    public function setSort($score,$value){
        $this->redis->zadd($this->parent_filter_sort_name,$score,$value);
    }
    public function setFilterMember(){
        $result = array();
        $parent_class = new ReflectionClass($this->parentHandle);
        $parent_instance = $parent_class->newInstanceArgs();
        $parent_id_arr = $parent_instance->getFilterRelation();
        if(!empty($parent_id_arr)){
            $score = 0;
            foreach($parent_id_arr as $parent_id=>$sub){
                foreach($sub as $subid){
                    if($this->parentHandle == 'CountryHandle'){
                        array_push($result,$this->setFilterList($parent_id,$subid,$score));
                    }else{
                        //because the offer is the last filter,so here we can get it by offer to its sub
                        array_push($result,$this->setFilterHash($parent_id,$subid,$subid));
//                    $this->setSort($score,$subid);
                    }
                    $score++;
                }
            }
        }
        return array_unique($result);
    }

    public function hgetFilterSub($parent_id,$self_id){
        return $this->redis->hget($this->parent_filter_prefix_name . $parent_id,$self_id);
    }

    public function zgetFilterSub($parent_id,$min,$max){
        return $this->redis->zrevrange($this->parent_filter_prefix_name . $parent_id,$min,$max);
    }

    public function removeFilterOrSub($parent_id ,$self_id = null){
        if(empty($self_id)){
            $this->redis->hDel($this->parent_filter_prefix_name.$parent_id,$self_id);
        }else{
            $this->redis->del($parent_id);
        }
    }

    public function setConfigFilter(){
        $data = array();
        $config_handle = new ConfigHandle();
        $arr = $config_handle->getFilterRelation();
        if(!empty($arr)){
            foreach($arr as $item){
                foreach($item as $sub_item){
                    $name = $sub_item['id'] . ':' .$sub_item['country'];
                    unset($sub_item['id']);
                    unset($sub_item['country']);
                    foreach($sub_item as $key=>$value){
                         $data[$key] = $this->redis->hset($name,$key,$value);
                    }
                }
            }
        }
        return array_unique($data);
    }

    public function getConfigParams($affid,$country_en){
        $optimal = $this->redis->hgetall($affid . ':' . $country_en);
        return $optimal;
    }

}