<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/3/16
 * Time: 16:25
 */
class OfferFilter
{
    private $redis;
    public static $redis_prefix = 'offer_detail_';
    public static $member_name = 'aff_members_';
    public static $sql_stack_name = 'sql_stack_';
    public static $sub_field = 'sub_filed_';
    public static $incrId = 'incr_id';

    public static $member_prefix;
    public $parent_filter_prefix_name;
    public $parent_filter_sort_name;
    public $parentFilter;
    public function __construct()
    {
        try {
            if (empty($this->redis)) {
                $this->redis = Yii::app()->redis_cache;
//                $this->redis = new Redis();
                $this->checkConnect();
            }
        }catch(Exception $e){
            return null;
        }
    }

    public static function getRedisPrefix(){
        return self::$redis_prefix;
    }

    public static function getSubFieldName(){
        return self::$sub_field;
    }

    //set session from database
    public function setMemberAndDetail(){
        $result = 0;
        $db = Yii::app()->db;
        $select_all_aff_sql = "select id from joy_system_user WHERE status=1 and groupid = " . AFF_GROUP_ID;
        $all_aff_command = $db->createCommand($select_all_aff_sql);
        $all_aff = $all_aff_command->queryAll();
        if(empty($all_aff)){
            return 0;
        }
        $all_aff = array_column($all_aff,'id');
        $select_all_sql = "select id,offer_url,payout from joy_offers WHERE status = 1";
        $all_offers_command = $db->createCommand($select_all_sql);
        $all_offers = $all_offers_command->queryAll();
        if(!empty($all_offers)){
            foreach($all_offers as $offer){
                $offers_arr[$offer['id']] = OfferHandle::getJsonOffer($offer['id'],$offer['offer_url'],$offer['payout']);
            }
        }
        if(!empty($offers_arr)){
            foreach($all_aff as $aff){
                foreach($offers_arr as $item=>$key) {
                    $result = $this->redis->hmset(self::$redis_prefix . $aff, $item,$key);
                    $this->redis->set(self::$member_name, $aff);
                }
            }
        }
        $this->setDefaultMember();
        return $result;
    }

    public function getMemberDetail($member, $name)
    {
        $arr = $this->redis->hget(self::$redis_prefix . $name,$member);
        $offer = OfferHandle::getDefaultOffer();
        if(!empty($arr)){
            $offer = OfferHandle::getObjectOffer($arr);
        }
        return $offer;
    }

    public function setMember(){
        $this->redis->hMset($this->getRedisPrefix(),OfferHandle::getClassField('OfferHandle'));
    }

    ##########

    public function setDefaultMember(){
        $filed_arr = OfferHandle::getClassField('OfferHandle');
        $fields = '';
        if(!empty($filed_arr)){
            $fields = implode(',',$filed_arr);
        }
        $offer = OfferHandle::getOffer($fields,' and status = 1');
        return $this->setOneMember($offer);
    }


    //get offer from database  with  active status only
    public function setMembers(){
        $filed_arr = OfferHandle::getClassField('OfferHandle');
        $fields = '';
        $result = array();
        if(!empty($filed_arr)){
            $fields = implode(',',$filed_arr);
        }
        $all_offers = OfferHandle::getOffers($fields,' and status = 1');
        foreach($all_offers as $item) {
            array_push($result, self::setOneMember($item));
        }
        array($result,$this->setDefaultMember());
        array($result,$this->setIncrId());
        return array_unique($result);
    }

    public function flushAll(){
        $this->redis->flushall();
    }

    public function getField($offerid,$param_name = array()){
        $result = array('id');
        if(!empty($param_name)){
            foreach($param_name as $name){
                $result[$name] = $this->redis->hget($this->getRedisPrefix() . $offerid,$name);
            }
        }else{
            $result = $this->redis->hgetall($this->getRedisPrefix() . $offerid);
            //we can get a complete offer detail
        }
        return $result;
    }

    public function incrId(){
        $this->redis->incr(self::$incrId);
    }

    //set a array  to redis
    public  function setOneMember($array){
        if(empty($array)){
            return false;
        }
        if(!isset($array['id']) || empty($array['id'])){
            $array['id'] = $this->getIncrId();
            $this->incrId();
        }
        $keys = array_keys($array);
        foreach($keys as $key){
            $result[$key] = $this->redis->hset($this->getRedisPrefix() . $array['id'],$key,$array[$key]);
            if(empty($result[$key])){
                return false;
            }
        }
        return true;
    }

    public function getIncrId(){
        if($this->redis->exists(self::$incrId)){
            $incr = $this->redis->get(self::$incrId);
        }else{
            $incr = joy_offers::getIncrId();
            $this->setIncrId($incr);
        }
        return $incr;
    }

    public function setIncrId($id = null){
        if(empty($id)){
            $id = $this->getIncrId();
        }
        return $this->redis->set(self::$incrId,$id);
    }

    public function pushSql($sql){
        $this->redis->lpush(self::$sql_stack_name ,$sql);
    }

    public  function setSubField($campaign_id,$array){
        //check value if it exist
        $id = 0;
        if(!$this->redis->exists(self::$sub_field . $campaign_id)){
            $this->redis->lpush(self::$sub_field.$campaign_id,$id);
            $this->setOneMember($array);
        }
        return $id;
    }

    public function getSubField($campaign_id){
        $this->redis->get(self::$sub_field.$campaign_id);
    }

    public function getListSize()
    {
    }

    public function checkConnect(){
        if(empty($redis)){
            //xxxxx
        }
    }
}
