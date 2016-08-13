<style type="text/css">
.pagination {
	display: inline-block;
	padding-left: 0;
	margin: 0;
	border-radius: 4px;
	font-size:10px;
}
.pagination>li {
	display: inline;
}
.pagination>.active>a, .pagination>.active>span, .pagination>.active>a:hover, .pagination>.active>span:hover, .pagination>.active>a:focus, .pagination>.active>span:focus {
	z-index: 2;
	color: #fff;
	cursor: pointer;
	background-color: #428bca;
	border-color: #428bca;
}
.pagination>li>a, .pagination>li>span {
	position: relative;
	float: left;
	padding: 2px 10px;
	margin-left: -1px;
	line-height: 1.428571429;
	text-decoration: none;
	background-color: #fff;
	border: 1px solid #ddd;
}
th{
	height:35px;
	font-size:15px;
	text-align:center;
}
tr{
	height:30px;
}
</style>
<?php
include_once dirname(dirname(__FILE__)) . '/sidebar.php';
?>
<script charset="utf-8" src="<?php echo Yii::app()->request->baseUrl; ?>/js/DatePicker/WdatePicker.js" type="text/javascript"></script>
<!-- content start -->
<div class="admin-content">
  	<div class="am-cf am-padding">
		<div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">系统日志</strong> </div>
  	</div>
	<div class="content-gird typography-heading" style="margin-left:20px;margin-right:20px;">
		<hr style="margin-bottom: 5px;">
			<form action="<?php echo $this->createUrl('system/systemlog');?>" method="GET">
				<input type="hidden" name="r" id="r" value="system/systemlog" />
				用户：<select name="userid" id="userid"><option value="0">-请选择-</option>
				<?php 
					if( isset( $data ) && isset($data['userlist']) ){
						foreach ($data['userlist'] as $item ){
							if( $data['userid'] > 0  && $data['userid'] == $item['id'] ){
								echo '<option value="',$item['id'] , '" selected>',$item['username'],'</option>';
							}else{
								echo '<option value="',$item['id'] , '">',$item['username'],'</option>';
							}
						}
					}
				?>
				</select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				开始时间：<input type="text" name="stime" id="stime" style="width:100px;"  value='<?php echo false != $data['stime'] ? $data['stime'] : ''; ?>' onclick="WdatePicker()" readonly >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				结束时间：<input type="text" name="etime" id="etime" style="width:100px;"  value='<?php echo false != $data['etime'] ? $data['etime'] : ''; ?>' onclick="WdatePicker()" readonly >&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; 
				<input type="submit" value="查询"  class="am-btn am-btn-primary am-btn-xs">
			</form>
		<hr style="margin-top: 5px;margin-bottom: 5px;">
		<?php
			if( 0 == $ret ){
		?>
		<div id	= "search_data_display"  class="fontpix" style="width:100%;">
			<table style="font-size:10px;text-align:center;width:100%;" border="1" cellspacing="0" bordercolor="#d3d3d3">
				<tbody>
						<tr class="tr_30">
							<th>操作用户</th>
							<th>操作内容</th>
							<th>IP地址</th>
							<th>操作时间</th>
						</tr>
						<?php
							foreach( $data['datalist'] as $item ){
								echo '<tr class="tr_30">';
									echo '	<td>', $item['uname'], '</td>';
									echo '	<td>', $item['remark'], '</td>';
									echo '	<td>', $item['ip'], '</td>';
									echo '	<td>', $item['dtime'], '</td>';
								echo '</tr>';
							}
						?>
						<tr class="tr_30">
							<td colspan="4" style="margin-right:10px;text-align:right;"><?php echo $data['fenye']; ?></td>
						</tr>
				</tbody>
			</table>
		</div>
		<?php 
			}
			if( !empty($msg) ){
				echo '<script>alert("', $msg , '");';
				echo '</script>';
			}
		?>
		</div>
</div>