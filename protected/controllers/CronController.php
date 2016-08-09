7<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/8/5
 * Time: 14:20
 */
class CronController extends Controller
{
    private $logpre		=	'';		//��־�ļ�ǰ׺����

    /**
     */
    public function init(){
        ini_set('memory_limit','512M');
        $this->logpre	=	'';
    }

    /**
     */
    public function mobilecore(){
        $ret_array	=	array('ret'=>1, 'msg'=>'', 'occur'=>'CronController_mobilecore', 'error'=>'','name'=>'mobilecore', 'data'=>array());
        $tpl		=	'mobilecore';
        $upper_info	=	require BASE_DIR.'/protected/config/advertiseradconf.php';
            do {
                try {
                    if (!$data_info = isset($upper_info[$tpl])) {
                        $ret_array['ret'] = 1;
                        $ret_array['msg'] = 'mobilecore no config!';
                        break;
                    }
                    $url = $upper_info[$tpl]['url'];
                    $advertiser_id = $upper_info[$tpl]['advertiser_id'];
                    $data_re = Common::curlGet(array('url' => $url));
                    if (!$data_re) {
                        $ret_array['ret'] = 1;
                        $ret_array['msg'] = 'mobilecore get data failed!';
                        break;
                    }
                    $data_arr = CfgAR::deJson($data_re);
                    if ($data_arr['error'] !== 'false') {
                        $offer_num = joy_offers::model()->count("advertiser_id=$advertiser_id");
                        if ($offer_num != 0) {
                            $connection = Yii::app()->db;
                            $command = $connection->createCommand();
                            $rs = $command->update('joy_offers', array('status' => 0), "advertiser_id=$advertiser_id and create_self=0");
                            if ($rs == 0) {
                                $ret_array['ret'] = 1;
                                $ret_array['msg'] = 'mobilecore update status error!';
                                $offer = joy_offers::model()->findByAttributes(array('advertiser_id' => $advertiser_id));
                                $offer->status = 1;
                                $offer->update();
                                break;
                            }
                        }
                        $types = JoyOffersType::model()->findAll();
                        $rows = array();
                        if ($types) {
                            foreach ($types as $key => $val) {
                                $row_key = explode(',', $val['key_words']);
                                $rows[$val['id']] = $row_key;
                            }
                        }
                        $str_id = '';
                        foreach ($data_arr['ads'] as $arr) {
                            $offer_id = $arr['offer_id'];
                            $campaign_id = $arr['campaign_id'];
                            $title = $arr['title'];
                            $platform = $arr['platform'];
                            $description = $arr['description'];
                            $revenue = $arr['bid'];
                            $payout = $revenue * 0.75;
                            $version = $arr['minOSVersion'];
                            if (!empty($arr['creatives'])) {
                                $creatives = $arr['creatives'][0]['url'];
                            } else {
                                $creatives = '';
                            }
                            $targeting = '';
                            $traffic = '';
                            if (!empty($arr['geoTargeting'])) {
                                $targeting = implode(",", $arr['geoTargeting']);
                            }
                            $size = $arr['packageSize'];
                            $downloads = $arr['downloads'];
                            $category = $arr['category'];
                            $clickURL = $arr['clickURL'] . "&p1=campaign_id&v1=$campaign_id&p2=offerid&v2=$offer_id&p3=aff_sub&v3={transaction_id}";
                            $offer = joy_offers::model()->findByAttributes(array('campaign_id' => $offer_id, 'advertiser_id' => $advertiser_id));
                            $str = ''; //strΪ���� ȥ���ظ�offer����
                            $str_arr = array();
                            //�õ����µ�offer_id
                            if (!empty($rows)) {
                                foreach ($rows as $key => $val) {
                                    $flag = false;
                                    foreach ($val as $val2) {
                                        if ($val2 && $title) {
                                            if (stristr($title, $val2)) {
                                                if (!in_array($key, $str_arr)) {
                                                    array_push($str_arr, $key);
                                                    $flag = true;
                                                    continue;
                                                }
                                            }
                                        }
                                    }
                                    if ($flag) continue;
                                }
                                if (!empty($str_arr)) {
                                    $str_arr = array_unique($str_arr);
                                    $str = implode(',', $str_arr);
                                }
                            }
                            if (!$offer) {
                                $offer = new joy_offers();
                                $offer->status = 1;
                                $offer->createtime = date("Y-m-d H:i:s", time());
                                $offer->revenue = $revenue;
                                $offer->payout = $payout;
                                $offer->name = $title;
                                $offer->description = $description;
                                $offer->offer_url = $clickURL;
                                $offer->advertiser_id = $advertiser_id;
                                $offer->thumbnail = $creatives;
                                $offer->campaign_id = $offer_id;
                                $offer->geo_targeting = $targeting;
                                $offer->type = $str;
                                $offer->min_android_version = $version;
                                $offer->platform = $platform;
                                $offer->traffic = $traffic;
                                $offer->joy_createtime = date('Y-m-d H:i:s');
                                if (!$offer->save()) {
                                    $ret_array['ret'] = 1;
                                    $ret_array['msg'] = 'mobilecore save failed:';
                                    break;
                                }
                            } else {
                                $system_id = $offer->id; //ȡ��offer id��
                                $str_id .= $str_id == '' ? $offer_id : ',' . $offer_id; //��ѯ�Ƿ��ڸ��±���
                                //������¼�¼��
                                //�Ȳ�ѯlogs�����Ƿ������offer�ļ�¼
                                $logs = JoyUpdateLogs::model()->findByAttributes(array('offer_id' => $system_id, 'time' => date('Y-m-d')));
                                if (empty($logs)) {
                                    $logs = new JoyUpdateLogs();
                                }
                                //ע���ж�offer�Ƿ��и���
//                                $old_payout = $offer->payout;
//                                $old_revenue = $offer->revenue;
//                                $old_name = $offer->name;
//                                $old_offer_url = $offer->offer_url;
//                                $old_type = $offer->type;
//                                $old_caps = $offer->caps;
//                                $old_platform = $offer->platform;
//                                $old_geo = $offer->geo_targeting;
//                                $old_description = $offer->description;
//                                $change = false;
//                                if ($old_payout != $revenue) {
//                                    $logs->payout = $old_payout;
//                                    $logs->revenue = $old_revenue;
//                                    $change = true;
//                                }
//                                $logs->name = $old_name;
//                                if ($old_platform != $platform) {
//                                    $logs->platform = $old_platform;
//                                    $change = true;
//                                }
//                                if ($old_type != $str) {
//                                    $logs->type = $str;
//                                    $change = true;
//                                }
//                                if ($old_offer_url != $clickURL) {
//                                    $logs->offer_url = $old_offer_url;
//                                    $change = true;
//                                }
//                                //��̨û�и��µ����и��µ�����
//                                if ($old_geo != $targeting) {
//                                    $logs->geo_targeting = $targeting;
//                                    $change = true;
//                                }
//                                if ($old_description != $description && !empty($description)) {
//                                    $logs->description = $description;
//                                    $change = true;
//                                }
                                $offer->revenue = $revenue;
                                $offer->payout = $payout;
                                $offer->name = $title;
                                $offer->offer_url = $clickURL;
                                $offer->campaign_id = $offer_id;
                                $offer->status = 1;
                                $offer->advertiser_id = $advertiser_id;
                                $offer->type = $str;
                                if (!$offer->update()) {
                                    $ret_array['ret'] = 1;
                                    $ret_array['msg'] = 'mobilecore update failed:';
                                    break;
                                }
                                //�и����������¼�¼��
//                                if ($change) {
//                                    $logs->offer_id = $system_id;
//                                    $logs->time = date('Y-m-d');
//                                    if (!$logs->save()) {
//                                        $ret_array['ret'] = 1;
//                                        $ret_array['msg'] = 'mobilecore update_logs insert failed:';
//                                        break;
//                                    }
//                                }
                            }
                        }
                        //���offer�Ƿ񱻹رգ��ر���д��pending����
                        if ($str_id != '') {
                            $offers_pending = joy_offers::model()->findAll("t.id not in ($str_id) and advertiser_id = $advertiser_id");
                            foreach ($offers_pending as $offer_pending) {
                                $pending_id = $offer_pending['id'];
                                $pending = JoyOfferPending::model()->findByAttributes(array('offer_id' => $pending_id, 'time' => date('Y-m-d')));
                                if (empty($pending)) {
                                    $pending = new JoyOfferPending();
                                }
                                $pending->time = date('Y-m-d');
                                $pending->offer_id = $pending_id;
                                if (!$pending->save()) {
                                    $ret_array['error'] = 'mobilecore pending save error!';
                                }
                            }
                        }
                        $ret_array['ret'] = 0;
                        $ret_array['msg'] = 'mobilecore no problem';
                    } else {
                        $ret_array['ret'] = 1;
                        $ret_array['msg'] = 'mobilecore updata error:' . $data_arr['error_message'];
                        break;
                    }
                }catch (Exception $e){
                    $ret_array['ret'] = 0;
                    $ret_array['msg'] = $e->getMessage();
                    break;
                }
            } while (0);
        if (0 != $ret_array['ret']) {
            //д����־
            Common::toTxt(array('file' => $this->logpre . 'Log_OfferCron_mobilecore.txt', 'txt' => 'Output:' . var_export($ret_array, true)));
        }
        return $ret_array;
    }

