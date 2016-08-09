<?php
include Yii::app()->basePath . '/components/SmaatoSnippet.php';
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/8/7
 * Time: 18:30
 *  standard�����ֻ��汾��ת��
 *  �ͻ��˶�ȡoffer
 *  ע����ⲿ������֤�Ľӿ�
 */
class StandardController extends Controller
{
    //�ֻ���ȡlist
    function actionMobileOfferList(){
        $ua_get =   Yii::app()->request->getParam('ua');
        if(empty($ua)){
            $user_agent    =   "Mozilla/5.0 (Linux; U; Android 4.2.2; zh-cn; vivo Y11i T Build/JDQ39) AppleWebKit/534.30(KHTML, like Gecko)Version/4.0 Mobile Safari/534.30; 360 Aphone Browser (6.9.9.14)";
        }else{
            $user_agent     =   $ua_get;
        }
        $ua =   substr($user_agent,0,strrpos($user_agent,'Gecko')+6);
        $agent_array    =   explode('Version',$user_agent);
        if(!empty($agent_array[0])){
//            $v  =   substr($agent_array[1],1,1);
            $v  =   4;
        }else{
            $v  =   4;
        }
        $ip     =   '54.169.61.72';
        $limit  =   Yii::app()->request->getParam('limit');
        $browser  =   Yii::app()->request->getParam('browser');
        $imgsize  =   Yii::app()->request->getParam('imgsize');
        $get    =   Yii::app()->request->getParam('get') ? 'text' : 'get';
        $tempid =   rand(134508,134509);
        $url    =   "http://show.buzzcity.net/showads.php?get=$get&partnerid=$tempid&ip=$ip&ua=".urlencode($ua)."&v=$v";
        if(!empty($limit)){
            $url   .=    "&limit=$limit";
        }
        if(!empty($browser)){
            $url   .=    "&browser=$browser";
        }
        if(!empty($imgsize)){
            $url   .=    "&imgsize=$imgsize";
        }
        $data   =   Common::curlGet(array('url'=>$url));
        $data_re    =   stripslashes($data);
        echo $data_re;
    }

