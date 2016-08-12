<?php
//================以下是系统日志文件目录=====================
define('DIRLOGS', 'G:\php_web\myservice\advs\logs');

define('BASE_DIR', 'G:\php_web\myservice\advs');

define('W_APP_NAME', 'New hasoffer');

//================以下是某些功能需要的常量=====================
define("CLICK_URL", "http://offer.joymedia.mobi/index.php?r=cloud/click&");//点击地址


//================以下是系统常用的常量=====================
define('MEM_KEY',"JIXIN");
define("MAINDB", "db");
define('CACHE', 'cache');//30分钟
define('MEM_USER_LOGIN_TIME', 1800);//登录缓存时长
defined('Admin_MEM_PIX') or define('Admin_MEM_PIX','AdminAgent_');

define('MEM_SYSTEM_USER_LIST',"SYSTEM_USER_LIST");		//系统用户表缓存
define('MEM_SYSTEM_POWER_LIST',"SYSTEM_POWER_LIST");	//系统权限表缓存
define('MEM_SYSTEM_GROUP_LIST',"SYSTEM_GROUP_LIST");	//系统用户组缓存
define('MEM_SYSTEM_GROUP_POWER_LIST',"SYSTEM_GROUP_POWER_LIST");	//系统权限表缓存

define('ADMIN_USER_ID',1);			//管理员ID
define('ADMIN_GROUP_ID',1);			//管理员组ID
define('MANAGER_GROUP_ID',2);			//代理商组ID
define('BUSINESS_GROUP_ID',3);
define('ADVERTISER_GROUP_ID',4);
define('AFF_GROUP_ID',5);//代理商开通的用户组名
define('AM_GROUP_ID',6);		//代理商开通的用户组ID
define('SITE_GROUP_ID',7);
define('FINANCE_GROUP_ID',8);
define('USER_GROUP_ADMIN', 1);			//管理员ID
//define('DEFAULT_OFFER_ID',75942);
define('DEFAULT_OFFER_ID',1);
define('DEFAULT_AFF_ID',1);
define('FEE',20);

define('MIN_PAYOUT',100);
define('PDF_SAVE_PATH','/ddata/nginx/www/advs/upload/pdf/');
define('AGENT_USER_GROUP_ID',1);
define('LINK','http://apbr.paopaogogo.com/index.php?r=api/click');
define('MANAGER_SERVER_NAME','mtpa.paopaogogo.com');
define('OUT_LAND_SERVER_NAME','apbr.paopaogogo.com');
define('IN_LAND_SERVER_NAME','apbs.paopaogogogo.info');
define('SEARCH_SERVER_NAME','');
