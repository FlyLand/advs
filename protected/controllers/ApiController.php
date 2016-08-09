<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/5/4
 * Time: 11:23
 */
require '/ddata/nginx/www/workerman/Applications/Statistics/Clients/StatisticClient.php';
class ApiController extends Controller
{
    public $ret_array	=	array('ret'=>-1, 'msg'=>'', 'occur'=>'', 'error'=>'');
    public function getErrorCode(){
        return $this->ret_array['ret'];
    }
    public function getErrorMsg(){
        return $this->ret_array['msg'];
    }
    public function actionClick(){
        StatisticClient::tick("Api", 'Click');
        $success = true; $code = 0; $msg = '';
        $info = $this->clickIm();
        if(!$info){
            $success = false;
            $code = $this->getErrorCode();
            $msg = $this->getErrorMsg();
        }
        StatisticClient::report('User', 'getInfo', $success, $code, $msg);
    }
    public function clickIm() {
        $default_offer = joy_offers::model()->findByPk(DEFAULT_OFFER_ID);
        do{
            try{
                //get the params
                $original_offer_id	=	Yii::app()->request->getParam('oid');
                $original_aff_id		=	Yii::app()->request->getParam('ffid');
                $aff_sub	=	Yii::app()->request->getParam('clickid');
                $subid		=	Yii::app()->request->getParam('f_sub');
                $query	=  Yii::app()->request->getParam('search');
                $query = urldecode($query);

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

                $url_this = $_SERVER['SERVER_NAME'];
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

                $click_str	=	$original_aff_id.'_'.$aff_sub.'_'.$offer_id;//	14_testbattery2_3
                //if(1 == $aff_id){
                 //   $publish_id = Common::getPublisherId($aff_id,$country_arr[0],$offer_id);
                  //  $params = array('url'=>$offers->offer_url, 'transaction_id'=>$click_str, 'affid'=>$publish_id,'search'=>$query);
                //}else{
                    $params = array('url'=>$offers->offer_url, 'transaction_id'=>$click_str, 'affid'=>$aff_id,'search'=>$query);
                //}
                $offer_url			=	self::OfferUrlReplace($params);
                $transaction				=	new JoyTransaction();
                $insert_params['offerid'] = $transaction->offerid		=	$offer_id;
                $insert_params['advid'] = $transaction->advid			=	$offers->advertiser_id;
                $insert_params['original_affid'] = $transaction->original_affid = $original_aff_id;
                $insert_params['original_offerid'] = $transaction->original_offerid = $original_offer_id;
                $insert_params['affid'] = $transaction->affid			=	intval($aff_id);
                $insert_params['transactionid'] = $transaction->transactionid	=	$aff_sub;
                $insert_params['aff_subid'] = $transaction->aff_subid		=	$subid;
                $insert_params['campaign_id'] = $transaction->campaign_id	= $campaign_id;
                $insert_params['country'] = $transaction->country       = $country_arr[0];
                $insert_params['ip'] = $transaction->ip			=	$ip;
                $insert_params['createtime'] = $transaction->createtime	=	date('Y-m-d H:i:s',time());
                $insert_params['createtime2'] = $transaction->createtime2	=	gmdate('Y-m-d H:i:s');
                if(empty($query)){
	                $insert_params['offer_url'] = $transaction->offer_url     = (string)$offer_url;
		}
//                $insert_params['net_type'] = $transaction->net_type = $network_type;
//                $handle = new SqlHandle2(Yii::app()->redis_cache,'sql_cache','appdown');
//                $sql = $handle->createSql(null,'joy_transaction_redis',$insert_params,'insert');
//                $handle->save_to_redis_one($sql);
                $res	=	$transaction->save();
                if(!$res){
                    $this->ret_array['ret']	=	10;
                    $this->ret_array['msg']	=	$transaction->errors;
                    $this->ret_array['error']	=	'点击存入数据库失败';
                    break;
                }
                $this->ret_array['ret']	=	0;
                $this->ret_array['data']	=	$offer_url;
            }catch (Exception $e){
                $this->ret_array['ret']	=	100;
                $this->ret_array['msg']	=	'服务器忙，请稍后再试';
                $this->ret_array['error']	=	$e->getMessage();
                break;
            }
        }while (0);
        $offer_url	=	isset($offer_url) ? $offer_url : $default_offer->offer_url;
        header("Location:$offer_url");
        if($this->ret_array['ret'] != 0){
            Common::toTxt(array('file'=>'Log_ApiController_actionOfferClick.txt', 'txt'=>'Input:'.var_export($_REQUEST, true).'|Output:'.var_export($this->ret_array, true)));
            return false;
        }else{
            return true;
        }
    }

