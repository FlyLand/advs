<?php
$offerId = Yii::app()->request->getParam('offer_id');
?>

<div class="row">
	<div class="col-sm-12">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<h5>Offer Detail</h5>
				<div class="ibox-tools">
					<a class="collapse-link">
						<i class="fa fa-chevron-up"></i>
					</a>
					<a class="dropdown-toggle" data-toggle="dropdown" href="tabs_panels.html#">
						<i class="fa fa-wrench"></i>
					</a>
					<ul class="dropdown-menu dropdown-user">
						<li><a href="tabs_panels.html#">选项1</a>
						</li>
						<li><a href="tabs_panels.html#">选项2</a>
						</li>
					</ul>
					<a class="close-link">
						<i class="fa fa-times"></i>
					</a>
				</div>
			</div>
			<div class="ibox-content">
				<div class="panel-body">
					<div class="panel-group" id="accordion">
						<div class="panel panel-default">
							<div class="panel-heading">
								<h5 class="panel-title">
									<a data-toggle="collapse" data-parent="#accordion" href="tabs_panels.html#collapseOne">DETAILS</a>
									<a style="float:right;color: #00a1b5" href="<?php echo $this->createUrl('offer/editdetail',array('id'=>$offers['id']));?>">Edit</a>
								</h5>
							</div>
							<div id="collapseOne" class="panel-collapse collapse in">
								<div class="panel-body">
									<div class="col-sm-12">
										<p>
											<strong>
												ID:
											</strong>
											<span id="offerId"><?php echo $offers['id']?></span>
										</p>
										<a href="<?php echo $this->createUrl('offer/offerthumbnail',array('id'=>$offers['id']))?>">Add Thumbnail</a>
										<p><?php if(!empty($offers['thumbnail'])){?>
												<img src="<?php echo $offers['thumbnail'];?>">
											<?php } ?></p>
										<p>
											<strong>
												Advertiser:
											</strong>
											<span id="advertiser_id"><?php
												$advertise = JoySystemUser::model()->findByPk($offers['advertiser_id']);
												echo $advertise['company'];?></span>
										</p>

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
						</div>
					</div>

					<div class="panel-group" id="accordion">
						<div class="panel panel-default">
							<div class="panel-heading">
								<h5 class="panel-title">
									<a data-toggle="collapse" data-parent="#accordion" href="tabs_panels.html#collapseTwo">DETAILS</a>
								</h5>
							</div>
							<div id="collapseTwo" class="panel-collapse collapse in">
								<div class="panel-body">
									<div class="col-sm-12">
										<p>Generate tracking links for this offer. Select an affiliate first and then a tracking link will be generated. Use the tracking links in advertising campaigns. Tracking links recorded as clicks in reporting.</p>
										<div class="col-sm-4">Affiliate:</div>
										<div class="col-sm-8">
											<?php if(in_array($this->user['groupid'],array('1','2','3','6'))){ ?>
												<select class="form-control m-b" id="affiliate" >
													<option value=" " selected="selected">-Select an affiliate-</option>
													<?php
													foreach ($affiliates as $aff){
														echo '<option value="'.$aff['id'].'">'.$aff['id'] . '    ' .$aff['company'].'</option>';
													}
													?>
												</select>
											<?php }else{ ?>
											<select class="form-control m-b" id="affiliate" >
												<option value=" " selected="selected">-Select an affiliate-</option>
												<?php
												foreach ($affiliates as $aff) {
													if ($this->user['userid'] == $aff['id']) {
														echo '<option value="' . $aff['id'] . '">' . $aff['company'] . '</option>';
													}
												}
												} ?></select>
										</div>

										<div class="col-sm-4">Country:</div>
										<div class="col-sm-8">
											<select id="land" class="form-control m-b" >
												<option value="0">Not China</option>
												<option value="1">China</option>
											</select>
										</div>

										<div class="col-sm-4">Tracking Link:</div>
										<div class="col-sm-8">
											<input type="text" class="form-control" id="track_link">
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="panel-group" id="accordion">
						<div class="panel panel-default">
							<div class="panel-heading">
								<h5 class="panel-title">
									<a data-toggle="collapse" data-parent="#accordion" href="tabs_panels.html#collapseThree">DETAILS</a>								</h5>
							</div>
							<div id="collapseThree" class="panel-collapse collapse in">
								<div class="panel-body">
									<div class="col-sm-12">
										<div style="margin-right:30px; float: right"><a href="<?php echo $this->createUrl('offer/editaccount',array('id'=>$offers['id']));?>">Affiliate Payouts</a>&nbsp;&nbsp;&nbsp;
											<a href="<?php echo $this->createUrl('offer/editpayout',array('id'=>$offers['id']));?>">Edit</a></div></div>
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
					</div>
				</div>
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
					var track_url = '<?php echo "http://".IN_LAND_SERVER_NAME."api/click?oid=$offerId&channel="?>'

				}else{
					var track_url = '<?php echo OUT_LAND_SERVER_NAME?>api/click?oid=<?php echo $offerId;?>&channel=';
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
