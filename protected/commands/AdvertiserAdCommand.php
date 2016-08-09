<?php
/**
 * 更新上游广告
 */
class AdvertiserAdCommand extends CConsoleCommand {
	private $logpre		=	'';		//日志文件前缀部分
	
	/**
	 * 构造函数，在New对象的时候自动调用
	 */
	public function init(){
		ini_set('memory_limit','512M');
		$this->logpre	=	'';
	}

	/**
	 * 更新用户信息主逻辑
	 */
	public function mobilecore(){
		$ret_array	=	array('ret'=>1, 'msg'=>'', 'occur'=>'AdvertiserAdCommand_mobilecore', 'error'=>'', 'data'=>array());
		do{
			try{
				$tpl		=	'mobilecore';
				$upper_info	=	require BASE_DIR.'/protected/config/advertiseradconf.php';
				if( !$data_info = isset($upper_info[$tpl]) ){
					$ret_array['ret']	=	1;
					$ret_array['msg']	=	'没有广告主配置信息';
				}
				$url = $upper_info[$tpl]['url'];
				$advertiser_id	=	$upper_info[$tpl]['advertiser_id'];
				$data_re	=	Common::curlGet(array('url'=>$url));
				$data_arr = CfgAR::deJson($data_re);
				$connect    =   Yii::app()->db->beginTransaction();
				$campaign_str	=	'';
				if($data_arr['error'] !== 'false') {
					foreach ($data_arr['ads'] as $arr) {
						$offer_id = $arr['offer_id'];
						$campaign_id = $arr['campaign_id'];
						$title = $arr['title'];
						$platform = $arr['platform'];
						$description = $arr['description'];
						$revenue = $arr['bid'];
						$payout = $revenue * 0.75;
						$version = $arr['minOSVersion'];
						if(!empty($arr['creatives'])){
							$creatives = $arr['creatives'][0]['url'];
						}else{
							$creatives = '';
						}
						$targeting	=	'';
						if(!empty($arr['geoTargeting'])){
							$targeting = implode(",", $arr['geoTargeting']);
						}
						$size = $arr['packageSize'];
						$downloads = $arr['downloads'];
						$category = $arr['category'];
						$clickURL = $arr['clickURL'];
						$offer = joy_offers::model()->findByAttributes(array('campaign_id' => $campaign_id,'advertiser_id'=>$advertiser_id));
						$campaign_str	.=	$campaign_id . ',';
						if (!$offer) {
							$offer = new joy_offers();
							$offer->status = 1;
							$offer->createtime = date("Y-m-d H:i:s", time());
							$offer->revenue = $revenue;
							$offer->payout = $payout;
							$offer->name = $title;
							$offer->description = $description;
							$offer->offer_url = $clickURL;
							$offer->advertiser_id	=	$advertiser_id;
							//            $offer->preview_url = $impressionURL;
							//            $offer->offer_category = $category;
							$offer->thumbnail = $creatives;
							$offer->campaign_id = $campaign_id;
							$offer->geo_targeting	=	$targeting;
							if (!$offer->save()) {
								$connect->rollback();
								$ret_array['ret'] = 1;
								throw new ErrorException('create error');
							}
							$ret_array['ret'] = 0;
							$ret_array['msg'] = '创建成功';
						} else {
							$offer->revenue = $revenue;
							$offer->payout = $payout;
							$offer->name = $title;
							$offer->description = $description;
							$offer->offer_url = $clickURL;
							$offer->offer_category = $category;
							$offer->thumbnail = $creatives;
							$offer->status  =   1;
							$offer->advertiser_id	= $advertiser_id;
							$offer->geo_targeting	=	$targeting;

							if (!$offer->update()) {
								$connect->rollback();
								$ret_array['ret'] = 1;
								throw new ErrorException('create error');
							}
							$ret_array['ret'] = 0;
							$ret_array['msg'] = '创建成功';
						}
					}
					//更新api表，将已经过期的offer状态修改
					if(!empty($campaign_str)){
						$campaign_str = substr($campaign_str,0,-1);
					}
					if ($ret_array['ret'] == 0) {
						$connect->commit();
					}
					$sql	=	"update joy_offers set status = 0 WHERE advertiser_id = $advertiser_id AND campaign_id NOT IN ($campaign_str)";
					$rs     =   Yii::app()->db->createCommand($sql)->query();
				}else{
					$ret_array['ret']	=	1;
					$ret_array['msg']	=	$data_arr['error_message'];
				}
			}catch(Exception $e){
				$ret_array['ret']	=	13;
				$ret_array['msg']	=	'程序出现异常退出';
				$ret_array['error']	=	$e->getMessage();

				break;
			}
		}while(0);
		if( 0 != $ret_array['ret'] ){
			Common::toTxt(array('file'=>$this->logpre.'Log_AdvertiserAdCommand_mobilecore.txt', 'txt'=>'Output:'.var_export($ret_array, true)));
		}
		return $ret_array;
	}

