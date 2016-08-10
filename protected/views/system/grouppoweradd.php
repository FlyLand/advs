<?php
include_once dirname(dirname(__FILE__)).'/sidebar.php';
?>
<script type="text/javascript">
function check(){
	var dom_name	=	$('#Pname');
	if( dom_name.length > 0 ){
		if( /^\s*$/.test(dom_name.val() ) ){
			alert('请输入权限组名称');
			dom_name.focus();
			return false;
		}
		if( !confirm('你确定要添加权限组吗？') ){
			return false;
		}
	}else{
		var dom_action	=	document.getElementById('Action');
		if( !/\//.test(dom_action.value) ){
			alert('执行动作格式错误');
			dom_action.focus();
			return false;
		}
		
		var dom_status	=	$('#Status');
		if( '-' == dom_status.val() ){
			alert('请选择状态');
			dom_status.focus();
			return false;
		}
		if( !confirm('你确定要添加权限吗？') ){
			return false;
		}
	}
	
	return true;
}
</script>
<?php
 $javarscript	=	'';
 if(  0 == $ret ){
 	if( isset($_GET['Gid']) ){
 		$javarscript	=	"location.href='".$this->createUrl('system/grouppowerlist', array('Gid'=>$_GET['Gid']))."'";
 	}else if( isset($_GET['Pid']) ){
 		$javarscript	=	"location.href='".$this->createUrl('system/grouppowerlist', array('Pid'=>$_GET['Pid']))."'";
 	}
 }
?>
<!-- content start -->
<div class="admin-content">
  	<form action="<?php echo $this->createUrl('system/grouppoweradd');?>" method="post" onsubmit="return check();">
  		<div class="am-tabs am-margin" data-am-tabs>
				<input type="hidden" name="r" id="r" value="system/grouppoweradd" />
				<ul class="am-tabs-nav am-nav am-nav-tabs">
					<li class="am-active"><a href="#tab1">组权限信息</a></li>
					<li style="float:right;margin-right:0px;"><div style="padding-left:20px;margin-bottom:5px;" onclick="javascript:<?php echo $javarscript; ?>;"><?php echo empty($javarscript) ? '' : '<input type="button" value="返回上一步" class="am-btn am-btn-primary am-btn-xs" />'; ?></div>
				</li>
				</ul>
				<div class="am-tabs-bd">
					<div class="am-tab-panel am-fade am-in am-active" id="tab1">
				<?php 
					if( 0 == $ret ){
						if( isset($_GET['Gid']) ){
							echo '<input type="hidden" name="Groupid" value="'.intval($_GET['Gid']).'" />';
				?>
							<div class="am-g am-margin-top">
								<div class="am-u-sm-4 am-u-md-2 am-text-right">权限组名称</div>
								<div class="am-u-sm-8 am-u-md-4">
									<input type="text" id="Pname" name="Pname">
								</div>
								<div class="am-hide-sm-only am-u-md-6">&nbsp;&nbsp;</div>
						 	</div>
						 	<div class="am-g am-margin-top">
								<div class="am-u-sm-4 am-u-md-2 am-text-right">排序权值</div>
								<div class="am-u-sm-8 am-u-md-4">
									<input type="text" id="Weight" name="Weight">
								</div>
								<div class="am-hide-sm-only am-u-md-6">&nbsp;&nbsp;</div>
						 	</div>
						 	<div class="am-g am-margin-top">
								<div class="am-u-sm-4 am-u-md-2 am-text-right">&nbsp;</div>
								<div class="am-u-sm-8 am-u-md-4">
									<input type="submit" value="添加权限组" style="font-size:15px;" class="am-btn am-btn-primary am-btn-xs" >
								</div>
								<div class="am-hide-sm-only am-u-md-6">&nbsp;&nbsp;</div>
						 	</div>
				<?php
						}else if( isset($_GET['Pid']) ){
							$powerconf	=	require BASE_DIR.'/protected/config/powerconf.php';
							echo '<input type="hidden" name="Parentid" value="'.intval($_GET['Pid']).'" />';
				?>
							<div class="am-g am-margin-top">
								<div class="am-u-sm-4 am-u-md-2 am-text-right">权限名称</div>
								<div class="am-u-sm-8 am-u-md-4">
									<select name="Action" id="Action">
										<?php
											foreach( $powerconf as $action => $item ){
												if( 2 == $item['canshow'] ){
													continue;
												}
												echo '<option value="', $action, '">', $item['name'], '</option>';
											}
										?>
									</select>&nbsp;<span class="highlight">*</span>
								</div>
								<div class="am-hide-sm-only am-u-md-6">&nbsp;&nbsp;</div>
						 	</div>
						 	<div class="am-g am-margin-top">
								<div class="am-u-sm-4 am-u-md-2 am-text-right">权限状态</div>
								<div class="am-u-sm-8 am-u-md-4">
									<select type="text" name="Status" id="Status"><option value="-">--请选择--</option><option value="1">正常</option><option value="0">冻结</option></select>&nbsp;<span class="highlight">*</span>
								</div>
								<div class="am-hide-sm-only am-u-md-6">&nbsp;&nbsp;</div>
						 	</div>
						 	<div class="am-g am-margin-top">
								<div class="am-u-sm-4 am-u-md-2 am-text-right">显示权值</div>
								<div class="am-u-sm-8 am-u-md-4">
									<input type="text" name="Weight" id="Weight" size="30" value="10000" >&nbsp;<span class="highlight">*</span>
								</div>
								<div class="am-hide-sm-only am-u-md-6">&nbsp;&nbsp;</div>
						 	</div>
						 	<div class="am-g am-margin-top">
								<div class="am-u-sm-4 am-u-md-2 am-text-right">&nbsp;</div>
								<div class="am-u-sm-8 am-u-md-4">
									<input type="submit" value="添加权限项" style="font-size:15px;" class="am-btn am-btn-primary am-btn-xs" >
								</div>
								<div class="am-hide-sm-only am-u-md-6">&nbsp;&nbsp;</div>
						 	</div>
					<?php
							}
						}
						if( !empty($msg) ){
							echo '<script>alert("', $msg , '");';
							//echo 'location.href="/system/grouppowerlist.html?'.Yii::app()->getRequest()->queryString.'";';
							echo '</script>';
						}
					?>
					</div>
				</div>
			</div>
		</form>
</div>
