<?php
/**
 * offer管理控制器
 * @author Administrator
 *
 */
class OfferController extends Controller{
    
	public function __construct(){
		parent::checkAction();//验证权限
	}
    /**
     * 显示offer的列表
     */
    public function actionList() {
        $types = Yii::app()->request->getParam('types');
        $data['countries_select'] = $countries_select = Yii::app()->request->getParam('countries');
        $countries_select_arr = array();
        if (!empty($countries_select)) {
            $countries_select_arr = explode(',', $countries_select);
        }
        $data['name'] = $name = trim(Yii::app()->request->getParam('name'));
        $data['offer_id'] = $offer_id = trim(Yii::app()->request->getParam('offer_id'));
        $data['status'] = $status = Yii::app()->request->getParam('status');
        $advertiser_id = 0;
        $rows = '';
        //判断offer_id是否不是数字
        if (!is_numeric($offer_id)) {
            $offer_id = '';
        }
        $criteria = new CDbCriteria();
        if (AFF_GROUP_ID == $this->user['groupid']) {
            $offerids_str = JoyOfferCut::getOfferShow($this->user['userid']);
           if(!empty($offerids_str)){
               if(strpos($offerids_str,',')){
                   $criteria->addCondition("t.id in ($offerids_str)");
               }else{
                   $criteria->addCondition("t.id = $offerids_str");
               }
           }else{
               $criteria->addCondition('t.id = 0');
           }
            $status = 1;
        }elseif(in_array($this->user['groupid'],$this->manager_group)){
            $advertiser_id = Yii::app()->request->getParam('advertiser');
            $advertisers = JoySystemUser::model()->findAllByAttributes(array('groupid' => ADVERTISER_GROUP_ID));
            $rows = array();// we need this array
            foreach ($advertisers as $i => $advertiser) {
                $select = '';
                $rows[$i] = $advertiser->attributes;
                if ($advertiser_id) {
                    if ($advertiser['id'] == $advertiser_id) {
                        $select = 'selected';
                    }
                }
                $rows[$i]['select'] = $select;
            }
        }
        $data['advertisers'] = $rows;
        if (!empty($name)) {
            $criteria->addCondition(" name like '%$name%'");      //根据条件查询
        }
        if (!empty($offer_id)) {
            $criteria->addCondition(" t.id = $offer_id");
        }
        if (!empty($advertiser_id)) {
            $criteria->addCondition(" advertiser_id = $advertiser_id");
        }
        if (!empty($types)) {
            $sql_type_condition = '';
            foreach ($types as $i => $t) {
                if ($i == 0) {
                    $sql_type_condition = " find_in_set('$t',type)";
                } else {
                    $sql_type_condition .= " OR find_in_set('$t',type)";
                }
            }
            $criteria->addCondition($sql_type_condition);
        }

        if (!empty($countries_select_arr)) {
            $sql_country_condition = '';
            foreach ($countries_select_arr as $i => $country_select) {
                if ($i == 0) {
                    $sql_country_condition = "find_in_set(geo_targeting,'$country_select')";
                } else {
                    $sql_country_condition .= " or find_in_set(geo_targeting,'$country_select')";
                }
            }
            $criteria->addCondition($sql_country_condition);
        }
        $criteria->order = 't.recommend desc,t.joy_createtime desc';
        $count = joy_offers::model()->count($criteria);
        $pager = new CPagination($count);
        $pager->pageSize = 30;
        $pager->applyLimit($criteria);
        $type_rout_str = '';
        if (!empty($types)) {
            foreach ($types as $type) {
                $type_rout_str .= "&types[]=$type";
            }
        }
        $pager->route = "offer/list&advertiser=$advertiser_id&name=$name&offer_id=$offer_id&status=$status$type_rout_str&countries=$countries_select";
        $data['pages'] = $pager;
        if ($status) {
            $criteria->addCondition(" t.status = $status");
        }
        $data['offers'] = $offers = joy_offers::model()->with('adv')->findAll($criteria);//查找出所有的offer数据
        $offer_id_str = '';
        foreach ($data['offers'] as $key => $val) {
            $offer_types_name = '';
            $offer_id_str .= empty($offer_id_str) ? $val['id'] : ',' . $val['id'];
            $types_str = $val['type'];
            if ($types_str && $types_str != '') {
                $types_arr = explode(',', $types_str);
                if ($types_arr) {
                    foreach ($types_arr as $type_str) {
                        $offer_type = JoyOffersType::model()->findByPk($type_str);
                        $offer_types_name .= $offer_types_name == '' ? $offer_type['type_name_en'] : ',' . $offer_type['type_name_en'];
                    }
                }
            }
            $data['offers'][$key]['type'] = empty($offer_types_name) ? 'other' : $offer_types_name;
        }
        $where_sql = '';
        if (AFF_GROUP_ID == $this->user['groupid']) {
            $userid = $this->user['userid'];
            $where_sql = " and affid=$userid";
        }
        $connection = Yii::app()->db;
        if (!empty($offer_id_str)) {
            $click_sql = "SELECT offerid, COUNT(*) AS click_num FROM joy_transaction WHERE offerid IN ($offer_id_str) $where_sql GROUP BY offerid";
            $conn = $connection->createCommand($click_sql);
            $click_res = $conn->queryAll();
            $data['click_arr'] = array();
            if (count($click_res) > 0) {
                foreach ($click_res as $val) {
                    $data['click_arr'][$val['offerid']] = $val['click_num'];
                }
            }
            //查询实际转化
            $income_sql = "SELECT offerid, COUNT(*) AS income_num, SUM(revenue) as total_revenue, SUM(payout) as total_payout FROM joy_transaction_income WHERE offerid IN ($offer_id_str) $where_sql GROUP BY offerid";
            $conn = $connection->createCommand($income_sql);
            $income_res = $conn->queryAll();
            $data['income_arr'] = array();
            if (count($income_res) > 0) {
                foreach ($income_res as $val) {
                    $data['income_arr'][$val['offerid']] = $val;
                }
            }
            //查询扣量后转换
            $income_sql = "SELECT offerid, COUNT(*) AS income_num, SUM(revenue) as total_revenue, SUM(payout) as total_payout FROM joy_transaction_income WHERE offerid IN ($offer_id_str) $where_sql AND ispostbacked=1 GROUP BY offerid";
            $conn = $connection->createCommand($income_sql);
            $income_res = $conn->queryAll();
            $data['income_cut_arr'] = array();
            if (count($income_res) > 0) {
                foreach ($income_res as $val) {
                    $data['income_cut_arr'][$val['offerid']] = $val;
                }
            }
        }
        //查询所有type
        $type_select = JoyOffersType::model()->findAll();
        $rows = array();
        if (empty($types)) {
            $types = array();
        }
        foreach ($type_select as $i => $type_r) {
            $rows[$i] = array_filter($type_r->attributes, 'strlen');
            $type_id = $type_r['id'];
            if (in_array($type_id, $types)) {
                $rows[$i]['select'] = 'selected';
            } else {
                $rows[$i]['select'] = '';
            }
        }
        $data['types'] = $rows;
        $this->render('offer/offer-list',$data);
    }
    /**
     * 增加offer
     */
    public function actionAdd() {
        if(!empty($_POST)) {
            $offer = new joy_offers();
            $offer->advertiser_id = isset($_POST['advertiser']) ? $_POST['advertiser'] : '';
            $offer->name = isset($_POST['name']) ? $_POST['name'] : '';
            $offer->description = isset($_POST['description']) ? $_POST['description'] : '';
            $offer->preview_url = isset($_POST['preview_url']) ? $_POST['preview_url'] : '';
            $offer->offer_url = isset($_POST['offer_url']) ? $_POST['offer_url'] : '';
            $offer->protocol = isset($_POST['protocol']) ? $_POST['protocol'] : '';
            $expiration_date = trim(Yii::app()->request->getParam('expirationDate'));
            if(!empty($expiration_date)){
                $offer->expiration_date = $expiration_date;
            }
            $offer->offer_category = isset($_POST['offer_category']) ? $_POST['offer_category'] : '';
            $offer->ref_id = isset($_POST['ref_id']) ? $_POST['ref_id'] : '';
            $offer->currency = isset($_POST['currency']) ? $_POST['currency'] : '';
            $offer->revenue_type = isset($_POST['revenue_type']) ? $_POST['revenue_type'] : '';
            $offer->revenue = isset($_POST['revenue']) ? $_POST['revenue'] : '';
            $offer->payout = isset($_POST['cost_per_conversion']) ? $_POST['cost_per_conversion'] : '';
            $offer->payout_type = isset($_POST['payout_type']) ? $_POST['payout_type'] : '';
            $offer->is_private = isset($_POST['Private']) ? $_POST['Private'] : '';
            $offer->require_approval = isset($_POST['require_approval']) ? $_POST['Private'] : '';
            $offer->require_terms_and_conditions = isset($_POST['terms']) ? $_POST['terms'] : '';
            $offer->is_seo_friendly_301 = isset($_POST['seo']) ? $_POST['seo'] : '';
            $offer->email_instructions = isset($_POST['email']) ? $_POST['email'] : '';
            $offer->email_instructions = isset($_POST['email']) ? $_POST['email'] : '';
            $offer->caps = isset($_POST['caps']) ? $_POST['caps'] : '';
            $offer->show_mail_list = isset($_POST['suppression_list']) ? $_POST['suppression_list'] : '';
            $offer->session_hours = isset($_POST['session_hours']) ? $_POST['session_hours'] : '';
            $offer->redirect_offer_id = isset($_POST['redirect_offer_id']) ? $_POST['redirect_offer_id'] : '';
            $offer->session_impression_hours = isset($_POST['session_impression_hours']) ? $_POST['session_impression_hours'] : '';
            $offer->enable_offer_whitelist = isset($_POST['enable_offer_whitelist']) ? $_POST['enable_offer_whitelist'] : '';
            $offer->note = isset($_POST['note']) ? $_POST['note'] : '';
            $offer->status = isset($_POST['status']) ? $_POST['status'] : '';
            $offer->geo_targeting = isset($_POST['geo_targeting']) ? $_POST['geo_targeting'] : '';
            $offer->createtime = date("Y-m-d H:i:s", time());
            $offer->type = isset($_POST['types']) ? $_POST['types'] : '';
            //默认不进行自动更新
            $offer->create_self = 1;
            $result = array('error_code'=> 1,'msg'=>'error!');
            if(!$offer->save()){
                $result = array(
                    'error_code'=> 0,
                    'msg'=>$offer->errors,
                );
            }
            if($_POST['caps'] == 1){
                $caps = new JoyOffersCaps();
                $caps->offer_id = $offer->attributes['id'];
                $caps->daily_con = isset($_POST['daily_con']) ? $_POST['daily_con'] : '';
                $caps->month_con = isset($_POST['month_con']) ? $_POST['month_con'] : '';
                $caps->daily_rev = isset($_POST['daily_re']) ? $_POST['daily_re'] : '';
                $caps->month_rev = isset($_POST['month_re']) ? $_POST['month_re'] : '';
                $caps->month_pay = isset($_POST['month_pay']) ? $_POST['month_pay'] : '';
                if(!$caps->save()){
                    $result = array(
                        'error_code'=> 0,
                        'msg'=>$caps->errors,
                    );
                }
            }
            if($result['error_code'] != 0){
                $result = array('error_code' => 1, 'msg' => 'success!');
            }
            echo CJSON::encode($result);
            exit();
        }
        //查询所有分类
        $types = JoyOffersType::model()->findAll();
        $advertises = JoySystemUser::model()->findAllByAttributes(array(
            'groupid' => 4
        ));
        if (empty($advertises)) {
            $this->redirect(array(
                'advertiser/create'
            ));
        }
        $this->render('offer/offer-add',array(
            'advertises'=>$advertises,
            'types'=>$types
        ));
    }

