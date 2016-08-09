<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/7/15
 * Time: 10:23
 */
class AdvertiserController extends Controller
{
    
	public function __construct(){
		parent::checkAction();
	}
	
	public function actionIndex(){
        $page			=	Yii::app()->request->getParam('page');
        $size			=	30;

        $data['count']	=	$count = JoySystemUser::model()->count('groupid=4');

        $jpurl			=	$this->createUrl('advertiser/index');
        $jparams		=	array();

        $page_obj			=	new Page();
        if( 0 < count($jparams) ){
            $tmp_str		=	strpos($jpurl, '?') ? '&' : '?';
            $jpurl			.=	$tmp_str.join('&', $jparams);
        }
        $page_control		=	$page_obj->pageCut($count,$page,$size);
        $data['page']		=	$page_control['page'];

        $query_count = $page_control['query_count'];
        $limit = "limit $size offset $query_count";
        $data['fenyecode']	=	$page_obj->createPage( array('url'=>$jpurl, 'size'=>$count, 'page'=>$data['page'], 'pageSize'=>$size) );

        $sql = 'select * from joy_system_user u LEFT JOIN
        (select advid,count(affid)as aff from joy_transaction GROUP BY advid)t
        on u.id=t.advid LEFT JOIN
        (select advid,count(advid)as conversions,SUM(revenue)as re_num,SUM(payout)as pay_num
        from joy_transaction_income group by advid)s on u.id=s.advid
        LEFT JOIN (select advid,count(affid)as aff_num from joy_offer_pixels GROUP BY advid)e
        on e.advid=u.id
        having u.groupid=4 '.$limit;

        $connect	=	CfgAR::setDbLink('db');
        $command	=	$connect->createCommand($sql);
        $data['advertisers']		=	$command->queryAll();//可能false

        $data['business'] = JoySystemUser::model()->findAllByAttributes(array('groupid'=>4));
        $this->render('advertiser/index',$data);
    }

