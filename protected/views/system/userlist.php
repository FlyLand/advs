<style type="text/css">
.pagination {
	display: inline-block;
	padding-left: 0;
	margin: 0;
	border-radius: 4px;
	font-size:14px;
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
include_once dirname(dirname(__FILE__)).'/sidebar.php';
?>
<!-- content start -->
<div class="admin-content">
  	<div class="am-cf am-padding">
		<div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">用户管理</strong> </div>
  	</div>
	<div class="am-tabs am-margin" data-am-tabs style="margin-top:5px;">
		<?php 
			if( 0 == $ret ){
		?>
	      		<hr style="margin:1px;"/>
				<div class="fontpix" style="width:100%;">
					<table style="font-size:14px;text-align:center;width:100%;" border="0" cellspacing="0" bordercolor="#d3d3d3">
						<tr>
							<td align="left">
								<form action="<?php echo $this->createUrl('system/userlist');?>" method="GET">
									<input type="hidden" name="r" id="r" value="system/userlist" />
								    用户组:<select name="groupid" id="groupid"><option value="0">-全部-</option>
								    <?php 
								    	foreach( $data['grouplist'] as $item ){
											if( $data['params']['groupid'] == $item['id'] ){
												echo '<option value="',$item['id'] , '" selected>',$item['name'],'</option>';
											}else{
												echo '<option value="',$item['id'] , '">',$item['name'],'</option>';
											}
										}
								    ?>
								    </select>&nbsp;&nbsp;&nbsp;&nbsp;
									状态：<select name="status" id="status">
									<?php
										$statusconf	=	array(array('status'=>1, 'desc'=>'正常'),array('status'=>0, 'desc'=>'待审核'));
										foreach( $statusconf as $item ){
											if( $data['params']['status'] == $item['status'] ){
												echo '<option value="',$item['status'] , '" selected>',$item['desc'],'</option>';
											}else{
												echo '<option value="',$item['status'] , '">',$item['desc'],'</option>';
											}
										}
									?>
									</select>&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; 
									<input type="submit" value="查询"  class="am-btn am-btn-primary am-btn-xs" >
								</form>
							</td>
							<td style="float:right;margin-right:10px;text-align:right;"><?php echo $data['fenyecode']; ?></td>
						</tr>
					</table>
				</div>
				<hr style="margin-top:0px;margin-bottom:10px;"/>
				<table style="font-size:10px;text-align:center;width:100%;" border="1" cellspacing="0" bordercolor="#d3d3d3">
            		<thead>
              			<tr class="am-primary">
                			<th class="table-title">用户ID</th>
							<th class="table-title">email</th>
							<th class="table-title">真实姓名</th>
							<th class="table-title">用户组</th>
							<th class="table-title">状态</th>
							<th class="table-title">创建时间</th>
							<th class="table-title">登录次数</th>
							<th class="table-title">最后登录</th>
							<th class="table-title">登录IP</th>
              			</tr>
          			</thead>
					<tbody>
					<?php
						foreach( $data['datalist'] as $item ){
							$manager_userid	=	$item['manager_userid'];
							$groupid		=	$item['groupid'];
							echo '<tr class="tr_30">';
								echo '	<td>', $item['id'], '</td>';
								echo '	<td><a href="'.$this->createUrl('system/usermod', array('Id'=>$item['id'])).'">', $item['email'], '</a></td>';
								echo '	<td>', $item['title'], '</td>';
								echo '	<td>', $data['grouplist'][$groupid]['name'], '</td>';
								echo '	<td>', (1 == $item['status']) ? '正常' : '待审核', '</td>';
								echo '	<td>', $item['createtime'], '</td>';
								echo '	<td>', $item['logincount'], '</td>';
								echo '	<td>', $item['lastlogin'], '</td>';
								echo '	<td>', $item['loginip'], '</td>';
								
							echo '</tr>';
						}
					?>
					<tr class="tr_30">
						<td colspan="17"><a href="<?php echo $this->createUrl('system/useradd');?>"  class="am-btn am-btn-primary am-btn-xs">添加用户</a></td>
					</tr>
					<tr class="tr_30">
						
					</tr>
				</tbody>
			</table>
		<?php 
			}
			if( !empty($msg) ){
				echo '<script>alert("', $msg , '");';
				if( 0 == $ret ){
					echo 'location.href="'.$this->createUrl('system/grouplist').'";';
				}
				echo '</script>';
			}
		?>
	</div>
</div>
<!-- content end -->