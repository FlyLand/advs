<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/7/28
 * Time: 11:40
 */
class ErrorController extends Controller
{
    public function actionIndex(){
        $error = Yii::app()->errorHandler->error;
        $this->layout = '//layouts/main';
        $this->render('error',array('error'=>$error));
    }
}