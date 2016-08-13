<?php
include_once dirname(dirname(__FILE__)) . '/sidebar.php';
?>
<script type="text/javascript">
function checkform(){
	var dom_email	=	document.getElementById('Email');
	if( /^\s*$/.test(dom_email.value) ){
		alert('邮箱不能为空');
		dom_email.focus();
		return false;
	}
	var dom_password	=	document.getElementById('Password');
	if( !/^\s*[0-9|A-Z|a-z]{6,16}\s*$/.test(dom_password.value) ){
		alert('密码必须是6-16位字母或数字');
		dom_password.focus();
		return false;
	}
	var dom_groupid	=	document.getElementById('Groupid');
	if( '-' == dom_groupid.value ){
		alert('请选择用户组');
		dom_groupid.focus();
		return false;
	}
	var dom_status	=	document.getElementById('Status');
	if( '-' == dom_status.value ){
		alert('请选择状态');
		dom_status.focus();
		return false;
	}
	<?php 
		if( ADMIN_GROUP_ID == $this->user['groupid'] ){//管理员组
	?>
	var dom_openuser	=	document.getElementById('Openuser');
	if( '-' == dom_openuser.value ){
		alert('请选择是否授权开户');
		dom_openuser.focus();
		return false;
	}
	<?php 
		}
	?>
	
	if( !confirm('你确定要添加用户吗？') ){
		return false;
	}
	return true;
}
</script>
<!-- content start -->
<div class="admin-content">
  	<div class="am-cf am-padding">
		<div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">添加用户</strong> </div>
  	</div>
	<div class="content-gird typography-heading">
		
		<form name='addcard' id='addcard' action='<?php echo $this->createUrl('system/useradd');?>' method='POST' onSubmit='return checkform();'>
			<div class="am-tabs am-margin" data-am-tabs>
				<ul class="am-tabs-nav am-nav am-nav-tabs">
					<li class="am-active"><a href="#tab1">用户信息</a></li>
				</ul>
				<div class="am-tabs-bd">
					<div class="am-tab-panel am-fade am-in am-active" id="tab1">
						<div class="am-g am-margin-top">
							<div class="am-u-sm-4 am-u-md-2 am-text-right">邮箱</div>
							<div class="am-u-sm-8 am-u-md-4">
								<input type="hidden" name="r" id="r" value="system/useradd" />
								<input type="text" name="Email" id='Email' size="30" maxlength="50" />
							</div>
							<div class="am-hide-sm-only am-u-md-6">&nbsp;&nbsp;</div>
					 	</div>
					 	<div class="am-g am-margin-top">
							<div class="am-u-sm-4 am-u-md-2 am-text-right">密码</div>
							<div class="am-u-sm-8 am-u-md-4">
								<input type="password" name="Password" id='Password' size="30"  maxlength="16" value='' >&nbsp;<span class='highlight'>*</span>
							</div>
							<div class="am-hide-sm-only am-u-md-6">&nbsp;&nbsp;</div>
					 	</div>
					 	<div class="am-g am-margin-top">
							<div class="am-u-sm-4 am-u-md-2 am-text-right">用户组</div>
							<div class="am-u-sm-8 am-u-md-4">
								<select type="text" name="Groupid" id="Groupid"><option value='-'>--请选择--</option>
								<?php
									foreach( $data['grouplist'] as $item ){
										if(4 == $item['id'] || 5 == $item['id']){continue;}
										echo '<option value="', $item['id'],'">',$item['name'] ,'</option>';
									}
								?>
								</select>&nbsp;<span class='highlight'>*</span>
							</div>
							<div class="am-hide-sm-only am-u-md-6">&nbsp;&nbsp;</div>
					 	</div>
					 	<div class="am-g am-margin-top">
							<div class="am-u-sm-4 am-u-md-2 am-text-right">状态</div>
							<div class="am-u-sm-8 am-u-md-4">
								<select name="Status" id="Status"><option value='-'>--请选择--</option><option value='1'>正常</option><option value='0'>待审核</option></select>&nbsp;<span class='highlight'>*</span>
							</div>
							<div class="am-hide-sm-only am-u-md-6">&nbsp;&nbsp;</div>
					 	</div>
						<?php 
							if( ADMIN_GROUP_ID == $this->user['groupid'] ){//管理员组
						?>
						<div class="am-g am-margin-top">
							<div class="am-u-sm-4 am-u-md-2 am-text-right">授权开户</div>
							<div class="am-u-sm-8 am-u-md-4">
								<select name="Openuser" id="Openuser"><option value='-'>--请选择--</option><option value='1'>是</option><option value='0' selected >否</option></select>
							</div>
							<div class="am-hide-sm-only am-u-md-6">&nbsp;&nbsp;</div>
					 	</div>
						<?php 
							}
						?>
						<div class="am-g am-margin-top">
							<div class="am-u-sm-4 am-u-md-2 am-text-right">真实姓名</div>
							<div class="am-u-sm-8 am-u-md-4">
								<input type="text" name="Title" id='Title' size="30" />&nbsp;<span class='highlight'>*</span>
							</div>
							<div class="am-hide-sm-only am-u-md-6">&nbsp;&nbsp;</div>
					 	</div>
					 	<div class="am-g am-margin-top">
							<div class="am-u-sm-4 am-u-md-2 am-text-right">手机号</div>
							<div class="am-u-sm-8 am-u-md-4">
								<input type="text" name="Phone" id="Phone" size="30" value='' >&nbsp;<span class='highlight'>*</span>
							</div>
							<div class="am-hide-sm-only am-u-md-6">&nbsp;&nbsp;</div>
					 	</div>
					 	<div class="am-g am-margin-top">
							<div class="am-u-sm-4 am-u-md-2 am-text-right">&nbsp;&nbsp;</div>
							<div class="am-u-sm-8 am-u-md-4">
								<input type="submit" value="添加用户" class="am-btn am-btn-primary am-btn-xs">
							</div>
							<div class="am-hide-sm-only am-u-md-6">&nbsp;&nbsp;</div>
					 	</div>
					 </div>
				</div>
			</div>
		</form>
		<?php 
			if( !empty($msg) ){
				echo '<script>alert("', $msg , '");';
				if( 0 == $ret ){
					echo 'location.href="'.$this->createUrl('system/userlist').'";';
				}
				echo '</script>';
			}
		?>
	</div>
</div>
