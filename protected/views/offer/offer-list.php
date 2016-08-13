<link href="<?php echo Yii::app()->params['cssPath']?>css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">
<link href="<?php echo Yii::app()->params['cssPath']?>css/animate.min.css" rel="stylesheet">
<link href="<?php echo Yii::app()->params['cssPath']?>css/style.min862f.css?v=4.1.0" rel="stylesheet">
<script>
	$(document).ready(function(){$(".dataTables-example").dataTable();var oTable=$("#editable").dataTable();oTable.$("td").editable("http://www.zi-han.net/theme/example_ajax.php",{"callback":function(sValue,y){var aPos=oTable.fnGetPosition(this);oTable.fnUpdate(sValue,aPos[0],aPos[1])},"submitdata":function(value,settings){return{"row_id":this.parentNode.getAttribute("id"),"column":oTable.fnGetPosition(this)[2]}},"width":"90%","height":"100%"})});function fnClickAddRow(){$("#editable").dataTable().fnAddData(["Custom row","New row","New row","New row","New row"])};
</script>
<script type="text/javascript" src="http://tajs.qq.com/stats?sId=9051096" charset="UTF-8"></script>

	<div class="col-sm-12">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<h5>基本 <small>分类，查找</small></h5>
				<div class="ibox-tools">
					<a class="collapse-link">
						<i class="fa fa-chevron-up"></i>
					</a>
					<a class="dropdown-toggle" data-toggle="dropdown" href="table_data_tables.html#">
						<i class="fa fa-wrench"></i>
					</a>
					<a class="close-link">
						<i class="fa fa-times"></i>
					</a>
				</div>
			</div>
			<div class="ibox-content">
				<div class="admin-content">
					<div class="am-cf am-padding">
						<div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">Offer Manager List</strong></div>
					</div>
					<div class="am-g">
						<div class="am-u-sm-12 am-u-md-6">
							<div class="am-btn-toolbar">
								<div class="am-btn-group am-btn-group-xs">
									<?php  if(in_array($this->user['groupid'],$this->manager_group)){?>
										<button type="button" class="am-btn am-btn-default" onclick="location.href='<?php echo $this->createUrl('offer/add');?>';return false;"><span class="am-icon-plus"></span> Create</button>
										<!-- <button type="button" class="am-btn am-btn-default"><span class="am-icon-trash-o"></span> 删除</button>-->
									<?php } ?>
								</div>
							</div>
						</div>
						<div class="am-u-sm-12 am-u-md-12">
							<form class="am-form am-form-horizontal" action="<?php echo $this->createUrl('offer/list');?>" method="post" id="findAdver">
								<div class="am-panel-hd am-cf">
									<div class="am-u-sm-6 am-u-6">
										<div class="am-form-group">
											<label for="offer_id" style="text-align: left" class="am-u-sm-3 am-form-label">Offer id:</label>
											<div class="am-u-sm-6 am-u-end">
												<input id="offer_id" type="text" class="am-form-field" name="offer_id" value="<?php echo $offer_id?>" placeholder="offer's id">
											</div>
										</div>
										<?php if(in_array($this->user['groupid'],$this->manager_group)){ ?>
											<div class="am-form-group">
												<label for="advertiser" style="text-align: left" class="am-u-sm-3 am-form-label">Advertiser:</label>
												<div class="am-u-sm-6 am-u-end">
													<select id="advertiser" name="advertiser" data-am-selected="{btnWidth: 300, btnSize: 'sm', btnStyle: 'secondary',maxHeight: 200,searchBox: 1}" id="adver" style="display: none;padding-top: 20px">
														<option value="">All Advertiser</option>
														<?php foreach($advertisers as $advertiser){ ?>
															<option <?php echo
															$advertiser['select'];?> value="<?php echo $advertiser['id'];?>"><?php echo
																$advertiser['company'];?></option>
														<?php } ?>
													</select>
												</div>
											</div>
										<?php } ?>
										<div class="am-form-group">
											<label for="advertiser" style="text-align: left" class="am-u-sm-3 am-form-label">Countries:</label>
											<div class="am-u-sm-6 am-u-end">
												<input type="text" id="countries" style="width: 50%" value="<?php echo $countries_select;?>" name="countries" class="am-form-field" />
											</div>
										</div>
									</div>
									<div class="am-u-sm-6 am-u-6">
										<div class="am-form-group">
											<label for="name" style="text-align: left" class="am-u-sm-3 am-form-label">offer's name</label>
											<div class="am-u-sm-6 am-u-end">
												<input type="text" class="am-form-field" name="name" value="<?php echo $name?>" placeholder="offer's name">
											</div>
										</div>
										<div class="am-form-group">
											<label for="name" style="text-align: left" class="am-u-sm-3 am-form-label">Offer's Status</label>
											<div class="am-u-sm-6 am-u-end">
												<select id="status" name="status" data-am-selected="{btnWidth: 300, btnSize: 'sm', btnStyle: 'secondary',maxHeight: 200}" id="adver" style="display: none;padding-top: 20px">
													<?php if($status == '1'){
														echo '  <option value="1" selected>Active</option>
									<option value="0">Pending</option>
									<option value="">All</option>';
													}else if($status == '0'){
														echo '<option value="1">Active</option>';
														echo '  <option value="0" selected>Pending</option>
									<option value="">All</option>';
													}else{
														echo ' <option value="1">Active</option>
									<option value="0">Pending</option>
									<option value="" selected>All</option>';
													}?>
												</select>
											</div>
										</div>
										<div>
											<label for="advertiser" style="text-align: left" class="am-u-sm-3 am-form-label">Types</label>
											<div class="am-u-sm-6 am-u-end">
												<select id="types" name="types[]" multiple data-am-selected="{btnWidth: 300, btnSize: 'sm', btnStyle: 'secondary',maxHeight: 200,searchBox: 1}" id="adver" style="display: none;padding-top: 20px">
													<?php foreach($types as $type){ ?>
														<option <?php echo
														$type['select'];?> value="<?php echo $type['id'];?>"><?php echo
															$type['type_name_en'];?></option>
													<?php } ?>
												</select>
											</div>
										</div>
									</div>
								</div>
								<br>
								<br>
								<br>
								<div style="float: left">
									<button class="am-btn am-btn-primary am-btn-sm" type="button" onclick="$('#findAdver').submit();">search</button>
								</div>
							</form>
							<!--				<span class="am-input-group-btn" style="padding-left: 10%">-->
							<!--					<a class="am-btn am-btn-default" type="button" href="--><?php //echo $this->createUrl('offer/toexcel');?><!--">Download As Excel</a>-->
							<!--				</span>-->
						</div>
					</div>
				</div>
				<table class="table table-striped table-bordered table-hover dataTables-example">
					<thead>
					<tr>
						<th ></th>
						<th >ID</th>
						<th >Offer</th>
						<th >Status</th>
						<?php if(in_array($this->user['groupid'],array('1','2','6'))){?>
							<th >Advertiser</th>
						<?php } ?>
						<th >Preview</th>
						<th >Country</th>
						<th >Payout</th>
						<?php if(in_array($this->user['groupid'],$this->manager_group)){?>
							<th >Revenue</th>
						<?php } ?>
						<th >Clicks</th>
						<th >Conversions</th>
						<?php if(in_array($this->user['groupid'],array('1','2'))){ ?>
							<th >Conversion Cut</th>
							<th >Total Revenue Truth</th>
							<th >Total Revenue</th>
							<th >Total Payout Truth</th>
						<?php } ?>
						<th >Total Cost</th>
						<th >Types</th>
						<th >Expiration Date</th>
					</tr>
					</thead>
					<tbody>
					<?php
					if(!empty($offers)){
						foreach ($offers as $offer){
							$offer = Common::instantPayout($offer,$this->user['userid']); ?>
							<tr class="gradeX">
								<td><?php if($offer['recommend'] == 1){
										if(in_array($this->user['groupid'],$this->manager_group)){
											echo "<a style='display: none' onclick='closeTop({$offer['id']},0);' id='top_close_{$offer['id']}' class='am-badge am-badge-secondary am-radius'>Add</a>";
											echo "<a id='top_open_{$offer['id']}' onclick='closeTop({$offer['id']},1);' class='am-badge am-badge-danger am-radius am-active'>Top</a>";
										}else{
											echo "<a id='top_open' class='am-badge am-badge-danger am-radius am-active'>Top</a>";
										}
									}else{
										if(in_array($this->user['groupid'],$this->manager_group)){
											echo "<a onclick='closeTop({$offer['id']},0);' id='top_close_{$offer['id']}' class='am-badge am-badge-secondary am-radius'>Add</a>";
											echo "<a id='top_open_{$offer['id']}' style='display: none' onclick='closeTop({$offer['id']},1);' class='am-badge am-badge-danger am-radius am-active'>Top</a>";
										}
									}?></td>
								<td><?php echo $offer['id']?></td>
								<td><a href="<?php echo $this->createUrl('offer/offerdetail',array('offer_id'=>$offer['id']))?>"><?php echo $offer['name']?></a></td>
								<td><?php
									if($offer['status'] == 0){
										echo 'Pending';
									}elseif ($offer['status'] == 1){
										echo 'Active';
									}elseif ($offer['status'] == 2){
										echo 'Deleted';
									} ?>
								</td>
								<?php if(!in_array($this->user['groupid'],array('5','3'))){?>
									<td><?php echo $offer['adv']['title'];?></td>
								<?php } ?>
								<?php if(!empty($offer['preview_url'])){?>
									<td><a href="<?php echo $offer['preview_url'];?>" target="">Preview</a></td>
								<?php }else{ ?>
									<td>no preview</td>
								<?php } ?>
								<td><?php if($offer['geo_targeting']){
										echo (substr($offer['geo_targeting'],0,10) . '...');
									}
									?></td>
								<td><?php echo $offer['payout'];?>$</td>
								<?php if(in_array($this->user['groupid'],$this->manager_group)){?>
									<td><?php echo $offer['revenue'];?>$</td>
								<?php }?>
								<td><?php echo isset($click_arr[$offer['id']]) ? $click_arr[$offer['id']] : 0; ?></td>
								<td><?php echo isset($income_cut_arr[$offer['id']]) ? $income_cut_arr[$offer['id']]['income_num'] : 0;?></td>
								<?php if(in_array($this->user['groupid'],array(ADMIN_GROUP_ID,MANAGER_GROUP_ID))){?>
									<td><?php echo isset($income_arr[$offer['id']]) ? $income_arr[$offer['id']]['income_num'] : 0;?></td>
									<td><?php echo isset($income_arr[$offer['id']]) ? $income_arr[$offer['id']]['total_revenue'] : 0;?></td>
									<td><?php echo isset($income_cut_arr[$offer['id']]) ? $income_cut_arr[$offer['id']]['total_revenue'] : 0;?></td>
									<td><?php echo isset($income_arr[$offer['id']]) ? $income_arr[$offer['id']]['total_payout'] : 0;?></td>
								<?php }?>
								<td><?php echo isset($income_cut_arr[$offer['id']]) ? $income_cut_arr[$offer['id']]['total_payout'] : 0;?></td>
								<td><?php echo $offer['type']?></td>
								<td><?php echo $offer['expiration_date'];?></td>
							</tr>
						<?php }
					}else{
						echo '<td>no data!</td>';
					} ?>
					</tbody>
				</table>
				<?php $this->widget('CLinkPager',array(
						'header'=>'',
						'firstPageLabel' => 'firstPage',
						'lastPageLabel' => 'endPage',
						'prevPageLabel' => '<<',
						'nextPageLabel' => '>>',
						'pages' => $pages,
						'maxButtonCount'=>8,
					)
				);?>
				<div class="hr-line-dashed"></div>
				<div class="form-group">
					<div class="col-sm-4 col-sm-offset-2">
						<button class="btn btn-primary" onclick="savecut()" type="button">Save</button>
					</div>
				</div>
			</div>
		</div>
	</div>