    /**
     * 显示offer 详细
     */
    public function actionOfferDetail(){
        $id = Yii::app()->request->getParam('offer_id');
        if(empty($id)){
            throw new ErrorException("所要查看的offer不存在", 404);
        }
        $offers = joy_offers::model()->findByPk($id);
        if(empty($offers)){
            throw new ErrorException("offer is not exist or has been deleted");
        }
        $offers = Common::instantPayout($offers,$this->user['userid']);
        $affiliates = JoySystemUser::model()->findAllByAttributes(array(
            'groupid'=>AFF_GROUP_ID
        )); 
    	$mckey_whitelist	=	'OFFER_WHITELIST_'.$id;
    	$mcres				=	CfgAR::getMem( array('link'=>CACHE, 'key'=>$mckey_whitelist) );
    	if(0 == $mcres['ret'] && isset($mcres['data']) && !empty($mcres['data'])){
    		//缓存查到
    		$ip_list		=	$mcres['data'];
    	}else{
    		//缓存未查到，查数据库
    		$ip_list = JoyOfferWhitelist::model()->findAllByAttributes(array(
    			'offerid'=>$id,
    			'status'=>1
    		));
    		//存入缓存
    		if(!empty($ip_list)){
    			CfgAR::setMc( array('link'=>CACHE, 'key'=>$mckey_whitelist, 'data'=>$ip_list, 'time'=>86400*7) );
    		}
    	}
    	//白名单 end
        $cuts = array();
/*        if(in_array($this->user['groupid'],array(ADMIN_GROUP_ID,MANAGER_GROUP_ID))){
            $cuts = JoyOfferCut::model()->with('aff')->findAllByAttributes(array('offer_id'=>$id));
        }*/
        $caps = JoyOffersCaps::model()->findByAttributes(array('offer_id'=>$id));
        $this->render('offer/offer-detail',array(
            'offers'=>$offers,
            'affiliates'=>$affiliates,
        	'ip_list'=>$ip_list,
            'caps'=>$caps,
            'cuts'=>$cuts
        ));
    }

    /**
     * 查询statistics数据表
     */
    public function actionStatistics(){
        $data['name']   =   $name   =   Yii::app()->request->getParam('name');
        $data['offer_id']   =   $offer_id = trim(Yii::app()->request->getParam('offer_id'));
        $advertiser_id =   Yii::app()->request->getParam('advertiser');
        $type   =   Yii::app()->request->getParam('type');
        $criteria = new CDbCriteria();
        $advertisers    =   JoySystemUser::model()->findAllByAttributes(array('groupid'=>4));

        $rows=array();// we need this array
        foreach($advertisers as $i=>$advertiser) {
            $select =   '';
            $rows[$i]=$advertiser->attributes;
            if($advertiser['id'] == $advertiser_id){
                $select   =   'selected';
            }
            $rows[$i]['select']=$select;
        }
        $data['advertisers'] =  $rows;
        if($type == 'search'){
            if($name){
                $criteria->addCondition(" name like '%$name%'");      //根据条件查询
            }
            if($offer_id){
                $criteria->addCondition(" t.id = $offer_id");
            }
            if($advertiser_id && $advertiser_id != ''){
                $criteria->addCondition(" advertiser_id = $advertiser_id");
            }
        }
        $criteria->order = 't.id ASC';
        $count = joy_offers::model()->count($criteria);
        $pager = new CPagination($count);
        $pager->pageSize=30;
        $pager->applyLimit($criteria);
        $pager->route   =   'offer/statistics';
        $data['pages']  =   $pager;
        if(4 == $this->user['groupid']){
            //广告主
            $criteria->condition=	'`t`.advid=:advid';
            $searchArr			=	array( ':advid'=>$this->user['userid'] );
            $criteria->params	=	$searchArr;
        }elseif(5 == $this->user['groupid']){
            //渠道商
            $criteria->condition=	'`t`.affid=:affid';
            $searchArr			=	array( ':affid'=>$this->user['userid'] );
            $criteria->params	=	$searchArr;
        }
        $criteria->with 	= 	array('offer','advertiser','affiliate');
        $data['offers'] = JoyTransactionIncome::model()->findAll($criteria);//查找出所有的offer数据
        $this->render('offer/offer-statistics',$data);
    }
    /**
     * 增加joy_offer_pixels表里的数据
     */
    public function actionAddPixels(){ 
        if(!empty($_POST)){
            $isNew = JoyOfferCut::model()->findByAttributes(array(
                'offer_id'=>$_POST['offerid'],
                'aff_id'=>$_POST['affid']
            ));
            if(empty($isNew)){
                $cut = new JoyOfferCut();
                $cut->offer_id = $_POST['offerid'];
                $cut->aff_id = $_POST['affid'];
                $code = Yii::app()->request->getParam('code');
                if(!empty($code)){
                    $cut->postback = $code;
                }
                if(!$cut->save()){
                    $result = array(
                        'error_code'=>0,
                        'msg'=>'pixels信息添加失败！'
                    );
                    throw new ErrorException("pixels保存出错");
                }
                $result = array(
                    'error_code'=>1,
                    'msg'=>'pixels信息添加成功！'
                );
            }else{
                //如果这条记录存在，那么只是更新code
                $code = Yii::app()->request->getParam('code');
                if(!empty($code)){
                    $isNew->postback = $code;
                }
                if(!$isNew->save()){
                    $result = array(
                        'error_code'=>3,
                        'msg'=>'pixels信息更新失败！'
                    );
                    Common::jsalerturl('postback update error!');
                }
                $result = array(
                    'error_code'=>4,
                    'msg'=>'pixels信息更新成功！'
                );
            }
        }else{
            $result = array(
                'error_code'=>0,
                'msg'=>'pixels信息添加失败！'
            );
        }
        echo CJSON::encode($result);
    }
    public function actionOfferUrlTest(){
        $this->render('offer/offer-test');
    }

