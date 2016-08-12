<!DOCTYPE html>
<html lang="cn" class="no-js">
<head>
	<meta charset="utf-8" />
	<title>Offer Manager</title>
	<meta content="width=device-width, initial-scale=1.0" name="viewport" />
	<meta content="" name="description" />
	<meta content="" name="author" />
	<meta name="renderer" content="webkit">
	<meta http-equiv="Cache-Control" content="no-siteapp" />

    <link rel="shortcut icon" href="favicon.ico">
    <link href="<?php echo Yii::app()->params['cssPath'];?>css/bootstrap.min14ed.css?v=3.3.6" rel="stylesheet">
    <link href="<?php echo Yii::app()->params['cssPath'];?>/css/font-awesome.min93e3.css?v=4.4.0" rel="stylesheet">

    <link href="<?php echo Yii::app()->params['cssPath'];?>/css/animate.min.css" rel="stylesheet">
    <link href="<?php echo Yii::app()->params['cssPath'];?>/css/style.min862f.css?v=4.1.0" rel="stylesheet">
</head>
<body>
<header class="am-topbar admin-header">
  <div class="am-topbar-brand">
    <strong style="color: #fff;"><img src="../assets/i/adlogo.png" width="28px;" /> Offer Manager</strong>
  </div>

  <button class="am-topbar-btn am-topbar-toggle am-btn am-btn-sm am-btn-success am-show-sm-only" data-am-collapse="{target: '#topbar-collapse'}"><span class="am-sr-only">导航切换</span> <span class="am-icon-bars"></span></button>
  <div class="am-collapse am-topbar-collapse" id="topbar-collapse">

    <ul class="am-nav am-nav-pills am-topbar-nav am-topbar-right admin-header-list" id="admin-header">
        <!--<li class="am-hide-sm-only"><a style="color: #0a0a0a" href="<?php /*echo $this->createUrl('system/message');*/?>"></span>
                <span class="am-icon-envelope-o"></span> Message <span class="am-badge am-badge-warning"><?php
/*                    echo JoyMessageMgr::model()->count("sendid={$this->user['userid']} and status=0");
                    */?></span></a></li>-->
      <li class="am-dropdown" data-am-dropdown>
        <a class="am-dropdown-toggle" style="color: #000;" data-am-dropdown-toggle href="javascript:;" id="admin">
          <span class="am-icon-users "></span> User <span class="am-icon-caret-down"></span>
        </a>
        <ul class="am-dropdown-content">
          <li><a href="<?php echo $this->createUrl('system/index');?>"><span class="am-icon-user"></span> User Detail</a></li>
          <li><a href="<?php echo $this->createUrl('system/logout');?>"><span class="am-icon-power-off"></span> Logout</a></li>
        </ul>
      </li>
      <li class="am-hide-sm-only"><a href="javascript:;" style="color: #000;" id="admin-fullscreen"><span class="am-icon-arrows-alt"></span> <span class="admin-fullText">Full Screen</span></a></li>
    </ul>
  </div>
</header>
<div data-am-sticky>
    <div id="div_right" style="position:absolute;right:0px;margin-right:10px;margin-top:5px;height:500px;">
        <a id="amz-go-top" class="am-icon-btn am-icon-arrow-up am-active" title="To top" href="#top"></a>
    </div>
</div>
<div class="">
</div>

<div class="am-cf admin-main">
 <?php echo $content;?>
<!-- content end -->
</div>
 <footer>
  <hr>
</footer>


</body>
</html>