    public function actionTestLink(){
        $link = Yii::app()->request->getParam('link');
        if(!empty($link)){
            $ret_array		=	array('ret'=>-1, 'msg'=>'', 'occur'=>'ApiController_actionOfferClick', 'error'=>'', 'data'=>'');
            $default_offerid = 50159;
            $default_affid = 1;
            $default_url = 'http://offer2.joymedia.mobi/index.php?r=api/offerclick&offer_id=33270&aff_id=38';
            do{
                try{
                    $params = explode('=',$link);
                    if(empty($params)){
                        exit();
                    }
                    $i = 0;
                    while($i < count($params) - 1){
                        if($i == 0){
                            $param_name = explode('&',$params[1])[1];
                            $par[$param_name] = $params[$i+2];
                        }else{
                            $par[$params[$i]] = $params[$i+2];
                        }
                        $i += 2;
                    }
                    $aff_id		=	isset($par['aff_id']) ? $par['aff_id'] : '';
                    $aff_sub	=	isset($par['aff_sub']) ? $par['aff_sub'] : '';
                    $subid		=	isset($par['subid']) ? $par['subid'] : '';
                    $query		=	isset($par['search']) ? $par['search'] : '';
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
                                $offers = array();
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

                    if($aff_id=130 && $offer_id==35228){
                        $offer_id = 89864;
                        $offers = array();
                    }
                    if(!empty($offers) && $offers['advertiser_id'] == 57){
                        $offer_id = DEFAULT_OFFER_ID;
                        if($aff_id == 140 || $aff_id==184){
                            $offer_id=50159;
                        }
                        if(in_array($aff_id,array(216,199,127,134,260,85,95,97))){
                            $offer_id=29209;
                        }
                    }
                    if($aff_id == 537){
                        $offer_id = 29209;
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
                    $offer_url			=	self::OfferUrlReplace( array('url'=>$offers->offer_url, 'transaction_id'=>$click_str, 'affid'=>$aff_id,'search'=>$query) );
                    $ret_array['ret']	=	0;
                    $ret_array['data']	=	$offer_url;
                }catch (Exception $e){
                    $ret_array['ret']	=	100;
                    $ret_array['msg']	=	'服务器忙，请稍后再试';
                    $ret_array['error']	=	$e->getMessage();
                    break;
                }
                $offer_url	=	isset($offer_url) ? $offer_url : $default_url;
                echo "url:$offer_url";
                echo "offerid:$offer_id";
                echo "affid:$aff_id";
                echo "transaction_id:$aff_sub";
            }while (0);
		exit(0);
        }
        $this->render('advertiser/test_link');
    }

    public function actionCreate(){
        $params['address'] = isset($_POST['address1']) ? trim($_POST['address1']) : '';
        $params['address2'] = isset($_POST['address2']) ? trim($_POST['address2']) : '';
        $params['city'] = isset($_POST['city']) ? trim($_POST['city']) : '';
        $params['status'] = isset($_POST['status']) ? trim($_POST['status']) : '1';
        $params['zipcode'] = isset($_POST['zipcode']) ? trim($_POST['zipcode']) : '';
        $params['phone'] = isset($_POST['phone']) ? trim($_POST['phone']) : '';
        $params['first_name'] = isset($_POST['first_name']) ? trim($_POST['first_name']) : '';
        $params['last_name'] = isset($_POST['last_name']) ? trim($_POST['last_name']) : '';
        $params['title'] = isset($_POST['title']) ? trim($_POST['title']) : '';
        $params['manager_userid'] = isset($_POST['account_manager_id']) ? trim($_POST['account_manager_id']) : '';
        $params['region'] = '';
        $params['password']=md5(Yii::app()->request->getParam('password'));
        $params['email'] = isset($_POST['email']) ? trim($_POST['email']) : '';
        $params['country'] = isset($_POST['country']) ? trim($_POST['country']) : '';
        $params['postback'] = isset($_POST['back_code']) ? trim($_POST['back_code']) : '';
        $params['verify']	=	  !isset($params['verify_token']) ? '': $params['verify_token'];
        if(!empty($_POST['first_name'])) {
            $check_arr = $this->checkData();
            if($check_arr['check_phone'] && $check_arr['check_email']) {
                if (empty(JoySystemUser::model()->findByAttributes(array('email' => Yii::app()->request->getParam('email'))))) {
                    if (JoySystemUser::model()->createUser(ADVERTISER_GROUP_ID,$params)) {
                        Common::jsalerturl('success', Yii::app()->createUrl('advertiser/index'));
                    } else {
                        Common::jsalerturl('failed');
                    }
                }else{
                    Common::jsalerturl('The email is already exist!');
                }
            }
        }
        $business = JoySystemUser::model()->findAllByAttributes(array('groupid'=>3));
        $this->render('advertiser/create',array('business'=>$business));
    }

    public function actionEdit(){
        if(!empty($_GET['id'])) {
            $id = $_GET['id'];
            $data['advertiser'] = JoySystemUser::model()->findByAttributes(array('id'=>$id));
            $data['business'] = JoySystemUser::model()->findAllByAttributes(array('groupid'=>3));
            $this->render('advertiser/edit', $data);
        }else{
            Common::jsalerturl('error about the server');
        }
    }

    /**
     * check type of create ,email and phone
    **/
    private function checkData(){
        $email = isset($_POST['email']) ? $_POST['email'] : '';
        $phone = isset($_POST['phone']) ? $_POST['phone'] : '';
        $rs_arr = array('msg'=>'','check_email'=>false,'check_phone'=>false);
        if(!$email == '' || !$phone == '') {
            $email_preg = '^[\w-]+(\.[\w-]+)*@[\w-]+(\.[\w-]+)+$^';
//            $phone_preg = '^((\(\d{2,3}\))|(\d{3}\-))?13\d{9}$^';
            if (!preg_match($email_preg, $email)) {
                $rs_arr['msg'] .= 'please check your email! ';
                return $rs_arr;
            }else{
                $rs_arr['check_email'] = true;
//                if (!preg_match($phone_preg, $phone)) {
//                    $rs_arr['msg'] .= 'please check your phone! ';
//                    return $rs_arr;
//                }else{
                    $rs_arr['check_phone'] = true;
//                }
            }
        }
         return $rs_arr;
    }

    public function actionUpdate(){
        $type   =   Yii::app()->request->getParam('type');
        $id     =   Yii::app()->request->getParam('id');
        if($type == 'delete'){
            if(!JoySystemUser::model()->deleteByPk($id)){
                Common::jsalerturl('delete erorr',$this->createUrl('advertiser/index'));
            }
            Common::jsalerturl('success!',$this->createUrl('advertiser/index'));
        }else{
            $data['email'] = isset($_POST['email']) ? $_POST['email'] : '';
            $data['phone'] = isset($_POST['phone']) ? $_POST['phone'] : '';
            $check_arr = $this->checkData();
            if($check_arr['check_phone'] && $check_arr['check_email']) {
                if (JoySystemUser::model()->update_advertiser($id)) {
                    Common::jsalerturl('success', $this->createUrl('advertiser/index'));
                } else {
                    Common::jsalerturl('failed', $this->createUrl('advertiser/index'));
                }
            } else {
                Common::jsalerturl($check_arr['msg'], $this->createUrl('advertiser/index'));
            }
        }
        Common::jsalerturl('error', $this->createUrl('advertiser/index'));
    }

    public function actionBuildUrl(){
        $url = Yii::app()->request->getParam('url');
        $aff_sub = Yii::app()->request->getParam('clickid');
        $tracklink = $url . '&aff_sub=' . $aff_sub;
        $this->render('advertiser/buildurl');
    }
}
