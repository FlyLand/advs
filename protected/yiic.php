<?php
define('ENVIRONMENT_PRODUCE', false);
$yiic	=	dirname(dirname(dirname(__FILE__))).'/yii/yiic.php';
if( ENVIRONMENT_PRODUCE ){
	$define	=	dirname( dirname( __FILE__ ) ).'/config.pro.php';
	$config	=	dirname(  __FILE__ ).'/config/main.pro.php';
}else{
	$define	=	dirname( dirname( __FILE__ ) ).'/config.dev.php';
	$config	=	dirname(  __FILE__ ).'/config/main.dev.php';
}
require_once($define);
require_once($yiic);
