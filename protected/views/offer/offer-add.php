<div class="row">
	<div class="col-sm-12">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<h5>Affiliate Edit</h5>
				<div class="ibox-tools">
					<a class="collapse-link">
						<i class="fa fa-chevron-up"></i>
					</a>
					<a class="dropdown-toggle" data-toggle="dropdown" href="form_basic.html#">
						<i class="fa fa-wrench"></i>
					</a>
					<ul class="dropdown-menu dropdown-user">
						<li><a href="form_basic.html#">选项1</a>
						</li>
						<li><a href="form_basic.html#">选项2</a>
						</li>
					</ul>
					<a class="close-link">
						<i class="fa fa-times"></i>
					</a>
				</div>
			</div>
			<div class="ibox-content">
				<form method="post" action="<?php echo $this->createUrl('advertiser/create');?>" class="form-horizontal">
					<div class="form-group">
						<label class="col-sm-2 control-label">Advertiser:</label>
						<div class="col-sm-6">
							<select id="advertiser" name="advertiser" class="form-control m-b">
								<?php foreach ($advertises as $ad){
									echo '<option value="'.$ad['id'].'">'.$ad['company'].'</option>';
								}?>
							</select>
						</div>
					</div>
					<div class="hr-line-dashed"></div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Name:</label>
						<div class="col-sm-6">
							<input type="text" class="form-control" name="name" id="name">
						</div>
					</div>
					<div class="hr-line-dashed"></div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Description:</label>
						<div class="col-sm-6">
							<textarea id="description" name="description" rows="5"  placeholder="description"></textarea>
						</div>
					</div>
					<div class="hr-line-dashed"></div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Preview Url:</label>
						<div class="col-sm-6">
							<input type="text" class="form-control" name="preview_url" id="preview_url">
						</div>
					</div>
					<div class="hr-line-dashed"></div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Default Offer Url:</label>
						<div class="col-sm-6">
							<input type="text" class="form-control" name="default_offer_url" id="offer_url">
						</div>
					</div>
					<div class="hr-line-dashed"></div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Status:</label>
						<div class="col-sm-6">
							<select name="status" id="status" class="form-control m-b">
								<option value="1" selected="selected">Active</option>
								<option value="0" >Pending</option>
								<option value="2">Deleted</option>
							</select>
						</div>
					</div>
					<div class="hr-line-dashed"></div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Category:</label>
						<div class="col-sm-6">
							<select id="types" class="form-control m-b">
								<option value="0">Others</option>
								<?php foreach($types as $type){ ?>
									<option value="<?php echo $type['id']?>"><?php echo $type['type_name_en']?></option>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="hr-line-dashed"></div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Revenue:</label>
						<div class="col-sm-6">
							<input type="text" name="revenue" id="revenue" class="form-control" value="" placeholder="%">
						</div>
					</div>
					<div class="hr-line-dashed"></div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Cost:</label>
						<div class="col-sm-6">
							<input type="text" name="cost_per_conversion" id="cost_per_conversion" class="form-control" checked=""placeholder="%">
						</div>
					</div>
					<div class="hr-line-dashed"></div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Country:</label>
						<div class="col-sm-6">
							<input type="text" name="geo_targeting" class="form-control" id="country" placeholder="">
						</div>
					</div>
					<div class="hr-line-dashed"></div>
					<div class="form-group">
						<div class="col-sm-4 col-sm-offset-2">
							<button class="btn btn-primary" onclick="addOffer()" type="button">Save</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
$("#caps").change(function(){
	var caps2 = $("#caps").val();
	if(caps2 == 1){ 
		$(".daily_con").removeClass('hide');
		$(".month_con").removeClass('hide');
		$(".daily_pay").removeClass('hide');
		$(".month_pay").removeClass('hide');
		$(".daily_re").removeClass('hide');
		$(".month_re").removeClass('hide'); 
	}
	if(caps2 == 0){ 
		$(".daily_con").addClass('hide');
		$(".month_con").addClass('hide');
		$(".daily_pay").addClass('hide');
		$(".month_pay").addClass('hide');
		$(".daily_re").addClass('hide');
		$(".month_re").addClass('hide'); 
	}
});

function addOffer(){
	var isTrue = true;
	if(!isTrue){
		alert("请勿重复提交");
		return;
		}
	var advertiser = $("#advertiser").val();
	var name = $("#name").val(); 
	var description = $("#description").val();
	var preview_url = $("#preview_url").val(); 
	var offer_url = $("#offer_url").val();
	var protocol = $("#OfferProtocol").val();
	var expirationDate = $("#expirationDate").val();
	var offer_category = $("#categories").val();
	var ref_id = $("#reference_id").val();
	var currency = $("#currency").val();
	var revenue_type = $("#revenue_type").val();
	var revenue = $("#revenue").val();
	var payout_type = $("#payout_type").val();
	var cost_per_conversion = $("#cost_per_conversion").val();
	var Private = $("#private").val();
	var require_approval = $("#require_approval").val();
	var terms = $("#terms").val();
	var seo = $("#seo").val();
	var email = $("#email").val();
	var suppression_list = $("#suppression_list").val();
	var redirect_offer_id = $("#redirect_offer_id").val();
	var session_impression_hours = $("#session_impression_hours").val();
	var session_hours = $("#session_hours").val();
	var enable_offer_whitelist = $("#enable_offer_whitelist").val();
	var note = $("#note").val();
	var status = $("#status").val(); 
	var caps = $("#caps").val(); 
	if(!advertiser){
		alert("advertiser can't be null！");
		return;
		}

	if(!name){
		alert("offer can't be null！");
		return;
		}
	if(!offer_url){
		alert("offer_url can't be null！");
		return;
		}
	if(!revenue){
		alert("revenue can't be null！");
		return;
		}
	if(caps == 1){//增加限制
		var daily_con = $("#daily_con").val(); 
		var month_con = $("#month_con").val(); 
		var daily_pay = $("#daily_pay").val(); 
		var month_pay = $("#month_pay").val(); 
		var daily_re = $("#daily_re").val(); 
		var month_re = $("#month_re").val();
		if(!daily_con || !month_con|| !daily_pay|| !month_pay|| !daily_re || !month_re ){
			alert("the caps can't be null！");
			return;
			}
		 $.ajax({    
			    url:'<?php echo $this->createUrl('offer/add');?>',// 跳转到 action    
			    data:{
				    //增加限制字段 
			    	daily_con : daily_con,     
			    	month_con : month_con,     
			    	daily_pay : daily_pay,     
			    	month_pay : month_pay,     
			    	daily_re : daily_re,     
			    	month_re : month_re,     
			    	advertiser : advertiser,
			    	name : name,
			    	description : description,
			    	preview_url : preview_url,
			    	offer_url : offer_url,
			    	protocol : protocol,
			    	expirationDate : expirationDate,
			    	offer_category : offer_category,
			    	ref_id : ref_id,
			    	currency : currency,
			    	revenue_type : revenue_type,
			    	revenue : revenue,
			    	payout_type : payout_type,
			    	cost_per_conversion : cost_per_conversion,
			    	Private : Private,
			    	require_approval : require_approval,
			    	terms : terms,
			    	seo : seo,
			    	email : email,
			    	caps : caps,
			    	suppression_list : suppression_list,
			    	redirect_offer_id : redirect_offer_id,
			    	session_impression_hours : session_impression_hours,
			    	session_hours : session_hours,
			    	enable_offer_whitelist : enable_offer_whitelist,
			    	note : note,
			    	status : status 
			    },    
			    type:'post',    
			    dataType:'json', 
			    complete:function(){
				    isTrue = false;
				    },   
			    success:function(data) { 
				    if(data.error_code == 1){
					    alert(data.msg);
					    window.location.href ="<?php echo $this->createUrl('offer/list');?>";
					    }else{
						alert(data.msg);
//						window.location.reload();
							}
				     
			     },
			     error:function(data){
			    	 console.log(data);
				     }   
			}); 
		}else{
			 $.ajax({    
				    url:'<?php echo $this->createUrl('offer/add');?>',// 跳转到 action    
				    data:{   
				    	advertiser : advertiser,
				    	name : name,
				    	description : description,
				    	preview_url : preview_url,
				    	offer_url : offer_url,
				    	protocol : protocol,
				    	expirationDate : expirationDate,
				    	offer_category : offer_category,
				    	ref_id : ref_id,
				    	currency : currency,
				    	revenue_type : revenue_type,
				    	revenue : revenue,
				    	payout_type : payout_type,
				    	cost_per_conversion : cost_per_conversion,
				    	Private : Private,
				    	require_approval : require_approval,
				    	terms : terms,
				    	seo : seo,
				    	email : email,
				    	caps : 0,
				    	suppression_list : suppression_list,
				    	redirect_offer_id : redirect_offer_id,
				    	session_impression_hours : session_impression_hours,
				    	session_hours : session_hours,
				    	enable_offer_whitelist : enable_offer_whitelist,
				    	note : note,
				    	status : status 
				    },    
				    type:'post',    
				    dataType:'json',
				    complete:function(){
					    isTrue = false;
					    },       
				    success:function(data) { 
					    if(data.error_code == 1){
						    alert(data.msg);
						    window.location.href ="<?php echo $this->createUrl('offer/list');?>";
						    }else{
							alert(data.msg);
//							window.location.reload();
								}
					     
				     },
				     error:function(data){
				    	 console.log(data);
					     }   
				}); 
			} 
}  
</script>