    public function yeahmobi(){
        $ret_array	=	array('ret'=>1, 'msg'=>'', 'occur'=>'CronController_mobilecore', 'error'=>'','name'=>'yeahmobi', 'data'=>array());
        $tpl		=	'yeahmobi';
        do {
            try {
                $upper_info = require BASE_DIR . '/protected/config/advertiseradconf.php';
                if (!$data_info = isset($upper_info[$tpl])) {
                    throw new ErrorException('no advertiser');
                }
                $advertiser_id = $upper_info[$tpl]['advertiser_id'];
                $api_id = $upper_info[$tpl]['api_id'];
                $psw = $upper_info[$tpl]['psw'];
                $api_token = md5($psw);
                $page = 1;
                $limit = '';
                //���±��е�ǰ����������offer��״̬Ϊ0
                $offer_num = joy_offers::model()->count("advertiser_id=$advertiser_id");
                if ($offer_num != 0) {
                    $connection = Yii::app()->db;
                    $command = $connection->createCommand();
                    $rs = $command->update('joy_offers', array('status' => 0), "advertiser_id=$advertiser_id and create_self=0");
                    if ($rs == 0) {
                        $ret_array['ret'] = 1;
                        $ret_array['msg'] = 'yeahmobi update status error!';
                        $offer = joy_offers::model()->findByAttributes(array('advertiser_id' => $advertiser_id));
                        $offer->status = 1;
                        $offer->update();
                        break;
                    }
                }
                //�õ����ݿ�������offer�����Լ��ؼ���
                $types = JoyOffersType::model()->findAll();
                $rows = array();
                if ($types) {
                    foreach ($types as $key => $val) {
                        $row_key = explode(',', $val['key_words']);
                        $rows[$val['id']] = $row_key;
                    }
                }
                do {
                    $url = "http://sync.yeahmobi.com/sync/offer/get?api_id=$api_id&api_token=$api_token&limit=$limit&page=$page";
                    $data = Common::curlGet(array('url' => $url));
                    $data_arr = json_decode($data, true);
                    $page_count = 0;
                    //�õ����µ�offer_id
                    $str_id = '';
                    if ($data_arr['flag'] === 'success') {
                        $page_count = $data_arr['data']['totalpage'];
                        foreach ($data_arr['data']['data'] as $val => $key) {
                            $name = $key['name'];
                            $preview_url = $key['preview_url'];
                            $offer_description = $key['offer_description'];
                            $revenue = $key['payout'];
                            $payout = $revenue * 0.75;
                            $category_arr = $key['category'];
                            $category = '';
                            $daily_cap = $key['remaining_daily_cap'];
                            $cap = 0;
                            if ($daily_cap != -1) {
                                $cap = 1;
                            }
                            foreach ($category_arr as $cate_key) {
                                $category .= $cate_key . ',';
                            }
                            $categories = substr($category, 0, strlen($category) - 1);
                            $traffics = '';
                            if (isset($key['traffic'])) {
                                $traffic_arr = $key['traffic'];    //��������
                                if (!empty($traffic_arr)) {
                                    foreach ($traffic_arr as $traffic) {
                                        $traffics = $traffics == '' ? $traffic : ',' . $traffic;
                                    }
                                }
                            }
                            $platform_arr = $key['platform'];    //����ƽ̨
                            $platforms = '';
                            if (!empty($platform_arr)) {
                                foreach ($platform_arr as $platform) {
                                    if ($platform != 'Firefox') {
                                        $platforms = $platforms == '' || empty($platform) ? $platform : ',' . $platform;
                                    }
                                }
                            }
                            $country = '';
                            $country_arr = $key['countries'];
                            foreach ($country_arr as $country_key) {
                                $country .= $country_key . ',';
                            }
                            $countries = substr($country, 0, strlen($country) - 1);
                            $tracklink = $key['tracklink'] . '&aff_sub={transaction_id}';
                            $campaign_id = '';
                            if (strpos($tracklink, 'offer_id')) {
                                $first = strpos($tracklink, 'offer_id') + 9;
                                $campaign_id = substr($tracklink, $first, strpos($tracklink, '&') - $first);
                            }
                            $offer = joy_offers::model()->findByAttributes(array('campaign_id' => $campaign_id, 'advertiser_id' => $advertiser_id));
                            $str = '';
                            if (!empty($rows)) {
                                foreach ($rows as $key => $val) {
                                    $flag = false;
                                    foreach ($val as $val2) {
                                        if ($val2 && $name) {
                                            if (stristr($name, $val2)) {
                                                $str .= $key . ',';
                                                $flag = true;
                                                continue;
                                            }
                                        }
                                    }
                                    if ($flag) continue;
                                }
                                $str = substr($str, 0, strlen($str) - 1);
                            }
                            if (empty($offer)) {
                                $offer = new joy_offers();
                                $offer->status = 1;
                                $offer->createtime = date("Y-m-d H:i:s", time());
                                $offer->revenue = $revenue;
                                $offer->payout = $payout;
                                $offer->name = $name;
                                $offer->description = $offer_description;
                                $offer->offer_url = $tracklink;
                                $offer->preview_url = $preview_url;
                                $offer->advertiser_id = $advertiser_id;
                                $offer->thumbnail = '';
                                $offer->campaign_id = $campaign_id;
                                $offer->geo_targeting = $countries;
                                $offer->type = $str;
                                $offer->caps = $cap;
                                $offer->platform = $platforms;
                                $offer->traffic = $traffics;
                                $offer->joy_createtime = date('Y-m-d H:i:s');
                                if (!$offer->save()) {
                                    $ret_array['ret'] = 1;
                                    $ret_array['msg'] = 'yeahmobi insert error:';
                                    return $ret_array;
                                }
                                $offer_id = $offer->attributes['id'];
                            } else {
                                $offer_id = $offer->id;
                                $str_id .= $str_id == '' ? $offer_id : ',' . $offer_id;
                                //������¼�¼��
                                //�Ȳ�ѯlogs�����Ƿ������offer�ļ�¼
//                                $logs = JoyUpdateLogs::model()->findByAttributes(array('offer_id' => $offer_id, 'time' => date('Y-m-d')));
//                                if (empty($logs)) {
//                                    $logs = new JoyUpdateLogs();
//                                }
//                                $old_payout = $offer->payout;
//                                $old_revenue = $offer->revenue;
//                                $old_name = $offer->name;
//                                $old_offer_url = $offer->offer_url;
//                                $old_type = $offer->type;
//                                $old_caps = $offer->caps;
//                                $old_platform = $offer->platform;
//                                $old_geo = $offer->geo_targeting;
//                                $old_description = $offer->description;
//                                $change = false;
//                                if ($old_payout != $payout) {
//                                    $logs->payout = $old_payout;
//                                    $logs->revenue = $old_revenue;
//                                    $change = false;
//                                }
//                                $logs->name = $old_name;
//                                if ($old_platform != $platforms) {
//                                    $logs->platform = $old_platform;
//                                    $change = false;
//                                }
//                                if ($old_type != $str) {
//                                    $logs->type = $str;
//                                    $change = false;
//                                }
//                                if ($old_offer_url != $tracklink) {
//                                    $logs->offer_url = $old_offer_url;
//                                    $change = false;
//                                }
//                                //��̨û�и��µ����и��µ�����
//                                if ($old_geo != $countries) {
//                                    $logs->geo_targeting = $countries;
//                                    $change = false;
//                                }
//                                if ($old_description != $offer_description && !empty($offer_description)) {
//                                    $logs->description = $offer_description;
//                                    $change = false;
//                                }
                                $offer->status = 1;
                                $offer->revenue = $revenue;
                                $offer->payout = $payout;
                                $offer->name = $name;
                                $offer->offer_url = $tracklink;
                                $offer->preview_url = $preview_url;
                                $offer->advertiser_id = $advertiser_id;
                                $offer->campaign_id = $campaign_id;
                                $offer->caps = $cap;
                                $offer->platform = $platforms;
                                if (!$offer->update()) {
                                    $ret_array['ret'] = 1;
                                    $ret_array['msg'] = 'yeahmobi update error:';
                                    break;
                                }
//                                if ($change) {
//                                    $logs->offer_id = $offer_id;
//                                    $logs->time = date('Y-m-d');
//                                    if (!$logs->save()) {
//                                        $ret_array['ret'] = 1;
//                                        $ret_array['msg'] = 'yeahmobi update_logs insert failed:';
//                                        break;
//                                    }
//                                }
                            }
                            $offer_cap = JoyOffersCaps::model()->findByAttributes(array('offer_id' => $offer_id));
                            if ($offer_cap) {
                                $offer_cap->daily_con = $daily_cap;
                                if (!$offer_cap->update()) {
                                    $ret_array['ret'] = 0;
                                    $ret_array['msg'] = 'yeahmobi cap update error';
                                    break;
                                }
                            } else {
                                $offer_cap = new JoyOffersCaps();
                                $offer_cap->daily_con = $daily_cap;
                                $offer_cap->offer_id = $offer_id;
                                if (!$offer_cap->save()) {
                                    $ret_array['ret'] = 0;
                                    $ret_array['msg'] = 'yeahmobi cap insert error';
                                    break;
                                }
                            }
                        }
                        $ret_array['ret'] = 0;
                        $ret_array['msg'] = 'yeahmobi is no problem!';
                    } else {
                        $ret_array['ret'] = 1;
                        $ret_array['msg'] = 'yeahmobi' . $data_arr['msg'];
                        break;
                    }
                    $page++;
                } while ($page < $page_count);
                if ($str_id != '') {
                    $offers_pending = joy_offers::model()->findAll("t.id not in ($str_id) and advertiser_id = $advertiser_id");
                    foreach ($offers_pending as $offer_pending) {
                        $pending_id = $offer_pending['id'];
                        $pending = JoyOfferPending::model()->findByAttributes(array('offer_id' => $pending_id, 'time' => date('Y-m-d')));
                        if (empty($pending)) {
                            $pending = new JoyOfferPending();
                        }
                        $pending->time = date('Y-m-d');
                        $pending->offer_id = $pending_id;
                        if (!$pending->save()) {
                            $ret_array['error'] = 'yeahmobi pending save error!';
                        }
                    }
                }
            }catch (Exception $e){
                $ret_array['ret'] = 0;
                $ret_array['msg'] = $e->getMessage();
                break;
            }
        }while(0);
        if( 0 != $ret_array['ret'] ){
            Common::toTxt(array('file'=>$this->logpre.'Log_OfferCron_mobilecore.txt', 'txt'=>'Output:'.var_export($ret_array, true)));
        }
        return $ret_array;
    }