    /**
     * just edit detail
     */
    public function actionEditDetail(){
        $offer_id = Yii::app()->request->getParam('id');
        if(empty($offer_id)){
            throw new ErrorException('the offer had already deleted!');
        }
        $data['offers'] = joy_offers::model()->findByPk($offer_id);
        $advertiser_id  =   $data['offers']['advertiser_id'];
        $advertisers = JoySystemUser::model()->findAllByAttributes(array('groupid'=>4));
        $rows=array();// we need this array
        foreach($advertisers as $i=>$advertiser) {
            $select =   '';
            $rows[$i]=$advertiser->attributes;
            if($advertiser['id'] == $advertiser_id){
                $select   =   'selected';
            }
            $rows[$i]['select']=$select;
        }
        //查询所有types
        $types = JoyOffersType::model()->findAll();
        $types_select = $data['offers']['type'];
        $types_select_arr = explode(',',$types_select);
        $rows_type = array();
        if(!empty($types)) {
            foreach ($types as $i => $type) {
                $rows_type = array();
                if (in_array($type['id'], $types_select_arr)) {
                    $rows_type[$i] = array_filter($type->attributes, 'strlen');
                    $rows_type[$i]['selected'] = array();
                }
            }
        }
        $data['types'] = $rows_type;
        $data['advertises'] =  $rows;
        $this->render('offer/edit_detail',$data);
    }

    /**
     * 更新详细列表
     */
    public function actionUpdateDetail(){
        $id = Yii::app()->request->getParam('id');
        if(!empty($id)){
            $offer = joy_offers::model()->findByAttributes(array('id'=>$_GET['id']));
            $offer->name = Yii::app()->request->getParam('name');
            $offer->advertiser_id = Yii::app()->request->getParam('advertiser');
            $offer->description = Yii::app()->request->getParam('description');
            $offer->preview_url = Yii::app()->request->getParam('preview_url');
            $offer->offer_url = Yii::app()->request->getParam('offer_url');
            $offer->protocol = Yii::app()->request->getParam('conversion_tracking');
            $offer->status = Yii::app()->request->getParam('status');
            if(!empty(Yii::app()->request->getParam('expiration_date'))){
                $offer->expiration_date = Yii::app()->request->getParam('expiration_date');
            }
            $categories = '';
            $offer->offer_category = $categories;
            $offer->ref_id = Yii::app()->request->getParam('reference_id');
            $offer->note = Yii::app()->request->getParam('note');
            $offer->geo_targeting = Yii::app()->request->getParam('country');
            if(!$offer->update()){
                throw new ErrorException('error about the server');
            }
            Common::jsalerturl('success!', $this->createUrl('offer/offerdetail',array('offer_id'=>$id)));
        }else{
            throw new ErrorException('error about the data');
	}
    }

    /**
     *跳转到更新页面
     */
    public function actionEditAccount(){
        $offer_id = Yii::app()->request->getParam('id');
        if(!$offer_id){
            throw new ErrorException('data error');
        }

        $page			=	Yii::app()->request->getParam('page');
        $size			=	30;

        $data['count']	=	$count = JoyOfferPixels::model()->count('offerid='.$offer_id);
        $jpurl		=	$this->createUrl('offer/editaccount',array('id'=>$offer_id));
        $jparams	=	array();

        /*
        if( isset($_GET['affid']) && intval($_GET['affid']) > 0 ){
            $affid	=	intval($_GET['affid']);
            $jparams[]	=	'affid='.$affid;
        }
        */
        $page_obj			=	new Page();
        if( 0 < count($jparams) ){
            $tmp_str	=	strpos($jpurl, '?') ? '&' : '?';
            $jpurl		.=	$tmp_str.join('&', $jparams);
        }

        $page_control	=	$page_obj->pageCut($count,$page,$size);
        $data['page']	=	$page_control['page'];


        $data['fenyecode']	=	$page_obj->createPage( array('url'=>$jpurl, 'size'=>$count, 'page'=>$data['page'], 'pageSize'=>$size) );


        $data['pixels'] = JoyOfferPixels::model()->with('affiliate','cups','offer')->findAll(
            'offerid='.$offer_id,array('offset'=>$page_control['query_count'],
            'limit'=>$size,
            'order'=>'`t`.`id` DESC'));
        $this->render('offer/offer_account_settings',$data);
    }

    /**
     * 删除pixel表记录
     */
    public function actionDeleteAccount(){
        $pixel_id = Yii::app()->request->getParam('pixel_id');
        $offer_id = Yii::app()->request->getParam('offer_id');
        if(!$pixel_id || !$offer_id){
            throw new ErrorException('this data is not exist');
        }
        if(JoyOfferPixels::model()->deleteByPk($pixel_id)){
            Common::jsalerturl('success',$this->createUrl('offer/editaccount',array('id'=>$offer_id)));
        }else{
            Common::jsalerturl('failed!please try again!');
        }
    }

    /**
     * 更新详细数据
     */
    public function actionUpdateAccount(){
        $type = Yii::app()->request->getParam('type');
        if($type == 'all'){
            $data = Yii::app()->request->getParam('data');
            if(!$data){
                throw new ErrorException('data error!');
            }
            $trans = Yii::app()->db->beginTransaction();
            try {
                if (! $this->update_account_all($data)) {
                    $trans->rollback();
                    echo 'failed';
                }else{
                    $trans->commit();
                    echo 'success';
                }
            }catch (Exception $e){
                $trans->rollback();
                echo $e->getMessage();
            }
        }
    }
    /**
     *  修改所有参数
     */
    private function update_account_all($data){
        $rs = false;
        foreach($data as $key){
            $pixel = JoyOfferPixels::model()->findByAttributes(array('id'=>$key[0]));
            if (!$pixel) {
                throw new ErrorException('data pixel error');
            }
            $pixel->payout = $key[1];
            $pixel->revenue = $key[2];
            $pixel->daily_con = $key[3];
            $pixel->month_con = $key[4];
            $pixel->daily_pay = $key[5];
            $pixel->month_pay = $key[6];
            $pixel->daily_rev = $key[7];
            $pixel->month_rev = $key[8];
            if($pixel->update()){
                $rs = true;
            }
        }
        return $rs;
    }


    /**
     *跳转到编辑payout界面
     */
    public function actionEditPayout(){
        $offer_id  = Yii::app()->request->getParam('id');
        $offer = joy_offers::model()->findByAttributes(array('id'=>$offer_id));
        if(empty($offer)){
            throw new ErrorException('the offer already be deleted');
        }
        if(!$offer){
            throw new ErrorException('the offer already be deleted');
        }
        $this->render('offer/edit_payout',array(
            'offer'=>$offer,
        ));
    }

    /**
     *更新Payout
     */
    public function actionUpdatePayout(){
        $offer_id = Yii::app()->request->getParam('id');
        if(empty($offer_id)){
            throw new ErrorException('there is an error about the offer!');
        }
        $offer = joy_offers::model()->findByAttributes(array('id'=>$offer_id));
        $currency = Yii::app()->request->getParam('currency');
        $revenueType = Yii::app()->request->getParam('revenueType');
        $payoutType = Yii::app()->request->getParam('payoutType');
        $maxRevenue = Yii::app()->request->getParam('max_revenue');
        $maxpayout = Yii::app()->request->getParam('max_payout');
//        $goalName = Yii::app()->request->getParam('offerDefaultGoalName');

        if(!empty($currency)){
            $offer->currency = $currency;
        }
        $offer->revenue = $maxRevenue;
        $offer->revenue_type = $revenueType;
        $offer->payout_type = $payoutType;
        $offer->payout = $maxpayout;
        if(!$offer->update()){
            throw new ErrorException();
        }
        Common::jsalerturl('success!');
    }
    
