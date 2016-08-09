<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/4/1
 * Time: 12:12
 */
class SiteController extends Controller
{
    public function __construct($id, $module)
    {
        $this->checkAction();
    }
    public function actionIndex(){
        if($this->user['groupid'] == BUSINESS_GROUP_ID){
            $sites = JoySites::getSitesInformationWithBusiness($this->user['userid']);
        }else{
            $sites = JoySites::getSitesInformation();
        }
        $this->render('sites/index',array('sites'=>$sites));
    }
    public function actionDl(){
        $id = Yii::app()->request->getParam('id');
        $site_id = Yii::app()->request->getParam('site_id');
        $result = JoySites::deleteAffiliateId($id,$site_id);
        if($result){
            $msg = 'Success';
        }else{
            $msg = 'Failed';
        }
        Common::jsalerturl($msg);
    }

    public function actionDeleteSite(){
        $site_id = Yii::app()->request->getParam('id');
        JoySites::model()->deleteAllByAttributes(array('site_id'=>$site_id));
        $result = JoySystemUser::model()->deleteByPk($site_id);
        if($result){
            $msg = 'Success';
        }else{
            $msg = 'Failed';
        }
        Common::jsalerturl($msg);
    }
    public function actionAddRel(){
        $affiliates = Yii::app()->request->getParam('affiliates');
        $site_id = Yii::app()->request->getParam('id');
        $msg = "Failed";
        do {
            if (empty($affiliates)) {
                $msg = 'Success!';
                break;
            }
            $affiliates_str = implode(',', $affiliates);
            $affiliates_db = JoySites::model()->getResult('affids', " and site_id=$site_id");
            if (empty($affiliates_db)) {
                $msg = 'System Error!';
                break;
            }
            if(!empty($affiliates_db['affids'])){
                $affiliates_str = $affiliates_db['affids'] . ',' . $affiliates_str;
            }
            $db = Yii::app()->db;
            $sql = "update joy_sites set affids = '$affiliates_str' where site_id=$site_id";
            $command = $db->createCommand($sql);
            if ($command->query()) {
                $msg = 'Success';
            }
        }while(0);
        Common::jsalerturl($msg);
    }

    public function actionEdit(){
        if(in_array($this->user['groupid'],$this->manager_group)) {
            $id = Yii::app()->request->getParam('id');
        }else{
            $id = $this->user['userid'];
        }
        if (!empty($id)) {
            $data['site'] = JoySystemUser::model()->findByPk($id);
            $data['business'] = JoySystemUser::model()->findAllByAttributes(array('groupid' => BUSINESS_GROUP_ID));
            $affiliates = JoySites::getResult('*', " and site_id = $id");
            if (!empty($affiliates)) {
                $data['affids'] = $affiliates['affids'];
            }
            $data['payment'] = JoyPayment::model()->findByAttributes(array('affid' => $id));
            $data['affiliates'] = JoySystemUser::model()->findAllByAttributes(array('groupid' => AFF_GROUP_ID));
            $this->render('sites/edit', $data);
        } else {
            Common::jsalerturl('error about the server');
            die();
        }
    }

    public function actionCreate(){
        $params = JoySystemUser::getParams();
        $data['business'] = JoySystemUser::model()->findAllByAttributes(array('groupid'=>BUSINESS_GROUP_ID));
        $data['affiliates'] = JoySystemUser::model()->findAllByAttributes(array('groupid'=>AFF_GROUP_ID));
        $affiliates_arr = Yii::app()->request->getParam('affiliates');
        $data['msg'] = '';
        if(!empty($params['company'])){
            $data = JoySystemUser::createUser(SITE_GROUP_ID,$params);
            if($data['result']){
                if (!empty($affiliates_arr)) {
                    $sites = new JoySites();
                    $sites->affids = implode(',', $affiliates_arr);
                    $sites->site_id = $data['result'];
                    if ($sites->save()) {
                        $data['msg'] = 'Success';
                    } else {
                        $data['msg'] = 'Add Rel Failed';
                    }
                }
            }
            Common::jsalerturl($data['msg']);
        }
        $this->render('sites/create',$data);
    }
    public function actionAffiliateList(){
        $rel = JoySites::getAllRel($this->user['userid']);
        $this->render('sites/affiliate_index',
            array('rel'=>$rel));
    }
}