    public function mobvista(){
        $ret_array	=	array('ret'=>1, 'msg'=>'', 'occur'=>'CronController_mobilecore', 'error'=>'','name'=>'mobvista', 'data'=>array());
        $tpl		=	'mobvista';
        $upper_info	=	require BASE_DIR.'/protected/config/advertiseradconf.php';
        if( !$data_info = isset($upper_info[$tpl]) ){
            throw new ErrorException('no advertiser');
        }
        do{
            try {
                $advertiser_id = $upper_info[$tpl]['advertiser_id'];
                $url = $upper_info[$tpl]['url'];
                $page = 1;
                $limit = '';
                $offer_num = joy_offers::model()->count("advertiser_id=$advertiser_id");
                if ($offer_num != 0) {
                    $connection = Yii::app()->db;
                    $command = $connection->createCommand();
                    $rs = $command->update('joy_offers', array('status' => 0), "advertiser_id=$advertiser_id and create_self=0");
                    if ($rs == 0) {
                        $ret_array['ret'] = 1;
                        $ret_array['msg'] = 'mobvista update status error!';
                        $offer = joy_offers::model()->findByAttributes(array('advertiser_id' => $advertiser_id));
                        $offer->status = 1;
                        $offer->update();
                        break;
                    }
                }
                $types = JoyOffersType::model()->findAll();
                $rows = array();
                if ($types) {
                    foreach ($types as $key => $val) {
                        $row_key = explode(',', $val['key_words']);
                        $rows[$val['id']] = $row_key;
                    }
                }
                do {
                    $url .= "&page=$page";
                    $data = Common::curlGet(array('url' => $url));
                    $data_arr = json_decode($data, true);
                    $page_count = 0;
                    //�õ����µ�offer_id
                    $str_id = '';
                    if ($data_arr['sucess']) {
                        $page_count = $data_arr['max_page'];
                        foreach ($data_arr['offers'] as $val => $key) {
                            $name = $key['offer_name'];
                            $campaign_id = $key['campid'];
                            $status = $key['status'];
                            $platforms = $key['platform'];    //��������
                            $offer_url = $key['tracking_link'] . '&aff_sub={transaction_id}';
                            $geo = $key['geo'];
                            $preview_url = $key['preview_link'];
                            $revenue = $key['price'];
                            $payout = $revenue * 0.75;
                            $icon_link = $key['icon_link'];
                            $app_name = $key['app_name'];
                            $app_desc = $key['app_desc'];
                            $create_time = $key['start_time'];
                            $update_time_get = $key['update_time'];
                            $update_time = null;
                            if (!empty($update_time_get)) {
                                $update_time = date('Y-m-d H:i:s', $update_time_get);
                            }
                            $traffic = $key['traffic_source'];
                            $min_android_version = '';
                            if (isset($key['min_android_version'])) {
                                $min_android_version = $key['min_android_version'];
                            }
                            $max_android_version = '';
                            if (isset($key['max_android_version'])) {
                                $max_android_version = $key['max_android_version'];
                            }
                            $note = $key['note'];
                            $cap = 0;
                            $daily_cap = '';
                            if (isset($key['daily_cap'])) {
                                $daily_cap = $key['daily_cap'];
                                $cap = 1;
                            }
                            $offer = joy_offers::model()->findByAttributes(array('campaign_id' => $campaign_id, 'advertiser_id' => $advertiser_id));
                            $str_arr = array();
                            $str = '';
                            if (!empty($rows)) {
                                foreach ($rows as $key => $val) {
                                    $flag = false;
                                    foreach ($val as $val2) {
                                        if ($val2 && $name) {
                                            if (stristr($name, $val2)) {
                                                if (!in_array($key, $str_arr)) {
                                                    array_push($str_arr, $key);
                                                    $flag = true;
                                                    continue;
                                                }
                                            }
                                        }
                                    }
                                    if ($flag) continue;
                                }
                                if (!empty($str_arr)) {
                                    $str_arr = array_unique($str_arr);
                                    $str = implode(',', $str_arr);
                                }
                            }
                            if (empty($offer)) {
                                $offer = new joy_offers();
                                $offer->status = 1;
                                $offer->revenue = $revenue;
                                $offer->payout = $payout;
                                $offer->name = $name;
                                $offer->description = $app_desc;
                                $offer->offer_url = $offer_url;
                                $offer->preview_url = $preview_url;
                                $offer->advertiser_id = $advertiser_id;
                                $offer->thumbnail = $icon_link;
                                $offer->geo_targeting = $geo;
                                $offer->campaign_id = $campaign_id;
                                $offer->caps = $cap;
                                $offer->type = $str;
                                $offer->platform = $platforms;
                                $offer->note = $note;
                                $offer->createtime = date('Y-m-d H:i:s', $create_time);
                                $offer->traffic = $traffic;
                                $offer->max_android_version = $max_android_version;
                                $offer->min_android_version = $min_android_version;
                                $offer->joy_createtime = date('Y-m-d H:i:s');
                                $offer->updatetime = $update_time;
                                if (!$offer->save()) {
                                    $ret_array['ret'] = 1;
                                    $ret_array['msg'] = 'mobvista insert error!';
                                    break;
                                }
                                $offer_id = $offer->attributes['id'];
                            } else {
                                $offer_id = $offer->id;
                                $str_id .= $str_id == '' ? $offer_id : ',' . $offer_id;
                                //������¼�¼��
                                //�Ȳ�ѯlogs�����Ƿ������offer�ļ�¼
//                                $logs = JoyUpdateLogs::model()->findByAttributes(array('offer_id' => $offer_id, 'time' => date('Y-m-d')));
//                                if (empty($logs)) {
//                                    $logs = new JoyUpdateLogs();
//                                }
//                                $old_payout = $offer->payout;
//                                $old_revenue = $offer->revenue;
//                                $old_name = $offer->name;
//                                $old_offer_url = $offer->offer_url;
//                                $old_type = $offer->type;
//                                $old_caps = $offer->caps;
//                                $old_platform = $offer->platform;
//                                $old_geo = $offer->geo_targeting;
//                                $old_description = $offer->description;
//                                $change = false;
//                                if ($old_payout != $payout) {
//                                    $logs->payout = $old_payout;
//                                    $logs->revenue = $old_revenue;
//                                    $change = true;
//                                }
//                                $logs->name = $old_name;
//                                if ($old_platform != $platforms) {
//                                    $logs->platform = $old_platform;
//                                    $change = true;
//                                }
//                                if ($old_type != $str) {
//                                    $logs->type = $str;
//                                    $change = true;
//                                }
//                                if ($old_offer_url != $offer_url) {
//                                    $logs->offer_url = $old_offer_url;
//                                    $change = true;
//                                }
//                                //��̨û�и��µ����и��µ�����
//                                if ($old_geo != $geo) {
//                                    $logs->geo_targeting = $geo;
//                                    $change = true;
//                                }
//                                if ($old_description != $app_desc && !empty($app_desc)) {
//                                    $logs->description = $app_desc;
//                                    $change = true;
//                                }
                                $offer->status = 1;
                                $offer->revenue = $revenue;
                                $offer->payout = $payout;
                                $offer->name = $name;
                                $offer->offer_url = $offer_url;
                                $offer->preview_url = $preview_url;
                                $offer->advertiser_id = $advertiser_id;
                                $offer->campaign_id = $campaign_id;
                                $offer->type = $str;
                                $offer->createtime = date('Y-m-d H:i:s', $create_time);
                                $offer->updatetime = $update_time;
                                $offer->caps = $cap;
                                if (!$offer->update()) {
                                    $ret_array['ret'] = 1;
                                    $ret_array['msg'] = 'mobvista update error!';
                                    break;
                                }
//                                if ($change) {
//                                    $logs->offer_id = $offer_id;
//                                    $logs->time = date('Y-m-d');
//                                    if (!$logs->save()) {
//                                        $ret_array['ret'] = 1;
//                                        $ret_array['msg'] = 'mobvista update_logs insert failed:';
//                                        break;
//                                    }
//                                }
                            }
                            //����ˢ�»������������caps��
                            $offer_cap = JoyOffersCaps::model()->findByAttributes(array('offer_id' => $offer_id));
                            if ($offer_cap) {
                                $offer_cap->daily_con = $daily_cap;
                                if (!$offer_cap->update()) {
                                    $ret_array['ret'] = 0;
                                    $ret_array['msg'] = 'mobvista cap update error';
                                }
                            } else {
                                $offer_cap = new JoyOffersCaps();
                                $offer_cap->daily_con = $daily_cap;
                                if (!$offer_cap->save()) {
                                    $ret_array['ret'] = 0;
                                    $ret_array['msg'] = 'mobvista cap insert error';
                                }
                            }
                            $ret_array['ret'] = 0;
                            $ret_array['msg'] = 'mobvista no problem';
                        }
                    } else {
                        $ret_array['ret'] = 1;
                        $ret_array['msg'] = $data_arr['msg'];
                        break;
                    }
                    $page++;
                    $ret_array['ret'] = 0;
                } while ($page < $page_count);
                if ($str_id != '') {
                    $offers_pending = joy_offers::model()->findAll("t.id not in ($str_id) and advertiser_id = $advertiser_id");
                    foreach ($offers_pending as $offer_pending) {
                        $pending_id = $offer_pending['id'];
                        $pending = JoyOfferPending::model()->findByAttributes(array('offer_id' => $pending_id, 'time' => date('Y-m-d')));
                        if (empty($pending)) {
                            $pending = new JoyOfferPending();
                        }
                        $pending->time = date('Y-m-d');
                        $pending->offer_id = $pending_id;
                        if (!$pending->save()) {
                            $ret_array['error'] = 'mobvista pending save error!';
                        }
                    }
                }
            }catch (Exception $e){
                $ret_array['ret'] = 0;
                $ret_array['msg'] = $e->getMessage();
                break;
            }
        }while(0);
        if(0 !=  $ret_array['ret']){
            Common::toTxt(array('file' => $this->logpre . 'Log_OfferCron_mobilecore.txt', 'txt' => 'Output:' . var_export($ret_array['msg'], true)));
        }
        return $ret_array;
    }

