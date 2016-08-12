<?php
/**
 * Created by PhpStorm.
 * User: land.zhang
 * Date: 2016/8/8
 * Time: 20:23
 */

return array(
    'cssPath'=>Yii::getPathOfAlias('webroot').'/assets/hplus/',
    'replaceParams'=>array(
        'clickid'=>'{clickid}',
        'oid'=>'{oid}',
        'search'=>'{search}',
        'ip'=>'{ip}',
        'payout'=>'{payout}',
        'channel'=>'{affid}',
        'subid'=>'{subid}',
    ),
    'ADMIN_USER_ID'=>1,
    'ADMIN_GROUP_ID'=>1,
    'MANAGER_GROUP_ID'=>2,
    'BUSINESS_GROUP_ID'=>3,
    'ADVERTISER_GROUP_ID'=>4,
    'AFF_GROUP_ID'=>5,
    'AM_GROUP_ID'=>6,
    'SITE_GROUP_ID'=>7,
    'FINANCE_GROUP_ID'=>8,
    'USER_GROUP_ADMIN'=>1,
    'DEFAULT_OFFER_ID'=>1,
    'DEFAULT_AFF_ID'=>1,
    'FEE'=>20,
    'MIN_PAYOUT'=>100,
    'PDF_SAVE_PATH'=>'/ddata/nginx/www/advs/upload/pdf/',
    'AGENT_USER_GROUP_ID'=>1,
    'LINK'=>'http://apbr.paopaogogo.com/index.php?r=api/click',
    'theme_path'=>array(
        'hplus'=>Yii::app()->basePath
    )
);