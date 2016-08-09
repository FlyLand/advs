<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/3/29
 * Time: 11:57
 */
class ApiFilter
{
    public static $redis_prefix = 'handle_name_list';
    public static $member_prefix;
    public $parent_filter_prefix_name;
    public $parent_filter_sort_name;
    public $parentHandle;
    private $redis;
    public function __construct()
    {
        if(empty($this->redis)){
            $this->redis = Yii::app()->redis_cache;
//            $this->redis = new Redis();
        }
    }
    //first we should get the filter
    public function findOfferWithRedis($affid,$type){
        $ipip = new ipip();
        $ip = $ipip->getIP();
        $country = $ipip->find($ip)[0];
        $last_offer_id = DEFAULT_OFFER_ID;
        $abbr = CountryHandle::getCountryAcronym($country);
        $filter_list = array('CountryHandle','JumpHandle');
        foreach($filter_list as $filter_name){
            $filter = new JumpFilter($filter_name);
            if($filter_name == 'JumpHandle'){
                $config_params = $filter->getConfigParams($affid,$abbr);
                if(isset($config_params['optimal_offer'])){
                    $last_offer_id = $config_params['optimal_offer'];
                    break;
                }
                if(isset($config_params['optimal_connect'])){
                    $type = $config_params['optimal_connect'];
                }
            }
            if($filter_name == 'CountryHandle'){
                $offerid = $filter->zgetFilterSub($abbr,0,0);
                if(!empty($offerid)){
                    $last_offer_id = $offerid[0];
                }
            }
        }
        $offerHandle = new OfferFilter();
        $result = $offerHandle->getField($last_offer_id);
        return $result;
    }

    public function setAllFilter(){
        $offerFilter = new OfferFilter();
//        $countryFilter = new CountryHandle();
//        $countryFilter = new JumpFilter('CountryHandle');
        $result['OfferHandle'] = $offerFilter->setMembers();
//        $result['CountryHandle'] = $countryFilter->setFilterMember();
//        $result['ConfigHandle'] = $countryFilter->setConfigFilter();
        return $result;
    }
}