<script>
	 function closeTop(id,rec){
		 if(confirm('Are you sure to change this top of offer?')) {
		 $.ajax({
				 type: "GET",
				 url: "<?php echo $this->createUrl('offer/changerecommend');?>",
				 data: {id,rec},
				 dataType: "json",
				 success: function (data) {
					 alert(data.msg);
					 var element_open_id = '#top_open_'+id;
					 var element_close_id = '#top_close_'+id;
					 if(rec == 1){
						 $(element_open_id).hide();
						 $(element_close_id).show();
					 }else{
						 $(element_close_id).hide();
						 $(element_open_id).show();
					 }
				 }
			 });
		 }
	}

	function log( message ) {
		$( "<div>" ).text( message ).prependTo( "#log" );
		$( "#log" ).scrollTop( 0 );
	}
	$( "#countries" ).autocomplete({
		source: function( request, response ) {
			$.ajax({
				url: "<?php echo $this->createUrl('offer/getcountriesinfo');?>",
				dataType: "json",
				data: {
					featureClass: "P",
					style: "full",
					maxRows: 12,
					name_startsWith: request.term,
				},
				success: function( data ) {
					response( $.map( data, function( item ) {
						return {
							label: item.label,
							value: item.label
						}
					}));
				}
			});
		},
		minLength: 1,
		select: function( event, ui ) {
			log( ui.item ?
			"Selected: " + ui.item.label :
			"Nothing selected, input was " + this.value);
		},
		open: function() {
			$( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
		},
		close: function() {
			$( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
		}
	});

</script>