<?php
include_once dirname(dirname(__FILE__)).'/sidebar.php';
?>
<div class="admin-content">
    <div class="am-cf am-padding">
        <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">Affiliate Offer Show</strong> </div>
    </div>
    <div class="am-tabs am-margin" data-am-tabs>
        <ul class="am-tabs-nav am-nav am-nav-tabs">
            <li class="am-active"><a href="#tab1">Info</a></li>
            <li style="float:right;margin-right:0px;"><div style="padding-left:20px;margin-bottom:5px;" onclick="javascript:location.href='<?php echo $this->createUrl('offer/affoffershow');?>';"><input type="button" value="return" class="am-btn am-btn-primary am-btn-xs" /></div></li>
        </ul>
        <div class="am-tabs-bd">
            <form action="<?php echo $this->createUrl('offer/offershow',array('type'=>'add'));?>" method="post" id="show_form">
                <div class="am-tab-panel am-fade am-in am-active" id="tab1">
                    <div class="am-g am-margin-top">
                        <?php if(!empty($affiliates)){ ?>
                            <div class="am-u-sm-4 am-u-md-2 am-text-right">Affiliates:</div>
                            <div class="am-u-sm-8 am-u-md-4">
                                <select multiple data-am-selected="{btnWidth: 300, btnSize: 'sm', btnStyle: 'secondary',maxHeight: 200,searchBox: 1}" name="affiliates[]" id="affiliates">
                                    <?php foreach($affiliates as $affiliate){ ?>
                                        <option value="<?php echo $affiliate['id'];?>"><?php echo $affiliate['id']. $affiliate['company'];?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        <?php }else{ ?>
                            <div class="am-g am-margin-top">
                                <div class="am-u-sm-4 am-u-md-2 am-text-right">Affiliate:</div>
                                <div class="am-u-sm-8 am-u-md-4 am-u-end">
                                    <input type="text" name="affiliates" id='affiliates' size="30" maxlength="20" value='<?php echo $affiliate['company'];?>' readonly="readonly" ><span class='highlight'></span>
                                </div>
                            </div>
                        <?php }?>
                        <div class="am-hide-sm-only am-u-md-6">&nbsp;&nbsp;</div>
                    </div>
                    <div class="am-g am-margin-top">
                        <div class="am-u-sm-4 am-u-md-2 am-text-right">Offers:</div>
                        <div class="am-u-sm-8 am-u-md-4">
                            <select multiple data-am-selected="{btnWidth: 300, btnSize: 'sm', btnStyle: 'secondary',maxHeight: 200,searchBox: 1}" name="offerids[]" id="offerids">
                                <?php foreach($offers as $offer){ ?>
                                    <option value="<?php echo $offer['id'];?>"><?php echo $offer['id'] . $offer['name'];?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="am-hide-sm-only am-u-md-6">&nbsp;&nbsp;</div>
                    </div>
                    <div class="am-g am-margin-top">
                        <div class="am-u-sm-4 am-u-md-2 am-text-right">Advertisers:</div>
                        <div class="am-u-sm-8 am-u-md-4">
                            <select multiple data-am-selected="{btnWidth: 300, btnSize: 'sm', btnStyle: 'secondary',maxHeight: 200,searchBox: 1}" name="advids[]" id="advids">
                                <?php foreach($advertisers as $advertiser){ ?>
                                    <option value="<?php echo $advertiser['id'];?>"><?php echo $advertiser['id'] . $advertiser['company'];?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="am-hide-sm-only am-u-md-6">&nbsp;&nbsp;</div>
                    </div>
                </div>
                <div class="am-g am-margin-top" style="padding-top: 40%">
                    <input type="button" value="submit" onclick="submit_show()" class="am-btn am-btn-primary am-btn-xs" />
        </div>
        </form>
    </div>
</div>
<?php
if( !empty($msg) ){
    echo '<script>alert("', $msg , '");';
    echo '</script>';
}
?>
<script>
    function submit_show(){
        if(confirm('Are you sure to save?') ){
            $('#show_form').submit();
//            window.location.href = "<?php //echo $this->createUrl('offer/offercut');?>//&affs="+str+"&cut_num="+cutnum+"&offerid="+offerid+"&payout="+payout;
        }
    }
    function save(affid){
        if(confirm('Are you sure to update?') ){
            var cutnum = document.getElementById("cut_num").value;
            var offerid = document.getElementById("hidden").value;
            var payout = document.getElementById("payout").value;
            window.location.href = "<?php echo $this->createUrl('offer/editoffercut',array('type'=>'update'));?>&affid="+affid+"&cut_num="+cutnum+"&offerid="+offerid+"&payout="+payout;
        }
    }
</script>
