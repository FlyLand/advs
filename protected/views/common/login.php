<body class="signin">
	<div class="signinpanel">
		<div class="row">
			<div class="col-sm-7">
				<div class="signin-info">
					<div class="logopanel m-b">
						<h1>[ Offer Manager ]</h1>
					</div>
					<div class="m-b"></div>
					<h4>Welcome to <strong>Offer Manager</strong></h4>
					<ul class="m-b">
						<li><i class="fa fa-arrow-circle-o-right m-r-xs"></i> VANTAGE-ONE: Quickly</li>
						<li><i class="fa fa-arrow-circle-o-right m-r-xs"></i> VANTAGE-TOW: Security</li>
						<li><i class="fa fa-arrow-circle-o-right m-r-xs"></i> VANTAGE-THREE: Convenient</li>
						<li><i class="fa fa-arrow-circle-o-right m-r-xs"></i> VANTAGE-FOUR: High Yield</li>
					</ul>
					<strong>No Account? <a href="#">Sign Up&raquo;</a></strong>
				</div>
			</div>
			<div class="col-sm-5">
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
				<form method="post" action="http://www.zi-han.net/theme/hplus/index.html">
					<h4 class="no-margins">Login：</h4>
					<p class="m-t-md">Login To Offer Manager</p>
					<?php echo $form->textField($model,'email',array('class'=>'form-control uname','placeholder'=>'Email','required'))?>
					<span class="help-block m-b-none">
						<i class="fa fa-info-circle"></i><?php echo $form->error($model,'email')?>
					</span>
					<?php echo $form->passwordField($model,'password',array('class'=>'form-control pword m-b','placeholder'=>'Password','required'))?>
					<span class="help-block m-b-none">
						<i class="fa fa-info-circle"></i><?php echo $form->error($model,'email')?>
					</span>
					<p style="color: red"><?php echo $form->error($model,'password')?></p>
<!--					<a href="#">忘记密码了？</a>-->
					<button class="btn btn-success btn-block">Login</button>
				</form>
				<?php $this->endWidget()?>
			</div>
		</div>
	</div>
</body>