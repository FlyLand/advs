<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/7/31
 * Time: 14:57
 */
class AffiliateAPIController extends Controller
{
    public function __construct(){
        parent::checkAction();//验证权限
    }

    //返回给下游API
    public function actionGetList(){
        $rows=array();
        $sign	=	Yii::app()->request->getParam('sign');
        $site_id =   Yii::app()->request->getParam('siteid');
        $page   =   Yii::app()->request->getParam('page');
        $limit   =   Yii::app()->request->getParam('limit');
        $size   =   30;//默认返回数组
//        $country    =   Yii::app()->request->getParam('country');
        if(empty($sign) || empty($site_id)){
            //直接返回500错误码
            header("http/1.1 500 internal server error");
            exit;
        }
        $whitelist =   JoyAffiliateWhitelist::model()->findByPk($site_id);
        $ip	=	Common::getIp();
        //根据请求IP查询IP白名单中的token信息
        if(!$whitelist || !$ip  ==  $whitelist->context || !1  ==  $whitelist->status){
            $rows['error']	=	'true';
            $rows['error_msg']	=	'error ip!';
            $offers_json	=	CJSON::encode($rows);
            echo $offers_json ;
            exit;
        }
        //更新登录时间
        $whitelist->last_login_time =   date('Y-m-d h:m:s');
        if(!$whitelist->update()){
            $rows['error']	=	'true';
            $rows['error_msg']	=	'server update error!';
            $offers_json	=	CJSON::encode($rows);
            echo $offers_json ;
            exit;
        }
        $token	=	$whitelist['token'];
        //判断sign是否一致
        if($token	!== $sign){
            $rows['error']	=	'true';
            $rows['error_msg']	=	'your token has expired ,please use the new one! ';
            $offers_json	=	CJSON::encode($rows);
            echo $offers_json ;
            exit;
        }
        $condition  =   ' status = 1 ';
//        if(!empty($country)){
//            $condition  .=   " and find_in_set('$country',geo_targeting)";
//        }
        //分页
        $all_count  =   joy_offers::model()->count('status=1');
        $count  =   joy_offers::model()->count($condition);
        $all_page   =   intval($all_count / $size);
        if($all_count % $size != 0) ++$all_page;
        $page_helper    =   new Page();
        if(!empty($limit))  $size=$limit;
        if(empty($page))   $page='1';
        $data   =   $page_helper->pageCut($count,$page,$size);
        $page   =   $data['page'];//返回处理后的页数
        $query_count    =   $data['query_count'];//查询的数目
        $limit_re  =   " limit $size offset $query_count";
        //查询所有符合条件的offer
        $offers		=	joy_offers::model()->findAll(array(
            'select'=>'name,description,offer_url,currency,
			payout,thumbnail',
            'condition'=>$condition . $limit_re,
        ));

        if($offers){
            $rows['error']	=	'false';
            $rows['error_msg']	=	'';
            $rows['page']	=	$page;
            $rows['count']	=	$count;
            $rows['all_page']	=	$all_page;
            $rows['all_count']	=	$all_count;
            foreach($offers as $i=>$offer) {
                $rows[$i]=array_filter($offer->attributes,'strlen');
            }
        }else{
            $rows['error']	=	'true';
            $rows['error_msg']	=	'no data here';
        }
        $offers_json	=	CJSON::encode($rows);
        echo $offers_json ;
    }

    /**
     *  后台录入下游白名单
     */
    public function actionTokenList(){
        $page   =   Yii::app()->request->getParam('page');
        $title   =   Yii::app()->request->getParam('title');
        $type   =   Yii::app()->request->getParam('type');
        $whiteList  =   '';
        if($type == 'search'){
            $ids_arr    =   JoySystemUser::model()->findAll(array(
                'select'=>'id',
                'condition'=>"title like '%$title%' and groupid = 5"
            ));
            $rows   =   array();
            if($ids_arr){
                foreach($ids_arr as $i=>$id) {
                    $rows[$i]=array_filter($id->attributes,'strlen');
                }
                $ids_str = '';
                foreach($rows as $rs){
                    $ids_str    .=   $rs['id'] . ',';
                }
                $ids    =   substr($ids_str,0,strlen($ids_str)-1);
                $condition  = '(' . $ids . ')';
                $count  =   JoyAffiliateWhitelist::model()->count("affiliate_id in $condition");
                $whiteList   =   JoyAffiliateWhitelist::model()->model()->findAll(array(
                    'condition'=>"affiliate_id in $condition"
                ));
            }else{
                $count  =   0;
            }
        }else{
            $count = JoyAffiliateWhitelist::model()->count();
            $whiteList  =   JoyAffiliateWhitelist::model()->findAll();
        }
        $jpurl  = $this->createUrl('affiliateapi/tokenlist');
        $size			=	30;
        $jparams		=	array();
        $page_obj			=	new Page();
        $page_control		=	$page_obj->pageCut($count,$page,$size);
        $data['page']		=	$page_control['page'];
        /*
         if( isset($_GET['affid']) && intval($_GET['affid']) > 0 ){
        $affid	=	intval($_GET['affid']);
        $jparams[]	=	'affid='.$affid;
        }
        */
        if( 0 < count($jparams) ){
            $tmp_str		=	strpos($jpurl, '?') ? '&' : '?';
            $jpurl			.=	$tmp_str.join('&', $jparams);
        }

        $page_obj			=	new Page();
        $fenyecode	=	$page_obj->createPage( array('url'=>$jpurl, 'size'=>$count, 'page'=>$data['page'], 'pageSize'=>$size));
        $this->render('affiliates/token_list',array(
            'whiteList'=>$whiteList,
            'fenyecode'=>$fenyecode,
            'count'=>$count,
            'title'=>$title,
        ));
    }