    //pocket media
    public function hasoffer()
    {
        $ret_array = array('ret' => 1, 'msg' => '','name'=>'pocket media','occur' => 'CronController_mobilecore', 'error' => '', 'data' => array());
        $tpl = 'hasoffers';
        $upper_info = require BASE_DIR . '/protected/config/advertiseradconf.php';
        do {
            try {
                if (!$data_info = isset($upper_info[$tpl])) {
                    $ret_array['ret'] = 1;
                    $ret_array['msg'] = 'pocket media no config!';
                    break;
                }
                $url = $upper_info[$tpl]['url'];
                $advertiser_id = $upper_info[$tpl]['advertiser_id'];
                $data_re = Common::curlGet(array('url' => $url));
                if (!$data_re) {
                    $ret_array['ret'] = 1;
                    $ret_array['msg'] = 'pocket media get data error!';
                    break;
                }
                $data_array = json_decode($data_re);
                if (empty($data_array->errors)) {
                    //���±��е�ǰ����������offer��״̬Ϊ0
                    $offer_num = joy_offers::model()->count("advertiser_id=$advertiser_id");
                    if ($offer_num != 0) {
                        $connection = Yii::app()->db;
                        $command = $connection->createCommand();
                        $rs = $command->update('joy_offers', array('status' => 0), "advertiser_id=$advertiser_id");
                        if ($rs == 0) {
                            $ret_array['ret'] = 1;
                            $ret_array['msg'] = 'pocket media update status error!';
                            $offer = joy_offers::model()->findByAttributes(array('advertiser_id' => $advertiser_id));
                            $offer->status = 1;
                            $offer->update();
                            break;
                        }
                    }
                    $types = JoyOffersType::model()->findAll();
                    $rows = array();
                    if ($types) {
                        foreach ($types as $key => $val) {
                            $row_key = explode(',', $val['key_words']);
                            $rows[$val['id']] = $row_key;
                        }
                    }
                    $str_id = '';
                    foreach ($data_array->response->data as $key) {
                        $campaign_id = $key->Offer->id;
                        $name = $key->Offer->name;
                        $description = $key->Offer->description;
                        $preview_url = $key->Offer->preview_url;
                        $offer_url = "http://track.12trackway.com/aff_c?offer_id=$campaign_id&aff_id={channel}&aff_sub={transaction_id}";
                        $currency = $key->Offer->currency;
                        $revenue = $key->Offer->default_payout;
                        $default_payout = $revenue * 0.75;
                        $payout_type = $key->Offer->payout_type;
                        $payout_cap = $key->Offer->payout_cap;
                        $status_cn = $key->Offer->status;
                        $expiration_date = $key->Offer->expiration_date;
                        $country = '';
                        $traffic = '';
                        $daily_cap = intval($key->Offer->conversion_cap);
                        $monthly_conversion_cap = intval($key->Offer->monthly_conversion_cap);
                        $daily_payout = intval($key->Offer->payout_cap);
                        $monthly_payout_cap = intval($key->Offer->monthly_payout_cap);
                        if (strpos($description, 'Country:')) {
                            $first = strpos($description, 'Country:') + 8;
                            $country_sb = trim(substr($description, $first, strpos($description, 'Text link') - $first));
                            $country_ab = strip_tags($country_sb);
                            $country = preg_replace_callback('/\s+/', function ($matches) {
                                return ' ';
                            }, $country_ab);
                            if (strlen($country) > 800) {
                                $country = substr($country, 0, 400) . '...';
                            }
                        }
                        if(strlen($description) > 800){
                            $description = substr($description,0,400) . '...';
                        }
                        if ($status_cn == 'active') {
                            $status = 1;
                        } else {
                            $status = 0;
                        }
                        //�ж�����url�Ƿ����clickid={transaction_id}&source={affiliate_id}
                        $preview_url .= '&aff_sub={transaction_id}';
                        $offer = joy_offers::model()->findByAttributes(array('advertiser_id' => $advertiser_id, 'campaign_id' => $campaign_id));
                        $str_arr = array();
                        $str = '';
                        if (!empty($rows)) {
                            //�õ����µ�offer_id
                            foreach ($rows as $key => $val) {
                                $flag = false;
                                foreach ($val as $val2) {
                                    if ($val2 && $name) {
                                        if (stristr($name, $val2)) {
                                            if (!in_array($key, $str_arr)) {
                                                array_push($str_arr, $key);
                                                $flag = true;
                                                continue;
                                            }
                                        }
                                    }
                                }
                                if ($flag) continue;
                            }
                            if (!empty($str_arr)) {
                                $str_arr = array_unique($str_arr);
                                $str = implode(',', $str_arr);
                            }
                        }
                        if (!$offer) {
                            $offer = new joy_offers();
                            $offer->advertiser_id = $advertiser_id;
                            $offer->campaign_id = $campaign_id;
                            $offer->name = $name;
                            $offer->description = $description;
                            $offer->offer_url = $offer_url = "http://track.12trackway.com/aff_c?offer_id=$campaign_id&aff_id={channel}&aff_sub={transaction_id}";
                            $offer->preview_url = $preview_url;
                            $offer->payout = $default_payout;
                            $offer->revenue = $revenue;
                            $offer->status = $status;
                            $offer->geo_targeting = $country;
                            $offer->type = $str;
                            $offer->traffic = $traffic;
                            $offer->joy_createtime = date('Y-m-d H:i:s');
                            $offer->expiration_date = $expiration_date;
                            if (!$offer->save()) {
                                $ret_array['ret'] = 1;
                                $ret_array['msg'] = $offer->errors;
                                break;
                            }
                            $offer_id = $offer->attributes['id'];
                        } else {
                            $offer_id = $offer->id;
                            $str_id .= $str_id == '' ? $offer_id : ',' . $offer_id;
                            //������¼�¼��
                            //�Ȳ�ѯlogs�����Ƿ������offer�ļ�¼
//                            $logs = JoyUpdateLogs::model()->findByAttributes(array('offer_id' => $offer_id, 'time' => date('Y-m-d')));
//                            if (empty($logs)) {
//                                $logs = new JoyUpdateLogs();
//                            }
//                            $old_payout = $offer->payout;
//                            $old_revenue = $offer->revenue;
//                            $old_name = $offer->name;
//                            $old_offer_url = $offer->offer_url;
//                            $old_type = $offer->type;
//                            $old_caps = $offer->caps;
//                            $old_platform = $offer->platform;
//                            $old_geo = $offer->geo_targeting;
//                            $old_description = $offer->description;
//                            $change = false;
//                            if ($old_payout != $default_payout) {
//                                $logs->payout = $old_payout;
//                                $logs->revenue = $old_revenue;
//                                $change = true;
//                            }
//                            $logs->name = $old_name;
//                            if ($old_type != $str) {
//                                $logs->type = $str;
//                                $change = true;
//                            }
//                            if ($old_offer_url != $offer_url) {
//                                $logs->offer_url = $old_offer_url;
//                                $change = true;
//                            }
//                            //��̨û�и��µ����и��µ�����
//                            if ($old_geo != $country) {
//                                $logs->geo_targeting = $country;
//                                $change = true;
//                            }
//                            if ($old_description != $description && !empty($description)) {
//                                $logs->description = $description;
//                                $change = true;
//                            }
                            $offer->advertiser_id = $advertiser_id;
                            $offer->campaign_id = $campaign_id;
                            $offer->name = $name;
                            $offer->offer_url = $offer_url;
                            $offer->preview_url = $preview_url;
                            $offer->payout = $default_payout;
                            $offer->revenue = $revenue;
                            $offer->status = $status;
                            $offer->type = $str;
                            $offer->expiration_date = $expiration_date;
                            if (!$offer->update()) {
                                $ret_array['ret'] = 1;
                                $ret_array['msg'] = $offer->errors;
                                break;
                            }
//                            //������¼�¼��
//                            if ($change) {
//                                $logs->offer_id = $offer_id;
//                                $logs->time = date('Y-m-d');
//                                if (!$logs->save()) {
//                                    $ret_array['ret'] = 1;
//                                    $ret_array['msg'] = $logs->errors;
//                                    break;
//                                }
//                            }
                        }
                        //����ˢ�»������������caps��
                        $offer_cap = JoyOffersCaps::model()->findByAttributes(array('offer_id' => $offer_id));
                        if (!empty($offer_cap)) {
                            $offer_cap->daily_con = $daily_cap;
                            $offer_cap->daily_pay = $daily_payout;
                            $offer_cap->month_con = $monthly_conversion_cap;
                            $offer_cap->month_pay = $monthly_payout_cap;
                            if (!$offer_cap->update()) {
                                $ret_array['ret'] = 0;
                                $ret_array['msg'] = $offer_cap->errors;
                            }
                        } else {
                            $offer_cap = new JoyOffersCaps();
                            $offer_cap->daily_con = $daily_cap;
                            $offer_cap->daily_pay = $daily_payout;
                            $offer_cap->month_con = $monthly_conversion_cap;
                            $offer_cap->month_pay = $monthly_payout_cap;
                            $offer_cap->offer_id = $offer_id;
                            if (!$offer_cap->save()) {
                                $ret_array['ret'] = 0;
                                $ret_array['msg'] = $offer_cap->errors;
                            }
                        }
                        $ret_array['ret'] = 0;
                        $ret_array['data'] = 'pocket media no problem.';
                    }
                    if ($str_id != '') {
                        $offers_pending = joy_offers::model()->findAll("t.id not in ($str_id) and advertiser_id = $advertiser_id");
                        foreach ($offers_pending as $offer_pending) {
                            $pending_id = $offer_pending['id'];
                            $pending = JoyOfferPending::model()->findByAttributes(array('offer_id' => $pending_id, 'time' => date('Y-m-d')));
                            if (empty($pending)) {
                                $pending = new JoyOfferPending();
                            }
                            $pending->time = date('Y-m-d');
                            $pending->offer_id = $pending_id;
                            if (!$pending->save()) {
                                $ret_array['error'] = 'pocket media pending save error!';
                            }
                        }
                    }
                } else {
                    $ret_array['ret'] = 1;
                    $ret_array['msg'] = 'pocket media' . $data_array->response->errorMessage;
                    break;
                }
            }catch (Exception $e){
                $ret_array['ret'] = 0;
                $ret_array['msg'] = $e->getMessage();
                break;
            }
            } while (0) ;
            if (0 != $ret_array['ret']) {
                Common::toTxt(array('file' => $this->logpre . 'Log_OfferCron_mobilecore.txt', 'txt' => 'Output:' . var_export($ret_array['msg'], true)));
            }
            return $ret_array;
        }


