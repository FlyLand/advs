<?php
include_once dirname(dirname(__FILE__)) . '/sidebar.php';
?>
<div class="admin-content">
        <div class="am-cf am-padding">
            <div class="am-fl am-cf">
                <strong class="am-text-primary am-text-lg">Add: Imitation Offer Click</strong>
            </div>
        </div>
        <div class="am-tabs am-margin" data-am-tabs="">
        <div class="am-panel am-panel-default am-form">
        <div class="am-panel-hd am-cf" data-am-collapse="{target: '#collapse-panel-1'}"><b>IP Address</b><span class="am-icon-chevron-down am-fr"></span></div>
        <div class="am-panel-bd am-collapse am-in" id="collapse-panel-1">
            <form name="add_form" id="add_form" action="<?php echo $this->createUrl('offer/mnclickadd');?>" method="post">
            <div class="am-g am-margin-top">
                <div class="am-u-sm-4 am-u-md-2 am-text-right">OfferID:</div>
                <div class="am-u-sm-8 am-u-md-4 am-u-end col-end"><input type="text" class="am-input-sm" name="offerid" id="offerid" /></div>
            </div>
            <div class="am-g am-margin-top">
                <div class="am-u-sm-4 am-u-md-2 am-text-right">Affiliate ID:</div>
                <div class="am-u-sm-8 am-u-md-4 am-u-end col-end"><input type="text" class="am-input-sm" name="affid" id="affid" /></div>
                <div class="am-hide-sm-only am-u-md-6">下游ID, 25为joydream.</div>
            </div>
            <div class="am-g am-margin-top">
        		<div class="am-u-sm-4 am-u-md-2 am-text-right">Start Date:</div>
        		<div class="am-u-sm-8 am-u-md-4 am-u-end col-end"><input type="text" class="am-form-field" id="start_date" name="start_date" placeholder="Start Date" data-am-datepicker /></div>
    			<div class="am-hide-sm-only am-u-md-6">Offer will start at 00:00 am of selected date.</div>
    		</div>
    		<div class="am-g am-margin-top">
        		<div class="am-u-sm-4 am-u-md-2 am-text-right">Expiration Date:</div>
        		<div class="am-u-sm-8 am-u-md-4 am-u-end col-end"><input type="text" class="am-form-field" id="end_date" name="end_date" placeholder="End Date" data-am-datepicker /></div>
    			<div class="am-hide-sm-only am-u-md-6">Offer will expire at 11:59 pm of selected date.</div>
    		</div>
            <div class="am-g am-margin-top">
                <div class="am-u-sm-4 am-u-md-2 am-text-right">Nation:</div>
                <div class="am-u-sm-8 am-u-md-4 am-u-end col-end"><input type="text" class="am-input-sm" name="nation" id="nation" /></div>
                <div class="am-hide-sm-only am-u-md-6">国家，不填为不限制国家（国家名称，请不要填国家代号，多个国家以英文逗号分隔）.</div>
            </div>
            <div class="am-g am-margin-top">
                <div class="am-u-sm-4 am-u-md-2 am-text-right">max total:</div>
                <div class="am-u-sm-8 am-u-md-4 am-u-end col-end"><input type="text" class="am-input-sm" name="max_total" id="max_total" /></div>
                <div class="am-hide-sm-only am-u-md-6">需要模拟的总点击数.</div>
            </div>
            <div class="am-g am-margin-top">
                <div class="am-u-sm-4 am-u-md-2 am-text-right">hour total:</div>
                <div class="am-u-sm-8 am-u-md-4 am-u-end col-end"><input type="text" class="am-input-sm" name="hour_total" id="hour_total" /></div>
                <div class="am-hide-sm-only am-u-md-6">每小时模拟的点击数.</div>
            </div>
            
            <div class="am-g am-margin-top">
                <div class="am-u-sm-4 am-u-md-2 am-text-right">Status:</div>
                <div class="am-u-sm-8 am-u-md-4 am-u-end col-end">
                    <select name="status" id="status" data-am-selected="{btnWidth: 300, btnSize: 'sm', btnStyle: 'secondary'}" style="display: none;">
                        <option value="1">start</option>
                        <option value="2" selected="selected">pause</option>
                    </select>
                </div>
            </div>
            <div class="am-margin">
            	<button type="button" onclick="clicksubmit()" id="btn_submit" class="am-btn am-btn-primary am-btn-xs">save</button>
            </div>
        </form>
        </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var clicksubmit = function(){
        var isTrue = true;
        if(!isTrue){
            alert("do not submit repeat!");
            return false;
        }

        var offerid = $("#offerid").val();
        var affid = $("#affid").val();
        var start_date = $("#start_date").val();
        var end_date = $("#end_date").val();
        var nation = $("#nation").val();
        var max_total = $("#max_total").val();
        var hour_total = $("#hour_total").val();
        var status = $("#status").val();

        if('' == offerid){
            alert("offerid could't be null！");
            return false;
        }
        if('' == affid){
            alert("affid could't be null！");
            return false;
        }
        if('' == start_date){
            alert("start date could't be null！");
            return false;
        }
        if('' == end_date){
            alert("end date could't be null！");
            return false;
        }
        if('' == max_total){
            alert("max total could't be null！");
            return false;
        }
        if('' == hour_total){
            alert("hour total could't be null！");
            return false;
        }
        document.getElementById("add_form").submit();
    }
</script>