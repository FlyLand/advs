<?php
include_once dirname(dirname(__FILE__)) . '/sidebar.php';

$this->breadcrumbs = array(
	array(
 		'name'=>'系统管理',
 		'url'=>$this->createUrl('card/addcard'),
 			),
     '权限管理'
 );
?>
<div class="grid-1">
  	<div class="title-grid">权限列表</div>
	<div class="content-gird typography-heading">
		<div id	= "search_data_display"  class="fontpix" style="width:100%;">
			<table border="1" cellspacing="0" bordercolor="#d3d3d3" width="100%" style="text-align:center;margin-left:0px;">
				<tbody>
					<tr class="tr_30">
						<td>权限ID</td>
						<td>权限名称</td>
						<td>权限操作</td>
						<td>当前状态</td>
						<td>所属组名</td>
						<td>排序权值</td>
						<td>创建时间</td>
						<td>修改时间</td>
					</tr>
					<?php
						if( 0 == $ret ){
							foreach( $data['powerlist'] as $item ){
								echo '<tr class="tr_30">';
									echo '	<td>', $item['id'], '</td>';
									if( 0 == $item['parentid'] ){
										echo '	<td><a href="', $this->createUrl('system/powerlist', array('Parentid'=>$item['parentid'],'Id'=>$item['id'])) ,'">', $item['pname'], '</a></td>';
									}else{
										echo '	<td><a href="', $this->createUrl('system/powermod', array('Parentid'=>$item['parentid'],'Id'=>$item['id'])) ,'">', $item['pname'], '</a></td>';
									}
									echo '	<td>', $item['action'], '</td>';
									echo '	<td>', (1 == $item['status']) ? '正常' : '隐藏', '</td>';
									if( false === $data['topmenu'] ){
										echo '	<td>顶级菜单</td>';
									}else{
										echo '	<td>', $data['topmenu']['pname'], '</td>';
									}
									echo '	<td>', $item['weight'], '</td>';
									echo '	<td>', $item['addtime'], '</td>';
									echo '	<td>', $item['lastmodify'], '</td>';
								echo '</tr>';
							}
					?>
					<tr class="tr_30">
						<td colspan="13"><a href="<?php echo $this->createUrl('system/poweradd', array('Parentid'=>$data['parentid'])); ?>" >添加权限</a></td>
					</tr>
					<?php 
						}
					?>
				</tbody>
			</table>
		</div>
	</div>
</div>