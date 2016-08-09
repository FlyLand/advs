<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/3/16
 * Time: 16:25
 */
class OfferHandle
{
    var $redis;
    var $redis_prefix = 'affid_';
    var $member_name = 'aff_members_';
    public function __construct()
    {
        try {
            if (empty($this->redis)) {
                $this->redis = new Redis();
                $this->redis->connect('127.0.0.1', 6379);
                $this->checkConnect();
            }
        }catch(Exception $e){
            var_dump($e->getMessage());
        }
    }

    //first you should find offer with affid in jump
    public  function getOfferSession($offerid,$affid){
        $arr = $this->redis->smembers($this->redis_prefix . $affid);
        $url = $arr[$offerid];
        if(empty($url)){
            $url = $this->getDefaultOffer();
        }
        return $url;
    }

    //set session from database
    public function setOfferSession(){
        $result = 0;
        $db = Yii::app()->db;
        $select_all_aff_sql = "select id from joy_system_user WHERE status=1 and groupid = " . AFF_GROUP_ID;
        $all_aff_command = $db->createCommand($select_all_aff_sql);
        $all_aff = $all_aff_command->queryAll();
        if(empty($all_aff)){
            return 0;
        }
        $select_all_sql = "select id,offer_url from joy_offers WHERE status = 1";
        $all_offers_command = $db->createCommand($select_all_sql);
        $all_offers = $all_offers_command->queryAll();
        if(!empty($all_offers)){
            foreach($all_offers as $offer){
                $offers_arr[$offer['id']] = $offer['offer_url'];
            }
        }
        if(!empty($offers_arr)){
            foreach($all_aff as $aff){
                $result = $this->redis->lPush($this->redis_prefix . $aff,$offers_arr);
                $this->redis->rPushx($this->member_name,$aff);
            }
        }
        $this->setDefaultSession();
        return $result;
    }

    public function setDefaultSession(){
        $default_offer = $this->getDefaultOffer();
        $arr = array($default_offer['id']=>$default_offer['offer_url']);
        $this->redis->lPush($this->redis_prefix . DEFAULT_AFF_ID,$arr);
        $this->redis->lPushx($this->member_name . DEFAULT_AFF_ID,$this->redis_prefix);
    }

    public function getDefaultOffer(){
        return OfferRedis::model()->findByPk(DEFAULT_OFFER_ID);
    }

    public function deleteByMember($affid){
        if($this->redis->exists($this->redis_prefix . $affid)){
            $this->redis->delete($this->redis_prefix . $affid);
        }
    }

    public function deleteMembers(){
        $member_arr = $this->redis->sMembers($this->member_name);
        if(!empty($member_arr)){
            foreach($member_arr as $member){
                $this->deleteByMember($member);
            }
        }
    }

    public function deleteSpecifiedMember($affid,$offerid){
        if($offer = $this->redis->sMembers($affid)){
        }
    }

    public function setSpecifiedMember($affid,$offerid,$params){
        $offer = $this->getSpecifiedMember($affid,$offerid);
        if(!empty($offer)){
            foreach($params as $key=>$value){
                $offer[$key] = $value;
            }
        }
    }

    public function getSpecifiedMember($affid,$offerid){
        $offer = array();
        if($this->redis->exists($this->redis_prefix.$affid)){
            $offers = $this->redis->sMembers($this->redis_prefix.$affid);
            if(!empty($offers)){
                $offer = $offers[$offerid];
            }
        }
        return $offer;
    }

    public function unsetRedis(){
        $this->deleteMembers();
        $this->setOfferSession();
    }

    public function checkListSize(){
        return $this->redis->dbSize();
    }

    public function checkConnect(){
        if(empty($redis)){
            //xxxxx
        }
    }
}