    //UC�ӿ�
    public function ucWall(){
        $ret_array	=	array('ret'=>1, 'msg'=>'', 'occur'=>'CronController_mobilecore', 'error'=>'','name'=>'urwall', 'data'=>array());
        $tpl		=	'ucWall';
        $upper_info	=	require BASE_DIR.'/protected/config/advertiseradconf.php';
        do {
            try {
                if (!$data_info = isset($upper_info[$tpl])) {
                    $ret_array['ret'] = 1;
                    $ret_array['msg'] = 'uc no config!';
                    break;
                }
                $url = $upper_info[$tpl]['url'];
                $advertiser_id = $upper_info[$tpl]['advertiser_id'];
                $data_re = Common::curlGet(array('url' => $url));
                if (!$data_re) {
                    throw new ErrorException('uc server error!');
                }
                $data_array = json_decode($data_re);
                if (!empty($data_array)) {
                    //���±��е�ǰ����������offer��״̬Ϊ0
                    $offer_num = joy_offers::model()->count("advertiser_id=$advertiser_id");
                    if ($offer_num != 0) {
                        $connection = Yii::app()->db;
                        $command = $connection->createCommand();
                        $rs = $command->update('joy_offers', array('status' => 0), "advertiser_id=$advertiser_id and create_self=0");
                        //UC��ʱֻ��һ��offer
//                    if ($rs == 0) {
//                        $ret_array['ret'] = 1;
//                        $ret_array['msg'] = 'uc update error';
//                        $offer = joy_offers::model()->findByAttributes(array('advertiser_id'=>$advertiser_id));
//                        $offer->status  =   1;
//                        $offer->update();
//                        break;
//                    }
                    }
                    foreach ($data_array as $key) {
                        $campaign_id = $key->campaign_id;
                        $name = $key->app_name;
                        $description = $key->desc;
                        $preview_url = $key->click_url;
                        $traffic = '';
                        $offer = joy_offers::model()->findByAttributes(array('advertiser_id' => $advertiser_id, 'campaign_id' => $campaign_id));
                        if (!$offer) {
                            $offer = new joy_offers();
                            $offer->advertiser_id = $advertiser_id;
                            $offer->campaign_id = $campaign_id;
                            $offer->name = $name;
                            $offer->description = $description;
                            $offer->offer_url = $preview_url;
                            $offer->preview_url = $preview_url;
                            $offer->revenue = 0;
                            $offer->payout = 0;
                            $offer->traffic = $traffic;
                            $offer->joy_createtime = date('Y-m-d H:i:s');
                            if (!$offer->save()) {
                                $ret_array['ret'] = 1;
                                $ret_array['msg'] = $offer->errors;
                                break;
                            }
                        } else {
                            $offer->advertiser_id = $advertiser_id;
                            $offer->campaign_id = $campaign_id;
                            $offer->name = $name;
                            $offer->offer_url = $preview_url;
                            $offer->preview_url = $preview_url;
                            $offer->revenue = 0;
                            $offer->payout = 0;
                            if (!$offer->update()) {
                                $ret_array['ret'] = 1;
                                $ret_array['msg'] = $offer->errors;
                                break;
                            }
                        }
                        $ret_array['ret'] = 0;
                        $ret_array['data'] = 'uc no problem.';
                    }
                } else {
                    $ret_array['ret'] = 1;
                    $ret_array['msg'] = 'uc no data';
                    break;
                }
            }catch (Exception $e){
                $ret_array['ret'] = 0;
                $ret_array['msg'] = $e->getMessage();
                break;
            }
        }while(0);
        if(0 !=  $ret_array['ret']){
            Common::toTxt(array('file' => $this->logpre . 'Log_OfferCron_mobilecore.txt', 'txt' => 'Output:' . var_export($ret_array['msg'], true)));
        }
        return $ret_array;
    }

