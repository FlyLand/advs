<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/3/23
 * Time: 19:37
 */
class JumpHandle
{
    private $table_name = "joy_jump";
    private $db;
    public static $filter_prefix = "jump_filter_";
    public  static $filter_sort = "jump_sort";
    public static $default_id = DEFAULT_OFFER_ID;
    public function __construct()
    {
        $this->db  = Yii::app()->db;
    }
    public function getFilterRelation(){
        $sql = "select offerid,affid from $this->table_name";
        $command = $this->db->createCommand($sql);
        $result = $command->queryAll();
        if(!empty($result)){
            foreach($result as $key=>$value){
                $id_affid[$key] = explode(',',$value['affid']);
                $id_offerid[$key] = $value['offerid'];
            }
        }
        $arr = array();
        foreach($id_affid as $key=>$item){
            foreach($item as $affid) {
                if (isset($arr[$affid])) {
                    if(!in_array($id_offerid[$key],$arr[$affid])){
                        array_push($arr[$affid], $id_offerid[$key]);
                    }
                }else{
                    $arr[$affid] = array($id_offerid[$key]);
                }
            }
        }
        return $arr;
    }
}