	public function yeahmobi(){
		$tpl		=	'yeahmobi';
		$upper_info	=	require BASE_DIR.'/protected/config/advertiseradconf.php';
		if( !$data_info = isset($upper_info[$tpl]) ){
			throw new ErrorException('no advertiser');
		}
		$advertiser_id	=	$upper_info[$tpl]['advertiser_id'];
		$api_id =   $upper_info[$tpl]['api_id'];
		$psw =   $upper_info[$tpl]['psw'];
		$api_token  =   md5($psw);
		$page   =   1;
		$limit  =   '';
		do{
			$url    =   "http://sync.yeahmobi.com/sync/offer/get?api_id=$api_id&api_token=$api_token&limit=$limit&page=$page";
			$data   =   Common::curlGet(array('url'=>$url));
			$data_arr  =   json_decode($data,true);
			$connect    =   Yii::app()->db->beginTransaction();
			$campaign_str	=	'';
			$page_count	=	$data_arr['data']['totalpage'];
			try {
				if ($data_arr['flag'] === 'success') {
					foreach ($data_arr['data']['data'] as $val => $key) {
						$name = $key['name'];
						$preview_url = $key['preview_url'];
						$title = $key['title'];
						$offer_description = $key['offer_description'];
						$payout = $key['payout'];
						$revenue = $payout * 0.75;
						$campaign_str .= $val . ',';
						$category_arr = $key['category'];
						$category = '';
						foreach ($category_arr as $cate_key) {
							$category .= $cate_key . ',';
						}
						$categories = substr($category, 0, strlen($category) - 1);
						$country = '';
						$country_arr = $key['countries'];
						foreach ($country_arr as $country_key) {
							$country .= $country_key . ',';
						}
						$countries = substr($country, 0, strlen($country) - 1);
						$tracklink = $key['tracklink'];
						$offer = joy_offers::model()->findByAttributes(array('campaign_id'=>$val,'advertiser_id'=>$advertiser_id));

						if (empty($offer)) {
							$offer = new joy_offers();
							$offer->status = 1;
							$offer->createtime = date("Y-m-d H:i:s", time());
							$offer->revenue = $revenue;
							$offer->payout = $payout;
							$offer->name = $name;
							$offer->description = $offer_description;
							$offer->offer_url = $preview_url;
							$offer->advertiser_id	=	$advertiser_id;
							$offer->thumbnail = '';
							$offer->campaign_id = $val;
							$offer->geo_targeting	=	$countries;
							if(!$offer->save()){
								throw new ErrorException('server error insert!');
							}
						} else {
							$offer->status = 1;
							$offer->createtime = date("Y-m-d H:i:s", time());
							$offer->revenue = $revenue;
							$offer->payout = $payout;
							$offer->name = $name;
							$offer->description = $offer_description;
							$offer->offer_url = $preview_url;
							$offer->advertiser_id	=	$advertiser_id;
							$offer->thumbnail = '';
							$offer->campaign_id = $val;
							$offer->geo_targeting	=	$countries;
							if(!$offer->update()){
								throw new ErrorException('server update error!');
							}
						}
					}
					$sql	=	"update joy_offers set status = 0 WHERE advertiser_id = $advertiser_id AND campaign_id NOT IN ($campaign_str)";
					$rs     =   Yii::app()->db->createCommand($sql)->query();
					if($rs){
						throw new ErrorException('server error update');
					}
					$connect->commit();
					return 'success';
				}else{
					echo $data_arr['msg'];
					Common::toTxt(array('file'=>$this->logpre.'Log_AdvertiserAdCommand_mobilecore.txt', 'txt'=>'Output:'.var_export($data_arr['msg'], true)));
				}
			}catch (Exception $e){
				$connect->rollback();
			}
			$page++;
		}while($page < $page_count);
	}

	/**
	 * 入口函数，调用时传递残数
	 */
	public function run($argus){
		$ret_array	=	array('ret'=>1, 'msg'=>'', 'data'=>'');
		do{
			try{
				if( empty($argus) || 1 > count($argus) || empty($argus[0]) ){
					$ret_array['ret']	=	1;
					$ret_array['msg']	=	'调用参数错误';
					break;
				}

				$funname	=	$argus[0];//函数名称，mobilecore
				$ret_array['ret']	=	0;
				$ret_array['data']	=	$this->$funname();
			}catch(Exception $e){
				$ret_array['ret']	=	13;
				$ret_array['msg']	=	'程序出现异常退出';
				$ret_array['error']	=	$e->getMessage();
				break;
			}
		}while(0);
		if( 0 != $ret_array['ret'] ){
			var_dump($ret_array);
		}
	}

	/**
	 * 析构函数
	 */
	function __destruct(){
	}
}
?>