    //后台添加白名单
    public function actionAddWhite(){
        $type   =   Yii::app()->request->getParam('type');
        if($type    ==  'add'){
            //判断session是否一致，防止植入数据
            $session    =   Yii::app()->session['form_add'];
            $form   =   Yii::app()->request->getParam('form');
            if(!$session == $form){
                unset(Yii::app()->session['form_add']);
                throw new ErrorException('error about your data,please try again!');
            }
            $affiliate_id   =   Yii::app()->request->getParam('affiliate');
            $content_type   =   Yii::app()->request->getParam('content_type');
            $content   =   Yii::app()->request->getParam('content');
            $token   =   Yii::app()->request->getParam('token');
            $status   =   Yii::app()->request->getParam('status');

            $white  =   JoyAffiliateWhitelist::model()->findByAttributes(array('affiliate_id'=>$affiliate_id));
            //判断是否已存在数据库，否则只更新数据库
            if(!empty($white)){
                $white->affiliate_id    =   $affiliate_id;
                $white->context_type    =   $content_type;
                $white->context =   $content;
                $white->token   =   $token;
                $white->status  =   $status;
                if(!$white->update()){
                    unset(Yii::app()->session['form_add']);
                    throw new ErrorException('server exception!');
                }
            }else{
                $white  =   new JoyAffiliateWhitelist();
                $white->affiliate_id    =   $affiliate_id;
                $white->context_type    =   $content_type;
                $white->context =   $content;
                $white->token   =   $token;
                $white->status  =   $status;
                $white->create_time =   date('Y-m-d H:i:s');

                if(!$white->save()){
                    unset(Yii::app()->session['form_add']);
                    throw new ErrorException('server exception!');
                }
            }
            unset(Yii::app()->session['form_add']);
            Common::jsalerturl('success',$this->createUrl('affiliateapi/tokenlist'));
        }
        //查询所有上游名称
        $data['affiliates']  =  JoySystemUser::model()->findAllByAttributes(array('groupid'=>AGENT_USER_GROUP_ID));
        //使用session作为表单令牌
        $data['form']   =   Yii::app()->session['form_add'] =   Common::GetRandChar(array('count'=>16,'type'=>4));
        $this->render('affiliates/add_white',$data);
    }

    /**
     * 得到token
     */
    public function actionGetToken(){
        $ip =   Yii::app()->request->getParam('ip');
        $form =   Yii::app()->request->getParam('form');
        $aff =   Yii::app()->request->getParam('aff_id');
        $form_web   =   Yii::app()->session['form_add'];
        if($form    !=  $form_web || empty($form)){
            $data['ret']    =   1;
            $data['msg']    =   'error!';
        }else{
            if(empty($ip) || empty($aff)){
                throw new ErrorException('error your data!');
            }
            $token_get  =    Common::genToken();
            $data['ret']    =   0;
            $data['data']   =   md5($aff.$token_get.$ip);
        }
        echo CfgAR::enJson($data);
    }

    /*
    *删除白名单
    */
    public function actionDeleteWhite(){
        $white_id   =   Yii::app()->request->getParam('id');
        $type   =   Yii::app()->request->getParam('type');
        $status =   Yii::app()->request->getParam('status');
        $white  =   JoyAffiliateWhitelist::model()->findByPk($white_id);
        if(empty($white)){
            throw new ErrorException('the white list is not exist!');
        }
        if($type !== 'change'){
            if(!$white->delete()){
                throw new ErrorException('server error!');
            }
        }else{
            $status =  $status == 1 ? 0 : 1;
            $white->status  =   $status;
            if(!$white->update()){
                throw new ErrorException('server error!');
            }
        }
        Common::jsalerturl('success',$this->createUrl('affiliateapi/tokenlist'));
    }
}