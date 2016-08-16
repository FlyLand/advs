<?php

/**
 * Created by PhpStorm.
 * User: land.zhang
 * Date: 2016/8/16
 * Time: 19:16
 */
class IndexController extends Controller
{
    public function actionList(){
        $criteria = new CDbCriteria();
        $dataProvider = new CActiveDataProvider($criteria);
        $this->render('list',array('dataProvider',$dataProvider));
    }
}