<?php
date_default_timezone_set('Asia/Shanghai');
define('ENVIRONMENT_PRODUCE', false);
if(ENVIRONMENT_PRODUCE){
    error_reporting(0);
    $yii=dirname(__FILE__).'/../yii/yiilite.php';
    $conf=dirname(__FILE__).'/config.pro.php';
    $config=dirname(__FILE__).'/protected/config/main.pro.php';
    defined('YII_DEBUG') or define('YII_DEBUG',false);
    defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',1);
}else{
    error_reporting(E_ALL);
    $yii=dirname(__FILE__).'/../yii/yii.php';
    $conf=dirname(__FILE__).'/config.dev.php';
    $config=dirname(__FILE__).'/protected/config/main.api.php';
    defined('YII_DEBUG') or define('YII_DEBUG',false);
    defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);
}

// remove the following line when in production mode
require_once ($conf);
require_once($yii);

Yii::createWebApplication($config)->run();