    //���׹��
    public function youmi(){
        $ret_array = array('ret' => 1, 'msg' => '','name'=>'youmi','occur' => 'CronController_mobilecore', 'error' => '', 'data' => array());
        $tpl = 'youmi';
        $upper_info = require BASE_DIR . '/protected/config/advertiseradconf.php';
        do {
            try {
                if (!$data_info = isset($upper_info[$tpl])) {
                    $ret_array['ret'] = 1;
                    $ret_array['msg'] = 'youmi no config!';
                    break;
                }
                $url = $upper_info[$tpl]['url'];
                $advertiser_id = $upper_info[$tpl]['advertiser_id'];
                $data_re = Common::curlGet(array('url' => $url));
                if (!$data_re) {
                    $ret_array['ret'] = 1;
                    $ret_array['msg'] = 'youmi get data error!';
                    break;
                }
                $data_array = json_decode($data_re);
                if (!empty($data_array)) {
                    $offer_num = joy_offers::model()->count("advertiser_id=$advertiser_id");
                    if ($offer_num != 0) {
                        $connection = Yii::app()->db;
                        $command = $connection->createCommand();
                        $rs = $command->update('joy_offers', array('status' => 0), "advertiser_id=$advertiser_id");
                        if ($rs == 0) {
                            $ret_array['ret'] = 1;
                            $ret_array['msg'] = 'youmi update status error!';
                            $offer = joy_offers::model()->findByAttributes(array('advertiser_id' => $advertiser_id));
                            $offer->status = 1;
                            $offer->update();
                            break;
                        }
                    }
                    $types = JoyOffersType::model()->findAll();
                    $rows = array();
                    if ($types) {
                        foreach ($types as $key => $val) {
                            $row_key = explode(',', $val['key_words']);
                            $rows[$val['id']] = $row_key;
                        }
                    }
                    foreach ($data_array->offers as $key) {
                        $campaign_id = $key->id;
                        $name = $key->name;
                        $preview_url = $key->preview_url;
                        $offer_url = $key->url;
                        $revenue = $key->payout;
                        $default_payout = $revenue * 0.75;
                        $country = $key->countries;
                        $countries = implode(',',$country);
                        $thumbnail = $key->iconUrl;
                        $description = $key->adtxt;
                        $category = $key->category;
                        $type = JoyOffersType::model()->findByAttributes(array('type_name_en'=>$category));
                        $str = '';
                        if(!empty($type)){
                            $str = $type->id;
                        }
                        $offer = joy_offers::model()->findByAttributes(array('advertiser_id' => $advertiser_id, 'campaign_id' => $campaign_id));
                        if (!$offer) {
                            $offer = new joy_offers();
                            $offer->advertiser_id = $advertiser_id;
                            $offer->campaign_id = $campaign_id;
                            $offer->name = $name;
                            $offer->thumbnail = $thumbnail;
                            $offer->description = $description;
                            $offer->offer_url = $offer_url;
                            $offer->preview_url = $preview_url;
                            $offer->payout = $default_payout;
                            $offer->revenue = $revenue;
                            $offer->status = 1;
                            $offer->geo_targeting = $countries;
                            $offer->type = $str;
                            $offer->joy_createtime = date('Y-m-d H:i:s');
                            if (!$offer->save()) {
                                $ret_array['ret'] = 1;
                                $ret_array['msg'] = $offer->errors;
                                break;
                            }
                        } else {
                            //������¼�¼��
                            $offer->advertiser_id = $advertiser_id;
                            $offer->campaign_id = $campaign_id;
                            $offer->name = $name;
                            $offer->description = $description;
                            $offer->offer_url = $offer_url;
                            $offer->preview_url = $preview_url;
                            $offer->payout = $default_payout;
                            $offer->revenue = $revenue;
                            $offer->geo_targeting = $countries;
                            $offer->type = $str;
                            $offer->status = 1;
                            if (!$offer->update()) {
                                $ret_array['ret'] = 1;
                                $ret_array['msg'] = $offer->errors;
                                break;
                            }
                        }
                        $ret_array['ret'] = 0;
                        $ret_array['data'] = 'youmi no problem.';
                    }
                } else {
                    $ret_array['ret'] = 1;
                    $ret_array['msg'] = 'youmi get data error';
                    break;
                }
            }catch (Exception $e){
                $ret_array['ret'] = 0;
                $ret_array['msg'] = $e->getMessage();
                break;
            }
        } while (0) ;
        if (0 != $ret_array['ret']) {
            Common::toTxt(array('file' => $this->logpre . 'Log_OfferCron_mobilecore.txt', 'txt' => 'Output:' . var_export($ret_array['msg'], true)));
        }
        return $ret_array;
    }