    /**
     * offer,IP白名单
     */
    public function actionWhitelist() {
    	$ret_array		=	array('ret'=>-1, 'msg'=>'', 'occur'=>'OfferController_actionWhitelist', 'error'=>'');
    	$offerid		=	isset($_GET['offerid']) ? $_GET['offerid'] : '';
    	if(empty($offerid)){
    		$this->redirect(array("offer/list"));
    		exit;
    	}
    	do{
    		try{
    			//先查缓存
    			$mckey_whitelist	=	'OFFER_WHITELIST_'.$offerid;
    			$mcres				=	CfgAR::getMem( array('link'=>CACHE, 'key'=>$mckey_whitelist) );
    			if(0 == $mcres['ret'] && isset($mcres['data']) && !empty($mcres['data'])){
    				//缓存查到
    				$ip_list		=	$mcres['data'];
    			}else{
    				//缓存未查到，查数据库
    				$ip_list = JoyOfferWhitelist::model()->findAllByAttributes(array(
    						'offerid'=>$offerid,
    						'status'=>1
    				));
    				//存入缓存
    				if(!empty($ip_list)){
    					CfgAR::setMc( array('link'=>CACHE, 'key'=>$mckey_whitelist, 'data'=>$ip_list, 'time'=>86400*7) );
    				}
    			}
    			 
    			$ret_array['ret']	=	0;
    			$ret_array['data']	=	$ip_list;
    			 
    		}catch (Exception $e){
    			$ret_array['ret']	=	100;
    			$ret_array['msg']	=	'服务器忙，请稍后再试';
    			$ret_array['error']	=	$e->getMessage();
    			break;
    		}
    	}while (0);
    	if(0 != $ret_array['ret']){
    		Common::toTxt(array('file'=>'Log_OfferController_actionWhitelist.txt', 'txt'=>'Input:'.var_export($_REQUEST, true).'|Output:'.var_export($ret_array, true)));
    	}
    	$ret_array['offerid']	=	$offerid;
    	$this->render('offer/whitelist', $ret_array);
    }
    /**
     * 增加offer,IP白名单
     */
    public function actionWhitelistAdd() {
    	$ret_array		=	array('ret'=>-1, 'msg'=>'', 'occur'=>'OfferController_actionWhitelistAdd', 'error'=>'');
    	$offerid		=	isset($_GET['offerid']) ? $_GET['offerid'] : '';
    	if(empty($offerid)){
    		$this->redirect(array("offer/list"));
    		exit;
    	}
    	do{
    		try{
    			if(!empty($_POST)){
    				$offerid			=	isset($_POST['offerid']) ? $_POST['offerid'] : $offerid;
    				$content_type		=	isset($_POST['content_type']) ? intval($_POST['content_type']) : 1;
    				$content			=	isset($_POST['content']) ? $_POST['content'] : '';
    				$status				=	isset($_POST['status']) ? $_POST['status'] : 1;
    
    				if(!in_array($content_type, array(1,2,3))){
    					$content_type	=	1;
    				}
    				$ip_list	=	JoyOfferWhitelist::model()->findByAttributes(array(
    						'offerid'=>$offerid,
    						'content'=>$content
    				));
    				if(empty($ip_list)){
    					$offer				=	new JoyOfferWhitelist();
    					$offer->offerid		=	$offerid;
    					$offer->content_type=	$content_type;
    					$offer->content		=	$content;
    					$offer->status		=	$status;
    					$offer->createtime	=	date('Y-m-d H:i:s');
    					if(!$offer->save()){
    						$ret_array['ret']	=	5;
    						$ret_array['msg']	=	'Fail';
    						break;
    					}
    				}elseif(0 == $ip_list['status']){
    					$count = JoyOfferWhitelist::model()->updateByPk($ip_list['id'],array('status'=>1));
    				}
    				//删缓存
    				$mckey_whitelist	=	'OFFER_WHITELIST_'.$offerid;
    				CfgAR::delMc( array('link'=>CACHE, 'key'=>$mckey_whitelist) );
    				
    				//修改成功
    				$ret_array['ret']	=	0;
    				$ret_array['msg']	=	'success';
    				$detail_url			=	$this->createUrl("offer/offerdetail", array('offer_id'=>$offerid));
    				//$this->redirect($detail_url);
    				Common::jsalerturl($ret_array['msg'], $detail_url);
    				exit;
    			}
    			 
    		}catch (Exception $e){
    			$ret_array['ret']	=	100;
    			$ret_array['msg']	=	'服务器忙，请稍后再试';
    			$ret_array['error']	=	$e->getMessage();
    			break;
    		}
    	}while (0);
    	if(0 != $ret_array['ret'] && -1 != $ret_array['ret']){
    		Common::toTxt(array('file'=>'Log_OfferController_actionWhitelistAdd.txt', 'txt'=>'Input:'.var_export($_REQUEST, true).'|Output:'.var_export($ret_array, true)));
    	}
    	$this->render('offer/whitelist_add', array('offerid'=>$offerid));
    }
    /**
     * 增加offer,IP白名单
     */
    public function actionWhitelistEdit() {
    	$ret_array		=	array('ret'=>-1, 'msg'=>'', 'occur'=>'OfferController_actionWhitelistAdd', 'error'=>'', 'data'=>array());
    	$offerid		=	isset($_GET['offerid']) ? $_GET['offerid'] : '';
    	$id				=	isset($_GET['id']) ? $_GET['id'] : '';
    	if(empty($offerid)){
    		//没有offerid，跳转回offer/list
    		$this->redirect(array("offer/list"));
    		exit;
    	}
    	if(empty($id)){
    		//没有白名单id，跳转回offer/whitelist
    		$this->redirect($this->createUrl("offer/whitelist", array('offerid'=>$offerid)));
    		exit;
    	}
    	 
    	do{
    		try{
    			$ip_list = JoyOfferWhitelist::model()->findByAttributes(array(
    					'id'		=>	$id,
    					'offerid'	=>	$offerid
    			));
    			if(empty($ip_list)){
    				//白名单id跟offerid不匹配，跳转回offer/whitelist
    				$this->redirect($this->createUrl("offer/whitelist", array('offerid'=>$offerid)));
    				exit;
    			}
    			 
    			if(!empty($_POST)){
    				$content	=	isset($_POST['content']) ? $_POST['content'] : '';
    
    				$count		=	JoyOfferWhitelist::model()->updateByPk($id, array('content'=>$content, 'status'=>1));
    
    				$ret_array['ret']	=	0;
    				$ret_array['msg']	=	'success';
    				
    				//删缓存
    				$mckey_whitelist	=	'OFFER_WHITELIST_'.$offerid;
    				CfgAR::delMc( array('link'=>CACHE, 'key'=>$mckey_whitelist) );
    				
    				//修改成功，跳转回详情页面
    				$detail_url			=	$this->createUrl("offer/offerdetail", array('offer_id'=>$offerid));
    				//$this->redirect($detail_url);
    				Common::jsalerturl($ret_array['msg'], $detail_url);
    				exit;
    			}
    
    		}catch (Exception $e){
    			$ret_array['ret']	=	100;
    			$ret_array['msg']	=	'服务器忙，请稍后再试';
    			$ret_array['error']	=	$e->getMessage();
    			break;
    		}
    	}while (0);
    	if(0 != $ret_array['ret'] && -1 != $ret_array['ret']){
    		Common::toTxt(array('file'=>'Log_OfferController_actionWhitelistEdit.txt', 'txt'=>'Input:'.var_export($_REQUEST, true).'|Output:'.var_export($ret_array, true)));
    	}	
    	$ret_array['data']['ip_info']	=	isset($ip_list) ? $ip_list : '';
    	$ret_array['data']['offerid']	=	$offerid;
    	$ret_array['data']['id']		=	$id;
    	$this->render('offer/whitelist_edit', $ret_array);
    }
    /**
     * 增加offer,IP白名单
     */
    public function actionWhitelistDel() {
    	$ret_array		=	array('ret'=>-1, 'msg'=>'', 'occur'=>'OfferController_actionWhitelistDel', 'error'=>'');
    	do{
    		try{
    			$id			=	isset($_GET['id']) ? $_GET['id'] : '';
    			$offerid	=	isset($_GET['offerid']) ? $_GET['offerid'] : '';
    			if(empty($id) || empty($offerid)){
    				$ret_array['ret']	=	5;
    				$ret_array['msg']	=	'Parameter is missing';
    			}
    			$ip_list = JoyOfferWhitelist::model()->findByAttributes(array(
    					'id'		=>	$id,
    					'offerid'	=>	$offerid
    			));
    			if(empty($ip_list)){
    				$ret_array['ret']	=	6;
    				$ret_array['msg']	=	'Fail';
    				break;
    			}
    			$count	=	JoyOfferWhitelist::model()->updateByPk($id,array('status'=>0));
    			
    			//删缓存
    			$mckey_whitelist	=	'OFFER_WHITELIST_'.$offerid;
    			CfgAR::delMc( array('link'=>CACHE, 'key'=>$mckey_whitelist) );
    			
    			//修改成功
    			$ret_array['ret']	=	0;
    			$ret_array['msg']	=	'success';
    			 
    		}catch (Exception $e){
    			$ret_array['ret']	=	100;
    			$ret_array['msg']	=	'服务器忙，请稍后再试';
    			$ret_array['error']	=	$e->getMessage();
    			break;
    		}
    	}while (0);
    	if(0 != $ret_array['ret'] && -1 != $ret_array['ret']){
    		Common::toTxt(array('file'=>'Log_OfferController_actionWhitelistDel.txt', 'txt'=>'Input:'.var_export($_REQUEST, true).'|Output:'.var_export($ret_array, true)));
    	}
    	echo CfgAR::enJson($ret_array);
    }

    /*
    *Edit caps
    */
    public function actionEditCaps(){
        $offer_id = Yii::app()->request->getParam('id');
        $type = Yii::app()->request->getParam('type');
        if($type == 'update'){
            $cap_id =  Yii::app()->request->getParam('cap_id');
            $cap = JoyOffersCaps::model()->findByAttributes(array('id'=>$cap_id));
            if(!$cap){
                $cap = new JoyOffersCaps();
                $cap->daily_con = Yii::app()->request->getParam('conversion_cap');
                $cap->month_con = Yii::app()->request->getParam('monthly_conversion_cap');
                $cap->daily_pay = Yii::app()->request->getParam('payout_cap');
                $cap->month_pay = Yii::app()->request->getParam('monthly_payout_cap');
                $cap->daily_rev = Yii::app()->request->getParam('revenue_cap');
                $cap->month_rev = Yii::app()->request->getParam('monthly_revenue_cap');
                $cap->offer_id = $offer_id;
                if(!$cap->save()){
                    throw new ErrorException('save failed');
                }
            }else {
                $cap->daily_con = Yii::app()->request->getParam('conversion_cap');
                $cap->month_con = Yii::app()->request->getParam('monthly_conversion_cap');
                $cap->daily_pay = Yii::app()->request->getParam('payout_cap');
                $cap->month_pay = Yii::app()->request->getParam('monthly_payout_cap');
                $cap->daily_rev = Yii::app()->request->getParam('revenue_cap');
                $cap->month_rev = Yii::app()->request->getParam('monthly_revenue_cap');
                if (!$cap->update()) {
                    throw new ErrorException('error update');
                }
            }
            Common::jsalerturl('success',$this->createUrl('offer/editcaps',array('id'=>$offer_id)));
        }
        $data['offer'] = $offer = joy_offers::model()->findByAttributes(array('id'=>$offer_id));
        if(!$offer) {
            throw new ErrorException('an error about the data');
        }
        $data['caps'] = JoyOffersCaps::model()->findByAttributes(array('offer_id'=>$offer_id));
        $this->render('offer/edit_caps',$data);
    }

