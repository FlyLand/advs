<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/3/15
 * Time: 19:56
 */
class TaskController extends Controller
{
    public function actionYoumi(){
        $url_original = "http://ad.api.yyapi.net/v1/offline?app_id=d1890fcfd5dc9f33";
        $result = array();
        try{
            $page =  1;
            $page_size = 100;
            do{
                joy_offers::setOfferPending(105);
                do {
                    $url = $url_original . "&page=$page&page_size=$page_size";
                    $url = Common::signUrl($url, '1ea214a178876ba4');
                    $data = Common::curlGet(array('url' => $url));
                    if (!empty($data)) {
                        $data_arr = json_decode($data, true);
                        foreach ($data_arr['offers'] as $offer) {
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
                            $offer = joy_offers::model()->findByAttributes(array('campaign_id' => $campaign_id, 'advertiser_id' => 105));
                            if (empty($offer)) {
                                $offer = new joy_offers();
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
                            } else {
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
                            }
                            $result = $offer->save();
                        }
                        $page++;
                    }
                }while(!empty($data_arr['offers']));
            }while(0);
            var_dump($result);
        }catch(Exception $e){
        var_dump($e->getMessage());die();
        }
    }

    public function actionBulk(){
        $url_original = "https://api.taptica.com/v2/bulk?token=Pnvm7rXp1TSvuglE1kPUnA%3d%3d&platforms=android&version=1&format=json";
        $result = array();
        try{
            $data = Common::curlGet(array('url'=>$url_original));
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
                    $offer = joy_offers::model()->findByAttributes(array('campaign_id'=>$campaign_id,'advertiser_id'=>105));
                    if(empty($offer)){
                        $offer = new joy_offers();
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
                    }else{
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
                    }
                    $result = $offer->save();
                }
            }
            var_dump($result);
        }catch(Exception $e){
            var_dump($e->getMessage());die();
        }
    }
}