    //���ظ�����API
    public function actionGetList(){
        $rows=array();
        $sign	=	Yii::app()->request->getParam('sign');
        $site_id =   Yii::app()->request->getParam('siteid');
        $page   =   Yii::app()->request->getParam('page');
        $limit   =   Yii::app()->request->getParam('limit');
        $size   =   30;//Ĭ�Ϸ�������
//        $country    =   Yii::app()->request->getParam('country');
        if(empty($sign) || empty($site_id)){
            //ֱ�ӷ���500������
            header("http/1.1 500 internal server error");
            exit;
        }
        $whitelist =   JoyAffiliateWhitelist::model()->findByPk($site_id);
        $ip	=	Common::getIp();
        //��������IP��ѯIP�������е�token��Ϣ
        if(!$whitelist || !$ip  ==  $whitelist->context || !1  ==  $whitelist->status){
            $rows['error']	=	'true';
            $rows['error_msg']	=	'error ip!';
            $offers_json	=	CJSON::encode($rows);
            echo $offers_json ;
            exit;
        }
        //���µ�¼ʱ��
        $whitelist->last_login_time =   date('Y-m-d h:m:s');
        if(!$whitelist->update()){
            $rows['error']	=	'true';
            $rows['error_msg']	=	'server update error!';
            $offers_json	=	CJSON::encode($rows);
            echo $offers_json ;
            exit;
        }
        $token	=	$whitelist['token'];
        //�ж�sign�Ƿ�һ��
        if($token	!== $sign){
            $rows['error']	=	'true';
            $rows['error_msg']	=	'your token has expired ,please use the new one! ';
            $offers_json	=	CJSON::encode($rows);
            echo $offers_json ;
            exit;
        }
        $condition  =   ' t.status = 1 ';
//        if(!empty($country)){
//            $condition  .=   " and find_in_set('$country',geo_targeting)";
//        }
        //��ҳ
        $all_count  =   joy_offers::model()->count('status=1');
        $count  =   joy_offers::model()->count($condition);
        $all_page   =   intval($all_count / $size);
        if($all_count % $size != 0) ++$all_page;
        $page_helper    =   new Page();
        if(!empty($limit))  $size=$limit;
        if(empty($page))   $page='1';
        $data   =   $page_helper->pageCut($count,$page,$size);
        $page   =   $data['page'];//���ش�����ҳ��
        $query_count    =   $data['query_count'];//��ѯ����Ŀ
        $limit_re  =   " limit $size offset $query_count";
        //��ѯ���з���������offer
        $offers		=	joy_offers::model()->findAll(array(
            'select'=>'id,name,description,offer_url,currency,revenue,type,
			thumbnail,preview_url,expiration_date,
			payout,geo_targeting,note,platform,traffic,
			min_android_version,max_android_version,advertiser_id,createtime,updatetime',
            'condition'=>$condition . $limit_re,
        ));
        $advertisers = array();
        foreach($offers as $key){

            $advertisers[]  =   JoySystemUser::model()->findByAttributes(array('id'=>$key['advertiser_id']));
        }
        if($offers){
            $rows['error']	=	'false';
            $rows['error_msg']	=	'';
            $rows['page']	=	$page;
            $rows['count']	=	$count;
            $rows['all_page']	=	$all_page;
            $rows['all_count']	=	$all_count;
            foreach($offers as $i=>$offer) {
                $offer_id = $offer->id;
                $rows[$i]=array_filter($offer->attributes,'strlen');
                $rows[$i]['title']=$advertisers[$i]['company'];
                $rows[$i]['caps']['daily_con']=$offer['caps']['daily_con'];
                $rows[$i]['caps']['month_con']=$offer['caps']['month_con'];
                $rows[$i]['caps']['daily_pay']=$offer['caps']['daily_pay'];
                $rows[$i]['caps']['month_pay']=$offer['caps']['month_pay'];
                $rows[$i]['caps']['daily_rev']=$offer['caps']['daily_rev'];
                $rows[$i]['caps']['month_rev']=$offer['caps']['month_rev'];
                //�ó�description���ݣ����и�ʽ��
                //���������Լ���offer_url
                $rows[$i]['offer_id'] = $offer_id;
                $url = Yii::app()->request->hostInfo.$this->createUrl('api/offerclick')."&offer_id=$offer_id&aff_id=49";
                $sub = '&aff_sub={transaction_id}&subid={subid}';
                $url .= $sub;
                $rows[$i]['offer_url'] = $url;
                unset($rows[$i]['id']);
                unset($rows[$i]['type']);
                unset($rows[$i]['description']);
                $description = $offer['description'];
                $description =strip_tags($description,strlen($description));
                $rows[$i]['description'] = $description;
                $offer_types_name = array();
                $types_str    =   $offer['type'];
                if($types_str && $types_str != ''){
                    $types_arr  =   explode(',',$types_str);
                    if($types_arr){
                        foreach($types_arr as $key=>$val){
                            $offer_type   =   JoyOffersType::model()->findByPk($val);
                            $offer_types_name[$val] = $offer_type['type_name_en'];
                        }
                    }
                }
                $rows[$i]['category']   =   $offer_types_name;
            }
        }else{
            $rows['error']	=	'true';
            $rows['error_msg']	=	'no data here';
        }
        $offers_json	=	CJSON::encode($rows);
        echo $offers_json ;
    }

    //ע��
    public function actionRegister(){
        $data   =   array('msg'=>'','rs'=>false);
        $company     =   trim(Yii::app()->request->getParam('company'));
        $address1    =   trim(Yii::app()->request->getParam('address1'));
        $address2    =   trim(Yii::app()->request->getParam('address2'));
        $city   =   trim(Yii::app()->request->getParam('city'));
        $country    =   trim(Yii::app()->request->getParam('country'));
        $zipcode    =   trim(Yii::app()->request->getParam('zipcode'));
        $phone    =   trim(Yii::app()->request->getParam('phone'));
        $first_name    =   trim(Yii::app()->request->getParam('first_name'));
        $last_name    =   trim(Yii::app()->request->getParam('last_name'));
        $title    =   trim(Yii::app()->request->getParam('title'));
        $email    =   trim(Yii::app()->request->getParam('email'));
        $password    =   Yii::app()->request->getParam('password');
        $re_password    =   Yii::app()->request->getParam('password_confirmation');

        if(empty($company) || empty($phone) || empty($title) || empty($email) || empty($password) || empty($re_password)){
            $data['msg']    =   'error about your params!';
            $data['rs']     =   false;
        }

        if($password !== $re_password){
            $data['msg']    =   'error about your password!';
            $data['rs']     =   false;
        }

        $user   =   JoySystemUser::model()->findByAttributes(array('email'=>$email));
        $str    =   '';
        if($user){
            $data['msg']    =   'username have been registered!';
            $data['rs']     =   false;
        }else{
            $user   =   new JoySystemUser();
            $params['verify_token'] =   $str    =   md5(time() . $user->password);
            if(!$user->create_advertiser(5,$params)){
                $data['msg']    =   'server error!';
                $data['rs']     =   false;
            }else{
                $data['rs'] =   true;
            }
        }
        if($data['rs']){
            $url    =   "http://offer2.joymedia.mobi/index.php?r=standard/verify&verify_token=$str";
            $content    =   " <div background='http://offer2.joymedia.mobi/assets/i/email_back.jpg'>
    <p><h3>Dear Partner</h3></p>
    <p>Thank you for registering to become an affiliate of JoyMedia. Your application </p>
    <p>has successfully been received by our Customer Service Team and will be reviewed<p>
    <p>within the next 1 business day.</p>
    <p>Our Customer Service hours are Monday - Friday from 9:00 am to 12:00 pm . We will</p>
    <p>be contacting you via the Phone Number and Email address you have provided.</p>
    <p>What to look forward to when becoming an affiliate of JoyMedia:</p>
    <p>- The most frequent contest giveaways of cash and prizes in the industry.</p>
    <p>- Company owned and operated campaigns along with 500+ offers</p>
    <p>Please Click to verify your identity:<a href='$url'>$url</a></p>
    <p>Thank you again for your interest in becoming a part of the JoyMedia Affiliate Network.</p>
    <p>Best Regards,</p>
    <p>Joy Media</p>
    <p>Support Team</p>
    </div>
";//$fromName,$title,$content,$user_name,$password,$acceptName,$host=null,$replay=null
            $host   =   'smtp.ym.163.com';
            $user_name  =   "admin@joydream.cn";
            $email_password   =   "292513148/bing";
            $recevie_name   =   'Dear Partner';
            if(Common::sendMail($email,'JoyDream verify',$content,$user_name,$email_password,$recevie_name,$host) === true){
                $data['msg']    =   'success create! And please verify your account in your email first.';
                $data['rs']     =   true;
            }else{
                $data['msg']    =   'send email failed,please check your email!';
                JoySystemUser::model()->deleteAll(array('email'=>$email));
                $data['rs']     =   false;
            }
        }
        if($data['rs']){
            Common::jsalerturl($data['msg'],$this->createUrl('index/index'));
        }else{
            Common::jsalerturl($data['msg']);
        }
    }

