<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/7/15
 * Time: 10:23
 */
class AffiliatesController extends Controller
{
    public function __construct(){
        parent::checkAction();//check permission
    }
    public function actionIndex(){
        $connection = Yii::app()->db;
        $aff = AFF_GROUP_ID;
        $select_sql = "select * from joy_system_user u LEFT JOIN (select affid,sum(conversion) as conversions,SUM(revenue)as re_num,SUM(payout)as pay_num
       ,count(click_count) as click FROM joy_offer_count GROUP BY affid)s on u.id=s.affid
       LEFT JOIN (SELECT count(*) as offer_num,affid from (SELECT * from joy_offer_count GROUP BY offerid,affid)t GROUP BY t.affid)g ON g.affid=u.id
        HAVING u.groupid=$aff";
        $command = $connection->createCommand($select_sql);
        $data['affiliates']		=	$command->queryAll();
        $data['business'] = JoySystemUser::model()->findAllByAttributes(array('groupid'=>BUSINESS_GROUP_ID));
        $this->render('affiliates/index',$data);
    }

    public function actionCreateAff(){
        $params['title'] =  Yii::app()->request->getParam('title');
        $params['password'] = md5(Yii::app()->request->getParam('password'));
        $params['email'] = $this->user['email'] . $params['title'];
        $siteid = Yii::app()->request->getParam('siteid');
        if($this->user['groupid'] == AFF_GROUP_ID){
            $siteid = $this->user['userid'];
        }
        $result['msg'] = '';
        if(!empty($params['email'])){
            if($data = JoySystemUser::model()->createUser(AFF_GROUP_ID,$params)){
                $site = JoySites::model()->findByAttributes(array('site_id'=>$siteid));
                if(empty($site)){
                    $site = new JoySites();
                    $site->site_id =$siteid;
                    $site->affids = $data['result'];
                }else {
                    if (empty($site['affids'])) {
                        $site->affids = $data['result'];
                    } else {
                        $site->affids = $site['affids'] . ',' . $data['result'];
                    }
                }
                $site->save();
            }
        }
        if($this->user['groupid'] == SITE_GROUP_ID){
            $this->redirect($this->createUrl('site/affiliatelist'),$result['msg']);
        }elseif(in_array($this->user['groupid'],$this->manager_group)){
            Common::jsalerturl('Success');
        }
    }

    public function actionCreate(){
        $data = array('rs'=>false,'msg'=>'');
        $params['company'] = isset($_POST['company']) ? trim($_POST['company']) : '';
        if(!empty($params['company'])) {
            $check_arr = $this->checkData();
            if($check_arr['check_phone'] && $check_arr['check_email']) {
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
                if ($data = JoySystemUser::model()->createUser(AFF_GROUP_ID,$params)) {
                    //if checked ,send email to the affiliate
                    if(Yii::app()->request->getParam('send_email') == 1){
                        $url = 'http://offer2.joymedia.mobi/index.php?r=system/login';
                        $email = Yii::app()->request->getParam('email');
                        $passwored = Yii::app()->request->getParam('password');
                        $content    =   " <div background='http://offer2.joymedia.mobi/assets/i/email_back.jpg'>
                        <p><h3>Dear Partner：</h3></p>
                        <p>Thank you for registering to become an affiliate of JoyMedia. Your application </p>
                        <p>has successfully been received by our Customer Service Team and will be reviewed<p>
                        <p>within the next 1 business day.</p>
                        <p><a href='$url'>$url</a></p>
                        <p>Your username:$email</p>
                        <p>Your password:$passwored</p>
                        <br>
                        <p>Our Customer Service hours are Monday - Friday from 9:00 am to 12:00 pm . We will</p>
                        <p>be contacting you via the Phone Number and Email address you have provided.</p>
                        <p>What to look forward to when becoming an affiliate of JoyMedia:</p>
                        <p>- The most frequent contest giveaways of cash and prizes in the industry.</p>
                        <p>- Company owned and operated campaigns along with 500+ offers</p>
                        <p>Thank you again for your interest in becoming a part of the JoyMedia Affiliate Network.</p>
                        <p>Best Regards,</p>
                        <p>Joy Media</p>
                        <p>Support Team</p>
                        </div>
";
                        $host   =   'smtp.ym.163.com';
                        $user_name  =   "admin@joydream.cn";
                        $email_password   =   "292513148/bing";
                        $recevie_name   =   'Dear Partner';
                        if(Common::sendMail($email,'JoyDream',$content,$user_name,$email_password,$recevie_name,$host) === true){
                            $data['msg']    =   'create success.';
                            $data['result']     =   1;
                        }else{
                            $data['msg']    =   'Send Email Failed,Please Check The Email!';
                            //Delete the aff,told them should try again
                            JoySystemUser::model()->deleteAllByAttributes(array('email'=>$email));
                            $data['result']     =   0;
                        }
                    }
                    if($data['result'] == 1){
                        Common::jsalerturl($data['msg'], $this->createUrl('affiliates/index'));
                    } else{
                        Common::jsalerturl($data['msg']);
                    }
                } else {
                    Common::jsalerturl('failed');
                }
            } else {
                Common::get_jump_url($check_arr['msg']);
            }
        }
        $data['AM'] = JoySystemUser::model()->findAllByAttributes(array('groupid'=>BUSINESS_GROUP_ID));
        $this->render('affiliates/create',$data);
    }

    public function actionEdit(){
        if(!empty($_GET['id'])) {
            $id = $_GET['id'];
            $data['affiliate'] = JoySystemUser::model()->findByAttributes(array('id'=>$id));
            if(!empty($data['affiliate']['manager_userid'])){
                $data['selected'] =     $data['affiliate']['manager_userid'];
            }
            $data['business'] = JoySystemUser::getResults('id,company','groupid='.BUSINESS_GROUP_ID);
            $this->render('affiliates/edit', $data);
        }else{
            Common::jsalerturl('error about the server');
            die();
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

    /**
     * update the data
     */
    public function actionUpdate(){
        $id =   Yii::app()->request->getParam('id');
        $type   =   Yii::app()->request->getParam('type');
        if($type == 'delete'){
            if(!JoySystemUser::model()->deleteByPk($id)){
                Common::jsalerturl('delete erorr',$this->createUrl('affiliates/index'));
            }
            Common::jsalerturl('success!',$this->createUrl('affiliates/index'));
        }else{
            $params = JoySystemUser::getParams();
            if (JoySystemUser::updateUserInfo2($params,"id=$id")) {
                Common::jsalerturl('Success!');
//                Common::jsalerturl('success', $this->createUrl('affiliates/index'));
            } else {
                Common::jsalerturl('Failed!');
//                Common::jsalerturl('failed', $this->createUrl('affiliates/index'));
            }

        }
        Common::jsalerturl('error', $this->createUrl('affiliates/index'));
    }

    //get OfferReport
    public function actionOfferReport(){
        $criteria = new CDbCriteria();
        $criteria->order = 't.id ASC';
        $count = joy_offers::model()->count($criteria);
        $data['pages']   =   $pager = new CPagination($count);
        $pager->pageSize=30;
        $pager->applyLimit($criteria);
        $data['offers'] =   $offers = joy_offers::model()->with('adv')->findAll($criteria);
        $this->render('affiliates/offer_report',$data);
    }

    public function actionPayment(){
        $action_type = Yii::app()->request->getParam('action_type');
        $msg = '';
        do{
            if('add' == $action_type) {
                $payment_type = Yii::app()->request->getParam('payment_type');
                $beneficiary = Yii::app()->request->getParam('beneficiary');
                $bankname = Yii::app()->request->getParam('bankname');
                $bankadd = Yii::app()->request->getParam('bankadd');
                $bankacc = Yii::app()->request->getParam('bankacc');
                $bank_email = Yii::app()->request->getParam('bank_email');
                $swift_code = Yii::app()->request->getParam('swift_code');
                $site_id = Yii::app()->request->getParam('site_id');
                $pee = Yii::app()->request->getParam('pee');
                if(!empty($site_id) && in_array($this->user['groupid'],$this->manager_group)){
                    $payment = JoyPayment::model()->findByAttributes(array('affid' => $site_id));
                }else{
                    $payment = JoyPayment::model()->findByAttributes(array('affid' => $this->user['userid']));
                }
                if (!empty($payment)) {
                    if (in_array($this->user['groupid'], $this->manager_group)) {
                        $payment->bank_account = $bankacc;
                        $payment->bank_address = $bankadd;
                        $payment->bank_name = $bankname;
                        $payment->beneficiary = $beneficiary;
                        $payment->type = $payment_type;
                        $payment->email = $bank_email;
                        $payment->swift_code = $swift_code;
                        $payment->pee = $pee;
                        $payment->affid = $site_id;
                    } elseif ($payment['type'] != $payment_type) { // if the payment_type is different from the databases
                        if ($payment_type == 0) {
                            $payment->affid = $this->user['userid'];
                            $payment->bank_account = $bankacc;
                            $payment->bank_address = $bankadd;
                            $payment->bank_name = $bankname;
                            $payment->beneficiary = $beneficiary;
                            $payment->swift_code = $swift_code;
                            $payment->pee = $pee;
                            $payment->type = $payment_type;
                            $payment->createtime = date('Y-m-d H:i:s');
                        } else {
                            $payment->affid = $this->user['userid'];
                            $payment->email = $bank_email;
                            $payment->type = $payment_type;
                            $payment->createtime = date('Y-m-d H:i:s');
                        }
                    } else {
                        $msg = 'Please Contact Your Manager If You Have To Change The Information!';
                        break;
                    }
                } else {
                    $payment = new JoyPayment();
                    if(!in_array($this->user['groupid'],$this->manager_group)){
                        $site_id = $this->user['userid'];
                    }
                    if ($payment_type == 0) {
                        $payment->affid = $site_id;
                        $payment->bank_account = $bankacc;
                        $payment->bank_address = $bankadd;
                        $payment->bank_name = $bankname;
                        $payment->beneficiary = $beneficiary;
                        $payment->swift_code = $swift_code;
                        $payment->pee = $pee;
                        $payment->createtime = date('Y-m-d');
                        $payment->type = $payment_type;
                    } else {
                        $payment->affid = $site_id;
                        $payment->email = $bank_email;
                        $payment->type = $payment_type;
                        $payment->createtime = date('Y-m-d');
                    }
                }
                if ($payment->save()) {
                    $msg = 'Save Success!';
                } else {
                    $msg = 'Save Failed!';
                }
            }elseif($action_type == 'verifier'){
                $id = Yii::app()->request->getParam('id');
                $payment = JoyPayment::model()->findByPk($id);
                if(empty($payment)){
                    Common::jsalerturl('System Error!');
                    exit();
                }
                $payment->updatetime = date('Y-m-d H:i:s');
                if($this->user['groupid'] == BUSINESS_GROUP_ID){
                    $payment->status = 1;
                    $payment->am_id = $this->user['userid'];
                }elseif($this->user['groupid'] == FINANCE_GROUP_ID){
                    $payment->status = 2;
                    $payment->finance_id = $this->user['userid'];
                }
                if($payment->save()){
                    $msg = 'Verifier Success';
                }else{
                    $msg = 'Verifier Failed';
                }
                Common::jsalerturl($msg);
                exit();
            }
        }while(0);
        $payment = JoyPayment::getPaymentInfo($this->user['userid'],$this->user['groupid']);
        if(in_array($this->user['groupid'],$this->manager_group)) {
            Yii::app()->cache->set('payment' . $this->user['userid'],$payment,500);
            $this->render('payment/aff_payment',array('payment'=>$payment));
        }else{
            $this->render('affiliates/payment', array(
                'payment' => $payment,
                'msg' => $msg,
            ));
        }
    }

    public function actionSetAffiliateConfig()
    {
        $this->render('affiliate/config');
    }

    public function actionAffCheckExist(){
        $siteid = Yii::app()->request->getParam('siteid');
        $title = Yii::app()->request->getParam('title');
        $sql = "select * from joy_system_user where title='$title' and parent_id=$siteid";
    }
}
