<style type="text/css">
th{height:35px;font-size:15px;text-align:center;}
tr{height:30px;}
</style>

<?php
include_once dirname(dirname(__FILE__)).'/sidebar.php';
?>
<script type="text/javascript">
function check(name){
	if( confirm('你确定要删除'+name+'?，一旦删除，将不可恢复') ){
		return true;
	}else{
		return false;
	}
}
</script>
<?php
 $javarscript	=	'';
 if(  0 == $ret ){
 	if( isset($_GET['Gid']) ){
 		$javarscript	=	"location.href='".$this->createUrl('system/grouppowerlist')."'";
 	}else if( isset($_GET['Pid']) ){
 		$javarscript	=	"location.href='".$this->createUrl('system/grouppowerlist', array('Gid'=>$data['groupinfo']['groupid']))."'";
 	}
 }
?>
<!-- content start -->
<div class="admin-content">
  	<div class="am-cf am-padding">
		<div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">组权限管理</strong> </div>
  	</div>
  	<div style="padding-left:20px;margin-bottom:5px;" onclick="javascript:<?php echo $javarscript; ?>;"><?php echo empty($javarscript) ? '' : '<input type="button" value="返回上一步" class="am-btn am-btn-primary am-btn-xs" />'; ?></div>
		<?php 
			if( 0 == $ret ){
		?>
		<div id	= "search_data_display"  class="fontpix" style="margin:0px 20px 30px 20px;">
			<table style="font-size:10px;text-align:center;width:100%;" border="1" cellspacing="0" bordercolor="#d3d3d3">
				<tbody>
					<?php 
						if( isset($data['grouplist']) ){
					?>
						<tr class="tr_30">
							<th>记录ID</th>
							<th>用户组名称</th>
						</tr>
						<?php
							foreach( $data['grouplist'] as $item ){
								echo '<tr class="tr_30">';
									echo '	<td>', $item['id'], '</td>';
									echo '	<td><a href="'.$this->createUrl('system/grouppowerlist', array('Gid'=>$item['id'])).'">', $item['name'], '</a></td>';
								echo '</tr>';
							} 
						}else if( isset($data['grouppowerlist']) ){
					?>
						<tr class="tr_30">
							<th>记录ID</th>
							<th>权限组名称</th>
							<th>排序权值</th>
							<th>管理操作</th>
						</tr>
						<?php
							foreach( $data['grouppowerlist'] as $item ){
								echo '<tr class="tr_30">';
									echo '	<td>', $item['id'], '</td>';
									echo '	<td><a href="', $this->createUrl('system/grouppowerlist', array('Pid'=>$item['id'])) ,'">', $item['pname'], '</a></td>';
									echo '	<td><a href="', $this->createUrl('system/grouppowermod', array('Pid'=>$item['id'])) ,'">', $item['weight'], '</a></td>';
									echo '	<td><a href="', $this->createUrl('system/grouppowerdel', array('Pid'=>$item['id'])) ,'" onclick="return check(\'权限组---'.$item['pname'].'\')" class="btn-small am-btn-primary" style="">删除权限组</a></td>';
								echo '</tr>';
							}
						?>
						<tr class="tr_30">
							<td colspan="13"><a href="<?php echo $this->createUrl('system/grouppoweradd', array('Gid'=>$_GET['Gid']));?>" class="am-btn am-btn-primary am-btn-xs">添加权限组</a></td>
						</tr>
					<?php	
						}else if( isset($data['powerlist']) ){
					?>
						<tr class="tr_30">
							<th>记录ID</th>
							<th>权限名称</th>
							<th>生效状态</th>
							<th>左侧显示</th>
							<th>排序权值</th>
							<th>管理操作</th>
						</tr>
						<?php
							foreach( $data['powerlist'] as $item ){
								echo '<tr class="tr_30">';
									echo '	<td>', $item['id'], '</td>';
									echo '	<td><a href="', $this->createUrl('system/powermod', array('Id'=>$item['id'])) ,'">', $item['pname'], '</a></td>';
									echo '	<td>', (0 == $item['status'] ?  '冻结' : '生效') , '</td>';
									echo '	<td>', (0 == $item['weight'] ?  '否' : '是'), '</td>';
									echo '	<td>', $item['weight'], '</td>';
									echo '	<td><a href="', $this->createUrl('system/grouppowerdel', array('Id'=>$item['id'])) ,'" onclick="return check(\'权限项---'.$item['pname'].'\')" class="btn-small am-btn-primary">删除权限项</a></td>';
								echo '</tr>';
							}
						?>
						<tr class="tr_30">
							<td colspan="13"><a href="<?php echo $this->createUrl('system/grouppoweradd', array('Pid'=>$_GET['Pid'])); ?>" class="am-btn am-btn-primary am-btn-xs">添加权限项</a></td>
						</tr>
					<?php	
						}
					?>
				</tbody>
			</table>
		</div>
		<?php 
			}
			if( !empty($msg) ){
				echo '<script>alert("', $msg , '");';
				if( 0 == $ret ){
					echo 'location.reload();';
				}
				echo '</script>';
			}
		?>
</div>