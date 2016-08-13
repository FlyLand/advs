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


<div class="row">
	<div class="col-sm-12">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<h5>权限添加</h5>
				<div class="ibox-tools">
					<a class="collapse-link">
						<i class="fa fa-chevron-up"></i>
					</a>
					<a class="dropdown-toggle" data-toggle="dropdown" href="form_basic.html#">
						<i class="fa fa-wrench"></i>
					</a>
					<ul class="dropdown-menu dropdown-user">
						<li><a href="form_basic.html#">选项1</a>
						</li>
						<li><a href="form_basic.html#">选项2</a>
						</li>
					</ul>
					<a class="close-link">
						<i class="fa fa-times"></i>
					</a>
				</div>
			</div>
			<div class="ibox-content">
				<form method="post" action="<?php echo $this->createUrl('system/grouppoweradd');?>" class="form-horizontal">
					<?php
					if( 0 == $ret ){
					if( isset($_GET['Gid']) ){
					echo '<input type="hidden" name="Groupid" value="'.intval($_GET['Gid']).'" />';
					?>


					<div class="form-group">
						<label class="col-sm-2 control-label">Group Name:</label>
						<div class="col-sm-6">
							<input type="hidden" name="r" id="r" value="system/groupadd" />
							<input type="text" name="Pname" id='Pname' class="form-control" value='' ><span class='highlight'>*</span>
						</div>
					</div>
					<div class="hr-line-dashed"></div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Weight:</label>
						<div class="col-sm-6">
							<input type="text" name="Weight" id='Weight' class="form-control" value='' >
						</div>
					</div>
					<div class="hr-line-dashed"></div>
					<div class="form-group">
						<div class="col-sm-6">
							<input type="submit" value="Add" class="am-btn am-btn-primary am-btn-xs">
						</div>
					</div>

						<?php
					}else if( isset($_GET['Pid']) ){
					$powerconf	=	require BASE_DIR.'/protected/config/powerconf.php';
					echo '<input type="hidden" name="Parentid" value="'.intval($_GET['Pid']).'" />';
					?>

					<div class="form-group">
						<label class="col-sm-2 control-label">Group Name:</label>
						<div class="col-sm-6">
							<select class="form-control m-b" name="Action" id="Action">
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
					</div>
					<div class="hr-line-dashed"></div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Group Status:</label>
						<div class="col-sm-6">
							<select  class="form-control m-b" name="Status" id="Status"><option value="-">--请选择--</option><option value="1">正常</option><option value="0">冻结</option></select>&nbsp;<span class="highlight">*</span>
						</div>
					</div>
					<div class="hr-line-dashed"></div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Group Status:</label>
						<div class="col-sm-6">
							<input type="text" name="Weight" id="Weight" size="30" value="10000" >&nbsp;<span class="highlight">*</span>
						</div>
					</div>
					<div class="hr-line-dashed">
						<input type="submit" value="Add" class="am-btn am-btn-primary am-btn-xs">
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
				</form>
			</div>
		</div>
	</div>
</div>
