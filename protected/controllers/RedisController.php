<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/5/4
 * Time: 11:23
 */
class RedisController extends Controller
{
    public function actionOfferClick() {
        $ret_array = array('ret'=>0);
        $default_offer = joy_offers::model()->findByPk(DEFAULT_OFFER_ID);
        do{
            try{
                //get the params
                $original_offer_id	=	Yii::app()->request->getParam('offer_id');
                $original_aff_id		=	Yii::app()->request->getParam('aff_id');
                $aff_sub	=	Yii::app()->request->getParam('aff_sub');
                $subid		=	Yii::app()->request->getParam('subid');
                $query	=  Yii::app()->request->getParam('search');
                $network_type = Yii::app()->request->getParam('net_type');
                if(empty($original_aff_id)){
                    $aff_id = DEFAULT_AFF_ID;
                }else{
                    $aff_id = $original_aff_id;
                }
                if(empty($original_offer_id)){
                    $offer_id = DEFAULT_OFFER_ID;
                }else{
                    $offer_id = $original_offer_id;
                }
                //we should find the first,if not then set default value
                $offers = joy_offers::model()->findByPk($offer_id);
                if(empty($offers)) {
                    $offer_id = DEFAULT_OFFER_ID;
                }

                //get the ip address
                $ipip = new ipip();
                $ip = $ipip->getIP();
                $country_arr = $ipip->find($ip);

                //jump the offer we had set
                if($aff_id != DEFAULT_AFF_ID){
                    $aff = JoySystemUser::model()->findByPk($aff_id);
                    if(empty($aff)){
                        $aff_id = DEFAULT_AFF_ID;
                    }else{
                        $aff_id = $aff['id'];
                    }
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("offerid=$offer_id");
                    $criteria->addCondition("find_in_set($aff_id,affid)");
                    $criteria->addCondition("status=1");
                    $jump = JoyJump::model()->find($criteria);
                    if(!empty($jump)){
                        if(1 == $jump['country_status']){
                            $country_find_arr = explode(',',$jump['countries']);
                            if(in_array($country_arr[0],$country_find_arr)){
                                if($jump['type'] == 1){
                                    $offer_url = $jump['offer_url'];
                                    break;
                                }else{
                                    $offer_id = $jump['offer_url'];
                                }
                            }
                        }else{
                            if($jump['type'] == 1){
                                $offer_url = $jump['offer_url'];
                                break;
                            }else{
                                $offer_id = $jump['offer_url'];
                            }
                        }
                    }
                }

                if($aff_id==310 && $offer_id==35228){
                    $offer_id = 89864;
                    $offers = joy_offers::model()->findByPk($offer_id);
                }

                if(!empty($offers) && $offers['advertiser_id'] == 57){
                    if($offer_id == 89864){
                        $offer_id = 89864;
                    }else{
                        $offer_id = DEFAULT_OFFER_ID;
                    }
                    if($aff_id == 140 || $aff_id==184){
                        $offer_id=50159;
                    }
                    if(in_array($aff_id,array(216,199,127,134,260,85,95,97,553,1001))){
                        $offer_id=29209;
                    }
                }
                if($aff_id == 537){
                    $offer_id = 29209;
                }
                if($aff_id == 131){
                    $offer_id = 89864;
                }
                $url_this = $_SERVER['SERVER_NAME'];
                if($country_arr[0] == '中国' && $url_this == OUT_LAND_SERVER_NAME){
                    $offer_id = 21;
                    $aff_id = 93566;
                }
                $campaign_id	=	Yii::app()->request->getParam('campaign_id');
                $offers = joy_offers::model()->findByPk($offer_id);
                if(empty($offers)){
                    $offers = $default_offer;
                }
                //transaction_id
                if(empty($aff_sub)){
                    $randchar	=	Common::GetRandChar(array('count'=>6, 'type'=>3));
                    $aff_sub	=	date('YmdHis').$offer_id.$randchar;
                }

                $click_str	=	$aff_id.'_'.$aff_sub.'_'.$offer_id;//	14_testbattery2_3
                $offer_url			=	self::OfferUrlReplace( array('url'=>$offers->offer_url, 'transaction_id'=>$click_str, 'affid'=>$aff_id,'search'=>$query) );
                $transaction				=	new JoyTransaction();
                $transaction->offerid		=	$offer_id;
                $transaction->advid			=	$offers->advertiser_id;
                $transaction->original_affid = $original_aff_id;
                $transaction->original_offerid = $original_offer_id;
                $transaction->affid			=	intval($aff_id);
                $transaction->transactionid	=	$aff_sub;
                $transaction->aff_subid		=	$subid;
                $transaction->campaign_id	= $campaign_id;
                $transaction->country       = $country_arr[0];
                $transaction->ip			=	$ip;
                $transaction->createtime	=	date('Y-m-d H:i:s',time());
                $transaction->createtime2	=	gmdate('Y-m-d H:i:s');
                $transaction->offer_url     = $offer_url;
                $transaction->net_type = $network_type;
                $res	=	$transaction->save();
                if(!$res){
                    $ret_array['ret']	=	10;
                    $ret_array['msg']	=	$transaction->errors;
                    $ret_array['error']	=	'点击存入数据库失败';
                    break;
                }
                $ret_array['ret']	=	0;
                $ret_array['data']	=	$offer_url;
            }catch (Exception $e){
                $ret_array['ret']	=	100;
                $ret_array['msg']	=	'服务器忙，请稍后再试';
                $ret_array['error']	=	$e->getMessage();
                break;
            }
        }while (0);
        $offer_url	=	isset($offer_url) ? $offer_url : $default_offer->offer_url;
//echo $offer_url;
        header("Location:$offer_url");

        $income_count = JoyIncomeCount::model()->findByAttributes(array('affid'=>$aff_id,'offerid'=>$offer_id,'country'=>$country_arr[0]));
        if(empty($income_count)){
            $income_count = new JoyIncomeCount();
            $income_count->affid = $aff_id;
            $income_count->offerid = $offer_id;
            $income_count->country = $country_arr[0];
        }else{
            $income_count->count = $income_count->count + 1;
        }
        $income_count->save();

        if($ret_array['ret'] != 0){
            Common::toTxt(array('file'=>'Log_ApiController_actionOfferClick.txt', 'txt'=>'Input:'.var_export($_REQUEST, true).'|Output:'.var_export($ret_array, true)));
        }
    }

