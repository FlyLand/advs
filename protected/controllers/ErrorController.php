<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/7/28
 * Time: 11:40
 */
class ErrorController extends Controller
{
    public function actionError(){
        $error = Yii::app()->errorHandler->error;
        $this->render('error',array('error'=>$error));
    }
}