    //������֤= ��= ��û��д����֤����߼��Լ�ҳ������
    public function actionVerify(){
        $verify =   Yii::app()->request->getParam('verify_token');
        if(!empty($verify)){
            $user =   JoySystemUser::model()->findByAttributes(array('verify'=>$verify));
            if(!$user){
                echo 'the url is out of style!';
                exit;
            }
            $user->verify   =   '';
            if(!$user->save()){
                throw new ErrorException('server error!');
            }
            $url    =   'http://offer2.joymedia.mobi/index.php?r=system/login';
            echo "verify success!you can login JoyDream now!<a href='$url'>$url</a>";
        }
    }
    /**
     * ������ע��
     */
    public function actionRegisterForm(){
        $this->renderPartial('/comm/register');
    }

    public function actionSmaatto(){
        $ua = Yii::app()->request->getParam('ua');
        $ip = Yii::app()->request->getParam('ip');
        $ua = " Mozilla/5.0 (Linux; U; Android 2.2; zh-cn; Nexus One Build/FRF91) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1";
        if(empty($ua)){
            $ua = 'Mozilla/5.0 (Linux; Android 4.4.4; MI 3W Build/KTU84P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.59 Mobile Safari/537.36';
        }
        if(empty($ip)){
            $ip = "47.88.18.24";
        }
        $snippet = new SmaatoSnippet();
        try {
            $snippet->setAdspaceId(130067301)
                ->setPublisherId(1100006100)
                ->setAdFormat(SmaatoSnippet::AD_FORMAT_ALL)
                ->setResponseFormat(SmaatoSnippet::RESPONSE_FORMAT_XML)
                ->setDimension('medrect')
                ->setAdFormat('img')
                ->setResponseFormat('xml')
                ->setGender("m")
                ->setUserAgent($ua)
                ->setDeviceIp($ip)
                ->setTimeout(5000)
                ->setReferrerUrl('http://offer2.joymedia.mobi' . Yii::app()->createUrl('standard/smaatto'));
            $snippet->requestAd();
            if($snippet->isAdAvailable()) {
                $banner = $snippet->getAd();
		$clickUrl = $banner->clickUrl;
		$beacons = $banner->beacons;
		$img_url = $banner->rawXml;
		$img_url = htmlspecialchars_decode($img_url);
                $img = substr($img_url,strrpos($img_url,'http'));
                $data['status'] = 1;
                $data['msg'] = 'success';
                $data['data'][0]['id'] = 130024879;
                $data['data'][0]['title'] = 'smaatto ads';
                $data['data'][0]['main_content'] = '';
                $data['data'][0]['main_image_url'] = $img;
                $data['data'][0]['icon_image_url'] = $img;
                $data['data'][0]['app_package_name'] = 'smaatto ads';
                $data['data'][0]['main_activity'] = 'main_activity';
                $data['data'][0]['image_with'] = '300';
                $data['data'][0]['image_highth'] = '250';
                $data['data'][0]['charge_type'] = 'URL';
		$clickUrl = (array)$clickUrl;
		$beacons = (array)$beacons;
                $data['data'][0]['charge_url'] = $clickUrl[0];
		$data['data'][0]['impression_url'] = $beacons[0];
            } else {
                $data['status'] = 0;
                $data['msg'] = "Currently no ad is available.";
            }
            echo json_encode($data);
        } catch (Exception $e) {
            $data['status'] = 0;
            $data['msg'] = $e->getMessage();
            echo json_encode($data);
        }
    }

