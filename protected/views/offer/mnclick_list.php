<?php
/**
 * offer click管理列表
 */
include_once dirname(dirname(__FILE__)) . '/sidebar.php';
?>
<div class="admin-content">
	 	<div class="am-cf am-padding">
	      <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">offer click list</strong></div>
	    </div>
	    <div class="am-g">
			<div class="am-u-sm-12 am-u-md-6">
	        	<div class="am-btn-toolbar">
	          		<div class="am-btn-group am-btn-group-xs">
	            		 <button type="button" class="am-btn am-btn-default" onclick="location.href='<?php echo $this->createUrl('offer/mnclickadd');?>';return false;"><span class="am-icon-plus"></span> Add offer click</button>
			          <!-- <button type="button" class="am-btn am-btn-default"><span class="am-icon-trash-o"></span> 删除</button>-->
	          		</div>
        		</div>
	      	</div>
		</div>

    	<div class="am-g">
      		<div class="am-u-sm-12">
        		<form class="am-form">
          		<table class="am-table am-table-striped am-table-hover table-main">
            		<thead>
              			<tr>
                			<th class="table-title">ID</th>
                			<th class="table-title">OfferId</th>
                			<th class="table-title">Affiliate ID</th>
                			<th class="table-title">Start Date</th>
                			<th class="table-title">End Date</th>
                			<th class="table-title">nation</th>
                			<th class="table-title">max total</th>
                			<th class="table-title">execute total</th>
                			<th class="table-title">hour total</th>
                			<th class="table-title">status</th>
                			<th class="table-title">createtime</th>
                			<th class="table-title">&nbsp;&nbsp;</th> 
              			</tr>
          			</thead>
          			<tbody>
						<?php 
          					if(count($data) > 0){
          					foreach ($data as $key=>$val){
						?>
          				  <tr>
          				      <td><?php echo $val['id'];?></td>
          				      <td><?php echo $val['offerid'];?></td>
          				      <td><?php echo $val['affid'];?></td>
          				      <td><?php echo $val['start_date'];?></td>
          				      <td><?php echo $val['end_date'];?></td>
          				      <td><?php echo $val['nation'];?></td>
          				      <td><?php echo $val['max_total'];?></td>
          				      <td><?php echo $val['execute_total'];?></td>
          				      <td><?php echo $val['hour_total'];?></td>
          				      <td>
          				      <?php
          				      	if(1 == $val['status']){
									echo 'start';
								}elseif(2 == $val['status']){
									echo 'pause';
								}else{
									echo 'delete';
								}
          				      ?>
          				      </td>
          				      <td><?php echo $val['createtime'];?></td>
          				      <td>
          				      	<a href="<?php echo $this->createUrl('offer/mnclickedit', array('id'=>$val['id']));?>">Edit</a>&nbsp;&nbsp;&nbsp;&nbsp;
          				      	<?php if(1 == $val['status']){?>
          				      		<a style="cursor: pointer;" onclick="return mnclick_pause(this, '<?php echo $val['id'];?>', '<?php echo $val['offerid'];?>')">Pause</a>&nbsp;&nbsp;&nbsp;&nbsp;
          				      	<?php }elseif(2 == $val['status']){?>
          				      		<a style="cursor: pointer;" onclick="return mnclick_start(this, '<?php echo $val['id'];?>', '<?php echo $val['offerid'];?>')">Start</a>&nbsp;&nbsp;&nbsp;&nbsp;
          				      	<?php }?>
          				      	<a style="cursor: pointer;" onclick="return mnclick_del(this, '<?php echo $val['id'];?>', '<?php echo $val['offerid'];?>')">Delete</a>
          				      </td>
          				   </tr>
          				  <?php }?>
						<?php }else{?>
							<tr><td colspan="12">No offer click.</td></tr>
						<?php }?>
				   </tbody>
				</table>
				<input type="hidden" name="click_del_url" id="click_del_url" value="<?php echo $this->createUrl('offer/mnclickupdatestatus');?>" />
				<hr />
				</form>
			</div>
		</div>
	</div>
<script type="text/javascript">
function mnclick_pause(obj, id, offerid){
	if('' == id){
		alert('Please select');
		return false;
	}
	if(confirm('Confirm Pause of the id '+id+' ?')){
		var delurl = $("#click_del_url").val();
		var url = delurl+'&id='+id+'&status=2';
		$.get(url,function(s){
			var ret_data = eval('('+s+')');
			if( 0 != ret_data.ret ){
				alert(ret_data.msg);
				return false;
			}else{
				 location.reload();
				return false;
			}
		});
	}else{
		return false;
	}
}
function mnclick_start(obj, id, offerid){
	if('' == id){
		alert('Please select');
		return false;
	}
	if(confirm('Confirm start of the id '+id+' ?')){
		var delurl = $("#click_del_url").val();
		var url = delurl+'&id='+id+'&status=1';
		$.get(url,function(s){
			var ret_data = eval('('+s+')');
			if( 0 != ret_data.ret ){
				alert(ret_data.msg);
				return false;
			}else{
				 location.reload();
				return false;
			}
		});
	}else{
		return false;
	}
}
function mnclick_del(obj, id, offerid){
	if('' == id){
		alert('Please select');
		return false;
	}
	if(confirm('Confirm the deletion of the id '+id+' ?')){
		var delurl = $("#click_del_url").val();
		var url = delurl+'&id='+id+'&status=0';
		$.get(url,function(s){
			var ret_data = eval('('+s+')');
			if( 0 != ret_data.ret ){
				alert(ret_data.msg);
				return false;
			}else{
				$(obj).parent().parent().remove();
				alert(ret_data.msg);
				return false;
			}
		});
	}else{
		return false;
	}
}

</script>