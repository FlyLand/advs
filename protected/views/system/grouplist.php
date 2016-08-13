<?php
include_once dirname(dirname(__FILE__)) . '/sidebar.php';
?>
<script type="text/javascript">
function check(id, name){
	if( confirm('你确定要删除用户组--'+name+'?，一旦删除，将不可恢复') ){
		//location.href='/system/groupdel.html?Id='+id;
		location.href='<?php echo $this->createUrl('system/grouplist', array('Id'=>''));?>'+id;
	}else{
		return false;
	}
}
</script>
<!-- content start -->
<div class="admin-content">
  	<div class="am-cf am-padding">
		<div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">用户组列表</strong> </div>
  	</div>
  	<div class="am-tabs am-margin" data-am-tabs style="margin-top:5px;">
      			<table class="am-table am-table-striped am-table-hover table-main">
            		<thead>
              			<tr class="am-primary">
                			<th class="table-title">用户组ID</th>
							<th class="table-title">用户组名</th>
							<th class="table-title">当前状态</th>
							<th class="table-title">创建时间</th>
							<th class="table-title">修改时间</th>
							<th class="table-title">管理操作</th>
              			</tr>
          			</thead>
					<tbody>
						<?php
							foreach( $data['grouplist'] as $item ){
								echo '<tr class="tr_30">';
									echo '	<td>', $item['id'], '</td>';
									echo '	<td><a href="'.$this->createUrl('system/groupmod', array('Id'=>$item['id'])).'">', $item['name'], '</a></td>';
									echo '	<td>', (1 == $item['status']) ? '正常' : '待审核', '</td>';
									echo '	<td>', $item['addtime'], '</td>';
									echo '	<td>', $item['lastmodify'], '</td>';
									echo '	<td><span style="cursor: pointer;" onclick="check(', $item['id'] ,',\''.$item['name'].'\')"  class="am-btn am-btn-primary am-btn-xs">删除该组</span></td>';
								echo '</tr>';
							}
						?>
						<tr class="tr_30">
							<td colspan="6"><a href="<?php echo $this->createUrl('system/groupadd'); ?>"  class="am-btn am-btn-primary am-btn-xs">添加用户组</a></td>
						</tr>
					</tbody>
				</table>
	</div>
</div>