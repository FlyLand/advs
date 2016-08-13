<?php
include_once dirname(dirname(__FILE__)) . '/sidebar.php';
?>
<script type="text/javascript">
function checkform(){
	var dom_pname	=	document.getElementById('Pname');
	if( /^\s*$/.test(dom_pname.value) ){
		alert('权限名称不能为空');
		dom_pname.focus();
		return false;
	}
	var dom_action	=	document.getElementById('Action');
	if( !/\//.test(dom_action.value) ){
		alert('执行动作格式错误');
		dom_action.focus();
		return false;
	}
	
	var dom_status	=	document.getElementById('Status');
	if( '-' == dom_status.value ){
		alert('请选择状态');
		dom_status.focus();
		return false;
	}
	
	if( !confirm('你确定要添加权限吗？') ){
		return false;
	}
	return true;
}
</script>
<?php
 $this->breadcrumbs = array(
 	array(
 		'name'=>'系统管理',
 		'url'=>$this->createUrl('card/addcard'),
 			),
     '添加权限'
 );
?>
<div class="grid-1">
  	<div class="title-grid">权限配置</div>
	<div class="content-gird typography-heading">
		<?php 
			if( 0 == $ret ){
		?>
		<form name='addcard' id='addcard' action='<?php echo $this->createUrl('system/poweradd');?>' method='POST' onSubmit='return checkform();'>
			<table class="tblclass" style="width:350px;">
				<tr><td class='lable'>权限名称:</td><td align="left"><select name="Action" id="Action">
				<?php
					$powerconf	=	require BASE_DIR.'/protected/config/powerconf.php';
					foreach( $powerconf as $action => $item ){
						if( 2 == $item['canshow'] ){
							continue;
						}
						echo '<option value="', $action, '">', $item['name'], '</option>';
					}
				?>
				</select>&nbsp;<span class='highlight'>*</td></tr>
				<tr><td class='lable'>所属组:</td><td align="left"><select type="text" name="Parentid" id="Parentid">
				<?php
					if( $data['parentid'] > 0 ){
						echo '<option value="-">--顶级权组--</option>';
					}else{
						echo '<option value="-" selected>--顶级权组--</option>';
					}
					foreach( $data['powerlist'] as $item ){
						if( 0 != $item['parentid'] ){
							continue;
						}
						if( $data['parentid'] == $item['id'] ){
							echo '<option value="', $item['id'],'" selected>',$item['pname'] ,'</option>';
						}else{
							echo '<option value="', $item['id'],'">',$item['pname'] ,'</option>';
						}
					}
				?>
				</select>&nbsp;<span class='highlight'>*</span></td></tr>
				<tr><td class='lable'>状态:</td><td align="left"><select type="text" name="Status" id="Status"><option value='-'>--请选择--</option><option value='1'>正常</option><option value='0'>冻结</option></select>&nbsp;<span class='highlight'>*</span></td></tr>
				<tr><td class='lable'>显示权值:</td><td align="left"><input type="text" name="Weight" id='Weight' size="30" value='10000' ></td></tr>
				<tr>
					<td class='lable' colspan="2" style="text-align:center;">
						<input type="hidden" name="r" id="r" value="system/poweradd" />
						<input type="submit" value="添加权限" style="font-size:15px;">
					</td>
				</tr>
			</table>
		</form>
		<?php 
			}
			if( !empty( $msg ) ){
				echo '<script>alert("', $msg , '");';
				if( 0 == $ret ){
					echo 'location.href="'.$this->createUrl('system/powerlist').'";';
				}
				echo '</script>';
			}
		?>
	</div>
</div>
