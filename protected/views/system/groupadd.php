<?php
include_once dirname(dirname(__FILE__)).'/sidebar.php';
?>
<script type="text/javascript">
function check(){
	var dom_name	=	document.getElementById('Name');
	if( /^\s*$/.test(dom_name.value) ){
		alert('用户组名不能为空');
		dom_name.focus();
		return false;
	}
	
	var dom_status	=	document.getElementById('Status');
	if( '-' == dom_status.value ){
		alert('请选择状态');
		dom_status.focus();
		return false;
	}
	
	if( !confirm('你确定要添加用户组吗？') ){
		return false;
	}
	return true;
}
</script>
<!-- content start -->
<div class="admin-content">
  	<div class="am-cf am-padding">
		<div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">添加用户组</strong> </div>
  	</div>
  	<form action="<?php echo $this->createUrl('system/groupadd');?>" method="post" onsubmit="return check();">
  		<div class="am-tabs am-margin" data-am-tabs>
				<ul class="am-tabs-nav am-nav am-nav-tabs">
					<li class="am-active"><a href="#tab1">用户组信息</a></li>
				</ul>
				<div class="am-tabs-bd">
					<div class="am-tab-panel am-fade am-in am-active" id="tab1">
					   <div class="am-g am-margin-top">
							<div class="am-u-sm-4 am-u-md-2 am-text-right">用户组名</div>
							<div class="am-u-sm-8 am-u-md-4">
								<input type="hidden" name="r" id="r" value="system/groupadd" />
								<input type="text" name="Name" id='Name' size="30" maxlength="20" value='' ><span class='highlight'>*</span>
							</div>
							<div class="am-hide-sm-only am-u-md-6">&nbsp;&nbsp;</div>
					 	</div>
					 	<div class="am-g am-margin-top">
							<div class="am-u-sm-4 am-u-md-2 am-text-right">当前状态</div>
							<div class="am-u-sm-8 am-u-md-4">
								<select type="text" name="Status" id="Status"><option value='-'>--请选择--</option><option value='1'>正常</option><option value='0'>冻结</option></select>
								<span class='highlight'>*</span>
							</div>
							<div class="am-hide-sm-only am-u-md-6">&nbsp;&nbsp;</div>
					 	</div>
					 	<div class="am-g am-margin-top">
							<div class="am-u-sm-4 am-u-md-2 am-text-right">&nbsp;</div>
							<div class="am-u-sm-8 am-u-md-4">
								<input type="submit" value="添加用户组" class="am-btn am-btn-primary am-btn-xs">
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
				echo 'location.href="'.$this->createUrl('system/grouplist').'";';
				
			}
			echo '</script>';
		}
	?>
</div>
