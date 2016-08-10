<?php
include_once dirname(dirname(__FILE__)).'/sidebar.php';
?>
<script type="text/javascript">
function check(){
	var dom_username	=	document.getElementById('Username');
	if( /^\s*$/.test(dom_username.value) ){
		alert('用户名不能为空');
		dom_username.focus();
		return false;
	}
	/*
	var dom_password	=	document.getElementById('Password');
	if( !/^\s*[0-9|A-Z|a-z]{6,16}\s*$/.test(dom_password.value) ){
		alert('密码必须是6-16位字母或数字');
		dom_password.focus();
		return false;
	}
	*/
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
	var dom_zhekou	=	$('#Zhekou');
	if( !/0\.\d{2}/.test(dom_zhekou.val()) ){
		//alert('请填写用户折扣');
		//dom_zhekou.focus();
		//return false;
	}
	if( $.trim($('#Password').val()).length > 5 ){
		var remind	=	'密码长度大于5位，将修改成新密码，你确定要修改用户信息吗？';
	}else{
		var remind	=	'密码长度小于6位，将不会修改密码，你确定要修改用户信息吗？';
	}
	
	if( !confirm(remind) ){
		return false;
	}
	return true;
}
</script>
<!-- content start -->
<div class="admin-content">
  	<div class="am-cf am-padding">
		<div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">修改用户信息</strong> </div>
  	</div>
  	<div class="am-tabs am-margin" data-am-tabs>
		<ul class="am-tabs-nav am-nav am-nav-tabs">
			<li class="am-active"><a href="#tab1">用户信息</a></li>
		</ul>
		<div class="am-tabs-bd">
            <form action="<?php echo $this->createUrl('system/usermod', array('Id'=>$data['userinfo']['id']));?>" method="post" onsubmit="return check();">
                <input type="hidden" name="r" id="r" value="system/usermod" />
                <input type="hidden" name="Id" id='Id' value='<?php echo $data['userinfo']['id']; ?>' >
                <div class="am-tab-panel am-fade am-in am-active" id="tab1">
                    <div class="am-g am-margin-top">
                    	<div class="am-u-sm-4 am-u-md-2 am-text-right">邮箱</div>
                    	<div class="am-u-sm-8 am-u-md-4">
                    		<input type="text" name="Email" id='Email' size="30" maxlength="50" value='<?php echo $data['userinfo']['email']; ?>' readonly><span class='highlight'>*</span>
                    	</div>
                        <div class="am-hide-sm-only am-u-md-6">&nbsp;&nbsp;</div>
                    </div>
                    <div class="am-g am-margin-top">
                    	<div class="am-u-sm-4 am-u-md-2 am-text-right">密&nbsp;&nbsp;码</div>
                    	<div class="am-u-sm-8 am-u-md-4">
                    		<input type="password" name="Password" id='Password' size="30" maxlength="20" value='' />密码为空，则不修改
                    	</div>
                        <div class="am-hide-sm-only am-u-md-6">&nbsp;&nbsp;</div>
                    </div>
                    <div class="am-g am-margin-top">
                    	<div class="am-u-sm-4 am-u-md-2 am-text-right">用户组</div>
                    	<div class="am-u-sm-8 am-u-md-4">
                    		<select type="text" name="Groupid" id="Groupid"><option value='-'>--请选择--</option>
                				<?php 
                					foreach( $data['grouplist'] as $item ){
                						if( $item['id'] == $data['userinfo']['groupid']){
                							echo '<option value="', $item['id'],'" selected>',$item['name'] ,'</option>';
                						}else{
                							echo '<option value="', $item['id'],'">',$item['name'] ,'</option>';
                						}
                					}
                				?>
            				</select><span class='highlight'>*</span>
                    	</div>
                        <div class="am-hide-sm-only am-u-md-6">&nbsp;&nbsp;</div>
                    </div>
                    <div class="am-g am-margin-top">
                    	<div class="am-u-sm-4 am-u-md-2 am-text-right">状态</div>
                    	<div class="am-u-sm-8 am-u-md-4">
                    		<select type="text" name="Status" id="Status"><option value='-'>--请选择--</option>
            				<?php
            					$statuslist	=	array(1=>'正常',0=>'冻结');
            					foreach( $statuslist as $key => $desc ){
            						if( $key == $data['userinfo']['status']){
            							echo '<option value="', $key ,'" selected>', $desc ,'</option>';
            						}else{
            							echo '<option value="', $key ,'">', $desc ,'</option>';
            						}
            					}
            				?>
            				</select><span class='highlight'>*</span>
                    	</div>
                        <div class="am-hide-sm-only am-u-md-6">&nbsp;&nbsp;</div>
                    </div>
    				<?php 
    					if( ADMIN_GROUP_ID == $this->user['groupid'] ){//管理员组
    						echo '<div class="am-g am-margin-top" style="display:none;"><div class="am-u-sm-4 am-u-md-2 am-text-right">授权开户</div><div class="am-u-sm-8 am-u-md-4"><select type="text" name="Openuser" id="Openuser"><option value=\'-\'>--请选择--</option>';
    						if( 0 == $data['userinfo']['openuser'] ){
    							echo "<option value='1'>是</option><option value='0' selected>否</option>";
    						}else{
    							echo "<option value='1' selected>是</option><option value='0'>否</option>";
    						}
    						echo '</select>&nbsp;<span class=\'highlight\'>*</span>'; 
    						echo '</div><div class="am-hide-sm-only am-u-md-6">&nbsp;&nbsp;</div></div>';
    					}
    				?>
    				<div class="am-g am-margin-top">
                    	<div class="am-u-sm-4 am-u-md-2 am-text-right">真实姓名</div>
                    	<div class="am-u-sm-8 am-u-md-4">
                    		<input type="text" name="Title" id='Title' size="30" value='<?php echo $data['userinfo']['title']; ?>' >
                    	</div>
                        <div class="am-hide-sm-only am-u-md-6">&nbsp;&nbsp;</div>
                    </div>
                    <div class="am-g am-margin-top">
                    	<div class="am-u-sm-4 am-u-md-2 am-text-right">手机号</div>
                    	<div class="am-u-sm-8 am-u-md-4">
                    		<input type="text" name="Phone" id="Phone" size="30" value='<?php echo $data['userinfo']['phone']; ?>' >
                    	</div>
                        <div class="am-hide-sm-only am-u-md-6">&nbsp;&nbsp;</div>
                    </div>
                    <div class="am-g am-margin-top">
                    	<div class="am-u-sm-4 am-u-md-2 am-text-right">&nbsp;</div>
                    	<div class="am-u-sm-8 am-u-md-4">
                    		<input type="submit" value="修改用户" class="am-btn am-btn-primary am-btn-xs">
                    	</div>
                        <div class="am-hide-sm-only am-u-md-6">&nbsp;&nbsp;</div>
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
</div>