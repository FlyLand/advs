<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/4/6
 * Time: 11:27
 */
class ConfigHandle
{
    public static $table_name;
    private $db;
    public static $filter_prefix = "";
    public static $filter_sort = "";
    public static $default_id = "";

    public static $table_name_country = 'joy_offer_country';
    public static $table_name_user = 'joy_system_user';
    public static $table_name_config = 'joy_affiliate_config';
    public function __construct()
    {
        $this->db = Yii::app()->db;
    }
    public function getConfigByAffiliateId($id){
        $sql = "select * from " . self::$table_name_config . " where id=$id";
        $command = $this->db->createCommand($sql);
        $result = $command->queryAll();
        return $result;
    }
    public function getAllCountry(){
        $sql = "select abbr from " . self::$table_name_country;
        $command = $this->db->createCommand($sql);
        $result = $command->queryAll($command);
        if(!empty($result)){
            $result = array_column($result,'abbr');
        }
        return $result;
    }
    public function getAllAff(){
        $result=  JoySystemUser::getResults('id','groupid=' . AFF_GROUP_ID);
        if(!empty($result)){
            $result = array_column($result,'id');
        }
        return $result;
    }
    public function getAllConfig(){
        return JoyAffiliateConfig::model()->findAll();
    }

    public function getFilterRelation(){
        $affid_arr = $this->getAllAff();
        $arr = array();
        if(!empty($affid_arr)){
            foreach($affid_arr as $affid){
                if($config = $this->getConfigByAffiliateId($affid)){
                    array_push($arr,$config);
                }
            }
        }
        return $arr;
    }
}