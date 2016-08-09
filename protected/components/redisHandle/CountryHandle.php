<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/3/28
 * Time: 20:53
 */
class CountryHandle
{
    public static $table_name = "joy_offer_country";
    private $db;
    public static $filter_prefix = "country_filter_";
    public static $filter_sort = "country_sort";
    public static $default_id = "unknown";
    public function __construct()
    {
        $this->db  = Yii::app()->db;
    }
    /**
     * here we set the offer id under the country
     *@return array abbr=>offerid
     */
    public function getFilterRelation()
    {
        $sql = "select abbr from " . self::$table_name;
        $command = $this->db->createCommand($sql);
        $result = $command->queryAll($command);
        foreach ($result as $value) {
            $sql_country = "select id from joy_offers where (find_in_set(geo_targeting,'{$value['abbr']}') and status=1) or (geo_targeting=0 and status=1) or (geo_targeting is NULL and status=1)";
            $command_country = $this->db->createCommand($sql_country);
            $result_county = $command_country->queryAll();
            foreach ($result_county as $subid) {
                if(isset($arr[$value['abbr']])){
                    array_push($arr[$value['abbr']],$subid['id']);
                }else{
                    $arr[$value['abbr']] = array($subid['id']);
                }
            }
        }
        return $arr;
    }

    #########here we set the network type,but now it's not work
    /*public function getFilterRelation(){
        $sql = "select abbr from " . self::$table_name;
        $command = $this->db->createCommand($sql);
        $result = $command->queryAll($command);
        $netType = array("wifi","not wifi","unknown");
        $arr = array();
        foreach($result as $value){
            $arr[$value['abbr']] = $netType;
        }
        return $arr;
    }*/

    public static function getCountryAcronym($country_name_cn){
        $db = Yii::app()->db;
        $sql = "select abbr from .".self::$table_name. " where cninfo='$country_name_cn'";
        $command = $db->createCommand($sql);
        $result = $command->queryRow();
        return $result['abbr'];
    }
}