    public function actionOfferBackData(){
        $ret_array		=	array('ret'=>-1, 'msg'=>'', 'occur'=>'ApiController_actionOfferBackData', 'error'=>'');
        $ispostbacked	=	0;
        $postback		=	'';
        $belong = 1;
        do{
            try{
                $click_str	=	Yii::app()->request->getParam('aff_sub');
                $clientip	=	Yii::app()->request->getParam('clientip');
                $country = Yii::app()->request->getParam('cou');
                $carrier = Yii::app()->request->getParam('car');
                $am = Yii::app()->request->getParam('am'); //this is the revenue params
                $platform = Yii::app()->request->getParam('pla');
                $kimia_id = Yii::app()->request->getParam('kimia_id');
                if(empty($click_str)){
                    $ret_array['ret']	=	2;
                    $ret_array['error']	 .=	'缺失click_str;';
                    $affid = 1;
                    $clickid = 0;
                    $offerid = 1;
                    $belong = 0;
                }else{
                    $click_str_arr	=	explode('_', $click_str);
                    $affid			=	isset($click_str_arr[0]) ? $click_str_arr[0] : '';//aff_id
                    $clickid		=	isset($click_str_arr[1]) ? $click_str_arr[1] : '';
                    $offerid		=	isset($click_str_arr[2]) ? $click_str_arr[2] : '';
                }
                $transaction	=	JoyTransaction::model()->findByAttributes(array('transactionid'=>$clickid, 'affid'=>$affid, 'offerid'=>$offerid));
                if(empty($transaction)){
                    $ret_array['ret']	=	9;
                    $ret_array['msg']	=	'This transactionid is not exist5';
                    $ret_array['error']	.=	'transactionid:'.$clickid.', is not exist;';
                    $belong = 0;
                }else{
                    $aff_subid		=	$transaction->aff_subid;
                    $campaign_id	= 	$transaction->campaign_id;
                }
                $offers = joy_offers::model()->findByPk($offerid);
                if(empty($offers)){
                    $ret_array['ret']	=	10;
                    $ret_array['msg']	=	'This offer is not exist6';
                    $ret_array['error']	.=	'offerid:'.$offerid.', is not exist6;';
                    $belong = 0;
                }
                $offers = Common::instantPayout($offers,$affid);
                $serverip			=	Common::getIp();
                if(isset($offers['enable_offer_whitelist']) && 1 == $offers['enable_offer_whitelist']){
                    $mckey_whitelist	=	'OFFER_WHITELIST_'.$offerid;
//					$mcres				=	CfgAR::getMem( array('link'=>CACHE, 'key'=>$mckey_whitelist) );
//					if(0 == $mcres['ret'] && isset($mcres['data']) && !empty($mcres['data'])){
//						缓存查到
//						$ip_list		=	$mcres['data'];
//					}else{
                    //缓存未查到，查数据库
                    $ip_list = JoyOfferWhitelist::model()->findAllByAttributes(array(
                        'offerid'=>$offerid,
                        'status'=>1
                    ));
//						//存入缓存
//						if(!empty($ip_list)){
//							CfgAR::setMc( array('link'=>CACHE, 'key'=>$mckey_whitelist, 'data'=>$ip_list, 'time'=>86400*7) );
//						}
//					}
                    if(!empty($ip_list) && !in_array($serverip, $ip_list)){
                        $ret_array['ret']	=	8;
                        $ret_array['msg']	=	'ip address error';
                        $ret_array['error']	=	'ip:'.$serverip.' is not in whitelist';
                        $belong = 0;
                    }
                }
                //check the whitelist end
                if(!empty($offers)){
                    $revenue	=	$offers['revenue'];
                    $payout		=	$offers['payout'];
                }else{
                    $payout = 0;
                    $revenue = 1;
                    $offers['advertiser_id'] = 1;
                }
                //set count data
                if(empty($transaction)){
                    $country = '未知';
                }else{
                    $country = $transaction['country'];
                }
                $income_count = JoyIncomeCount::model()->findByAttributes(array('affid'=>$affid,'offerid'=>$offerid,'country'=>$country));
                if(empty($income_count)){
                    $income_count = new JoyIncomeCount();
                    $income_count->affid = $affid;
                    $income_count->offerid = $offerid;
                    $income_count->country = $country;
                }else{
                    $income_count->revenue = $income_count->revenue + $am;
                }
                $income_count->save();
                //get advertiser's info
                $advertises = JoySystemUser::model()->findByPk($offers['advertiser_id']);
                //check the offer if here is the cut or a lone payout param
                $cut = JoyOfferCut::model()->findByAttributes(array('offer_id'=>$offerid,'aff_id'=>$affid));
                if(!empty($cut) && !empty($cut['payout'])){
                    $payout = $cut['payout'];
                }
                $aff_info	=	JoySystemUser::model()->findByPk($affid);
                if(!empty($am)){
                    $revenue = $am;
                }
                $transaction_income					=	new JoyTransactionIncome();
                $transaction_income->advid			=	$advertises['id'];
                $transaction_income->offerid		=	$offerid;
                $transaction_income->affid			=	$transaction['affid'];
                $transaction_income->transactionid	=	$clickid;
                if(isset($aff_subid)){
                    $transaction_income->aff_subid		=	$aff_subid;
                }
                $transaction_income->revenue		=	$revenue;
                $transaction_income->payout			=	$payout;
                $transaction_income->clientip		=	$clientip;
                $transaction_income->serverip		=	$serverip;
                $transaction_income->transactiontime=	$transaction['createtime'];
                $transaction_income->transactiontime2	= 	$transaction['createtime2'];
                $transaction_income->createtime		=	date('Y-m-d H:i:s',time());
                $transaction_income->createtime2	=	gmdate('Y-m-d H:i:s');//create the the of the Europe
                $transaction_income->country = $country;
                $transaction_income->carrier = $carrier;
                $transaction_income->kimia_id = $kimia_id;
                $transaction_income->platform = $platform;
                $transaction_income->belong = $belong;
                $transaction_income->error = $ret_array['error'];
                $cut_num	=	rand(1, 100);
                $cut_num2 = rand(1,100);
                $transaction_income->cut_num		=	$cut_num;
                if(empty($cut) || empty($cut['cut_num']) || $cut['cut_num'] == 0){
                    $offer_cut_num = 100;
                }else{
                    $offer_cut_num = $cut['cut_num'];
                }
                $aff_cutcount		=	$aff_info['cutcount'];
                if(null == $aff_cutcount || empty($aff_cutcount)){
                    $aff_cutcount	=	0;  //if here is nothing they should get all
                }
                if(isset($aff_info['postback'])){
                    $postback	=	$aff_info['postback'];
                    $postback	=	str_replace('{aff_sub}', $clickid, $postback);
                    $postback	=	str_replace('{payout}', $payout, $postback);
                    if(isset($aff_subid)){
                        $postback	=	str_replace('{affsub_id}', $aff_subid, $postback);
                    }
                    if(isset($campaign_id)){
                        $postback	=	str_replace('{campaign_id}', $campaign_id, $postback);
                    }
                    $postback	=	str_replace('{platform}', $platform, $postback);
                    $postback	=	str_replace('{ip}', $clientip, $postback);
                    $transaction_income->postback = $postback;
                }
                if($cut_num > $aff_cutcount && $cut_num2 > $offer_cut_num){
                    $transaction_income->ispostbacked = 1;
                    $ispostbacked	=	1;
                }else{
                    $transaction_income->ispostbacked = 0;
                    $ispostbacked	=	0;
                }
                $res	=	$transaction_income->save();
                if(!$res){
                    $ret_array['ret']	=	6;
                    $ret_array['error']	=	'error';
                    $ret_array['msg']	=	'数据保存出错';
                    break;
                }
                $ret_array['ret']	=	0;
                $ret_array['data']	=	array('ispostbacked'=>$ispostbacked, 'postback'=>$postback);

            }catch (Exception $e){
                $ret_array['ret']	=	100;
                $ret_array['msg']	=	'服务器忙，请稍后再试';
                $ret_array['error']	=	$e->getMessage();
                break;
            }
        }while (0);
        Common::toTxt(array('file'=>'Log_ApiController_actionOfferBackData.txt', 'txt'=>'Input:'.var_export($_REQUEST, true).'|Output:'.var_export($ret_array, true)));
        if(0 != $ret_array['ret']){
            echo 'fail';
        }else{
            if($ispostbacked && !empty($postback)){
                header("Location:$postback");
            }else{
                echo 'success';
            }
        }
    }
    /**
     * Here is a test function which tert the url
     */
    public function actionUrlTest(){
        $offer_id		=	Yii::app()->request->getParam('offer_id');
        $aff_id			=	Yii::app()->request->getParam('aff_id');
        $transactionid	=	Yii::app()->request->getParam('test', '1');

        if (empty($offer_id) || empty($aff_id)){
            echo 'offer_id 和 aff_id 为空';
            exit;
        }
        $offer	=	joy_offers::model()->findByPk($offer_id);
        $aff	=	JoySystemUser::model()->findByPk($aff_id);
        if(empty($offer) || empty($aff)){
            echo '相关数据为空，测试失败！';
            exit;
        }
        $postback	=	$aff['postback'];
        $postback	=	str_replace('{aff_sub}', $transactionid, $postback);
        $postback	=	str_replace('{payout}', '', $postback);
        if(!empty($postback)){
            Common::curlGet(array('url'=>$postback));
        }
        echo 'offer_id:'.$offer_id."<br>";
        echo 'aff_id:'.$aff_id.'<br>';
        echo 'postback:'.$postback.'<br>';
    }

    private static function OfferUrlReplace($params=array()){
        $url			=	isset($params['url']) ? $params['url'] : '';
        $transaction_id	=	isset($params['transaction_id']) ? $params['transaction_id'] : '';
        $affid			=	isset($params['affid']) ? $params['affid'] : '';
        $search			=	isset($params['search']) ? $params['search'] : '';

        $url			=	str_replace('{transaction_id}', $transaction_id, $url);
        $url			=	str_replace('{publisher_id}', $affid, $url);
        $url			=	str_replace('{affid}', $affid, $url);
        $url			=	str_replace('{channel}', $affid, $url);
        $url			= str_replace('{search}',$search,$url);
        return $url;
    }
}
