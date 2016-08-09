<?php
class ClickController extends Controller{
     
    /**
     * 获取到offer点击的次数
     */
    public function actionOfferClick() { 
    	$ret_array		=	array('ret'=>-1, 'msg'=>'', 'occur'=>'ClickController_actionOfferClick', 'error'=>'', 'data'=>array());
    	$ispostbacked	=	0;
    	$postback		=	'';
    	$execute_num_str		=	'';
    	do{
    		try{
    			$curr_date		=	date('Y-m-d');
    			

    			$curr_time		=	date('Y-m-d H:i:s');
    			$connection		=	Yii::app()->db;
    			 
    			$offer_sql		=	'SELECT * FROM joy_c_offers WHERE status=1 AND start_date<="'.$curr_time.'" AND end_date>"'.$curr_time.'"';
    			$offer_con		=	$connection->createCommand($offer_sql);
    			$offer_res		=	$offer_con->queryAll();
    			
    			if(empty($offer_res) || count($offer_res) < 1){
    				$ret_array['ret']	=	7;
    				$ret_array['msg']	=	'offer empty';
    				break;
    			}
    			//循环所有需要跑的offer
    			
    			foreach($offer_res as $val){
    				$num	=	(int)$val['max_total'] - (int)$val['execute_total'];
    				
    				$num_curr	=	0;
    				if($num < $val['hour_total']){//20分钟跑一次定时任务，所以除以6
    					$num_curr	=	ceil($num / 3);
    				}else{
    					$num_curr	=	ceil($val['hour_total'] / 3);
    				}
    				if($num_curr < 1){
    					continue;
    				}
    				
    				//查询offer信息
    				$offers = joy_offers::model()->findByPk($val['offerid']);
    				if(empty($offers)){
    					continue;
    				}
    				//offer状态、有效期检查start
    				$expiration_date	=	isset($offers->expiration_date) ? $offers->expiration_date : '';
    				if(0 == $offers->status || (!empty($expiration_date) && $curr_date > $expiration_date)){
    					continue;
    				}
    				
    				$execute_num_str	.=	'|offerid:'.$val['offerid'];
    				$tmp_i	=	0;
    				//循环点击
    				echo 'num_curr:'.$num_curr;
    				for($i=0; $i<$num_curr; $i++){
    					//无caps限制或者通过caps限制
    					$randchar	=	Common::GetRandChar(array('count'=>6, 'type'=>3));
    					$aff_sub	=	date('YmdHis').$val['offerid'].$randchar;
    					$click_str	=	$val['affid'].'_'.$aff_sub.'_'.$val['offerid'];//14_testbattery2_3
    					
    					$transaction				=	new JoyTransaction();
    					$transaction->offerid		=	$val['offerid'];
    					$transaction->advid			=	$offers->advertiser_id;
    					$transaction->affid			=	$val['affid'];
    					$transaction->transactionid	=	$aff_sub;
    					$transaction->type			=	1;
    					$transaction->ref_offerid	=	0;
    					$transaction->ip			=	Common::getIp();
    					$transaction->createtime	=	date('Y-m-d H:i:s',time());
    					$transaction->createtime2	=	gmdate('Y-m-d H:i:s');
    					$res	=	$transaction->save();
    					if(!$res){
    						//$ret_array['ret']	=	10;
    						//$ret_array['msg']	=	'Server Busy';
    						//$ret_array['error']	=	'点击存入数据库失败';
    						continue;
    					}
    					$offer_url			=	self::OfferUrlReplace( array('url'=>$offers->offer_url, 'transaction_id'=>$aff_sub, 'affid'=>$val['affid']) );
    					
    					//取IP
    					if(!empty($val['nation'])){
	    					$geo_arr		=	explode(',', $val['nation']);
	    					$rand			=	rand(0, count($geo_arr)-1);
	    					$geo_select_str	=	$geo_arr[$rand];
	    					$nation_sql		=	'SELECT ip FROM joy_c_ip WHERE nation="'.$geo_select_str.'" ORDER BY RAND() LIMIT 1';
	    				}else{
	    					$rand_ip	=	rand(1, 944372);//数据库中最大的ID，数据库中ID连续最好不要断，否则可能出现取不到
	    					$nation_sql	=	'SELECT ip FROM joy_c_ip WHERE id='.$rand_ip.' LIMIT 1';
    					}
    					$nation_con		=	$connection->createCommand($nation_sql);
    					$nation_res		=	$nation_con->queryRow();
    					$ip				=	isset($nation_res['ip']) ? $nation_res['ip'] : '';
    					if(empty($ip)){
    						//$ret_array['ret']	=	12;
    						//$ret_array['msg']	=	'ip error';
    						continue;
    					}
    					//取UA信息
    					$rand_ua		=	rand(1, 17720);//数据库中最大的ID，数据库中ID连续最好不要断，否则可能出现取不到
    					$ua_sql			=	'SELECT * FROM joy_c_useragent WHERE id='.$rand_ua;
    					$ua_con			=	$connection->createCommand($ua_sql);
    					$ua_res			=	$ua_con->queryRow();
    					if(empty($ua_res)){
    						//$ret_array['ret']	=	13;
    						//$ret_array['msg']	=	'not have data, ua id:'.$rand_ua;
    						continue;
    					}
    					//取referer信息
    					$rand_referer	=	rand(1, 32172);//数据库中最大的ID，数据库中ID连续最好不要断，否则可能出现取不到
    					$referer_sql	=	'SELECT * FROM joy_c_referer WHERE id='.$rand_referer;
    					$referer_con	=	$connection->createCommand($referer_sql);
    					$referer_res	=	$referer_con->queryRow();
    					if(empty($referer_res)){
    						//$ret_array['ret']	=	13;
    						//$ret_array['msg']	=	'not have data, referer id:'.$rand_referer;
    						continue;
    					}
    						
    					
    					$ret_array['ret']				=	0;
    					$ret_array['data']['url']		=	$offer_url;
    					 
    					$ret_array['data']['ua']		=	$ua_res['useragent'];
    					$ret_array['data']['referer']	=	$referer_res['referer'];
    					 
    					$headers					=	array();
    					$headers['CLIENT-IP']		=	$ip;
    					$headers['X-FORWARDED-FOR']	=	$ip;
    					$headers['X-FORWARDED']		=	$ip;
    					$headers['FORWARDED-FOR']	=	$ip;
    					$headers['FORWARDED']		=	$ip;
    					//$headers['REMOTE-ADDR']	=	$ip;
    					$headerArr	=	array();
    					foreach ($headers as $n => $v )
    					{
    						$headerArr[] = $n . ':' . $v;
    					}
    					$ret_array['data']['header']	=	$headerArr;
    					
    					$res	=	array();
    					$res	=	self::curlGet($ret_array['data']);
    					var_dump($res);
    					//$res['ret']	=	0;
    					if(0 == $res['ret']){
    						$update_sql	=	'UPDATE joy_c_offers SET execute_total=(execute_total + 1) WHERE id='.$val['id'];
    						$update_con	=	$connection->createCommand($update_sql);
    						$update_con->execute();//执行次数更新
    						
    						$tmp_i ++;
    					}
    					
    				}
    				$execute_num_str	.=	', num:'.$tmp_i;
    			}
    			
    			
    		}catch (Exception $e){
	        	$ret_array['ret']	=	100;
	        	$ret_array['msg']	=	'服务器忙，请稍后再试';
	        	$ret_array['error']	=	$e->getMessage();
	        	break;
	        }
        }while (0);
        //测试时放这里，正式可以关闭此处，打开0 !=
        Common::toTxt(array('file'=>'Log_ClickController_actionOfferClick.txt', 'txt'=>$execute_num_str));

		echo $execute_num_str;
    }
    