    /**
     *图像上传
     */
    public function actionOfferThumbnail(){
        $offer_id = Yii::app()->request->getParam('id');
        $type = Yii::app()->request->getParam('type');
        $offer = joy_offers::model()->findByAttributes(array('id'=>$offer_id));
        if(empty($offer_id)){
            throw new ErrorException('data error!');
        }
        if($type == 'upload'){
            if(empty($_FILES['upfile']['name'])){
               throw new ErrorException('data is empty!');
            }
            if(!$offer){
                throw new ErrorException('the offer already be deleted');
            }
            $file_upload = new FileUpload();
            if(!$file_upload->upload('upfile')){
                Common::jsalerturl($file_upload->getErrorMsg());
            }
            $path = $file_upload->getFilePath();
            $offer->thumbnail = $path;
            if(!$offer->update()){
                throw new ErrorException('save failed!');
            }
            Common::jsalerturl('success!');
        }
        $this->render('offer/offer_thumbnail',array('offer_id'=>$offer_id));
    }

    //更新cut
    public function actionUpdateCut(){
        $data =   Yii::app()->request->getParam('data');
        $conn   =   Yii::app()->db->beginTransaction();
        foreach($data as $key){
            $id =   $key[1];
            $cut    =   $key[2];
            $user   =   JoySystemUser::model()->findByPk($id);
            $user->cutcount =   $cut;
            if(!$user->update()){
                echo $user->errors;
                $conn->rollback();
                die();
            }
        }
        $conn->commit();
        echo 'success';
    }

    //生成excel文档
    public function actionToExcel(){
        $email  =   $this->user['email'];
        $user   =   JoySystemUser::model()->findByAttributes(array('email'=>$email));
        $criteria 			= 	new CDbCriteria;
        if(4 == $this->user['groupid']){
            //广告主
            $criteria->condition=	'`t`.advid=:advid';
            $searchArr			=	array( ':advid'=>$this->user['userid'] );
            $criteria->params	=	$searchArr;
        }elseif(5 == $this->user['groupid']){
            //渠道商
            $criteria->condition=	'`t`.affid=:affid';
            $searchArr			=	array( ':affid'=>$this->user['userid'] );
            $criteria->params	=	$searchArr;
        }
        $criteria->with 	= 	array('offer','advertiser','affiliate');
        $data['offers']		=	JoyTransactionIncome::model()->findAll($criteria);

        $headArr    = array('ID','Offer','AffiliateID','Affiliate','AdvertiserID','Advertiser','Sesstion Date / Time'
            ,'Date / Time' ,'Date / Time Diff' ,'Payout' ,'Sesstion IP' ,'Conversion IP' ,'Transaction ID');
        /** Error reporting */
        error_reporting(E_ALL);
        ini_set('display_errors', TRUE);
        ini_set('display_startup_errors', TRUE);
        date_default_timezone_set('Europe/London');
        if (PHP_SAPI == 'cli')
            die('This example should only be run from a Web Browser');
        /** Include PHPExcel */
        Yii::$enableIncludePath = false;
        Yii::import('application.extensions.PHPExcel.PHPExcel', 1);
    // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();

        //设置表头
        $key = ord("A");
        foreach($headArr as $v){
            $colum = chr($key);
            $objPHPExcel->setActiveSheetIndex(0) ->setCellValue($colum.'1', $v);
            $key += 1;
        }
        // 内容
        foreach($data['offers'] as $key=>$value) {
            if(in_array($this->user['groupid'], array('1', '2'))){
                $objPHPExcel->getActiveSheet(0)->setCellValue('A' . ($key + 3), $value['id']);
                $objPHPExcel->getActiveSheet(0)->setCellValue('B' . ($key + 3), $value['offer']['name']);
                $objPHPExcel->getActiveSheet(0)->setCellValue('C' . ($key + 3), $value['affid']);
                $objPHPExcel->getActiveSheet(0)->setCellValue('D' . ($key + 3), $value['affiliate']['company']);
                $objPHPExcel->getActiveSheet(0)->setCellValue('E' . ($key + 3), $value['advid']);
                $objPHPExcel->getActiveSheet(0)->setCellValue('F' . ($key + 3), $value['advertiser']['company']);
                $objPHPExcel->getActiveSheet(0)->setCellValue('G' . ($key + 3), $value['transactiontime']);
                $objPHPExcel->getActiveSheet(0)->setCellValue('H' . ($key + 3), $value['createtime']);
                $objPHPExcel->getActiveSheet(0)->setCellValue('I' . ($key + 3), $this->timediff(strtotime($value['createtime']), strtotime($value['transactiontime'])));
                $objPHPExcel->getActiveSheet(0)->setCellValue('J' . ($key + 3), $value['payout']);
                $objPHPExcel->getActiveSheet(0)->setCellValue('K' . ($key + 3), $value['serverip']);
                $objPHPExcel->getActiveSheet(0)->setCellValue('L' . ($key + 3), $value['clientip']);
                $objPHPExcel->getActiveSheet(0)->setCellValue('M' . ($key + 3), $value['transactionid']);
                $row    =   $key + 3;
                $objPHPExcel->getActiveSheet()->mergeCells("M$row:O$row");
            }else{
                $objPHPExcel->getActiveSheet(0)->setCellValue('A' . ($key + 3), $value['id']);
                $objPHPExcel->getActiveSheet(0)->setCellValue('B' . ($key + 3), $value['offer']['name']);
                if(in_array($this->user['groupid'], array( '3'))){
                    $objPHPExcel->getActiveSheet(0)->setCellValue('C' . ($key + 3), $value['affid']);
                }
                 if (in_array($this->user['groupid'], array( '6'))) {
                     $objPHPExcel->getActiveSheet(0)->setCellValue('C' . ($key + 3), $value['advertiser']['company']);
                 }
                $objPHPExcel->getActiveSheet(0)->setCellValue('D' . ($key + 3), $value['transactiontime']);
                $objPHPExcel->getActiveSheet(0)->setCellValue('E' . ($key + 3), $value['createtime']);
                $objPHPExcel->getActiveSheet(0)->setCellValue('F' . ($key + 3), $this->timediff(strtotime($value['createtime']), strtotime($value['transactiontime'])));
                $objPHPExcel->getActiveSheet(0)->setCellValue('G' . ($key + 3), $value['payout']);
                $objPHPExcel->getActiveSheet(0)->setCellValue('H' . ($key + 3), $value['serverip']);
                $objPHPExcel->getActiveSheet(0)->setCellValue('I' . ($key + 3), $value['clientip']);
                $objPHPExcel->getActiveSheet(0)->setCellValue('J' . ($key + 3), $value['transactionid']);
                $row    =   $key + 3;
                $objPHPExcel->getActiveSheet()->mergeCells("J$row:L$row");
            }
        }
        $fileName   =   $user['title'] . '报表';
        //重命名表
        $objPHPExcel->getActiveSheet()->setTitle('Simple');
        //设置活动单指数到第一个表,所以Excel打开这是第一个表
        $objPHPExcel->setActiveSheetIndex(0);
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
            ->setLastModifiedBy("Maarten Balliauw")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="JoyDream_'.$fileName.'.xlsx"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    }

