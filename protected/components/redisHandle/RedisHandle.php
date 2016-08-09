<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/5/18
 * Time: 19:07
 */
class RedisHandle
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

}