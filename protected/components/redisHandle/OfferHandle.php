<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/3/17
 * Time: 17:01
 */
class OfferHandle
{
    public $id;
    public $offer_url;
    public $campaign_id;
    public $name;
    public $revenue;
    public $payout;
    public $preview_url;
    public $geo_targeting;
    public $platform;
    public $createtime;
    public $traffic;
    public $type;
    public $advertiser_id;
    public $description;
    public $min_android_version;
    public $thumbnail;
    public $status;
    public $joy_createtime;
    public function __set($name, $value)
    {
        // TODO: Implement __set() method.
        $this->$name = $value;
    }

    public function __get($name)
    {
        // TODO: Implement __set() method.
        return $this->$name;
    }

    public static function getClassField($className){
        $class = new ReflectionClass($className);
        if(empty($class)){
            return null;
        }
        $params = $class->getProperties();
        $names = array();
        foreach($params as $key){
            array_push($names,$key->getName());
        }
        return $names;
    }

    public function getOfferArray($offer){
        $fields = $this->getClassField('OfferHandle');
        $arr = array();
        foreach($fields as $field){
            $arr[$field] = $offer->$field;
        }
        return $arr;
    }

    public static function getOffers($fields,$condition = ''){
        $db = Yii::app()->db;
        $select_all_sql = "select $fields from joy_offers WHERE 1=1  $condition";
        $all_offers_command = $db->createCommand($select_all_sql);
        return $all_offers_command->queryAll();
    }

    public static function getOffer($fields,$condition = ''){
        $db = Yii::app()->db;
        $select_all_sql = "select $fields from joy_offers WHERE 1=1  $condition";
        $all_offers_command = $db->createCommand($select_all_sql);
        return $all_offers_command->queryRow();
    }

    public static function getDefaultOffer(){
        return joy_offers::model()->findByPk(DEFAULT_OFFER_ID);
    }

    #####
    public static function getJsonOffer($id,$offer_url,$payout){
        return json_encode(array(
            $id,$offer_url,$payout
        ),true);
    }

    public function flushData(){
        $offer = joy_offers::model()->findByPk($this->id);
        if(empty($offer)){
            $offer = new joy_offers();
        }
    }

    public static function getObjectOffer($json_offer){
        $offer_arr = json_decode($json_offer);
        $offer = new OfferHandle();
        $offer->id = $offer_arr['id'];
        $offer->offer_url = $offer_arr['offer_url'];
        $offer->payout = $offer_arr['payout'];
        return $offer;
    }
}