    public function kissmyads(){
        $ret_array = array('ret' => 1, 'msg' => '','name'=>'kissmyads','occur' => 'CronController_mobilecore', 'error' => '', 'data' => array());
        $tpl = 'kissmyads';
        $upper_info = require BASE_DIR . '/protected/config/advertiseradconf.php';
        do {
            try {
                if (!$data_info = isset($upper_info[$tpl])) {
                    $ret_array['ret'] = 1;
                    $ret_array['msg'] = 'kissmyads no config!';
                    break;
                }
                $url = $upper_info[$tpl]['url'];
                $advertiser_id = $upper_info[$tpl]['advertiser_id'];
                $data_re = Common::curlGet(array('url' => $url));
                if (!$data_re) {
                    $ret_array['ret'] = 1;
                    $ret_array['msg'] = 'kissmyads get data error!';
                    break;
                }
                $data_array = json_decode($data_re);
                if ($data_array->response->status == 1) {
                    //���±��е�ǰ����������offer��״̬Ϊ0
                    $offer_num = joy_offers::model()->count("advertiser_id=$advertiser_id");
                    if ($offer_num != 0) {
                        $connection = Yii::app()->db;
                        $command = $connection->createCommand();
                        $rs = $command->update('joy_offers', array('status' => 0), "advertiser_id=$advertiser_id");
                        if ($rs == 0) {
                            $ret_array['ret'] = 1;
                            $ret_array['msg'] = 'kissmyads update status error!';
                            $offer = joy_offers::model()->findByAttributes(array('advertiser_id' => $advertiser_id));
                            $offer->status = 1;
                            $offer->update();
                            break;
                        }
                    }
                    $types = JoyOffersType::model()->findAll();
                    $rows = array();
                    if ($types) {
                        foreach ($types as $key => $val) {
                            $row_key = explode(',', $val['key_words']);
                            $rows[$val['id']] = $row_key;
                        }
                    }
                    $str_id = '';
                    foreach ($data_array->response->data as $key) {
                        $campaign_id = $key->Offer->id;
                        $name = $key->Offer->name;
                        $description = $key->Offer->description;
                        $preview_url = $key->Offer->preview_url;
                        $offer_url = $offer_url = "http://tracking.kissmyads.com/aff_c?offer_id=$campaign_id&aff_id={channel}&aff_sub={transaction_id}";
                        $revenue = $key->Offer->default_payout;
                        $default_payout = $revenue * 0.75;
                        $status_cn = $key->Offer->status;
                        $is_expired = $key->Offer->is_expired;
                        $expiration_date = $key->Offer->expiration_date;
                        $country = '';
                        $daily_cap = intval($key->Offer->conversion_cap);
                        $monthly_conversion_cap = intval($key->Offer->monthly_conversion_cap);
                        $daily_payout = intval($key->Offer->payout_cap);
                        $monthly_payout_cap = intval($key->Offer->monthly_payout_cap);
                        if (strpos($description, 'Country:')) {
                            $first = strpos($description, 'Country:') + 8;
                            $country_sb = trim(substr($description, $first, strpos($description, 'Text link') - $first));
                            $country_ab = strip_tags($country_sb);
                            $country = preg_replace_callback('/\s+/', function ($matches) {
                                return ' ';
                            }, $country_ab);
                            if (strlen($country) > 800) {
                                $country = substr($country, 0, 400) . '...';
                            }
                        }
                        if(strlen($description) > 800){
                            $description = substr($description,0,400) . '...';
                        }
                        if ($status_cn == 'active') {
                            $status = 1;
                        } else {
                            $status = 0;
                        }
                        //�ж�����url�Ƿ����clickid={transaction_id}&source={affiliate_id}
                        $preview_url .= '&aff_sub={transaction_id}';
                        $offer = joy_offers::model()->findByAttributes(array('advertiser_id' => $advertiser_id, 'campaign_id' => $campaign_id));
                        $str_arr = array();
                        $str = '';
                        if (!empty($rows)) {
                            //�õ����µ�offer_id
                            foreach ($rows as $key => $val) {
                                $flag = false;
                                foreach ($val as $val2) {
                                    if ($val2 && $name) {
                                        if (stristr($name, $val2)) {
                                            if (!in_array($key, $str_arr)) {
                                                array_push($str_arr, $key);
                                                $flag = true;
                                                continue;
                                            }
                                        }
                                    }
                                }
                                if ($flag) continue;
                            }
                            if (!empty($str_arr)) {
                                $str_arr = array_unique($str_arr);
                                $str = implode(',', $str_arr);
                            }
                        }
                        if (!$offer) {
                            $offer = new joy_offers();
                            $offer->advertiser_id = $advertiser_id;
                            $offer->campaign_id = $campaign_id;
                            $offer->name = $name;
                            $offer->description = $description;
                            $offer->offer_url = $offer_url;
//                            $offer->offer_url = $offer_url = "http://track.12trackway.com/aff_c?offer_id=$campaign_id&aff_id=2481&aff_sub={transaction_id}";
                            $offer->preview_url = $preview_url;
                            $offer->payout = $default_payout;
                            $offer->revenue = $revenue;
                            $offer->status = $status;
                            $offer->geo_targeting = $country;
                            $offer->type = $str;
                            $offer->joy_createtime = date('Y-m-d H:i:s');
                            $offer->expiration_date = $expiration_date;
                            if (!$offer->save()) {
                                $ret_array['ret'] = 1;
                                $ret_array['msg'] = $offer->errors;
                                break;
                            }
                            $offer_id = $offer->attributes['id'];
                        } else {
                            $offer_id = $offer->id;
                            $offer->advertiser_id = $advertiser_id;
                            $offer->campaign_id = $campaign_id;
                            $offer->name = $name;
                            $offer->offer_url = $offer_url;
                            $offer->preview_url = $preview_url;
                            $offer->payout = $default_payout;
                            $offer->revenue = $revenue;
                            $offer->status = $status;
                            $offer->type = $str;
                            $offer->expiration_date = $expiration_date;
                            if (!$offer->update()) {
                                $ret_array['ret'] = 1;
                                $ret_array['msg'] = $offer->errors;
                                break;
                            }
                        }
                        //����ˢ�»������������caps��
                        $offer_cap = JoyOffersCaps::model()->findByAttributes(array('offer_id' => $offer_id));
                        if (!empty($offer_cap)) {
                            $offer_cap->daily_con = $daily_cap;
                            $offer_cap->daily_pay = $daily_payout;
                            $offer_cap->month_con = $monthly_conversion_cap;
                            $offer_cap->month_pay = $monthly_payout_cap;
                            if (!$offer_cap->update()) {
                                $ret_array['ret'] = 0;
                                $ret_array['msg'] = $offer_cap->errors;
                            }
                        } else {
                            $offer_cap = new JoyOffersCaps();
                            $offer_cap->daily_con = $daily_cap;
                            $offer_cap->daily_pay = $daily_payout;
                            $offer_cap->month_con = $monthly_conversion_cap;
                            $offer_cap->month_pay = $monthly_payout_cap;
                            $offer_cap->offer_id = $offer_id;
                            if (!$offer_cap->save()) {
                                $ret_array['ret'] = 0;
                                $ret_array['msg'] = $offer_cap->errors;
                            }
                        }
                        $ret_array['ret'] = 0;
                        $ret_array['data'] = 'kissmyads no problem.';
                    }
                    if ($str_id != '') {
                        $offers_pending = joy_offers::model()->findAll("t.id not in ($str_id) and advertiser_id = $advertiser_id");
                        foreach ($offers_pending as $offer_pending) {
                            $pending_id = $offer_pending['id'];
                            $pending = JoyOfferPending::model()->findByAttributes(array('offer_id' => $pending_id, 'time' => date('Y-m-d')));
                            if (empty($pending)) {
                                $pending = new JoyOfferPending();
                            }
                            $pending->time = date('Y-m-d');
                            $pending->offer_id = $pending_id;
                            if (!$pending->save()) {
                                $ret_array['error'] = 'kissmyads pending save error!';
                            }
                        }
                    }
                } else {
                    $ret_array['ret'] = 1;
                    $ret_array['msg'] = 'kissmyads error';
                    break;
                }
            }catch (Exception $e){
                $ret_array['ret'] = 0;
                $ret_array['msg'] = $e->getMessage();
                break;
            }
        } while (0) ;
        if (0 != $ret_array['ret']) {
            Common::toTxt(array('file' => $this->logpre . 'Log_OfferCron_mobilecore.txt', 'txt' => 'Output:' . var_export($ret_array['msg'], true)));
        }
        return $ret_array;
    }

    /**
     */
    public function actionIndex(){
        $argus  =   array('yeahmobi','mobilecore','mobvista','hasoffer','youmi','kissmyads');//mobilecore
        $ret_array	=	array('ret'=>1, 'msg'=>'', 'data'=>'');
        do{
            try{
                if( empty($argus)  || empty($argus[0]) ){
                    $ret_array['ret']	=	1;
                    $ret_array['msg']	=	'Error';
                    break;
                }
                for($i=0;$i<count($argus);$i++){
                    $conn   =   Yii::app()->db->beginTransaction();
                    $funname = $argus[$i];
                    $ret_array[$i]['data'] = $this->$funname();
                    $conn->commit();
                }
            }catch(Exception $e){
                $ret_array['ret']	=	13;
                $ret_array['msg']	=	'Error';
                $ret_array['error']	=	$e->getMessage();
                break;
            }
        }while(0);
        if( 0 != $ret_array['ret'] ){
            print_r($ret_array);
        }
    }

