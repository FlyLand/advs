<!DOCTYPE html>
<html lang="zh-CN"><head>
<meta charset="UTF-8">
<title>Offer Manager</title>
<meta http-equiv="X-UA-Compatible" content="IE=8">
<meta http-equiv="X-UA-Compatible" content="chrome=1">


<style>
#content{
	margin:0 auto;
	width:500px;
	text-align:center;
}
legend {
	font-weight: bold;
}
</style>

</head>
<body>

<div class="bot_city"></div>
<div style=" position:relative;">
<div class="cloud"></div><div class="cloud2"></div><div class="cloud3"></div><div class="cloud cloud_pos"></div><div class="cloud2 cloud_pos1"></div>
</div>

<div id="content">
	<div class="login_right">
		<div class="well bs-component" style="margin-top: 50%;">
			<form class="form-horizontal" id="loginform" action="<?php echo $this->createUrl('system/login');?>" method="post" onsubmit="return checkInput();">
			<div style="margin:0 auto;color: red;"><?php echo isset($msg) ? $msg :'';?></div>
				<fieldset>
					<legend>Offer Manager</legend>
					<div class="am-u-sm-6">
<!--						<label class="am-radio">
							<input type="radio" name="login_group" value="0" data-am-ucheck checked>
							Manager
						</label>
						<label class="am-radio">
							<input type="radio" name="login_group" value="1" data-am-ucheck>
							Affiliate
						</label>-->
					<div class="form-group">
						<label for="inputEmail" class="col-lg-2 control-label">Email:</label>
						<div class="col-lg-10">
							<input type="text" class="form-control" id="email" placeholder="Email Address" name="email" />
						</div>
					</div>
<!--					<div class="form-group" id="title_div" style="display: none">
						<label for="inputEmail" class="col-lg-2 control-label">Title:</label>
						<div class="col-lg-10">
							<input type="text" class="form-control" id="email" placeholder="Title" name="title" />
						</div>
					</div>-->
					<div class="form-group">
						<label for="inputPassword" class="col-lg-2 control-label">Password:</label>
						<div class="col-lg-10">
							<input type="password" class="form-control" id="password" placeholder="Your Password" name="password" />
						</div>
					</div>
					<div class="form-group">
						<div class="col-lg-10 col-lg-offset-2">
							<button type="submit" class="btn btn-primary">Sign In</button>
<!--							<button onclick="register()" type="button" class="btn btn-primary">Register</button>-->
						</div>
					</div>
				</fieldset>
			</form>
		</div>
		
	</div>
</div>

<script type="text/javascript">
/*	$("[name='login_group']").click(function(){
		var val = $(this).val();
		if(val == 1){
			$('#title_div').show();
		}else{
			$('#title_div').hide();
		}
	});*/
function checkInput(){
	var form=document.getElementById('loginform');
	var email = document.getElementById("email");
	var password = document.getElementById("password");
	if(email.value == ""){
		alert('Please enter email address');
		uname.focus();
		return false;
	}
	if(password.value==""){
		alert('Please enter password')
		password.focus();
		return false;
	}
	if(password.value.length < 3) {
		alert('Password less than 3, please re-enter');
		password.focus();
		return false;
	}
	form.submit();
}
var register = function(){
	location.href='<?php echo $this->createUrl('standard/registerForm');?>';
};
</script>
</body>
</html>