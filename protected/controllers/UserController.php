<?php

/**
 * Created by PhpStorm.
 * User: land.zhang
 * Date: 2016/8/16
 * Time: 19:16
 */
class UserController extends Controller
{
    public function filters(){
    }
    
    public function actionList(){
        $criteria = new CDbCriteria();
        $dataProvider = new CActiveDataProvider($criteria);
        $this->render('list',array('dataProvider',$dataProvider));
    }

    /**
     * 登录
     */
    public function actionLogin()
    {
        $model=new LoginForm;
        if(isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate() && $model->login()) {
                $user = JoySystemUser::model()->findByPk(Yii::app()->user->id);
                $reqData = System::getGroupPowerDetail(array('groupid' => $user['groupid'], 'datatype' => 'simple'));//用户登录检查
                $powerinfo = $reqData['data'];
                $actioninfo = array();
                foreach ($powerinfo as $item) {
                    $actioninfo = array_merge($actioninfo, array_keys($item['actions']));
                }
                $session_data = array();
                $session_data['userid'] = Yii::app()->user->id;
                $session_data['email'] = $user['email'];
                $session_data['powerinfo'] = $powerinfo;
                $session_data['actioninfo'] = $actioninfo;
                $session_data['groupid'] = $user['groupid'];
                $session_data['openuser'] = $user['openuser'];
                $session_data['showmenu'] = '';
                $session_data['dtime'] = date('Y-m-d H:i:s');
                Yii::app()->user->setState('userSession', $session_data);
                $this->redirect(Yii::app()->createUrl('index/index'));
            }
        }
        $this->layout = '//layouts/login.php';
        // display the login form
        $this->render('login',array('model'=>$model));
    }
}