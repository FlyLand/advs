<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/3/15
 * Time: 19:56
 */
class S2sController extends Controller
{
    private $logpre		=	'';		//��־�ļ�ǰ׺����
    public function init(){
        ini_set('memory_limit','512M');
        $this->logpre	=	'';
    }
    public function actionYoumi(){
        $url_original = "http://ad.api.yyapi.net/v1/offline?app_id=d1890fcfd5dc9f33";
        $result = array();
        try{
            $page =  10;
            $page_size = 100;
            do{
                $url = $url_original."&page=$page&page_size=$page_size";
                $url = Common::signUrl($url,'1ea214a178876ba4');
                $data = Common::curlGet(array('url'=>$url));
                if(!empty($data)){
                    $data_arr = json_decode($data,true);
//                    joy_offers::setOfferPending(105);
                    foreach($data_arr['offers'] as $offer){
                        $campaign_id = $offer['id'];
                        $name = $offer['name'];
                        $revenue = $offer['payout'];
                        $payout = $revenue * 0.8;
                        $preview_url = $offer['preview_url'];
                        $countries_arr = $offer['countries'];
                        $countries = implode($countries_arr);
                        $store_label = $offer['store_rating'];
                        $os = $offer['os'];
                        $task = $offer['task'];
                        $traffic = $offer['traffic'];
                        $os_version = $offer['os_version'];
                        $carrier = $offer['carrier'];
                        $nettype = $offer['nettype'];
                        $creatives = $offer['creatives'];
                        $size = $offer['size'];
                        $device = $offer['device'];
                        $mandatory_device = $offer['mandatory_device'];
                        $icon_url = $offer['icon_url'];
                        $adtxt = $offer['adtxt'];
                        $package = $offer['package'];
                        $category = $offer['category'];
                        $trackinglink = $offer['trackinglink'];
                        $offer = new OfferHandle();
                        $offer->name = $name;
                        $offer->campaign_id = $campaign_id;
                        $offer->createtime = date("Y-m-d H:i:s", time());
                        $offer->campaign_id = $campaign_id;
                        $offer->geo_targeting = $countries;
                        $offer->traffic = 'http';
                        $offer->name = $name;
                        $offer->type = '';
                        $offer->revenue = $revenue;
                        $offer->advertiser_id = 105;
                        $offer->preview_url = $preview_url;
                        $offer->offer_url = $trackinglink;
                        $offer->description = $adtxt;
                        $offer->platform = $os;
                        $offer->min_android_version = $os_version;
                        $offer->thumbnail = $icon_url;
                        $offer->payout = $payout;
                        $offer->status = 1;
                        $offer->joy_createtime = date('Y-m-d H:i:s');
                        $handle = new OfferFilter();
                        $arr = $offer->getOfferArray($offer);
                        $result[$campaign_id] = $handle->setOneMember($arr);
                    }
                    $page ++;
                }
            }while(!empty($data));
            var_dump($result);
        }catch(Exception $e){
            var_dump($e->getMessage());die();
        }
    }

    public function actionAppnext(){
        $ret_array = array('ret' => 1, 'msg' => '','name'=>'appnext','occur' => 'Log_OfferCron_appnext.txt', 'error' => '', 'data' => array());
        $init_url = "https://admin.appnext.com/offerWallApi.aspx?id=80365ee9-0aa2-4cca-bd8f-495e4db1b289&cnt=200&ip=";
        $array_ip = array(
            '117.96.18.31',
            '114.4.21.203',
            '203.87.144.20',
            '203.87.144.20',
            '103.21.150.198',
            '183.171.177.253',
            '125.24.49.66',
            '5.141.208.216',
            '187.121.180.198',
            '187.240.55.214',
            '72.213.6.16',
        );
        $advertiser_id = 606;
        try {
            $offer_num = joy_offers::model()->count("advertiser_id=$advertiser_id");
            if ($offer_num != 0) {
                $connection = Yii::app()->db;
                $command = $connection->createCommand();
                $rs = $command->update('joy_offers', array('status' => 0), "advertiser_id=$advertiser_id");
                if ($rs == 0) {
                    $ret_array['ret'] = 1;
                    $ret_array['msg'] = 'appnext update status error!';
                    $offer = joy_offers::model()->findByAttributes(array('advertiser_id' => $advertiser_id));
                    $offer->status = 1;
                    $offer->update();
                }
            }
            $i = 0;
            $count = count($array_ip);
            while($i < $count){
                $url = $init_url.$array_ip[$i];
                $data = Common::curlGet(array('url' => $url));
                $data_arr = json_decode($data, true);
                if (!empty($data_arr)) {
                    if (empty($data_arr['apps'])) {
                        echo "No data!";
                        continue;
                    }
                    foreach ($data_arr['apps'] as $offer) {
                        $campaign_id = $offer['campaignId'];
                        $name = $offer['title'];
                        $revenue = $offer['revenueRate'];
                        $payout = $revenue * 0.8;
                        $preview_url = $offer['urlApp'];
                        $countries = $offer['country'];
                        $os_version = $offer['storeRating'];
                        $icon_url = $offer['urlImg'];
                        $trackinglink = $offer['urlApp'];
                        $desc = $offer['desc'];
                        $offer = joy_offers::model()->findByAttributes(array('advertiser_id' => $advertiser_id, 'campaign_id' => $campaign_id));
                        if (empty($offer)) {
                            $offer = new joy_offers();
                        }
                        $offer->name = $name;
                        $offer->campaign_id = $campaign_id;
                        $offer->createtime = date("Y-m-d H:i:s", time());
                        $offer->campaign_id = $campaign_id;
                        $offer->geo_targeting = $countries;
                        $offer->traffic = 'http';
                        $offer->name = $name;
                        $offer->type = '';
                        $offer->revenue = $revenue;
                        $offer->advertiser_id = $advertiser_id;
                        $offer->preview_url = $preview_url;
                        $offer->offer_url = $trackinglink;
                        $offer->description = $desc;
                        $offer->min_android_version = $os_version;
                        $offer->thumbnail = $icon_url;
                        $offer->payout = $payout;
                        $offer->status = 1;
                        $offer->joy_createtime = date('Y-m-d H:i:s');
                        if(!$offer->save()){
                            echo 'error';
                        }
                    }
                }
                $i += 1;
            }
        }catch (Exception $e){
            $ret_array['ret'] = 0;
            $ret_array['msg'] = $e->getMessage();
            var_dump($ret_array);
            if (0 != $ret_array['ret']) {
                Common::toTxt(array('file' => $this->logpre . 'Log_OfferCron_appnext.txt', 'txt' => 'Output:' . var_export($ret_array['msg'], true)));
            }
        }
        var_dump($ret_array);
        if (0 != $ret_array['ret']) {
            Common::toTxt(array('file' => $this->logpre . 'Log_OfferCron_appnext.txt', 'txt' => 'Output:' . var_export($ret_array['msg'], true)));
        }
        return $ret_array;
    }

    public function actionSetOfferRedis(){
        $handle = new OfferHandle();
        $result = $handle->setOfferSession();
        var_dump($result);
        var_dump($handle->checkListSize());die();
        if(!$result){
            ####we can do something while it's not work
        }
    }
}