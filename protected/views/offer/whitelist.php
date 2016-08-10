<?php
/**
 * offer管理列表
 */
include_once dirname(dirname(__FILE__)).'/sidebar.php';
?>
<div class="admin-content">
	 	<div class="am-cf am-padding">
	      <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">offer whitelist</strong></div>
	    </div>
	    <div class="am-g">
			<div class="am-u-sm-12 am-u-md-6">
	        	<div class="am-btn-toolbar">
	          		<div class="am-btn-group am-btn-group-xs">
	            		 <button type="button" class="am-btn am-btn-default" onclick="location.href='<?php echo $this->createUrl('offer/whitelistadd', array('offerid'=>$offerid));?>';return false;"><span class="am-icon-plus"></span> Add Whitelist</button>
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
                			<th class="table-title">IP Address</th>
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
          				      <td><?php echo $val['content'];?></td>
          				      <td>
          				      	<a href="<?php echo $this->createUrl('offer/whitelistedit', array('offerid'=>$val['offerid'], 'id'=>$val['id']));?>">Edit</a>&nbsp;&nbsp;&nbsp;&nbsp;
          				      	<a style="cursor: pointer;" onclick="return whitelist_del(this, '<?php echo $val['id'];?>', '<?php echo $val['offerid'];?>', '<?php echo $val['content'];?>')">Delete</a>
          				      </td>
          				   </tr>
          				  <?php }?>
						<?php }else{?>
							<tr><td colspan="4">No Offer Whitelists.</td></tr>
						<?php }?>
				   </tbody>
				</table>
				<input type="hidden" name="whitelist_del_url" id="whitelist_del_url" value="<?php echo $this->createUrl('offer/whitelistdel');?>" />
				<hr />
				</form>
			</div>
		</div>
	</div>
<script type="text/javascript">
function whitelist_del(obj, id, offerid, ip){
	if('' == id){
		alert('Please select IP address');
		return false;
	}
	if(confirm('Confirm the deletion of the white list IP address '+ip+' ?')){
		var delurl = $("#whitelist_del_url").val();
		var url = delurl+'&id='+id+'&offerid='+offerid;;
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