<!DOCTYPE html>
<html>
<!-- Mirrored from www.zi-han.net/theme/hplus/login.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 20 Jan 2016 14:18:23 GMT -->
<head>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Offer Login</title>
	<meta name="keywords" content="Offer Login">
	<meta name="description" content="Offer Login">
	<script src="<?php echo Yii::app()->params['cssPath']?>js/jquery.min.js?v=2.1.4"></script>
	<script src="<?php echo Yii::app()->params['cssPath']?>js/bootstrap.min.js?v=3.3.6"></script>
	<script type="text/javascript" src="http://tajs.qq.com/stats?sId=9051096" charset="UTF-8"></script>
	<link rel="shortcut icon" href="<?php echo Yii::app()->params['cssPath']?>favicon.ico">
	<link href="<?php echo Yii::app()->params['cssPath']?>css/bootstrap.min14ed.css?v=3.3.6" rel="stylesheet">
	<link href="<?php echo Yii::app()->params['cssPath']?>css/font-awesome.min93e3.css?v=4.4.0" rel="stylesheet">
	<link href="<?php echo Yii::app()->params['cssPath']?>css/animate.min.css" rel="stylesheet">
	<link href="<?php echo Yii::app()->params['cssPath']?>css/style.min862f.css?v=4.1.0" rel="stylesheet">
	<!--[if lt IE 9]>
	<meta http-equiv="refresh" content="0;ie.html" />
	<![endif]-->
	<script>if(window.top !== window.self){ window.top.location = window.location;}</script>
</head>

<body class="gray-bg">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'login-form',
	'htmlOptions'=>array(
		'class'=>'form-vertical login-form'
	),
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
		'inputContainer'=>'.control-group'
	),
)); ?>
<div class="middle-box text-center loginscreen  animated fadeInDown">
	<div>
		<div>
			<h1 class="logo-name">Offer</h1>
		</div>
		<h3>Welcome to Offer</h3>
		<form class="m-t" role="form" id="LoginForm" method="post">
			<div class="form-group">
				<?php echo $form->textField($model,'email',array('class'=>'form-control','placeholder'=>'email','required'))?>
<!--				<input id="email" type="email" class="form-control" placeholder="userName" required="">-->
				<?php echo $form->error($model,'email')?>
			</div>
			<div class="form-group">
				<?php echo $form->passwordField($model,'password',array('class'=>'form-control'),'required')?>
				<?php echo $form->error($model,'email')?>
			</div>

			<button type="submit" class="btn btn-primary block full-width m-b">Login</button>
		</form>
	</div>
</div>
</body>
<!-- Mirrored from www.zi-han.net/theme/hplus/login.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 20 Jan 2016 14:18:23 GMT -->
</html>
<?php $this->endWidget()?>
