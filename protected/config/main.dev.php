<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.

$basePath = dirname(__FILE__).DIRECTORY_SEPARATOR.'..';
return array(
	'basePath'=>$basePath,
	'name'=>'管理后台',
	'viewPath' => $basePath.'/views',
	'controllerPath' => $basePath.'/controllers',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.components.redisHandle.*',
		'application.dao.*',
	),



	'modules'=>array(
		// uncomment the following to enable the Gii tool
		/*
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'123456',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),*/
	),


	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
		'cache'=>array(
				'class'=>'CFileCache',
				'cachePath'=> '/ddata/nginx/www/advs/protected/runtime/cache/',
		),
		'redis_cache' => array (
			'class' => 'ext.redis.CRedisCache',
			'servers'=>array(
				array(
					'host'=>'127.0.0.1',
					'port'=>6379,
				),
			),

		),
			/*
		'cache' => array(
				'class'     => 'CMemCache',
				'keyPrefix'=>'BDT',
	        	'servers' => array(
	           		 array('host' => '58.221.59.78', 'port' => 21211, 'weight' => 60),
	         ),
		),*/

		// uncomment the following to enable URLs in path-format

		'urlManager'=>array(
			'urlFormat'			=>	'path',
			'showScriptName'	=>	false,
			'appendParams'		=>	false,
			'rules'				=>	array(
				'index'         =>	'index/index',
				'<controller:\w+>/<action:\w+>/<id:\d+>'	=>	'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'				=>	'<controller>/<action>',
			),
		),
		// uncomment the following to use a MySQL database	
		// 用户注册，查询用户是否已经注册
		
		'db'=>array(
				'class'=>'CDbConnection',
				'connectionString' => 'mysql:host=localhost;dbname=project',
				'emulatePrepare' => true,
				'username' => 'land',
				'password' => 'rain',
				'charset' => 'utf8',
				'enableProfiling'=>'true',
		),
		'slave'=>array(
				'class'=>'CDbConnection',
				'connectionString' => 'mysql:host=localhost;dbname=project',
				'emulatePrepare' => true,
				'username' => 'land',
				'password' => 'rain',
				'charset' => 'utf8',
				'enableProfiling'=>'true',
		),	
		

		'errorHandler'=>array(
			// use 'site/error' action to display errors
			// 'errorAction'=>'error/error',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
// 				array(
// 					'class'=>'CWebLogRoute',
// 				),
			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']

	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
		'otherConf'=>require 'otherConf.php',
	),
	'defaultController'=>'index',
	'language'=>"zh_cn",
);
