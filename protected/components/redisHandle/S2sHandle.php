<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/3/16
 * Time: 18:19
 */
class S2sHandle extends RedisHandle
{
    private $redis;
    public static $redis_prefix = 's2s_';
    public static $member_name = 's2s_members_';


    public function __construct()
    {
        if(empty($this->redis)){
            $this->redis = Yii::app()->redis_cache;
        }
    }

    public function checkConnect(){
    }

    public function setMemberAndDetail()
    {
    }

    //push all offers which is already in redis
    public function pushAllOffers(){
        if(empty($this->redis->sMembers(self::$member_name))){
            $members = $this->redis->sMembers(self::$member_name);
            foreach($members as $member){
                $this->pushMemberByName($member);
            }
        }
    }

    public function flushData(){
        $members_name_arr = $this->redis->mget(self::$member_name);
        if(!empty($members_name_arr)){
            foreach($members_name_arr as $name){
                $this->redis->hGetAll($this->getRedisPrefix().$name);
            }
        }
    }

    //push an advertiser's offer to redis complete
    public function pushMemberByName($advid){
        $result = joy_offers::getOfferFlied('*',"advertiser_id=$advid");
        if(!empty($result)){
            foreach($result as $value){
                $arr[$value['campaign_id']] = $value;
                $this->redis->set( self::$redis_prefix.$advid,$arr);
                $this->redis->rPushx(self::$member_name,$advid);
            }
        }
    }
}