    private static function OfferUrlReplace($params=array()){
    	$url			=	isset($params['url']) ? $params['url'] : '';
    	$transaction_id	=	isset($params['transaction_id']) ? $params['transaction_id'] : '';
    	$affid			=	isset($params['affid']) ? $params['affid'] : '';
		
    	$url			=	str_replace('{transaction_id}', $transaction_id, $url);
    	$url			=	str_replace('{publisher_id}', $affid, $url);
    	$url			=	str_replace('{channel}', $affid, $url);
    	return $url;
    }
	/**
	 * 
	 * @param string $params
	 * @return mixed
	 */
    private static function curlGet($params = '') {
    	$ret_array = array('ret'=>-1, 'msg'=>'', 'occur'=>'ClickController_curlGet', 'error'=>'', 'data'=>'');
    	$loop	=	isset($params['loop']) ? $params['loop'] :1;
    	do{
    		try{
    			if( !is_array($params) || !isset($params['url']) ){
    				$ret_array['msg']	= 	'参数类型错误';
    				break;
    			}
    			$timeout	=	isset($params['timeout']) ? $params['timeout'] :5;
    	
		    	$url		=	trim($params['url']);//请求地址
		    	$ua			=	$params['ua'];
		    	$headerArr	=	$params['header'];
		    	$referer	=	$params['referer'];
		    	
		    	$curl = curl_init();
		    	curl_setopt($curl, CURLOPT_HEADER, 0);
		    	curl_setopt($curl, CURLOPT_USERAGENT, $ua);
		    	curl_setopt($curl, CURLOPT_HTTPHEADER, $headerArr);
		    	curl_setopt($curl, CURLOPT_REFERER, $referer); //构造访问来源
		    	curl_setopt($curl, CURLOPT_URL, $url);
		    	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		    	curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
		    	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		    	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
		    	//在需要用户检测的网页里需要增加下面两行
		    	//curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
		    	//curl_setopt($curl, CURLOPT_USERPWD, US_NAME.":".US_PWD);
		    	$data = curl_exec($curl);
		    	curl_close($curl);
		    	$data = str_replace("\r\n","",$data);
    			/*
    			if( 0 == strlen($data) ){
    				$ret_array['ret']	=	-2;
    				$ret_array['msg']	= 	'请求超时或没用响应';
    				$loop --;
    				continue;
    			}
    			*/
    			$ret_array['ret']		=	0;
    			$ret_array['data']		=	$data;
    			break;
    		}catch(Exception $e){
    			$ret_array['ret']		=	-2;
    			$ret_array['msg']		= 	'程序执行异常';
    			$ret_array['error']		= 	$e->getMessage();
    			break;
    		}
    	}while($loop > 0);
    	if( 0 != $ret_array['ret'] ){
    		Common::toTxt(array('file'=>'Log_ClickController_curlGet.txt', 'txt'=>$url.'|操作结果:'.var_export($ret_array, true)));
    	}
    	return $ret_array;
    }
    
}