    /**
     * offer,模拟点击
     */
    public function actionMNClickList() {
    	$ret_array		=	array('ret'=>-1, 'msg'=>'', 'occur'=>'OfferController_actionMNClickList', 'error'=>'');
    	
    	do{
    		try{
    			//查数据库
    			$connection	=	Yii::app()->db;
    			$sql 		=	'SELECT * FROM joy_c_offers WHERE status in (1,2) ORDER BY id DESC';
    			$click_con	=	$connection->createCommand($sql);
    			$click_list	=	$click_con->queryAll();
    		
    			$ret_array['ret']	=	0;
    			$ret_array['data']	=	$click_list;
    
    		}catch (Exception $e){
    			$ret_array['ret']	=	100;
    			$ret_array['msg']	=	'服务器忙，请稍后再试';
    			$ret_array['error']	=	$e->getMessage();
    			break;
    		}
    	}while (0);
    	if(0 != $ret_array['ret']){
    		Common::toTxt(array('file'=>'Log_OfferController_actionMNClickList.txt', 'txt'=>'Input:'.var_export($_REQUEST, true).'|Output:'.var_export($ret_array, true)));
    	}
    	$this->render('offer/mnclick_list', $ret_array);
    }
    /**
     * offer,添加模拟点击
     */
    public function actionMNClickAdd() {
    	$ret_array		=	array('ret'=>-1, 'msg'=>'', 'occur'=>'OfferController_actionMNClickAdd', 'error'=>'');
    	
    	do{
    		try{
    			if(!empty($_POST)){
    				$offerid		=	isset($_POST['offerid']) ? $_POST['offerid'] : '';
    				$affid			=	isset($_POST['affid']) ? intval($_POST['affid']) : '';
    				$start_date		=	isset($_POST['start_date']) ? $_POST['start_date'] : '';
    				$end_date		=	isset($_POST['end_date']) ? $_POST['end_date'] : '';
    				$nation			=	isset($_POST['nation']) ? $_POST['nation'] : '';
    				$max_total		=	isset($_POST['max_total']) ? $_POST['max_total'] : '';
    				$execute_total	=	isset($_POST['execute_total']) ? $_POST['execute_total'] : '';
    				$hour_total		=	isset($_POST['hour_total']) ? $_POST['hour_total'] : '';
    				$status			=	isset($_POST['status']) ? $_POST['status'] : 2;
    				$createtime		=	isset($_POST['createtime']) ? $_POST['createtime'] : '';
    
    				if(empty($offerid) || empty($affid)){
    					$ret_array['ret']	=	5;
    					$ret_array['msg']	=	'Please enter Offer id and Affiliate id';
    					break;
    				}
    				if(empty($start_date) || empty($end_date)){
    					$ret_array['ret']	=	6;
    					$ret_array['msg']	=	'Please enter start date and end date';
    					break;
    				}
    				if(empty($max_total) || empty($hour_total)){
    					$ret_array['ret']	=	7;
    					$ret_array['msg']	=	'Please enter max total and hour total';
    					break;
    				}
    				if(!in_array($status, array(0,1,2))){
    					$status	=	2;
    				}
    				//检查是否有同offerid、affid的任务
    				/*
	    			$click_list = JoyCOffers::model()->findAll(array(
	    				'order' => 'id DESC',
	    				'condition' => 'offerid=:offerid AND affid=:affid AND status IN (:status)',
	    				'params' => array(':offerid'=>$offerid, ':affid'=>$affid, ':status'=>'1,2'),
	    			));
	    			*/
    				if(strlen($start_date) < 12){
    					$start_date	=	substr($start_date, 0, 10).' 00:00:00';
    				}
    				if(strlen($end_date) < 12){
    					$end_date	=	substr($end_date, 0, 10).' 23:59:59';
    				}
    				
    				$c_offer				=	new JoyCOffers();
    				$c_offer->offerid		=	$offerid;
    				$c_offer->affid			=	$affid;
    				$c_offer->start_date	=	$start_date;
    				$c_offer->end_date		=	$end_date;
    				$c_offer->nation		=	$nation;
    				$c_offer->max_total		=	$max_total;
    				$c_offer->hour_total	=	$hour_total;
    				$c_offer->execute_total	=	0;
    				$c_offer->status		=	$status;
    				$c_offer->createtime	=	date('Y-m-d H:i:s');
    				if(!$c_offer->save()){
    					$ret_array['ret']	=	5;
    					$ret_array['msg']	=	'Fail';
    					break;
    				}
    				
    				//添加成功
    				$ret_array['ret']	=	0;
    				$ret_array['msg']	=	'success';
    				$detail_url			=	$this->createUrl("offer/mnclicklist");
    				//$this->redirect($detail_url);
    				Common::jsalerturl($ret_array['msg'], $detail_url);
    				exit;
    			}
    
    		}catch (Exception $e){
    			$ret_array['ret']	=	100;
    			$ret_array['msg']	=	'服务器忙，请稍后再试';
    			$ret_array['error']	=	$e->getMessage();
    			break;
    		}
    	}while (0);
    	if(0 != $ret_array['ret'] && -1 != $ret_array['ret']){
    		Common::toTxt(array('file'=>'Log_OfferController_actionMNClickAdd.txt', 'txt'=>'Input:'.var_export($_REQUEST, true).'|Output:'.var_export($ret_array, true)));
    	}
    	$this->render('offer/mnclick_add');
    }
    /**
     * offer,修改模拟点击
     */
    public function actionMNClickEdit(){
    	$ret_array		=	array('ret'=>-1, 'msg'=>'', 'occur'=>'OfferController_actionMNClickEdit', 'error'=>'', 'data'=>array());
    	$id				=	isset($_GET['id']) ? $_GET['id'] : '';
    	if(empty($id)){
    		//没有id，跳转回offer/list
    		$this->redirect(array("offer/mnclicklist"));
    		exit;
    	}
    
    	do{
    		try{
    			$click_list = JoyCOffers::model()->findByPk($id);
    			if(empty($click_list)){
    				$this->redirect($this->createUrl("offer/mnclicklist"));
    				exit;
    			}
    			$ret_array['data']['info']	=	array(
    					'id'=>$click_list->id,
    					'offerid'=>$click_list->offerid,
    					'affid'=>$click_list->affid,
    					'start_date'=>$click_list->start_date,
    					'end_date'=>$click_list->end_date,
    					'nation'=>$click_list->nation,
    					'max_total'=>$click_list->max_total,
    					'execute_total'=>$click_list->execute_total,
    					'hour_total'=>$click_list->hour_total,
    					'status'=>$click_list->status,
    					'createtime'=>$click_list->createtime,
    			);
    
    			if(!empty($_POST)){
    				$start_date		=	isset($_POST['start_date']) ? $_POST['start_date'] : '';
    				$end_date		=	isset($_POST['end_date']) ? $_POST['end_date'] : '';
    				$nation			=	isset($_POST['nation']) ? $_POST['nation'] : '';
    				$max_total		=	isset($_POST['max_total']) ? $_POST['max_total'] : '';
    				$hour_total		=	isset($_POST['hour_total']) ? $_POST['hour_total'] : '';
    				
    				$update_arr		=	array();
    				if(!empty($start_date)){
    					if(strlen($start_date) < 12){
    						$start_date	=	substr($start_date, 0, 10).' 00:00:00';
    					}
    					$update_arr['start_date']	=	$start_date;
    				}
    				if(!empty($end_date)){
    					if(strlen($end_date) < 12){
    						$end_date	=	substr($end_date, 0, 10).' 23:59:59';
    					}
    					$update_arr['end_date']		=	$end_date;
    				}
    				$update_arr['nation']	=	$nation;
    				
    				if(!empty($max_total)){
    					$update_arr['max_total']	=	$max_total;
    				}
    				if(!empty($hour_total)){
    					$update_arr['hour_total']	=	$hour_total;
    				}
    
    				$count		=	JoyCOffers::model()->updateByPk($id, $update_arr);
    
    				$ret_array['ret']	=	0;
    				$ret_array['msg']	=	'success';
    
    				//修改成功，跳转回详情页面
    				$detail_url			=	$this->createUrl("offer/mnclickedit", array('id'=>$id));
    				//$this->redirect($detail_url);
    				Common::jsalerturl($ret_array['msg'], $detail_url);
    				exit;
    			}
    
    		}catch (Exception $e){
    			$ret_array['ret']	=	100;
    			$ret_array['msg']	=	'服务器忙，请稍后再试';
    			$ret_array['error']	=	$e->getMessage();
    			break;
    		}
    	}while (0);
    	if(0 != $ret_array['ret'] && -1 != $ret_array['ret']){
    		Common::toTxt(array('file'=>'Log_OfferController_actionMNClickEdit.txt', 'txt'=>'Input:'.var_export($_REQUEST, true).'|Output:'.var_export($ret_array, true)));
    	}
    	$ret_array['data']['id']		=	$id;
    	$this->render('offer/mnclick_edit', $ret_array['data']);
    }
    /**
     * offer,删除模拟点击
     */
    public function actionMNClickUpdateStatus() {
    	$ret_array		=	array('ret'=>-1, 'msg'=>'', 'occur'=>'OfferController_actionMNClickUpdateStatus', 'error'=>'');
    	do{
    		try{
    			$id			=	isset($_GET['id']) ? $_GET['id'] : '';
    			$status		=	isset($_GET['status']) ? $_GET['status'] : false;
    			if(empty($id) || (0 !== $status && empty($status))){
    				$ret_array['ret']	=	5;
    				$ret_array['msg']	=	'Parameter is missing';
    			}
    			if(!in_array($status, array(0,1,2))){
    				$status	=	2;
    			}
    			$click_list = JoyCOffers::model()->findByAttributes(array(
    					'id'	=>	$id
    			));
    			if(empty($click_list)){
    				$ret_array['ret']	=	6;
    				$ret_array['msg']	=	'Fail';
    				break;
    			}
    			$count	=	JoyCOffers::model()->updateByPk($id,array('status'=>$status));
    			
    			//修改成功
    			$ret_array['ret']	=	0;
    			$ret_array['msg']	=	'success';
    
    		}catch (Exception $e){
    			$ret_array['ret']	=	100;
    			$ret_array['msg']	=	'服务器忙，请稍后再试';
    			$ret_array['error']	=	$e->getMessage();
    			break;
    		}
    	}while (0);
    	if(0 != $ret_array['ret'] && -1 != $ret_array['ret']){
    		Common::toTxt(array('file'=>'Log_OfferController_actionMNClickUpdateStatus.txt', 'txt'=>'Input:'.var_export($_REQUEST, true).'|Output:'.var_export($ret_array, true)));
    	}
    	echo CfgAR::enJson($ret_array);
    }
    //计算时差
    function timediff($begin_time,$end_time)
    {
        if($begin_time < $end_time){
            $starttime = $begin_time;
            $endtime = $end_time;
        }
        else{
            $starttime = $end_time;
            $endtime = $begin_time;
        }
        $timediff = $endtime-$starttime;
        $days = intval($timediff/86400);
        $remain = $timediff%86400;

        //$hours = intval($remain/3600);
        $hours = intval($timediff/3600);

        $remain = $remain%3600;
        $mins = intval($remain/60);
        $secs = $remain%60;
        $res = array("day" => $days,"hour" => $hours,"min" => $mins,"sec" => $secs);
        //return $res;

        $hour_str		=	$hours;
        if(strlen($hours) < 2){
            $hour_str	=	'0'.$hours;
        }
        $mins_str		=	$mins;
        if(strlen($mins) < 2){
            $mins_str	=	'0'.$mins;
        }
        $sesc_str		=	$secs;
        if(strlen($secs) < 2){
            $sesc_str	=	'0'.$secs;
        }

        return $hour_str.':'.$mins_str.':'.$sesc_str;
    }

