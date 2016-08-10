<?php
/**
 * 增加offer
 */
include_once dirname(dirname(__FILE__)).'/sidebar.php';
$offerId = Yii::app()->request->getParam('offer_id');
?>
<style>
	img{
		width: 120px;height: 80px;overflow: hidden;
		min-width: 120px;  min-height: 80px
	}
</style>
<div class="admin-content">
	<div class="am-cf am-padding">
		<div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">offers</strong> / <small><?php echo $offers['name']?></small></div>
	</div>

	<div class="am-tabs am-margin" >
		<div class="am-panel am-panel-default am-form">
			<div class="am-panel-hd am-cf" data-am-collapse="{target: '#collapse-panel-1'}"><b>DETAILS</b><span class="am-icon-chevron-down am-fr"></span>
				<?php if(in_array($this->user['groupid'],array('1','2','3','6'))){ ?>
					<div style="margin-right:30px;float: right"><a href="<?php echo $this->createUrl('offer/editdetail',array('id'=>$offers['id']));?>">Edit</a></div>
				<?php } ?>
			</div>
			<div class="am-panel-bd am-collapse am-in" id="collapse-panel-1">
				<div class="am-g am-margin-top">
					<p>
						<strong>
							ID:
						</strong>
						<span id="offerId"><?php echo $offers['id']?></span>
					</p>
					<?php if(in_array($this->user['groupid'],array('1','2','3','6'))){ ?>
					<p style="float: right;padding-right: 400px">
						<a href="<?php echo $this->createUrl('offer/offerthumbnail',array('id'=>$offers['id']))?>">Add Thumbnail</a>
					</p>
					<p  style="float: right;padding-right: 500px">
						<?php } ?>
						<?php if(!empty($offers['thumbnail'])){?>
							<img src="<?php echo $offers['thumbnail'];?>">
						<?php } ?>
					</p>
					<?php if(in_array($this->user['groupid'],array('1','2','3','6'))){ ?>
						<p>
							<strong>
								Advertiser:
							</strong>
			         <span id="advertiser_id"><?php
						 $advertise = JoySystemUser::model()->findByPk($offers['advertiser_id']);
						 echo $advertise['company'];?></span>
						</p>
					<?php } ?>
					<p>
						<strong>
							Name:
						</strong>
						<span id="offerId"><?php echo $offers['name']?></span>
					</p>
					<p>
						<strong>
							Description:
						</strong>
						<span id="offerId"><?php echo $offers['description']?></span>
					</p>
					<p>
						<strong>
							Preview:
						</strong>
						<span id="offerId"><a href="<?php echo $offers['preview_url']?>" target="_blank"><?php echo $offers['preview_url']?></a></span>
					</p>
					<p>
						<strong>
							Status:
						</strong>
			         <span id="offerId"><?php
						 if($offers['status'] == 0){
							 echo 'Pending';
						 }elseif ($offers['status'] == 1){
							 echo 'Active';
						 }elseif ($offers['status'] == 2){
							 echo 'Deleted';
						 } ?></span>
					</p>
					<?php if(!empty($offers['expiration_date']) && $offers['expiration_date'] != ''  && $offers['expiration_date'] != '0000-00-00'){ ?>
						<p>
							<strong>
								Expires:
							</strong>
							<span id="offerId"><?php echo $offers['expiration_date']?></span>
						</p>
					<?php } ?>
					<?php if(isset($offers['geo_targeting']) && $offers['geo_targeting'] != ''){ ?>
						<p>
							<strong>
								Geo_targeting:
							</strong>
 			         <span id="offerId">
  			         	<p><?php echo $offers['geo_targeting'] ?></p>
 			         </span>
						</p>
					<?php } ?>
					<p>
						<strong>
							Daily Conversions :
						</strong>
						<span id="offerId">&nbsp;&nbsp;<?php echo isset($caps['daily_con'])  && intval($caps['daily_con']) != 0 ? ($caps['daily_con'])  : 'open';?></span>
						<?php if(in_array($this->user['groupid'],array('1','2','3','6'))){ ?><a href="<?php echo $this->createUrl('offer/editcaps',array('id'=>$offers['id']));?>">&nbsp;
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Edit</a>
						<?php } ?>
					</p>
					<?php if(isset($offers['traffic']) && $offers['traffic'] != ''){ ?>
						<p>
							<strong>
								Platform :
							</strong>
							<span id="offerId"><?php echo isset($offers['platform']) ? $offers['platform'] : '';?></span>
						</p>
					<?php } ?>
					<?php if(isset($offers['traffic']) && $offers['traffic'] != ''){ ?>
						<p>
							<strong>
								Traffic :
							</strong>
							<span id="offerId"><?php echo $offers['traffic'];?></span>
						</p>
					<?php } ?>
					<?php if($offers['min_android_version']){ ?>
						<p>
							<strong>
								min_android_version :
							</strong>
							<span id="offerId"><?php echo $offers['min_android_version']?></span>
						</p>
					<?php } ?>
					<?php if($offers['protocol']){ ?>
						<p>
							<strong>
								protocol :
							</strong>
							<span id="offerId"><?php echo $offers['protocol'];?></span>
						</p>
					<?php } ?>
					<p>
						<strong>
							Effect:
						</strong>
						<span id="offerId"><?php echo $offers['description']?></span>
					</p>
				</div>

			</div>
		</div>

		<div class="am-panel am-panel-default am-form">
			<div class="am-panel-hd am-cf" data-am-collapse="{target: '#collapse-panel-2'}"><b>GENERATE TRACKING</b><span class="am-icon-chevron-down am-fr"></span></div>
			<div class="am-panel-bd am-collapse am-in" id="collapse-panel-2">
				<p>Generate tracking links for this offer. Select an affiliate first and then a tracking link will be generated. Use the tracking links in advertising campaigns. Tracking links recorded as clicks in reporting.</p>
				<div class="am-g am-margin-top">
					<div class="am-u-sm-4 am-u-md-2 am-text-right">Affiliate:</div>
					<div class="am-u-sm-8 am-u-md-10">
						<?php if(in_array($this->user['groupid'],array('1','2','3','6'))){ ?>
							<select id="affiliate" >
								<option value=" " selected="selected">-Select an affiliate-</option>
								<?php
								foreach ($affiliates as $aff){
									echo '<option value="'.$aff['id'].'">'.$aff['id'] . '    ' .$aff['company'].'</option>';
								}
								?>
							</select>
						<?php }else{ ?>
						<select id="affiliate" >
							<option value=" " selected="selected">-Select an affiliate-</option>
							<?php
							foreach ($affiliates as $aff) {
								if ($this->user['userid'] == $aff['id']) {
									echo '<option value="' . $aff['id'] . '">' . $aff['company'] . '</option>';
								}
							}
							} ?></select>
					</div>
				</div>
				<div class="am-g am-margin-top">
					<div class="am-u-sm-4 am-u-md-2 am-text-right">
						Country:
					</div>
					<div class="am-u-sm-8 am-u-md-4 am-u-end col-end">
						<select id="land" >
							<option value="0">Out Land</option>
							<option value="1">In Land</option>
						</select>
					</div>
				</div>
				<div class="am-g am-margin-top">
					<div class="am-u-sm-4 am-u-md-2 am-text-right">
						Tracking Link:
					</div>
					<div class="am-u-sm-8 am-u-md-4 am-u-end col-end">
						<input type="text" class="am-input-sm" id="track_link">
					</div>
					<a href="#">update</a>
				</div>
				<div class="am-g am-margin-top">
					<div class="am-u-sm-4 am-u-md-2 am-text-right">
						Affiliate Sub ID:
					</div>
					<div class="am-u-sm-8 am-u-md-4" id="sub_id">
						<div id="someting"><input type="radio" class="am-input-sm" id="wenzi" >Pass in unique user information for conversion tracking</div>
					</div>
					<div class="am-hide-sm-only am-u-md-6"></div>
				</div>
				<div class="am-g am-margin-top">
					<div class="am-u-sm-4 am-u-md-2 am-text-right">
						SubID:
					</div>
					<div class="am-u-sm-8 am-u-md-4" id="sub_id2">
						<div id="someting2"><input type="radio" class="am-input-sm" id="sub_id2" >Pass in unique user information for conversion tracking</div>
					</div>
					<div class="am-hide-sm-only am-u-md-6"></div>
				</div>
			</div>
		</div>

		<div class="am-panel am-panel-default am-form">
			<div class="am-panel-hd am-cf" data-am-collapse="{target: '#collapse-panel-3'}"><b>Payout</b><span class="am-icon-chevron-down am-fr"></span>
				<?php if(in_array($this->user['groupid'],array('6','1','2','3'))){ ?>
				<div style="margin-right:30px; float: right"><a href="<?php echo $this->createUrl('offer/editaccount',array('id'=>$offers['id']));?>">Affiliate Payouts</a>&nbsp;&nbsp;&nbsp;
					<a href="<?php echo $this->createUrl('offer/editpayout',array('id'=>$offers['id']));?>">Edit</a></div></div>
			<div class="am-panel-bd am-collapse am-in" id="collapse-panel-3">
				<?php } ?>
				<p>
					<strong>
						Currency:
					</strong>
					<span id="offerId"><?php echo $offers['currency'];?></span>
				</p>
				<?php if(in_array($this->user['groupid'],array('1','2','3','6'))){ ?>
					<p>
						<strong>
							Revenue Value:
						</strong>
						<span id="offerId"><?php echo $offers['revenue'];?></span>
					</p>
				<?php } ?>
				<p>
					<strong>
						Default Payout:
					</strong>
					<span id="offerId"><?php echo $offers['payout'];?></span>
				</p>
			</div>
		</div>
		<?php if(in_array($this->user['groupid'],array('1','2'))){ ?>
			<div class="am-panel am-panel-default am-form">
				<div class="am-panel-hd am-cf" data-am-collapse="{target: '#collapse-panel-4'}"><b>Conversion Pixels / URLs</b><span class="am-icon-chevron-down am-fr"></span></div>
				<div class="am-panel-bd am-collapse am-in" id="collapse-panel-4">
					<p>Add third-party conversion tracking pixels and postback URLs below. The system will dynamically replace several optional variables. </p>
					<div class="am-g am-margin-top">
						<div class="am-u-sm-4 am-u-md-2 am-text-right">Affiliate:</div>
						<div class="am-u-sm-8 am-u-md-10">
							<select id="offerPixelAffiliateId" name="offerPixel_affiliate_id">
								<option value=" " selected="selected">-Select an affiliate-</option>
								<?php
								foreach ($affiliates as $aff){
									echo '<option value="'.$aff['id'].'">'.$aff['company'].'</option>';
								}
								?>
							</select>
						</div>
					</div>
					<div class="am-g am-margin-top">
						<div class="am-u-sm-4 am-u-md-2 am-text-right">Type:</div>
						<div class="am-u-sm-8 am-u-md-10">
							<select id="offerPixelType" >
								<option value="url" selected="selected">Postback URL</option>
							</select>
						</div>
					</div>
					<div class="am-g am-margin-top">
						<div class="am-u-sm-4 am-u-md-2 am-text-right">Add Postback URL:</div>
						<div class="am-u-sm-8 am-u-md-10">
							<textarea id="offerPixelCode" name="code"></textarea>
							<button onclick="save_code()">save</button>
							<p>Postback URL for Server (Cookieless) Tracking. Optional Variables</p>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>
		<!-- whitelist start -->
		<div class="am-panel am-panel-default am-form">
			<div class="am-panel-hd am-cf" data-am-collapse="{target: '#collapse-panel-5'}">
				<b>Whitelist</b><span class="am-icon-chevron-down am-fr"></span>
				<div style="margin-right:30px; float: right">
					<a href="<?php echo $this->createUrl('offer/whitelistadd',array('offerid'=>$offers['id']));?>">Add</a>&nbsp;&nbsp;&nbsp;
					<a href="<?php echo $this->createUrl('offer/whitelist',array('offerid'=>$offers['id']));?>">Manage</a>
				</div>
			</div>
			<div class="am-panel-bd am-collapse am-in" id="collapse-panel-5">
				<div class="am-g am-margin-top">
					<table style="text-align:center;width:50%;" border="1" cellspacing="0" bordercolor="#d3d3d3">
						<thead>
						<tr class="am-primary">
							<th class="table-title">IP Address</th>
							<th class="table-title">&nbsp;&nbsp;</th>
						</tr>
						</thead>
						<tbody>
						<?php
						if(count($ip_list) > 0){
							foreach ($ip_list as $key=>$val){
								?>
								<tr>
									<td><?php echo $val['content'];?></td>
									<td>
										<a href="<?php echo $this->createUrl('offer/whitelistedit', array('offerid'=>$val['offerid'], 'id'=>$val['id']));?>">Edit</a>
									</td>
								</tr>
							<?php } ?>
						<?php }else{ ?>
							<tr><td colspan="2">No Offer Whitelists.</td></tr>
						<?php }?>
						</tbody>
					</table>
				</div>
			</div>
			<?php if(in_array($this->user['groupid'],array('1','2'))){ ?>
				<div class="am-panel am-panel-default am-form">
					<div class="am-panel-hd am-cf" data-am-collapse="{target: '#collapse-panel-6'}">
						<b>Affiliate Cut</b><span class="am-icon-chevron-down am-fr"></span>
						<div style="margin-right:30px; float: right">
							<a href="<?php echo $this->createUrl('offer/offercut',array('offerid'=>$offers['id']))?>">Add</a>&nbsp;&nbsp;&nbsp;
						</div>
					</div>
					<div class="am-panel-bd am-collapse am-in" id="collapse-panel-6">
						<div class="am-g am-margin-top">
							<table style="text-align:center;width:50%;" border="1" cellspacing="0" bordercolor="#d3d3d3">
								<thead>
								<tr class="am-primary">
									<th class="table-title">Affiliate Company</th>
									<th class="table-title">Cut Num</th>
									<th class="table-title">Payout</th>
									<th class="table-title"></th>
								</tr>
								</thead>
								<tbody>
								<?php
								if(!empty($cuts)){
									foreach ($cuts as $val){
										?>
										<tr>
											<td><?php echo $val['aff']['company'];?></td>
											<td><?php echo $val['cut_num'];?>%</td>
											<td><?php echo $val['payout'];?>$</td>
											<td><a href="<?php echo $this->createUrl('offer/editoffercut',array('offerid'=>$offerId,'affid'=>$val['aff']['id'],'type'=>'edit'));?>">Edit</a></td>
											<td><a id="delete_cut" onclick="deletecut(<?php echo $val['aff']['id'];?>)">Delete</a></td>
										</tr>
									<?php } ?>
								<?php }else{ ?>
									<tr><td colspan="2">No Offer Cut Affiliate.</td></tr>
								<?php }?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			<?php } ?>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(function(){
		$("#someting").click(function(){
			$("#someting").html(" ");
			$("#sub_id").append("<input type='text' class='am-input-sm'id='add_sub_id'><a href='#' onclick='updateSubId()'>update</a>");
		});
		$("#someting2").click(function(){
			$("#someting2").html(" ");
			$("#sub_id2").append("<input type='text' class='am-input-sm'id='add_sub_id2'><a href='#' onclick='updateSubId2()'>update</a>");
		});
		$("#affiliate").change(function(){
			var affiliateId = $("#affiliate").val();
			<?php if($offers['protocol'] == 'search'){ ?>
				var track_url = "http://s.joymedia.mobi/?c="+affiliateId+"&o="+ '<?php echo $offerId;?>' + "&s=";
			<?php }else{ ?>
				if($('#land').val() == 1){
					var track_url = '<?php echo "http://".IN_LAND_SERVER_NAME."/index.php?r=api/click&oid=$offerId&ffid="?>'

				}else{
					var track_url = '<?php echo LINK?>&oid=<?php echo $offerId;?>&ffid=';
				}
			<?php } ?>
			track_url = track_url+affiliateId;
			$("#track_link").val(track_url);
		});
	});
	function save_code(){
		var code = $("#offerPixelCode").val();
		var offid = $("#offerPixelAffiliateId").val();
		var type = $("#offerPixelType").val();
		$.ajax({
			url:"<?php echo $this->createUrl('offer/addpixels');?>",// 跳转到 action
			data:{
				offerid : <?php echo $offerId?>,
				affid : offid,
				type : type,
				code : code
			},
			type:'post',
			dataType:'json',
			success:function(data) {
				//var ret_data = eval('('+data+')');
				alert(data.msg);
				return false;
			},
			error:function(data){
				alert('failed');
				return false;
			}
		});
	}
	function deletecut(affid){
		if(confirm("Are you sure to delete the cut?")){
			var href = "<?php echo $this->createUrl('offer/editoffercut',array('offerid'=>$offerId,'type'=>'delete'));?>&affid="+affid;
			window.location.href=href;
		}
	}

	function updateSubId(){
		var affiliateId = $("#affiliate").val();
		var add_sub_id = $("#add_sub_id").val();
		if(!add_sub_id){
			alert("请填写subId");
			return;
		}
		var track_link = $("#track_link").val();
		var add_sub_id2 = $("#add_sub_id2").val();//第二个添加参数的值

		if(track_link.indexOf("aff_sub")>0){

			if(add_sub_id2){
				var track_link_change ='<?php echo LINK?>&oid=<?php echo $offerId?>&ffid='+ affiliateId+'&subid='+ add_sub_id2;
			}else{
				var track_link_change ='<?php echo LINK?>&offer_id=<?php echo $offerId?>&ffid='+ affiliateId;
			}
			track_link_change = track_link_change+'&aff_sub='+add_sub_id;
			if(track_link_change){
				alert("aff_sub添加成功！");
			}
			//第一个添加字段，添加成功也要将其url数据更新到数据库
			$.ajax({
				url:'<?php echo $this->createUrl('offer/addpixels');?>',// 跳转到 action    
				data:{
					offerid : <?php echo $offerId?>,
					affid : affiliateId,
					type :'url',
					track : track_link_change
				},
				type:'post',
				dataType:'json',
				success:function(data) {

				},
				error:function(data){
					console.log(data);
				}
			});

			$("#track_link").val(track_link_change);
		}else{
			track_link = track_link+'&aff_sub='+add_sub_id;
			if(track_link){
				alert("aff_sub添加成功！");
			}
			//第一个添加字段，添加成功也要将其url数据更新到数据库
			$.ajax({
				url:'<?php echo $this->createUrl('offer/addpixels');?>',// 跳转到 action    
				data:{
					offerid : <?php echo $offerId?>,
					affid : affiliateId,
					type :'url',
					track : track_link
				},
				type:'post',
				dataType:'json',
				success:function(data) {

				},
				error:function(data){
					console.log(data);
				}
			});
			$("#track_link").val(track_link);
		}

	}
	function updateSubId2(){
		var add_sub_id2 = $("#add_sub_id2").val();
		var affiliateId = $("#affiliate").val();
		if(!add_sub_id2){
			alert("请填写subid");
			return;
		}
		var track_link = $("#track_link").val();
		var add_sub_id = $("#add_sub_id").val();//第一个添加参数的值 
		if(track_link.indexOf("subid")>0){
			if(add_sub_id){
				var track_link_change ='<?php echo Yii::app()->request->hostInfo.$this->createUrl('api/offerclick')?>&offer_id=<?php echo $offerId?>&aff_id='+ affiliateId+'&aff_sub='+add_sub_id;
			}else{
				var track_link_change ='<?php echo Yii::app()->request->hostInfo.$this->createUrl('api/offerclick')?>&offer_id=<?php echo $offerId?>&aff_id='+ affiliateId;
			}
			track_link_change = track_link_change+'&subid='+add_sub_id2;
			if(track_link_change){
				alert("subId 添加成功！");
			}

			//第二个添加字段，添加成功也要将其url数据更新到数据库
			$.ajax({
				url:'<?php echo $this->createUrl('offer/addpixels');?>',// 跳转到 action    
				data:{
					offerid : <?php echo $offerId?>,
					affid : affiliateId,
					type :'url',
					track : track_link_change
				},
				type:'post',
				dataType:'json',
				success:function(data) {

				},
				error:function(data){
					console.log(data);
				}
			});
			$("#track_link").val(track_link_change);
		}else{
			track_link = track_link+'&subid='+add_sub_id2;
			if(track_link){
				alert("subId 添加成功！");
			}
			//第二个添加字段，添加成功也要将其url数据更新到数据库
			$.ajax({
				url:'<?php echo $this->createUrl('offer/addpixels');?>',// 跳转到 action    
				data:{
					offerid : <?php echo $offerId?>,
					affid : affiliateId,
					type :'url',
					track : track_link
				},
				type:'post',
				dataType:'json',
				success:function(data) {

				},
				error:function(data){
					console.log(data);
				}
			});
			$("#track_link").val(track_link);
		}

	}
	$(document).ready(function () {
		$('.fancybox').fancybox();
	});
</script>