    public function actionAdsBack(){
        $ispostbacked	=	0;
        $postback		=	'';
        $belong = 1;
        do{
            try{
                $click_str	=	Yii::app()->request->getParam('transaction_id');
                $clientip	=	Yii::app()->request->getParam('clientip');
                $country = Yii::app()->request->getParam('cou');
                $carrier = Yii::app()->request->getParam('car');
                $am = Yii::app()->request->getParam('revenue'); //this is the revenue params
                $platform = Yii::app()->request->getParam('pla');
                $kimia_id = Yii::app()->request->getParam('kimia_id');
                if(empty($click_str)){
                    $this->ret_array['ret']	=	2;
                    $this->ret_array['error']	 .=	'缺失click_str;';
                    $affid = 1;
                    $clickid = 0;
                    $offerid = 1;
                    $belong = 0;
                }else{
                    $endPoint = strrpos($click_str,'_');
                    $sPoint = strpos($click_str,'_');
		            $affid = substr($click_str,0,$sPoint);
                    $clickid = substr($click_str,$sPoint+1,$endPoint - $sPoint - 1);
                    $offerid = substr($click_str,$endPoint+1);
                }
                $transaction	=	JoyTransaction::model()->findByAttributes(array('transactionid'=>$clickid, 'original_affid'=>$affid, 'original_offerid'=>$offerid));
                if(empty($transaction)){
                    $this->ret_array['ret']	=	9;
                    $this->ret_array['msg']	=	'This transactionid is not exist5';
                    $this->ret_array['error']	.=	'transactionid:'.$clickid.', is not exist;';
                    $belong = 0;
                }else{
                    $aff_subid		=	$transaction->aff_subid;
                    $campaign_id	= 	$transaction->campaign_id;
                }
                $offers = joy_offers::model()->findByPk($offerid);
                if(empty($offers)){
                    $this->ret_array['ret']	=	10;
                    $this->ret_array['msg']	=	'This offer is not exist6';
                    $this->ret_array['error']	.=	'offerid:'.$offerid.', is not exist6;';
                    $belong = 0;
                }
                $offers = Common::instantPayout($offers,$affid);
                $serverip			=	Common::getIp();
                if(isset($offers['enable_offer_whitelist']) && 1 == $offers['enable_offer_whitelist']){
                    $ip_list = JoyOfferWhitelist::model()->findAllByAttributes(array(
                        'offerid'=>$offerid,
                        'status'=>1
                    ));
//					}
                    if(!empty($ip_list) && !in_array($serverip, $ip_list)){
                        $this->ret_array['ret']	=	8;
                        $this->ret_array['msg']	=	'ip address error';
                        $this->ret_array['error']	=	'ip:'.$serverip.' is not in whitelist';
                        $belong = 0;
                    }
                }
                if(!empty($offers)){
                    $revenue	=	$offers['revenue'];
                    $payout		=	$offers['payout'];
                }else{
                    $payout = 0;
                    $revenue = 1;
                    $offers['advertiser_id'] = 1;
                }
                $advertises = JoySystemUser::model()->findByPk($offers['advertiser_id']);
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
                $transaction_income->affid			=	$affid;
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
                $transaction_income->error = $this->ret_array['error'];
                $cut_num	=	rand(1, 100);
                $cut_num2 = rand(1,100);
                $transaction_income->cut_num		=	$cut_num;
                if(empty($cut) || empty($cut['cut_num']) || $cut['cut_num'] == 0){
                    $offer_cut_num = 0;
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
					$postback	=	str_replace('{offerid}', $offerid, $postback);
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
                    $this->ret_array['ret']	=	6;
                    $this->ret_array['error']	=	'error';
                    $this->ret_array['msg']	=	'数据保存出错';
                  
                    break;
                }
                $this->ret_array['ret']	=	0;
                $this->ret_array['data']	=	array('ispostbacked'=>$ispostbacked, 'postback'=>$postback);

            }catch (Exception $e){
                $this->ret_array['ret']	=	100;
                $this->ret_array['msg']	=	'服务器忙，请稍后再试';
                $this->ret_array['error']	=	$e->getMessage();
                break;
            }
        }while (0);
        Common::toTxt(array('file'=>'Log_ApiController_actionOfferBackData.txt', 'txt'=>'Input:'.var_export($_REQUEST, true).'|Output:'.var_export($this->ret_array, true)));
        if(0 != $this->ret_array['ret']){
            echo 'fail';
        }else{
	    if(empty($belong) || empty($postback)){
                $ispostbacked = 0;
            }
            if($ispostbacked == 1){
                $result = Common::curlGet(array('url'=>$postback));
                var_dump($result);
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