    public function actionTestWork(){
        echo '<html>'.json_encode(array('message'=>'success')).'</html>';
    }
    //���ȡ��offer
    public function actionGetMobileOffer(){
        do {
            $country = Yii::app()->request->getParam('country');
            if(empty($country)){
                $data['status'] = 0;
                $data['msg'] = 'No country!';
                break;
            }
            $affid = 134;
            $criteria = new CDbCriteria();
            $country = JoyOfferCountry::model()->find("cninfo like '%$country%'");
            if (empty($country)) {
                $data['status'] = 0;
                $data['msg'] = 'No country!';
                break;
            }
            $country_en = $country['abbr'];
            $criteria->addCondition("find_in_set(geo_targeting,'$country_en')");
            $criteria->addCondition('status=1');
            $criteria->order = 'payout,thumbnail DESC';
            $random = rand(1,3);
            switch($random) {
                case 1:
                    $offer_id = 29973;
                    break;
                case 2:
                    $offer_id = 32284;
                    break;
                case 4:
		    $offer_id = 32584;
		    break;
                default:
                    $offer_id = 27900;
            }
            $offer = joy_offers::model()->findByPk($offer_id);
            if(empty($offer)){
                $data['status'] = 0;
                $data['msg'] = 'No Offer Selected!';
                break;
            }
            //���û��ͼƬ��ʹ��Ĭ��ͼƬ
            if(empty($offer['thumbnail']) || $offer['thumbnail'] == ''){
                $thumbnail = 'http://offer2.joymedia.mobi/assets/i/default.png';
            }else{
                $thumbnail = $offer['thumbnail'];
            }
            $target_url = 'http://offer2.joymedia.mobi' . $this->createUrl('api/offerclick') . "&offer_id={$offer['id']}&aff_id=$affid";
            $data['status'] = 1;
            $data['msg'] = 'success';
            $data['data'] = array();
//            array_push($data['data'],array('id',$offer['id']));
//            array_push($data['data'],array('title',$offer['name']));
//            array_push($data['data'],array('main_content',''));
//            array_push($data['data'],array('main_image_url',$thumbnail));
//            array_push($data['data'],array('icon_image_url',$thumbnail));
//            array_push($data['data'],array('app_package_name','offer ads'));
//            array_push($data['data'],array('main_activity','main_activity'));
//            array_push($data['data'],array('image_with',300));
//            array_push($data['data'],array('image_highth',250));
//            array_push($data['data'],array('charge_type','URL'));
//            array_push($data['data'],array('charge_url',$target_url));


            $data['data'][0]['id'] = $offer['id'];
            $data['data'][0]['title'] = $offer['name'];
            $data['data'][0]['main_content'] = '';
            $data['data'][0]['main_image_url'] = $thumbnail;
            $data['data'][0]['icon_image_url'] = $thumbnail;
            $data['data'][0]['app_package_name'] = 'offer ads';
            $data['data'][0]['main_activity'] = 'main_activity';
            $data['data'][0]['image_with'] = '300';
            $data['data'][0]['image_highth'] = '250';
            $data['data'][0]['charge_type'] = 'URL';
            $data['data'][0]['charge_url'] = $target_url;
        }while(0);
            echo json_encode($data,true);
    }

    public function actionGetRecord(){
        $affid = Yii::app()->request->getParam('affid');
        $date = Yii::app()->request->getParam('date');
        $project = Yii::app()->request->getParam('project');
        $cdb = new CDbCriteria();
        $result = array();
        do {
            if (empty($affid) || empty($date) || empty($project)) {
                break;
            }
            $cdb->addCondition("affid=$affid");
            $cdb->addCondition("time='$date'");
            $cdb->addCondition("project_name='$project'");
            $cdb->select = 'affid,time,click_count,project_name,revenue';
            $count = OfferCountSelf::model()->findAll($cdb);
            foreach($count as $key=>$item){
                $result[$key]['affid'] = $item['affid'];
                $result[$key]['time'] = $item['time'];
                $result[$key]['revenue'] = $item['revenue'];
                $result[$key]['click'] = $item['click_count'];
                $result[$key]['project'] = $item['project_name'];
            }
        }while(0);
        echo json_encode($result,true);
    }
}