    //offer分类
    public function actionTypeManager(){
        $ret_array		=	array('ret'=>-1, 'msg'=>'', 'occur'=>'OfferController_actionMNClickUpdateStatus', 'error'=>'');
        $type   =   Yii::app()->request->getParam('type');
        if($type == 'add'){
            $keywords   =   Yii::app()->request->getParam('keywords');
            $name_en   =   Yii::app()->request->getParam('name_en');
            if($keywords && $name_en){
                $joy_type    =   new JoyOffersType();
                $joy_type->key_words    =   trim($keywords);
                $joy_type->type_name_en =   trim($name_en);
                if(!$joy_type->save()){
                    $ret_array['ret']   =   0;
                    $ret_array['msg']  =   'Add Failed!';
                }else{
                    $ret_array['ret']   =   1;
                    $ret_array['msg']   =   'Add Success!';
                }
            }
            $this->render('offer/type_add',$ret_array);
            exit;
        }
        if($type == 'edit'){
            $type_id    =   Yii::app()->request->getParam('id');
            $data['type']   =   JoyOffersType::model()->findByPk($type_id);
            $this->render('offer/type_add',$data);
            exit;
        }
        if($type == 'update'){
            $keywords   =   Yii::app()->request->getParam('keywords');
            $name_en   =   Yii::app()->request->getParam('name_en');
            $type_id    =   Yii::app()->request->getParam('id');
            $ret_array['type']    =   $type   =   JoyOffersType::model()->findByPk($type_id);
            $type->key_words    =   trim($keywords);
            $type->type_name_en =   trim($name_en);
            if($type->update()){
                $data['ret']   =   1;
                $data['msg']  =   'Update Success!';
            }else{
                $data['ret']   =   0;
                $data['msg']  =   'Update Failed!';
            }
        }
        if($type == 'delete'){
            $type_id    =   Yii::app()->request->getParam('id');
            if(JoyOffersType::model()->deleteByPk($type_id)){
                $data['ret']   =   1;
                $data['msg']   =   'Success';
            }else {
                $data['ret'] = 0;
                $data['msg'] = 'Failed';
            }
        }
        $criteria = new CDbCriteria();
        $criteria->order = 'id ASC';
        $count = JoyOffersType::model()->count($criteria);
        $pager = new CPagination($count);
        $pager->pageSize=30;
        $pager->applyLimit($criteria);
        $pager->route   =   'offer/typemanager';
        $data['pages']  =   $pager;
        $data['types'] = JoyOffersType::model()->findAll($criteria);//查找出所有的offer数据
        $this->render('offer/offer_types',$data);
    }
    //offer扣量  新增
    public function actionOfferCut(){
        $data['msg'] = '';
        $data['offerid'] = $offer_id = Yii::app()->request->getParam('offerid');
        $num = Yii::app()->request->getParam('cut_num');
        $affiliates = Yii::app()->request->getParam('affs');
        $payout = Yii::app()->request->getParam('payout');
        //进入页面
        if(!empty($affiliates)){
            $aff_arr = explode(',',$affiliates);
            foreach($aff_arr as $aff){
                $cut = JoyOfferCut::model()->findByAttributes(array('aff_id'=>$aff,'offer_id'=>$offer_id));
                if(empty($cut)){
                    $cut = new JoyOfferCut();
                }
                $cut->aff_id = $aff;
                $cut->offer_id = $offer_id;
                $cut->cut_num = $num;
                $cut->payout = $payout;
                if(!$cut->save()){
                    $data['ret'] = 1;
                    $data['msg'] = 'error!';
                    break;
                }
                $data['ret'] = 0;
                $data['msg'] = 'success';
            }
        }
        $data['affiliates'] = JoySystemUser::model()->findAllByAttributes(array('groupid'=>AFF_GROUP_ID));
        $this->render('offer/offer_cut',$data);
    }

    //offer 扣量修改
    public function actionEditOfferCut(){
        $data = array('ret'=>0,'msg'=>'');
        $data['offerid'] = $offerid = Yii::app()->request->getParam('offerid');
        $affid = Yii::app()->request->getParam('affid');
        $type = Yii::app()->request->getParam('type');
        $cut_num = Yii::app()->request->getParam('cut_num');
        $payout = Yii::app()->request->getParam('payout');
        do {
            if (!empty($offerid) && !empty($affid)) {
                if ($type == 'delete') {
                    $rs = JoyOfferCut::model()->deleteAllByAttributes(array('offer_id' => $offerid, 'aff_id' => $affid));
                    if (!$rs) {
                        $data['ret'] = 0;
                        $data['msg'] = 'error!';
                        break;
                    }
                    $this->redirect($this->createUrl('offer/offerdetail',array('offer_id'=>$offerid)));
                    exit;
                }
                if($type == 'edit'){
                    $data['cut'] = JoyOfferCut::model()->findByAttributes(array('offer_id'=>$offerid,'aff_id'=>$affid));
                    $data['affiliate'] = JoySystemUser::model()->findByPk($affid);
                    $data['ret'] = 1;
                    break;
                }
                if($type == 'update'){
                    $cut = JoyOfferCut::model()->findByAttributes(array('offer_id'=>$offerid,'aff_id'=>$affid));
                    $cut->cut_num = $cut_num;
                    $cut->payout = $payout;
                    if(!$cut->update()){
                        $data['ret'] = 0;
                        $data['msg'] = 'update failed!';
                        break;
                    }
                    $data['cut'] = JoyOfferCut::model()->findByAttributes(array('offer_id'=>$offerid,'aff_id'=>$affid));
                    $data['affiliate'] = JoySystemUser::model()->findByPk($affid);
                    $data['ret'] = 1;
                }
            }
        }while(0);
        if($data['ret'] == 1 && $type != 'edit'){
            $data['msg'] = 'Success!';
        }
        $this->render('offer/offer_cut',$data);
    }

    //ajax获取国家信息
    public function actionGetCountriesInfo(){
        $maxRows = Yii::app()->request->getParam('maxRows');
        $name_startsWith = Yii::app()->request->getParam('name_startsWith');
        $rows = array();
        $criteria=new CDbCriteria;
        $criteria->addCondition("abbr like '%$name_startsWith%'");
        $criteria->limit = $maxRows;
        $countries = JoyOfferCountry::model()->findAll($criteria);
        foreach ($countries as $i => $country) {
            $rows[$i]['value'] = $country['id'];
            $rows[$i]['label'] = $country['abbr'];
        }
        echo json_encode($rows);
    }
    //offer更新记录
    public function actionUpdateLogs(){
        $criteria = new CDbCriteria();
        $count = JoyUpdateLogs::model()->count($criteria);
        $criteria->order = 'time desc';
        $pager = new CPagination($count);
        $pager->pageSize=30;
        $pager->applyLimit($criteria);
        $pager->route   = 'offer/updatelogs';
        $offers = JoyUpdateLogs::model()->findAll($criteria);
        $this->render('offer/update_logs',array(
            'offers'=>$offers,
            'pages'=>$pager
        ));
    }

    //修改定置
    public function actionChangeRecommend(){
        $offer_id = Yii::app()->request->getParam('id');
        $offer = joy_offers::model()->findByPk($offer_id);
        $offer->recommend == 1 ? $offer->recommend = 0 : $offer->recommend = 1;
        if($offer->save()){
            $data['ret'] = 1;
            $data['msg'] = 'update success!';
        }else{
            $data['ret'] = 0;
            $data['msg'] = 'update failed!';
        }
        echo json_encode($data);
    }