    public function actionSendEmail()
    {
        $ret_array = array('ret'=>0,'msg'=>array(),'error'=>'errors');
        do {
            $pendings = JoyOfferPending::model()->with('offers')->findAllByAttributes(array('time' => date('Y-m-d')));
            $offer_change = JoyUpdateLogs::model()->findAllByAttributes(array('time' => date('Y-m-d')));
            $users = JoySystemUser::model()->findAllBySql('select * from joy_system_user where `groupid` in (1)');
            $pending_subject = '';
            $preview_urls = '';

            if(!empty($pendings) || !empty($offer_change)) {
                $host = 'smtp.ym.163.com';
                $user_name = "admin@joydream.cn";
                $email_password = "292513148/bing";
                $recevie_name = 'Dear Partner';
                foreach ($users as $user) {
                    if(!empty($pendings)){
                        foreach ($pendings as $pending) {
                            $pending_subject .= $pending_subject == '' ? $pending['offers']['name'] : ',' . $pending['offers']['name'];
                            $preview_urls .= $preview_urls == '' ? "<p><a href='{$pending['offers']['preview_url']}'>{$pending['offers']['preview_url']}</a></p>" : "<p><a href='{$pending['offers']['preview_url']}'>{$pending['offers']['preview_url']}</a></p>";
                        }
                        $subject = "Your $pending_subject account is being reviewed";
                        $content = "<p><h3>Hi ".$user['first_name']."</h3></p>,
                <p>Your application to $pending_subject is currently being reviewed. An account manager will contact you shortly.</p>
                <p>In the meantime, please save this e-mail so you may have your login credentials</p>
                 <p>on-hand when your account is activated.</p>
                 $preview_urls
                 <p>Sincerely,</p>
                 <p>JoyDream</p>
                 <p>This e-mail has been sent from an e-mail address that is not monitored. </p>
                 <p>Please do not reply to this message. We are unable to respond to any replies</p>
                 ";
                        if (Common::sendMail($user['email'], $subject, $content, $user_name, $email_password, $recevie_name, $host) !== true) {
                            $ret_array['ret'] = 0;
                            array_push($ret_array['msg'], $user['email'] . ' send failed!');
                        }
                    }
                    if(!empty($offer_change)){
                        //���·���һ����µ�����
                        $content_table_thead = '<table border="1">
<thead style="padding: 2px;
	text-align: center;
	font-weight: normal;
	height: 25px;"><tr style="height: 40px;"><td style="padding: 2px;
	text-align: left;
	overflow: hidden;
	white-space: nowrap;">ID</td><td style="padding: 2px;
	text-align: left;
	overflow: hidden;
	white-space: nowrap;">payout</td><td style="padding: 2px;
	text-align: left;
	overflow: hidden;
	white-space: nowrap;">offer_url</td><td style="padding: 2px;
	text-align: left;
	overflow: hidden;
	white-space: nowrap;">types</td><td style="padding: 2px;
	text-align: left;
	overflow: hidden;
	white-space: nowrap;">platform</td><td style="padding: 2px;
	text-align: left;
	overflow: hidden;
	white-space: nowrap;">geo_targeting</td><td style="padding: 2px;
	text-align: left;
	overflow: hidden;
	white-space: nowrap;">description</td><td style="padding: 2px;
	text-align: left;
	overflow: hidden;
	white-space: nowrap;">revenue</td></tr></thead>';
                        $content_table_tbody = '<tbody>';
                        foreach($offer_change as $change){
                            $content_table_tbody .= "<tr style='height: 40px;'><td>{$change['offer_id']}</td>
                        <td>{$change['payout']}</td>
                        <td>{$change['offer_url']}</td>
                        <td>{$change['type']}</td>
                        <td>{$change['platform']}</td>
                        <td>{$change['geo_targeting']}</td>
                        <td>{$change['description']}</td>
                        <td>{$change['revenue']}</td>
</tr>";
                        }
                        $content2 = "<p><h3>Dear {$user['company']}</h3></p>
                        <p>Hope this email finds you well. </p>
                        <p>Please kindly check the following latest offer updates and make the changes </p>
                        <p>accordingly . If you need any help, please feel free to contact your account </p>
                        <p>managers.</p>
                        $content_table_thead
                        $content_table_tbody
</tbody>
</table>
";
                        $subject_send = 'JoyMedia--Offer Changes';
                        if (Common::sendMail($user['email'], $subject_send, $content2, $user_name, $email_password, $recevie_name, $host) !== true) {
                            $ret_array['ret'] = 0;
                            array_push($ret_array['msg'], $user['email'] . ' send failed!');
                            continue;
                        }
                    }
                }
            }
            //����msg��û���κ�ֵ���򷵻�����
            if(empty($ret_array['msg'])){
                $ret_array['ret'] = 1;
                unset($ret_array['error']);
            }
        }while(0);
        if(1 !=  $ret_array['ret']){
            Common::toTxt(array('file' => $this->logpre . 'Log_EmailSend_mobilecore.txt', 'txt' => 'Output:' . var_export($ret_array['msg'], true)));
        }
        print_r($ret_array);
    }

    public function actionCutClickTable(){
        $ret_array = array('ret'=>0,'msg'=>array(),'error'=>'errors');
        $connection	=	Yii::app()->db;
        $dateday = date("Ymd",strtotime("-1 day"));
        $cutday = date("Y-m-d",strtotime("-1 day"));
        $table_name = "joy_transaction$dateday";
        $sql_create = "CREATE TABLE `$table_name` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `offerid` int(11) NOT NULL DEFAULT '0',
          `advid` int(11) NOT NULL DEFAULT '0' COMMENT '',
          `affid` int(11) NOT NULL DEFAULT '0' COMMENT '',
          `transactionid` varchar(200) NOT NULL DEFAULT '' COMMENT '',
          `aff_subid` varchar(100) DEFAULT NULL,
          `campaign_id` varchar(200) DEFAULT NULL,
          `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '',
          `ref_offerid` int(11) NOT NULL DEFAULT '0' COMMENT '',
          `ip` varchar(50) DEFAULT NULL COMMENT 'IP',
          `createtime` datetime DEFAULT NULL COMMENT '',
          `createtime2` datetime DEFAULT '0000-00-00 00:00:00' COMMENT '',
          PRIMARY KEY (`id`),
          KEY `offerid` (`offerid`),
          KEY `advid` (`advid`),
          KEY `affid` (`affid`),
          KEY `transactionid` (`transactionid`) USING BTREE
        ) ENGINE=Innodb DEFAULT CHARSET=utf8;";
        $create_con	=	$connection->createCommand($sql_create);
        $rs_create	=	$create_con->query();
        if($rs_create){
            $sql_query = "insert into `$table_name` select * from `joy_transaction` where createtime < '$cutday 00:00:00'";
            $query_con	=	$connection->createCommand($sql_query);
            $rs_query = $query_con->query();
            if($rs_query){
                $sql_delete_data = "delete from `joy_transaction` where createtime < '$cutday 00:00:00'";
                $query_con	=	$connection->createCommand($sql_delete_data);
                $rs_delete_data = $query_con->query();
                if($rs_delete_data){
                    $delete_date = date('Ymd',strtotime("-10 day"));
                    $delete_sql = "DROP TABLE IF EXISTS `joy_transaction$delete_date`;";
                    $delete_con	=	$connection->createCommand($delete_sql);
                    if(!$delete_con->query()){
                        $ret_array['msg'] = 'delete before table error!';
                    }else{
                        $ret_array['msg'] = 'success';
                    }
                }else{
                    $ret_array['msg'] = 'failed delete data error!';
                }
            }else{
                $sql_delete_table = "drop tabel $table_name";
                $query_con	=	$connection->createCommand($sql_delete_table);
                $rs_delete_table = $query_con->query();
                if($rs_delete_table){
                    $ret_array['msg'] = 'failed,delete the table before today error!';
                }
            }
        }else{
            $ret_array['msg'] = 'failed,create table error!';
        }
        Common::toTxt(array('file' => $this->logpre . 'Log_Cut_Table.txt', 'txt' => 'Output:' . var_export($ret_array['msg'], true)));
        print_r($ret_array);
    }

    public  function actionDailyCount()
    {
        $time = date('Y-m-d');
        $time_last = date('Y-m-d',strtotime('-1 day'));
        $connection = Yii::app()->db;
        $last_date = date("Ymd",strtotime("-1 day"));
        $table_name =  "joy_transaction$last_date";
	        $least = JoyOfferCount::model()->findByAttributes(array('time'=>$time_last));
        if(!empty($least)){
            echo 'none';
            return;
        }
        $click_count = "insert into joy_offer_count(offerid,affid,conversion, revenue,payout,click_count,time)
        select s.offerid,s.affid,t.conversion,t.revenue_sum,t.payout_sum,s.click_count,s.createtime from (select offerid,count(*) as click_count,affid,createtime from $table_name  GROUP BY offerid,affid)s
        LEFT JOIN (select offerid,affid,count(*) as conversion,sum(revenue) as revenue_sum,SUM(payout) as payout_sum,createtime from joy_transaction_income WHERE createtime > '$time_last' and createtime < '$time'
        GROUP BY offerid,affid)t on s.affid=t.affid AND s.offerid=t.offerid";
        $command_create = $connection->createCommand($click_count);
        $result = $command_create->query();
        $count = JoyOfferCount::model()->findByPk($connection->lastInsertID);
        if(!empty($count)){
            $count->time = $time;
            $count->save();
            echo 'save success';
            exit;
        }
        var_dump(Yii::app()->db->getLastInsertID());
    }

    public function actionSetInvoice(){
        $invoice_date = JoyInvoice::getSystemPayDay();
        $count_date = JoyInvoice::getLastMonthFirstDay($invoice_date);
        $today = date('Y-m-d H:i:s');

        try {
            $invoice_info = JoyInvoice::getMonthInfo();
           
            foreach ($invoice_info as $count) {
                $flag = JoyInvoice::checkExist($count['affid'],$count_date);
                if(empty($count) || !isset($count['payout']) || $flag){
                    continue;
                }
                $invoice = new JoyInvoice();
                $invoice->invoice_date = $invoice_date;
                $invoice->count_date =$count_date;
                $invoice->createtime = $today;
                $invoice->affid = $count['affid'];
                $invoice->amount = $count['payout'];
                $invoice->status = 0;
                $result = $invoice->save();
                if($result){
                    $id = JoyInvoice::model()->dbConnection->getLastInsertID();
                }
            }
            $result = JoySites::getSiteList($count_date);
            foreach($result as $siteid=>$item){
                $flag = JoyCountPay::checkExist($siteid,$count_date);
                if($flag){
                    continue;
                }
                $pay = new JoyCountPay();
                $pay->amount = $item['amount'];
                $pay->site_id = $siteid;
                $pay->invoice_date = $invoice_date;
                $pay->createtime = $today;
                $pay->count_date = JoyInvoice::getLastMonthFirstDay($invoice_date);
                if($pay->save()){
                    JoyCountPay::PrintPDF($siteid,$count_date,2);
                }
            }
//            $transaction->commit();
        }catch (Exception $e){
            var_dump($e->getMessage());
            var_dump($e->getCode());
//            $transaction->rollback();
        }
    }
}
