<?php
include_once dirname(dirname(__FILE__)).'/sidebar.php';
?>
<div class="admin-content">
        <div class="am-cf am-padding">
            <div class="am-fl am-cf">
                <strong class="am-text-primary am-text-lg">Offer Details: <?php echo $offers['name']?> - Offer </strong>
            </div>
        </div>
        <div class="am-tabs am-margin" data-am-tabs="">
        <div class="am-panel am-panel-default am-form">
        <div class="am-panel-hd am-cf" data-am-collapse="{target: '#collapse-panel-1'}"><b>detail</b><span class="am-icon-chevron-down am-fr"></span></div>
        <div class="am-panel-bd am-collapse am-in" id="collapse-panel-1">
            <form  id="edit_form" action="<?php echo $this->createUrl('offer/updatedetail',array('id'=>$offers['id']))?>" method="post">
            <div class="am-g am-margin-top">
                <div class="am-u-sm-4 am-u-md-2 am-text-right">Advertiser:</div>
                <div class="am-u-sm-8 am-u-md-10">
                    <select name="advertiser" id="advertiser" data-am-selected="{btnWidth: 300, btnSize: 'sm', btnStyle: 'secondary'}" style="display: none;">
                        <?php foreach ($advertises as $ad){
                            echo '<option  '.$ad['select'].'  value="'.$ad['id'].'">'.$ad['company'].'</option>';
                        }?>
                    </select>
                </div>
            </div>
            <div class="am-g am-margin-top">
                <div class="am-u-sm-4 am-u-md-2 am-text-right">
                    Name:
                </div>
                <div class="am-u-sm-8 am-u-md-4">
                    <input type="text" class="am-input-sm" name="name" id="name" value="<?php echo $offers['name']?>">
                </div>
                <div class="am-hide-sm-only am-u-md-6"></div>
            </div>

            <div class="am-g am-margin-top">
                <div class="am-u-sm-4 am-u-md-2 am-text-right">
                    Description:
                </div>
                <div class="am-u-sm-12 am-u-md-8">
                    <textarea id="description" name="description" rows="5"  placeholder="description" ><?php echo $offers['description']?></textarea>
                </div>
                <div class="am-hide-sm-only am-u-md-6"></div>
            </div>
            <!-- 			示例url -->
            <div class="am-g am-margin-top">
                <div class="am-u-sm-4 am-u-md-2 am-text-right">
                    Preview URL:
                </div>
                <div class="am-u-sm-8 am-u-md-4">
                    <input type="text" class="am-input-sm" name="preview_url" id="preview_url" value="<?php echo $offers['preview_url']?>">
                </div>
                <div class="am-hide-sm-only am-u-md-6">Link to landing page with no geo targeting so Affiliates can see landing page example.</div>
            </div>
            <!--     		  offer URL  		   -->
            <div class="am-g am-margin-top">
                <div class="am-u-sm-4 am-u-md-2 am-text-right">
                    Default Offer URL:
                </div>
                <div class="am-u-sm-8 am-u-md-4">
                    <input type="text" class="am-input-sm" name="offer_url" id="offer_url" value="<?php echo $offers['offer_url']?>">
                </div>
                <div class="am-hide-sm-only am-u-md-6">The Offer URL where traffic will be directed to. You must specify at least the Default Offer URL. The optional variables below can be used in Offer URLs.</div>
            </div>
            <!--     		 协议 -->
            <div class="am-g am-margin-top">
                <div class="am-u-sm-4 am-u-md-2 am-text-right">Conversion Tracking:</div>
                <div class="am-u-sm-8 am-u-md-10">
                    <?php
                    $checked = '';
                    if($offers['protocol'] == 'search'){
                        $checked = 'selected';
                    }
                    ?>
                    <select name="conversion_tracking"  id="OfferProtocol" data-am-selected="{btnWidth: 300, btnSize: 'sm', btnStyle: 'secondary'}" style="display: none;">
                        <option value="none">Normal</option>
                        <option <?php echo $checked;?> value="search">Search</option>
                    </select>
                    <p>iFrame and Image conversion pixels use client-based cookie or cookieless tracking while Server Postback uses server-based cookieless tracking URLs. </p>
                </div>
            </div>
            <div class="am-g am-margin-top">
                <div class="am-u-sm-4 am-u-md-2 am-text-right">Status:</div>
                <div class="am-u-sm-8 am-u-md-10">
                    <select name="status" id="status" data-am-selected="{btnWidth: 300, btnSize: 'sm', btnStyle: 'secondary'}" style="display: none;">
                        <?php if($offers['status'] == '2'){
                            echo '<option value="2" selected>Deleted</option>';
                            echo '  <option value="1">Active</option>
                                    <option value="0">Pending</option>';
                        }else if($offers['status'] == '1'){
                            echo '<option value="2">Deleted</option>';
                            echo '  <option value="1" selected>Active</option>
                                    <option value="0">Pending</option>';
                        }else{
                            echo ' <option value="2">Deleted</option>
                                    <option value="1">Active</option>
                                    <option value="0" selected="selected">Pending</option>';
                        }?>
                    </select>
                    <p>Active allows all Affiliates to view the offer. Pending allows the offer to be tested as if it were active except Affiliates won't be able to see it. Conversion pixels can be tested with the pending status. Paused removes the offer for Affiliates to view and redirects traffic to the redirect offer if set. Deleted causes all jump links to return dead.</p>
                </div>
            </div>
            <!-- 			过期时间 -->
            <div class="am-g am-margin-top">
                <div class="am-u-sm-4 am-u-md-2 am-text-right">
                    Expiration Date:
                </div>
                <div class="am-u-sm-8 am-u-md-4">
                    <input value="<?php echo $offers['expiration_date'];?>" type="text" id= "expirationDate" class="am-form-field" name= "expiration_date"placeholder="Expiration Date" data-am-datepicker />
                </div>
                <div class="am-hide-sm-only am-u-md-6">Offer will expire at 11:59 pm of selected date.</div>
            </div>
            <!--     		offer分类 -->
            <div class="am-g am-margin-top">
                <div class="am-u-sm-4 am-u-md-2 am-text-right">Categories:</div>
                <div class="am-u-sm-8 am-u-md-10">
                    <select id="types" name="types" data-am-selected="{btnWidth: 300, btnSize: 'sm', btnStyle: 'secondary',searchBox: 1,maxHeight: 200}" style="display: none;">
                        <option value="0">Others</option>
                        <?php foreach($types as $type){ ?>
                            <option <?php echo $type['selected'];?> value="<?php echo $type['id']?>"><?php echo $type['type_name_en'];?></option>
                        <?php } ?>
                    </select>
                    <p>Categorize offer for Affiliates to search and group by.</p>
                </div>
            </div>
            <div class="am-g am-margin-top">
                <div class="am-u-sm-4 am-u-md-2 am-text-right">
                    Reference ID:
                </div>
                <div class="am-u-sm-8 am-u-md-4">
                    <input type="number" class="am-input-sm" name="reference_id" id="reference_id" value="<?php echo $offers['ref_id']?>">
                </div>
                <div class="am-hide-sm-only am-u-md-6"> Assign a reference ID to this offer and pass this value into Offer URLs.</div>
            </div>
            <div class="am-g am-margin-top">
                <div class="am-u-sm-4 am-u-md-2 am-text-right">
                    Note:
                </div>
                <div class="am-u-sm-8 am-u-md-4">
                    <textarea class="" name="note" id="note" rows="5" id="note" placeholder="note"><?php echo $offers['note'] ?></textarea>
                </div>
                <div class="am-hide-sm-only am-u-md-6">The contents of this note will not be displayed to Affiliates</div>
            </div>
            <br>
            <div class="am-g am-margin-top">
                <div class="am-u-sm-4 am-u-md-2 am-text-right">
                    Country:
                </div>
                <div class="am-u-sm-8 am-u-md-4 am-u-end">
                    <input class="" name="country" id="country"  id="country" value="<?php echo $offers['geo_targeting'] ?>" placeholder="country">
                </div>
            </div>

            <div class="am-margin">
                <button type="button" onclick="editsubmit()" id="btn_submit" class="am-btn am-btn-primary am-btn-xs">save</button>
            </div>
        </form>
        </div>
        </div>
    </div>
</div>
<script>
    var editsubmit = function(){
        var isTrue = true;
        if(!isTrue){
            alert("do not submit repeat!");
            return;
        }

        var advertiser = $("#advertiser").val();
        var name = $("#name").val();
        var offer_url = $("#offer_url").val();
        var protocol = $("#OfferProtocol").val();
        var offer_category = $("#categories").val();
        var ref_id = $("#reference_id").val();

        if(!advertiser){
            alert("advertiser could't be null！");
            return ;
        }

        if(!name){
            alert("offer name could't be null！");
            return ;
        }

        if(!protocol){
            alert("offer protocol could't be null！");
            return ;
        }

        if(!ref_id){
            alert("offer reference_id date could't be null！");
            return;
        }
        document.getElementById("edit_form").submit();
    }
</script>