<?php

/**
 * Created by PhpStorm.
 * User: land.zhang
 * Date: 2016/8/20
 * Time: 11:42
 * 对外公共方法
 */
class CommonController extends Controller
{
    public function filters(){
    }
    /**
     * 用户登录
     */
    public function actionLogin()
    {
        $model=new LoginForm;
        if(isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate() && $model->login()) {
                $this->redirect(Yii::app()->createUrl('index/index'));
            }
        }

        $this->layout = '//layouts/login';
        // display the login form
        $this->render('login',array('model'=>$model));
    }
    /**
     * 用户登出
    */
    public function actionLogout(){
        Yii::app()->user->logout();
        $this->redirect('/');
    }
    
}