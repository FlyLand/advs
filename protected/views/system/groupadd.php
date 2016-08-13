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

<div class="row">
	<div class="col-sm-12">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<h5>Affiliate Edit</h5>
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
				<form method="post" action="<?php echo $this->createUrl('system/groupadd');?>" class="form-horizontal">
					<div class="form-group">
						<label class="col-sm-2 control-label">Group Name:</label>
						<div class="col-sm-6">
							<input type="hidden" name="r" id="r" value="system/groupadd" />
							<input type="text" name="Name" id='Name' class="form-control" value='' ><span class='highlight'>*</span>
						</div>
					</div>
					<div class="hr-line-dashed"></div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Status:</label>
						<div class="col-sm-6">
							<select  name="Status" id="Status"><option value='-'>--请选择--</option><option value='1'>正常</option><option value='0'>冻结</option></select>
							<span class='highlight'>*</span>
						</div>
					</div>
					<div class="hr-line-dashed"></div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Status:</label>
						<div class="col-sm-6">
							<input type="submit" value="Add" class="am-btn am-btn-primary am-btn-xs">
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<script>
<?php
	if( !empty($msg) ){
		echo '<script>alert("', $msg , '");';
		if( 0 == $ret ){
			echo 'location.href="'.$this->createUrl('system/grouplist').'";';

		}
		echo '</script>';
	}
?>
</script>