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
<link href="<?php echo Yii::app()->params['cssPath']?>css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">
<link href="<?php echo Yii::app()->params['cssPath']?>css/animate.min.css" rel="stylesheet">
<link href="<?php echo Yii::app()->params['cssPath']?>css/style.min862f.css?v=4.1.0" rel="stylesheet">
<script>
	function check(name){
		if( confirm('你确定要删除'+name+'?，一旦删除，将不可恢复') ){
			return true;
		}else{
			return false;
		}
	}
	$(document).ready(function(){$(".dataTables-example").dataTable();var oTable=$("#editable").dataTable();oTable.$("td").editable("http://www.zi-han.net/theme/example_ajax.php",{"callback":function(sValue,y){var aPos=oTable.fnGetPosition(this);oTable.fnUpdate(sValue,aPos[0],aPos[1])},"submitdata":function(value,settings){return{"row_id":this.parentNode.getAttribute("id"),"column":oTable.fnGetPosition(this)[2]}},"width":"90%","height":"100%"})});function fnClickAddRow(){$("#editable").dataTable().fnAddData(["Custom row","New row","New row","New row","New row"])};
</script>
<script type="text/javascript" src="http://tajs.qq.com/stats?sId=9051096" charset="UTF-8"></script>

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
			<?php
			if( 0 == $ret ){
			?>
			<table class="table table-striped table-bordered table-hover dataTables-example">
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
	</div>
</div>
</div>