    //offer分类
    public function actionJumpOffer(){
        $ret_array		=	array('ret'=>-1, 'msg'=>'', 'occur'=>'OfferController_actionJumpOffer', 'error'=>'');
        $type   =   Yii::app()->request->getParam('type');
        $affiliate_ids = '';
        try {
            if ($type == 'add') {
                $offers_id = Yii::app()->request->getParam('offers');
                $offer_types = Yii::app()->request->getParam('offer_types');
                $affiliates = Yii::app()->request->getParam('affiliates');
                $offer_url = Yii::app()->request->getParam('offer_url');
                $status = Yii::app()->request->getParam('status');
                $affid = Yii::app()->request->getParam('affid');
                $jump_offer_id = Yii::app()->request->getParam('jump_offer_id');
                $countries = Yii::app()->request->getParam('countries');
                $country_status = Yii::app()->request->getParam('country_status');
                if (empty($affiliates)) {
                    Common::jsalerturl('Please select the affiliate!');
                    exit();
                }
                if(empty($offer_url) && empty($offers_id)){
                    Common::jsalerturl('Please make sure where did you want to jump for!');
                    exit();
                }
                //检查是否有一样的并且开启状态的跳转
                foreach ($offers_id as $offerid) {
                    $jump = JoyJump::model()->findAllByAttributes(array('offerid' => $offerid, 'status' => 1,'affid'=>$affid));
                    if (!empty($jump)) {
                        Common::jsalerturl("please make sure the offer is not jump:offerid:$offerid");
                        exit();
                    }
                }
                foreach ($affiliates as $affiliate) {
                    $affiliate_ids .= $affiliate . ',';
                }
                $affiliate_ids = substr($affiliate_ids, 0, strlen($affiliate_ids) - 1);
                foreach ($offers_id as $offer_id) {
                    $joyjump = new JoyJump();
                    $joyjump->time = date('Y-m-d H:i:s');
                    $joyjump->offerid = $offer_id;
                    $joyjump->affid = $affiliate_ids;
                    $joyjump->status = $status;
                    $joyjump->type = $offer_types;
                    if (0 == $offer_types) {
                        $joyjump->offer_url = $jump_offer_id;
                    } else {
                        if(!strstr($offer_url,"http:")){
                            $offer_url = 'http://' . $offer_url;
                        }
                        $joyjump->offer_url = $offer_url;
                    }
                    $joyjump->country_status = $country_status;
                    $joyjump->user_id = $this->user['userid'];
                    if (1 == $country_status) {
                        $joyjump->countries = $countries;
                    }
                    $result_status = $joyjump->save();
                    if($result_status) {
                        Common::record(array('userid' => $this->user['userid'], 'typeid' => 2, 'jumpid' => JoyJump::$db->lastInsertID));
                    }
                }
                if (!empty($result_status)) {
                    $js_msg = 'success!';
                } else {
                    $js_msg = 'failed!';
                }
            }
            if ($type == 'add_jump') {
                $data['affiliates'] = JoySystemUser::model()->findAllByAttributes(array('groupid' => AFF_GROUP_ID));
//                $data['affiliates'] = JoySites::getSitesInformation();
                $criteria_offer = new CDbCriteria();
                $criteria_offer->select = 'id,name';
                $criteria_offer->addCondition('status=1');
                $data['offers'] = joy_offers::model()->findAll($criteria_offer);
                $this->render('offer/jump_add_page', $data);
                exit;
            }
            if($type == 'close'){
                $jump_id = Yii::app()->request->getParam('id');
                $status = Yii::app()->request->getParam('status');
                $status_code = $status == 0 ? 1 :  0;
                $result_status = JoyJump::model()->updateByPk($jump_id,array('status'=>$status_code));
                if(!empty($result_status)){
                    $js_msg = 'success';
                }else{
                    $js_msg = 'failed';
                }
            }
            if ($type == 'delete') {
                $type_id = Yii::app()->request->getParam('id');
                Common::record(array('userid'=>$this->user['userid'],'typeid'=>1,'jumpid'=>$type_id));
                if (JoyJump::model()->deleteByPk($type_id)) {
                    $data['ret'] = 1;
                    $data['msg'] = 'Success';
                } else {
                    $data['ret'] = 0;
                    $data['msg'] = 'Failed';
                }
            }
            $criteria = new CDbCriteria();
            $criteria->order = ' t.id  DESC';
            $count = JoyJump::model()->count($criteria);
            $pager = new CPagination($count);
            $pager->pageSize = 30;
            $pager->applyLimit($criteria);
            $pager->route = 'offer/jumpoffer';
            $data['pages'] = $pager;
            $data['jumps'] = JoyJump::model()->with('users')->findAll($criteria);//查找出所有的offer数据
            if (!empty($js_msg)) {
                $data['js_msg'] = $js_msg;
            }
            $this->render('offer/jump_list', $data);
        }catch (Exception $e){
//            var_dump($e->getMessage());
            Common::jsalerturl('System Error!');
        }
    }

    public function actionJumpTask(){
        $type = Yii::app()->request->getParam('type');
        $taskid = Yii::app()->request->getParam('taskid');
        $send_url = Yii::app()->request->getParam('running_url');
        $msg = '';
        if('dl' == $type){
            if(JoyJumpTask::model()->deleteByPk($taskid)){
                $msg = 'Success';
            }else{
                $msg = 'Failed';
            }
        }elseif('audit' == $type){
            $audit_date = date('Y-m-d H:i:s');
            $audit = $this->user['userid'];
            $params = array('jump_status'=>1,'audit_date'=>$audit_date,'auditor'=>$audit);
            $jump_task = JoyJumpTask::model()->findByPk($taskid);
            if($jump_task->task_type == 1){
                $params += array('back_url'=>$send_url);
            }else if($jump_task->task_type == 0){
                $params += array('now_url'=>$send_url);
            }else if($jump_task->task_type == 2){
                $params += array('affid'=>$send_url);
            }
            if(JoyJumpTask::model()->updateByPk($taskid,$params)){
                $joy_jump = JoyJumpTask::model()->findByPk($taskid);
                $apply_id = $joy_jump->applicant_id;
                JoyMessageMgr::sendMessage(0,$this->user['userid'],array($apply_id),array('content'=>"Your application had been accomplish:the Id is {$taskid}"));
                $msg = 'Success';
            }else{
                $msg = 'Failed';
            }
        }
        $tasks = JoyJumpTask::model()->findAll();
        $this->render('offer/jump_task',array(
            'tasks'=>$tasks,
            'msg'=>$msg,
        ));
    }

    public function actionOfferListShow(){
        $cuts = JoyOfferCut::model()->findAllByAttributes(array('isshow'=>1));
        $this->render('offer/offer_show_list',array(
            'list'=>$cuts
        ));
    }

    public function actionOfferShow(){
        $affids = Yii::app()->request->getParam('affiliates');
        $offerids = Yii::app()->request->getParam('offerids');
        $advids = Yii::app()->request->getParam('advids');
        $type = Yii::app()->request->getParam('type');
        $msg = '';
        if('add' == $type){
            if(empty($affids)){
                Common::jsalerturl('Please select affiliates');
            }
            foreach($affids as $affid){
                if(!empty($offerids)){
                    foreach($offerids as $offerid){
                        $cut = JoyOfferCut::model()->findByAttributes(array('offer_id'=>$offerid,'aff_id'=>$affid));
                        $offer_by_pk = joy_offers::model()->findByPk($offerid);
                        if(empty($cut)){
                            $cut = new JoyOfferCut();
                        }
                        $cut->offer_id = $offerid;
                        $cut->aff_id = $affid;
                        $cut->isshow = 1;
                        $cut->advid = $offer_by_pk['advertiser_id'];
                        if(!$cut->save()){
                            Common::jsalerturl('Error!');
                        }
                    }
                }
                if(!empty($advids)){
                    foreach($advids as $advid) {
                        $db = Yii::app()->db;
                        $command = $db->createCommand("select * from joy_offers where advertiser_id = $advid and status = 1");
                        $offers_by_advid = $command->queryAll($command);
                        foreach ($offers_by_advid as $item) {
                            $cut = JoyOfferCut::model()->findByAttributes(array('offer_id' => $item['id'], 'aff_id' => $affid));
                            if (empty($cut)) {
                                $cut = new JoyOfferCut();
                            }
                            $cut->offer_id = $item['id'];
                            $cut->aff_id = $affid;
                            $cut->isshow = 1;
                            $cut->advid = $advid;
                            if (!$cut->save()) {
                                Common::jsalerturl('Error!');
                            }
                        }
                    }
                }
            }
            $msg = 'success';
        }elseif('update' == $type){

        }
        $affs = JoySystemUser::model()->findAllByAttributes(array('groupid'=>AFF_GROUP_ID));
        $offers = joy_offers::model()->findAllByAttributes(array('status'=>1));
        $advs = JoySystemUser::model()->findAllByAttributes(array('groupid'=>ADVERTISER_GROUP_ID));
        $this->render('offer/offer_show',array(
            'affiliates'=>$affs,
            'offers'=>$offers,
            'advertisers'=>$advs,
            'msg'=>$msg,
        ));
    }
}
