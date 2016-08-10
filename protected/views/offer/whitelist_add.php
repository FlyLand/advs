<?php
include_once dirname(dirname(__FILE__)).'/sidebar.php';
?>
<div class="admin-content">
        <div class="am-cf am-padding">
            <div class="am-fl am-cf">
                <strong class="am-text-primary am-text-lg">Add: Offer Whitelist</strong>
            </div>
        </div>
        <div class="am-tabs am-margin" data-am-tabs="">
        <div class="am-panel am-panel-default am-form">
        <div class="am-panel-hd am-cf" data-am-collapse="{target: '#collapse-panel-1'}"><b>IP Address</b><span class="am-icon-chevron-down am-fr"></span></div>
        <div class="am-panel-bd am-collapse am-in" id="collapse-panel-1">
            <form name="edit_form" id="edit_form" action="<?php echo $this->createUrl('offer/whitelistadd', array('offerid'=>$offerid));?>" method="post">
            <div class="am-g am-margin-top">
                <div class="am-u-sm-4 am-u-md-2 am-text-right">OfferID:</div>
                <div class="am-u-sm-8 am-u-md-10"><?php echo isset($offerid) ? $offerid : '';?></div>
            </div>
            
            <div class="am-g am-margin-top">
                <div class="am-u-sm-4 am-u-md-2 am-text-right">Type:</div>
                <div class="am-u-sm-8 am-u-md-10">
                    <select name="content_type" id="content_type" data-am-selected="{btnWidth: 300, btnSize: 'sm', btnStyle: 'secondary'}" style="display: none;">
                        <option value="1">IP Address</option>
                    </select>
                </div>
            </div>
            
            <div class="am-g am-margin-top">
                <div class="am-u-sm-4 am-u-md-2 am-text-right">IP Address:</div>
                <div class="am-u-sm-8 am-u-md-4 am-u-end col-end">
        			<input type="text" class="am-input-sm" name="content" id="content" />
        		</div>
            </div>
            
            <div class="am-margin">
            	<input type="hidden" name="offerid" id="offerid" value="<?php echo isset($offerid) ? $offerid : '';?>" />
            	<button type="button" onclick="ipsubmit()" id="btn_submit" class="am-btn am-btn-primary am-btn-xs">save</button>
            </div>
        </form>
        </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var ipsubmit = function(){
        var isTrue = true;
        if(!isTrue){
            alert("do not submit repeat!");
            return;
        }

        var offerid = $("#offerid").val();
        var content_type = $("#content_type").val();
        var content = $("#content").val();

        if('' == content){
            alert("IP Address could't be null！");
            return ;
        }
        document.getElementById("edit_form").submit();
    }
</script>