
<?php require_once dirname(dirname(__FILE__)).'/sidebar.php';?>

<!-- content start -->
	<div class="admin-content">
		<div class="am-cf am-padding">
			<div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">修改个人信息</strong> </div>
		</div>
		<form class="am-form" action="<?php echo $this->createUrl('user/info');?>" method="post" id="doForm">
		<div class="am-tabs am-margin" data-am-tabs>
			<ul class="am-tabs-nav am-nav am-nav-tabs">
				<li class="am-active"><a href="#tab1">基本信息</a></li>
			</ul>
			<div class="am-tabs-bd">
				<div class="am-tab-panel am-fade am-in am-active" id="tab1">
					<div class="am-g am-margin-top">
						<div class="am-u-sm-4 am-u-md-2 am-text-right">
							用户名
						</div>
						<div class="am-u-sm-8 am-u-md-4">
							<input type="text" class="am-input-sm" name="username" id="name" disabled value="<?php echo $this->user['USERNAME'];?>" >
						</div>
						<div class="am-hide-sm-only am-u-md-6"></div>
				 	</div>
					<div class="am-g am-margin-top">
						<div class="am-u-sm-4 am-u-md-2 am-text-right">
							密码
						</div>
						<div class="am-u-sm-8 am-u-md-4">
							<input type="password" class="am-input-sm" name="passwd" id="passwd" value="<?php echo $this->user['PASSWORD'];?>" />
						</div>
						<div class="am-hide-sm-only am-u-md-6"></div>
				 	</div>
				 	<div class="am-g am-margin-top">
						<div class="am-u-sm-4 am-u-md-2 am-text-right">
							确认密码
						</div>
						<div class="am-u-sm-8 am-u-md-4">
							<input type="password" class="am-input-sm" name="passwd2" id="passwd2" value="" />
						</div>
						<div class="am-hide-sm-only am-u-md-6"></div>
				 	</div>
				</div>
			</div>
		</div>
		
		</form>
	  	<div class="am-margin">
			<button type="button" onclick="addUser()" class="am-btn am-btn-primary am-btn-xs">提交保存</button>
			&nbsp;&nbsp;&nbsp;&nbsp;<span style="color: #ff0000;"><?php if(isset($msg) && !empty($msg)){ echo $msg;}?></span>
		</div>
	</div>
<!-- content end -->
<script>
function addUser() {
	var passwd = $("#passwd");
	if (passwd.val() == '') {
		alert("密码不能为空！");
		passwd.focus();
		return false;
	}
	var passwd2 = $("#passwd2");
	if (passwd2.val() == '') {
		alert("确认密码不能为空！");
		passwd2.focus();
		return false;
	}
	if(passwd2.val() != passwd.val()){
		alert("两次密码输入不一致");
		passwd2.focus();
		return false;
	}
	$("#doForm").submit();
}
</script>