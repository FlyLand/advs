<?php
include_once dirname(dirname(__FILE__)) . '/sidebar.php';
?>
<div class="admin-content">
    <div class="am-cf am-padding">
        <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">Cut Num Manager</strong> </div>
    </div>
    <div class="am-tabs am-margin" data-am-tabs>
        <ul class="am-tabs-nav am-nav am-nav-tabs">
            <li class="am-active"><a href="#tab1">Info</a></li>
            <li style="float:right;margin-right:0px;"><div style="padding-left:20px;margin-bottom:5px;" onclick="javascript:location.href='<?php echo $this->createUrl('offer/offerdetail',array('offer_id'=>$offerid));?>';"><input type="button" value="return" class="am-btn am-btn-primary am-btn-xs" /></div></li>
        </ul>
        <div class="am-tabs-bd">
            <form action="" method="post">
                <div class="am-tab-panel am-fade am-in am-active" id="tab1">
                    <div class="am-g am-margin-top">
                        <?php if(isset($affiliates)){ ?>
                        <div class="am-u-sm-4 am-u-md-2 am-text-right">Please Select The Affiliates:</div>
                        <div class="am-u-sm-8 am-u-md-4">
                            <select multiple data-am-selected="{maxHeight: 200}" name="affiliates" id="affs">
                                    <?php foreach($affiliates as $affiliate){ ?>
                                        <option value="<?php echo $affiliate['id'];?>"><?php echo $affiliate['company'];?></option>
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
                        <div class="am-u-sm-4 am-u-md-2 am-text-right">Cut Num:</div>
                        <div class="am-u-sm-8 am-u-md-4">
                            <?php if(isset($cut)){ ?>
                                <input type="number" name="cut_num" id='cut_num' size="30" maxlength="20" value='<?php echo $cut['cut_num'];?>'>%<span class='highlight'></span>
                            <?php }else{ ?>
                                <input type="number" name="cut_num" id='cut_num' size="30" maxlength="20" value=''>%<span class='highlight'></span>
                            <?php }?>
                        </div>
                        <div class="am-hide-sm-only am-u-md-6">&nbsp;&nbsp;</div>
                    </div>
                    <div class="am-g am-margin-top">
                        <div class="am-u-sm-4 am-u-md-2 am-text-right">Payout:</div>
                        <div class="am-u-sm-8 am-u-md-4">
                            <?php if(isset($cut)){ ?>
                                <input type="number" name="payout" id='payout' size="30" maxlength="20" value='<?php echo $cut['payout'];?>'>$<span class='highlight'></span>
                            <?php }else{ ?>
                                <input type="number" name="payout" id='payout' size="30" maxlength="20" value=''>%<span class='highlight'></span>
                            <?php }?>
                        </div>
                        <div class="am-hide-sm-only am-u-md-6">&nbsp;&nbsp;</div>
                    </div>
                </div>
                <div class="am-g am-margin-top">
                    <?php if(isset($affiliates)){ ?>
                        <input type="button" value="submit" onclick="fun()" class="am-btn am-btn-primary am-btn-xs" />
                    <?php }else{ ?>
                        <input type="button" value="submit" onclick="save(<?php echo $affiliate['id'];?>)" class="am-btn am-btn-primary am-btn-xs" />
                    <?php } ?>
                    <input type="hidden" value="<?php echo $offerid;?>" id="hidden">
                </div>
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
    function fun(){
        if(confirm('Are you sure to save?') ){
            var select = document.getElementById("affs");
            var str = [];
            for(i=0;i<select.length;i++){
                if(select.options[i].selected){
                    str.push(select[i].value);
                }
            }
            var cutnum = document.getElementById("cut_num").value;
            var offerid = document.getElementById("hidden").value;
            var payout = document.getElementById("payout").value;
            window.location.href = "<?php echo $this->createUrl('offer/offercut');?>&affs="+str+"&cut_num="+cutnum+"&offerid="+offerid+"&payout="+payout;
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