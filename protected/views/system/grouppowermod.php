<?php
include_once dirname(dirname(__FILE__)) . '/sidebar.php';
?>
<script type="text/javascript">
function checkform(){
	var dom_groupid	=	document.getElementById('Groupid');
	if( '-' == dom_groupid.value ){
		alert('请选择用户组');
		dom_groupid.focus();
		return false;
	}
	
	var dom_powerid	=	document.getElementById('Powerid');
	if( '-' == dom_powerid.value ){
		alert('请选择组权限');
		dom_powerid.focus();
		return false;
	}
	
	if( !confirm('你确定要修改用户组权限吗？') ){
		return false;
	}
	return true;
}
</script>
<?php
 $javarscript	=	'';
 if(  0 == $ret ){
 	$javarscript	=	"location.href='".$this->createUrl('system/grouppowerlist', array('Gid'=>$data['grouppowerinfo']['groupid']))."'";
 }
?>
<!-- content start -->
<div class="admin-content">
	<div class="title-grid"><div class="gridtitle">组权限信息</div><div class="backlast" onclick="javascript:<?php echo $javarscript; ?>;"><?php echo empty($javarscript) ? '' : '返回上一步'; ?></div></div>
	<div class="content-gird typography-heading">
		<?php 
			if( 0 == $ret ){
		?>
		<form name='addcard' id='addcard' action='<?php echo $this->createUrl('system/grouppowermod');?>' method='POST' onSubmit='return checkform();'>
		<input type="hidden" name="r" id="r" value="system/grouppowermod" />
		<input type="hidden" name="Id" value="<?php echo $data['grouppowerinfo']['id']; ?>" />
		<table style="font-size:10px;text-align:center;width:100%;" border="1" cellspacing="0" bordercolor="#d3d3d3">
				<tr><td class="lable">权限组名称:</td><td align="left"><input type="text" id="Pname" name="Pname"  value="<?php echo $data['grouppowerinfo']['pname']; ?>"></td></tr>
				<tr><td class="lable">排序权值:</td><td align="left"><input type="text" id="Weight" name="Weight" value="<?php echo $data['grouppowerinfo']['weight']; ?>"></td></tr>
				<tr><td class='lable' colspan="2" style="text-align:center;"><input type="submit" value="修改权限组" style="font-size:15px;"></td></tr>
		</table>
		</form>
		<?php 
			}
			if( !empty($msg) ){
				echo '<script>alert("', $msg , '");';
				echo '</script>';
			}
		?>
	</div>
</div>
