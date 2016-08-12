<?php 

$nav_color_arr	=	array('admin-icon-green', 'admin-icon-blue', 'admin-icon-red', 'admin-icon-yellow');

//计算当前选择大栏
$curr_key	=	''; 
$powerinfo	=	$this->user['powerinfo'];
//print_r($powerinfo);
/*
$requesturl	=	Yii::app()->request->getUrl();
$requesturl	=	str_replace('.html', '', $requesturl);
if(0 === strpos($requesturl, '/')){
	$requesturl	=	substr($requesturl, 1);
}
*/
$requesturl	=	isset($_GET['r']) ? $_GET['r'] : '';
var_dump($user);exit(1);
foreach( $powerinfo as $key=>$item ){
	$temp	=	array();
	foreach( $item['actions'] as $action => $it ){
		if( 0 == $it['weight'] ){
			continue;
		}
		$temp[$action]	=	$it;	
	}
	if( 0 < count($temp) ){
		foreach( $temp as $action => $it ){
			if( $action == $requesturl ){
				$curr_key	=	$key;
			}
		}
	}
}

//读取offer是否为推荐
$offers_top = joy_offers::model()->findAllByAttributes(array('recommend'=>1));
?>
<style>
	#logout:hover{
		cursor:pointer;
	}
</style>
<script>
</script>

<div   class="admin-sidebar am-offcanvas" id="admin-offcanvas">
    <div class="am-offcanvas-bar admin-offcanvas-bar">
      <ul class="am-list admin-sidebar-list">
      			<?php
					//根据用户的权限生成左侧的操作菜单
					$requesturl	=	Yii::app()->request->getUrl();
					foreach( $powerinfo as $key=>$item ){
						$temp_in		=	'';
						if($key == $curr_key){
							$temp_in	=	'am-in';
						}
						$nav_num	=	$key % 4;
						$nav_color	=	$nav_color_arr[$nav_num];

						$temp	=	array();
						foreach( $item['actions'] as $action => $it ){
							if($action == 'offer/list'){
								if(AFF_GROUP_ID == $this->user['groupid']){
									//查询是否有配置单独的offer
									$offer_power = JoyOfferCut::model()->findByAttributes(array('aff_id'=>$this->user['userid'],'isshow'=>1));
									if(empty($offer_power)){
										continue;
									}
								}
							}
							if($action == 'payment/paymenttotal'){
								if(AFF_GROUP_ID == $this->user['groupid'] && $this->user['userid'] != 1){
									continue;
								}
							}
							if( 0 == $it['weight'] ){
								continue;
							}
							$temp[$action]	=	$it;	
						}
						if( 0 < count($temp) ){
							echo '<li class="admin-parent">';
							echo '<a class="am-cf am-collapsed" data-am-collapse="{target: \'#collapse-'.$key.'\'}">';
							echo '<span class="am-icon-file '.$nav_color.'"></span> '.$item['name'].' <span class="am-icon-angle-right am-fr am-margin-right"></span></a>';
							echo '<ul class="am-list am-collapse admin-sidebar-sub '.$temp_in.'" id="collapse-'.$key.'">';
							foreach( $temp as $action => $it ){
								echo '<li><a href="'.$this->createUrl($action).'" class="am-cf"><span class="am-icon-calendar"></span> '.$it['pname'].'</a></li>';
							}
							echo '</ul>';
							echo '</li>';
						}
					}
				?>
        
        
        <li><a id="logout" style="hover:point;" onclick="logout()"><span class="am-icon-sign-out"></span> Logout </a></li>
       </ul>

		<?php if(!empty($offers_top)){ ?>
      <div class="am-panel am-panel-default admin-sidebar-panel">
        <div class="am-panel-bd">
			<p style="color: red"><span class="am-icon-bookmark"></span>    TOP  OFFERS </p>

				<table class="am-table am-table-striped am-table-hover table-main">
					<thead>
					<tr>
						<td>ID</td>
						<td>NAME</td>
					</tr>
					</thead>
					<tbody>
					<?php foreach($offers_top as $offer){
						$url = $this->createUrl('offer/offerdetail',array('offer_id'=>$offer['id']));
						echo '<tr>';
						echo "<td class='table-title'>{$offer['id']}</td>";
						echo "<td class='table-title'><a href='$url'>{$offer['name']}</a></td>";
						echo '</tr>';
					} ?>
					</tbody>
				</table>
        </div>
      </div>
		<?php } ?>

		<?php if(in_array($this->user['groupid'],$this->manager_group)){?>
		<div class="am-panel am-panel-default admin-sidebar-panel">
			<div class="am-panel-bd">
				<p style="color: forestgreen"><span class="am-icon-bookmark"></span> NOTICE </p>
				手动添加offer流程：
				<p>
				1、获取上游url地址，在Default Offer URL栏中填写需要跳转的地址，
					参数名称由上游定义，不能随意改动。参数值改成系统对应的值，本系统支持对应上游值有：</br>
					transaction_id:唯一标识</br>
					publisher_id、affid、channel：本系统下游id号</br>
					search：搜索跳转需要查找的内容</br>
				2、在上游系统中填写本系统的回调地址，一旦调用本接口则视为转化数据，现在本系统支持接收上游的参数有：</br>
					aff_sub:点击唯一标识</br>
					clientip:用户点击ip</br>
					cou:点击用户国家</br>
					car:载体</br>
					am:offer价格</br>
					platform:平台</br>
					kimia_id:特殊用户的特殊字段</br>
					如有其它需求，必须让技术协助
				3、在对应下游渠道中填写下游回调地址。
					在PostBack一栏中填写从下游获取的回调地址，本系统支持回调的参数类型有：</br>
					aff_sub:点击唯一标识</br>
					payout:价格</br>
					affsub_id:下游的下游渠道号</br>
					campaign_id:某些渠道的特定值(需要技术协助)</br>
					platform:平台</br>
					ip:用户点击的ip地址</br>
				</p>
			</div>
		</div>
<?php } ?>
    </div>
  </div>
<script>
	var logout = function(){
		if(window.confirm('Are you sure to logout the system?')){
			window.location.href	=	'<?php echo $this->createUrl('system/logout');?>';
		}
	}
</script>
  