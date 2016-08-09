<?php
class CdController extends Controller{

		public function actionOfferClick(){
		$params['offer_id']	=	Yii::app()->request->getParam('offer_id');
		$params['aff_id']		=	Yii::app()->request->getParam('aff_id');
		$params['aff_sub']	=	Yii::app()->request->getParam('aff_sub');
		$params['subid']		=	Yii::app()->request->getParam('subid');
		$params['query']	=  Yii::app()->request->getParam('search');
		$params['campaign_id']	=	Yii::app()->request->getParam('campaign_id');
		$key = md5(strtotime(date('His')));
		Yii::app()->cache->set($key,json_encode($params,true),500);
		$this->renderPartial('/api/checkNetwork',array('key'=>$key));
	}
    public function actionOfferClick1() {
    	$ret_array		=	array('ret'=>-1, 'msg'=>'', 'occur'=>'ApiController_actionOfferClick', 'error'=>'', 'data'=>'');
    	$default_offerid = 75942;
		$default_affid = 1;

		$default_url = 'http://offer2.joymedia.mobi/index.php?r=api/offerclick&offer_id=33270&aff_id=38';
		$ispostbacked	=	0;
    	$postback		=	'';
    	do{
    		try{
    			$offer_id	=	Yii::app()->request->getParam('offer_id');
    			$aff_id		=	Yii::app()->request->getParam('aff_id');
    			$aff_sub	=	Yii::app()->request->getParam('aff_sub');
    			$subid		=	Yii::app()->request->getParam('subid');
				$query	=  Yii::app()->request->getParam('search');

				if(empty($aff_id)){
					$aff_id = $default_affid;
				}
				if(empty($offer_id)){
					$offer_id = $default_offerid;
				}
				//we should find the first,if not then set it default value
				$offers = joy_offers::model()->findByPk($offer_id);
				if(empty($offers)) {
					$offer_id = $default_offerid;
				}

				if($aff_id != $default_affid){
					$aff = JoySystemUser::model()->findByPk($aff_id);
					if(empty($aff)){
						$aff_id = $default_affid;
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
							$ipip = new ipip();
							$ip = $ipip->getIP();
							$country_arr = $ipip->find($ip);
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
				//transaction_id
				if(empty($aff_sub)){
					$randchar	=	Common::GetRandChar(array('count'=>6, 'type'=>3));
					$aff_sub	=	date('YmdHis').$offer_id.$randchar;
				}

		/*		//为其添加缓存
				$memcache = new Memcache();
				$memcache->connect('127.0.0.1',11211);
				$key = "offer2" . $offer_id;
				$offers = $memcache->get($key);
				if(empty($offers)){
					$offers = joy_offers::model()->findByPk($offer_id);
					if(empty($offers)){
						$offers = joy_offers::model()->findByPk(56445);
					}
					$memcache->add($key,$offers);
				}*/
				$click_str	=	$aff_id.'_'.$aff_sub.'_'.$offer_id;//	14_testbattery2_3
				$curr_date			=	date('Y-m-d');
				$expiration_date	=	isset($offers->expiration_date) ? $offers->expiration_date : '';

				if(0 === $offers->status || (!empty($expiration_date) && $curr_date > $expiration_date)){
    				$re_offer = joy_offers::model()->findByPk($offers->redirect_offer_id);
    				if(empty($re_offer)){
						$re_offer = joy_offers::model()->findByPk($default_offerid);
    				}
    				$transaction				=	new JoyTransaction();
    				$transaction->offerid		=	$re_offer->redirect_offer_id;//refer offer id
    				$transaction->advid			=	$re_offer->advertiser_id;
    				$transaction->affid			=	$aff_id;
    				$transaction->transactionid	=	$aff_sub;
    				$transaction->aff_subid		=	$subid;
					$transaction->campaign_id	= $campaign_id;
    				$transaction->ip			=	Common::getIp();
    				$transaction->createtime	=	date('Y-m-d H:i:s',time());
    				$transaction->createtime2	=	gmdate('Y-m-d H:i:s');
    				$res	=	$transaction->save();
    				if(!$res){
    					$ret_array['ret']	=	10;
    					$ret_array['msg']	=	'插入refer error';
    					$ret_array['error']	=	'error!';
    				}
    				$offer_url			=	self::OfferUrlReplace( array('url'=>$re_offer->offer_url, 'transaction_id'=>$click_str, 'affid'=>$aff_id,'search'=>$query));
    				$ret_array['ret']	=	0;
    				$ret_array['data']	=	$offer_url;
    			}

    			if(isset($offers->caps) && 1 == $offers->caps){
					$caps = JoyOffersCaps::model()->findByAttributes(array(
    						'offer_id'=>$offer_id
    				));
    				if(!empty($caps)){
    					$is_to_ref	=	false;

	    				$daily_con = $caps->daily_con;
	    				$month_con = $caps->month_con;
	    				$daily_pay = $caps->daily_pay;
	    				$month_pay = $caps->month_pay;
	    				$daily_rev = $caps->daily_rev;
	    				$month_rev = $caps->month_rev;
	    				$curr_date	=	date('Y-m-d');
	    				$connection	=	Yii::app()->db;
	    				$trand_con_sql = 'SELECT COUNT(*) as trand_con FROM joy_transaction_income WHERE offerid='.$offer_id.' AND DATE(createtime) = "'.$curr_date.'"';//计算转化的数量
	    				$trand_pay_sql = 'SELECT SUM(payout) as trand_pay FROM joy_transaction_income WHERE offerid='.$offer_id.' AND DATE(createtime) = "'.$curr_date.'"';
	    				$trand_rev_sql = 'SELECT SUM(revenue) as trand_rev FROM joy_transaction_income WHERE offerid='.$offer_id.' AND DATE(createtime) = "'.$curr_date.'"';
	    				$tranm_con_sql = 'SELECT COUNT(*) as tranm_con FROM joy_transaction_income WHERE offerid='.$offer_id.' AND DATE_FORMAT( createtime, "%Y%m" ) = DATE_FORMAT( CURDATE( ) , "%Y%m" )';//计算转化的数量
	    				$tranm_pay_sql = 'SELECT SUM(payout) as tranm_pay FROM joy_transaction_income WHERE offerid='.$offer_id.' AND DATE_FORMAT( createtime, "%Y%m" ) = DATE_FORMAT( CURDATE( ) , "%Y%m" )';
	    				$tranm_rev_sql = 'SELECT SUM(revenue) as tranm_rev FROM joy_transaction_income WHERE offerid='.$offer_id.' AND DATE_FORMAT( createtime, "%Y%m" ) = DATE_FORMAT( CURDATE( ) , "%Y%m" )';
	    				if(false == $is_to_ref && $daily_con > 0){
	    					$trand_con	=	$connection->createCommand($trand_con_sql);
		    				$trand_con	=	$trand_con->queryRow();
		    				$trand_con	=	$trand_con['trand_con'];

		    				if($trand_con >= $daily_con){
		    					$is_to_ref	=	true;
		    				}
	    				}

	    				if(false == $is_to_ref && $daily_pay > 0){
	    					$trand_pay	=	$connection->createCommand($trand_pay_sql);
		    				$trand_pay	=	$trand_pay->queryRow();
		    				$trand_pay	=	$trand_pay['trand_pay'];

		    				if($trand_pay >= $daily_pay){
		    					$is_to_ref	=	true;
		    				}
	    				}
	    				if(false == $is_to_ref && $daily_rev > 0){
	    					$trand_rev	=	$connection->createCommand($trand_rev_sql);
		    				$trand_rev	=	$trand_rev->queryRow();
		    				$trand_rev	=	$trand_rev['trand_rev'];

		    				if($trand_rev >= $daily_rev){
		    					$is_to_ref	=	true;
		    				}
	    				}
	    				if(false == $is_to_ref && $month_con > 0){
	    					$tranm_con	=	$connection->createCommand($tranm_con_sql);
		    				$tranm_con	=	$tranm_con->queryRow();
		    				$tranm_con	=	$tranm_con['tranm_con'];

		    				if($tranm_con >= $month_con){
		    					$is_to_ref	=	true;
		    				}
	    				}

	    				if(false == $is_to_ref && $month_pay > 0){
		    				$tranm_pay	=	$connection->createCommand($tranm_pay_sql);
		    				$tranm_pay	=	$tranm_pay->queryRow();
		    				$tranm_pay	=	$tranm_pay['tranm_pay'];

		    				if($tranm_pay >= $month_pay){
		    					$is_to_ref	=	true;
		    				}
	    				}

	    				if(false == $is_to_ref && $month_rev > 0){
		    				$tranm_rev = $connection->createCommand($tranm_rev_sql);
		    				$tranm_rev = $tranm_rev->queryRow();
		    				$tranm_rev = $tranm_rev['tranm_rev'];

		    				if($tranm_rev >= $month_rev){
		    					$is_to_ref	=	true;
		    				}
	    				}
	    				if(true == $is_to_ref && !empty($offers->redirect_offer_id)){
	    					$re_offer = joy_offers::model()->findByPk($offers->redirect_offer_id);
	    					if(empty($re_offer)){
	    						$ret_array['ret']	=	10;
	    						$ret_array['msg']	=	'This refer offer is not exist3';
	    						$ret_array['error']	=	'refer offerid:'.$offer_id.', is not exist3';
	    					}
	    					$transaction				=	new JoyTransaction();
	    					$transaction->offerid		=	$offers->redirect_offer_id;//refer offer id
	    					$transaction->advid			=	$re_offer->advertiser_id;
	    					$transaction->affid			=	$aff_id;
	    					$transaction->transactionid	=	$aff_sub;
	    					$transaction->aff_subid		=	$subid;
							$transaction->campaign_id = $campaign_id;
	    					$transaction->ip			=	Common::getIp();
	    					$transaction->createtime	=	date('Y-m-d H:i:s',time());
	    					$transaction->createtime2	=	gmdate('Y-m-d H:i:s');
	    					$res	=	$transaction->save();
	    					if(!$res){
	    						$ret_array['ret']	=	10;
	    						$ret_array['msg']	=	'Server error!';
	    						$ret_array['error']	=	'refer offer点击存入数据库失败';
	    						break;
	    					}

	    					$offer_url			=	self::OfferUrlReplace( array('url'=>$re_offer->offer_url, 'transaction_id'=>$click_str, 'affid'=>$aff_id,'search'=>$query) );
	    					$ret_array['ret']	=	0;
	    					$ret_array['data']	=	$offer_url;
	    					break;
	    				}
    				}
    			}
    			$transaction				=	new JoyTransaction();
    			$transaction->offerid		=	$offer_id;
    			$transaction->advid			=	$offers->advertiser_id;
    			$transaction->affid			=	intval($aff_id);
    			$transaction->transactionid	=	$aff_sub;
    			$transaction->aff_subid		=	$subid;
				$transaction->campaign_id	= $campaign_id;
    			$transaction->ip			=	Common::getIp();
    			$transaction->createtime	=	date('Y-m-d H:i:s',time());
    			$transaction->createtime2	=	gmdate('Y-m-d H:i:s');
    			$res	=	$transaction->save();
				$offer_url			=	self::OfferUrlReplace( array('url'=>$offers->offer_url, 'transaction_id'=>$click_str, 'affid'=>$aff_id,'search'=>$query) );
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
		$offer_url	=	isset($offer_url) ? $offer_url : $default_url;
		header("Location:$offer_url");
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

	//Test the offer jump,is not a security function,it should be removed.
	public function actionTestAdJump(){
		$ret_array		=	array('ret'=>-1, 'msg'=>'', 'occur'=>'ApiController_actionOfferClick', 'error'=>'', 'data'=>'');
		$default_offerid = 56445;
		$default_affid = 1;
		$default_url = 'http://offer2.joymedia.mobi/index.php?r=api/offerclick&offer_id=33270&aff_id=38';
		$ispostbacked	=	0;
		$postback		=	'';
		do{
			try{
				$offer_id	=	Yii::app()->request->getParam('offer_id');
				$aff_id		=	Yii::app()->request->getParam('aff_id');
				$aff_sub	=	Yii::app()->request->getParam('aff_sub');
				$subid		=	Yii::app()->request->getParam('subid');
				$query	=  Yii::app()->request->getParam('search');

				if(!empty($aff_id)){
					$aff = JoySystemUser::model()->findByPk($aff_id);
					if(empty($aff)){
						$aff_id = $default_affid;
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
							$ipip = new ipip();
							$ip = $ipip->getIP();
							$country_arr = $ipip->find($ip);
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

				//特殊用户增加字段
				$campaign_id	=	Yii::app()->request->getParam('campaign_id');
				if(empty($offer_id) || empty($aff_id)){
					//如果全部没有则跳转到默认的offer
					$offer_id = $default_offerid;
					$aff_id = $default_affid;
				}
				//transaction_id
				if(empty($aff_sub)){
					$randchar	=	Common::GetRandChar(array('count'=>6, 'type'=>3));
					$aff_sub	=	date('YmdHis').$offer_id.$randchar;
				}

				//PHP7暂且不支持缓存，先关闭
				/*		//为其添加缓存
                        $memcache = new Memcache();
                        $memcache->connect('127.0.0.1',11211);
                        $key = "offer2" . $offer_id;
                        $offers = $memcache->get($key);
                        if(empty($offers)){
                            $offers = joy_offers::model()->findByPk($offer_id);
                            if(empty($offers)){
                                $offers = joy_offers::model()->findByPk(56445);
                            }
                            $memcache->add($key,$offers);
                        }*/
				$offers = joy_offers::model()->findByPk($offer_id);
				$click_str	=	$aff_id.'_'.$aff_sub.'_'.$offer_id;//	14_testbattery2_3
				if(empty($offers)) {
					$offers = joy_offers::model()->findByPk($default_offerid);
				}
				//offer状态、有效期检查start
				$curr_date			=	date('Y-m-d');
				$expiration_date	=	isset($offers->expiration_date) ? $offers->expiration_date : '';
				if(0 === $offers->status || (!empty($expiration_date) && $curr_date > $expiration_date)){
					//跳转到offer的ref_id下对应的offer的链接中去(符合限制条件)
					$re_offer = joy_offers::model()->findByPk($offers->redirect_offer_id);
					if(empty($re_offer)){
						$offers = joy_offers::model()->findByPk($default_offerid);
					}
					//插入refer offer的点击
					$transaction				=	new JoyTransaction();
					$transaction->offerid		=	$offers->redirect_offer_id;//refer offer id
					$transaction->advid			=	$re_offer->advertiser_id;
					$transaction->affid			=	$aff_id;
					$transaction->transactionid	=	$aff_sub;
					$transaction->aff_subid		=	$subid;
					$transaction->campaign_id	= $campaign_id;
					$transaction->ip			=	Common::getIp();
					$transaction->createtime	=	date('Y-m-d H:i:s',time());
					$transaction->createtime2	=	gmdate('Y-m-d H:i:s');
					$res	=	$transaction->save();
					if(!$res){
						$ret_array['ret']	=	10;
						$ret_array['msg']	=	'插入refer error';
						$ret_array['error']	=	'error!';
					}
					$offer_url			=	self::OfferUrlReplace( array('url'=>$re_offer->offer_url, 'transaction_id'=>$click_str, 'affid'=>$aff_id,'search'=>$query));
					$ret_array['ret']	=	0;
					$ret_array['data']	=	$offer_url;
				}
				//offer状态、有效期检查end

				//caps有限制 start
				if(isset($offers->caps) && 1 == $offers->caps){
					$caps = JoyOffersCaps::model()->findByAttributes(array(
							'offer_id'=>$offer_id
					));
					if(!empty($caps)){
						$is_to_ref	=	false;//是否需要跳转到ref offer

						//offer里面的各种限制条件
						$daily_con = $caps->daily_con;
						$month_con = $caps->month_con;
						$daily_pay = $caps->daily_pay;
						$month_pay = $caps->month_pay;
						$daily_rev = $caps->daily_rev;
						$month_rev = $caps->month_rev;
						//joy_transaction_income表里面的各种点击数据
						$curr_date	=	date('Y-m-d');
						$connection	=	Yii::app()->db;
						//按天统计
						$trand_con_sql = 'SELECT COUNT(*) as trand_con FROM joy_transaction_income WHERE offerid='.$offer_id.' AND DATE(createtime) = "'.$curr_date.'"';//计算转化的数量
						$trand_pay_sql = 'SELECT SUM(payout) as trand_pay FROM joy_transaction_income WHERE offerid='.$offer_id.' AND DATE(createtime) = "'.$curr_date.'"';
						$trand_rev_sql = 'SELECT SUM(revenue) as trand_rev FROM joy_transaction_income WHERE offerid='.$offer_id.' AND DATE(createtime) = "'.$curr_date.'"';
						//按月统计
						$tranm_con_sql = 'SELECT COUNT(*) as tranm_con FROM joy_transaction_income WHERE offerid='.$offer_id.' AND DATE_FORMAT( createtime, "%Y%m" ) = DATE_FORMAT( CURDATE( ) , "%Y%m" )';//计算转化的数量
						$tranm_pay_sql = 'SELECT SUM(payout) as tranm_pay FROM joy_transaction_income WHERE offerid='.$offer_id.' AND DATE_FORMAT( createtime, "%Y%m" ) = DATE_FORMAT( CURDATE( ) , "%Y%m" )';
						$tranm_rev_sql = 'SELECT SUM(revenue) as tranm_rev FROM joy_transaction_income WHERE offerid='.$offer_id.' AND DATE_FORMAT( createtime, "%Y%m" ) = DATE_FORMAT( CURDATE( ) , "%Y%m" )';
						//按天的结果
						if(false == $is_to_ref && $daily_con > 0){
							//查询当天转化是否大于限制数
							$trand_con	=	$connection->createCommand($trand_con_sql);
							$trand_con	=	$trand_con->queryRow();
							$trand_con	=	$trand_con['trand_con'];

							if($trand_con >= $daily_con){
								$is_to_ref	=	true;
							}
						}

						if(false == $is_to_ref && $daily_pay > 0){
							//查询当天转化支出是否大于限制数
							$trand_pay	=	$connection->createCommand($trand_pay_sql);
							$trand_pay	=	$trand_pay->queryRow();
							$trand_pay	=	$trand_pay['trand_pay'];

							if($trand_pay >= $daily_pay){
								$is_to_ref	=	true;
							}
						}
						if(false == $is_to_ref && $daily_rev > 0){
							//查询当天转化收入是否大于限制数
							$trand_rev	=	$connection->createCommand($trand_rev_sql);
							$trand_rev	=	$trand_rev->queryRow();
							$trand_rev	=	$trand_rev['trand_rev'];

							if($trand_rev >= $daily_rev){
								$is_to_ref	=	true;
							}
						}
						//按月的结果
						if(false == $is_to_ref && $month_con > 0){
							//查询当月转化是否大于限制数
							$tranm_con	=	$connection->createCommand($tranm_con_sql);
							$tranm_con	=	$tranm_con->queryRow();
							$tranm_con	=	$tranm_con['tranm_con'];

							if($tranm_con >= $month_con){
								$is_to_ref	=	true;
							}
						}

						if(false == $is_to_ref && $month_pay > 0){
							//查询当月转化支出是否大于限制数
							$tranm_pay	=	$connection->createCommand($tranm_pay_sql);
							$tranm_pay	=	$tranm_pay->queryRow();
							$tranm_pay	=	$tranm_pay['tranm_pay'];

							if($tranm_pay >= $month_pay){
								$is_to_ref	=	true;
							}
						}

						if(false == $is_to_ref && $month_rev > 0){
							//查询当月转化收入是否大于限制数
							$tranm_rev = $connection->createCommand($tranm_rev_sql);
							$tranm_rev = $tranm_rev->queryRow();
							$tranm_rev = $tranm_rev['tranm_rev'];

							if($tranm_rev >= $month_rev){
								$is_to_ref	=	true;
							}
						}
						//下面指该点击不符合点击条件
						if(true == $is_to_ref && !empty($offers->redirect_offer_id)){
							//跳转到offer的ref_id下对应的offer的链接中去(符合限制条件)
							$re_offer = joy_offers::model()->findByPk($offers->redirect_offer_id);
							if(empty($re_offer)){
								$ret_array['ret']	=	10;
								$ret_array['msg']	=	'This refer offer is not exist3';
								$ret_array['error']	=	'refer offerid:'.$offer_id.', is not exist3';
							}
							//插入refer offer的点击
							$transaction				=	new JoyTransaction();
							$transaction->offerid		=	$offers->redirect_offer_id;//refer offer id
							$transaction->advid			=	$re_offer->advertiser_id;
							$transaction->affid			=	$aff_id;
							$transaction->transactionid	=	$aff_sub;
							$transaction->aff_subid		=	$subid;
							$transaction->campaign_id = $campaign_id;
							$transaction->ip			=	Common::getIp();
							$transaction->createtime	=	date('Y-m-d H:i:s',time());
							$transaction->createtime2	=	gmdate('Y-m-d H:i:s');
							$res	=	$transaction->save();
							if(!$res){
								$ret_array['ret']	=	10;
								$ret_array['msg']	=	'Server error!';
								$ret_array['error']	=	'refer offer点击存入数据库失败';
								break;
							}

							$offer_url			=	self::OfferUrlReplace( array('url'=>$re_offer->offer_url, 'transaction_id'=>$click_str, 'affid'=>$aff_id,'search'=>$query) );
							$ret_array['ret']	=	0;
							$ret_array['data']	=	$offer_url;
							break;

						}
						//该offer通过限制继续执行
					}
					//该offer, caps表中无数据，继续执行
				}
				//caps有限制 end
				//无caps限制或者通过caps限制
				$transaction				=	new JoyTransaction();
				$transaction->offerid		=	$offer_id;
				$transaction->advid			=	$offers->advertiser_id;
				$transaction->affid			=	intval($aff_id);
				$transaction->transactionid	=	$aff_sub;
				$transaction->aff_subid		=	$subid;
				$transaction->campaign_id	= $campaign_id;
				$transaction->ip			=	Common::getIp();
				$transaction->createtime	=	date('Y-m-d H:i:s',time());
				$transaction->createtime2	=	gmdate('Y-m-d H:i:s');
				$res	=	$transaction->save();
				$offer_url			=	self::OfferUrlReplace( array('url'=>$offers->offer_url, 'transaction_id'=>$click_str, 'affid'=>$aff_id,'search'=>$query) );
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
		if($ret_array['ret'] != 0){
			Common::toTxt(array('file'=>'Log_ApiController_actionOfferClick.txt', 'txt'=>'Input:'.var_export($_REQUEST, true).'|Output:'.var_export($ret_array, true)));
		}
		$offer_url	=	isset($offer_url) ? $offer_url : $default_url;
		echo $offer_url;
		var_dump($ret_array['error']);
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
