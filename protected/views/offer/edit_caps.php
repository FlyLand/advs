<?php require_once dirname(dirname(__FILE__)) . '/sidebar.php';?>
<style>
    .am-form div{
        padding-top: 10px;
    }
    .hide{
        display: none;
    }
</style>
<!-- content start -->
<div class="admin-content">
    <div class="am-cf am-padding">
        <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">Offer Payout: <?php echo $offer['name']?> - Offer </strong></div>
    </div>
        <form class="am-form" action="<?php echo $this->createUrl('offer/editcaps',array('cap_id'=>$caps['id'],'id'=>$offer['id'],'type'=>'update'));?>" method="post" id="doForm">
            <h2>Settings:</h2><p style="font-size: small">Control how affiliates are able to access your offer (optional)</p>
            <hr data-am-widget="divider" style="" class="am-divider am-divider-default"/>
            <div class="am-u-sm-12">
                <div class="am-u-sm-4 am-u-md-2 am-text-right">Caps:</div>
                <div  class="am-u-sm-3 am-u-end">
                    <select data-am-selected="{btnWidth: 300, btnSize: 'sm', btnStyle: 'secondary'}" id="offerCustomCurrency" name="custom_currency">
                        <option value="0">Enabled</option>
                        <option value="1">Disabled</option>
                    </select>
                    <div class="am-hide-sm-only am-u-md-6"></div>
                 </div>
            </div>

            <div id="off_curr" class="am-u-sm-12">
                <div class="am-u-sm-4 am-u-md-2 am-text-right">Daily Conversions:</div>
                <div  class="am-u-sm-3 am-u-end">
                    <input type="text" id="conversion_cap" name="conversion_cap" value="<?php echo $caps['daily_con'];?>">
                <p>Max number of conversions offer can receive per day. Leave blank or set to 0 for no conversion cap.</p>
            </div>

            <div id="off_curr" class="am-u-sm-12">
                <div class="am-u-sm-4 am-u-md-2 am-text-right">Monthly Conversions:</div>
                <div  class="am-u-sm-3 am-u-end">
                    <input type="text" id="monthly_conversion_cap" name="monthly_conversion_cap" value="<?php echo $caps['month_con'];?>">
                <p>Max number of conversions offer can receive per month. Leave blank or set to 0 for no monthly conversion cap.</p>
            </div>

            <div class="am-u-sm-12">
                <div class="am-u-sm-4 am-u-md-2 am-text-right">Daily Payout:</div>
                <div  class="am-u-sm-3 am-u-end">
                    <input type="text" id="payout_cap" name="payout_cap" value="<?php echo $caps['daily_pay'];?>">
                </div>
                <p>Max payout amount offer can post per day. Leave blank or set to 0 for no payout cap.</p>
            </div>
            <div class="am-u-sm-12">
                <div class="am-u-sm-4 am-u-md-2 am-text-right">Monthly Payout:</div>
                <div  class="am-u-sm-3 am-u-end">
                    <input type="text" id="monthly_payout_cap" name="monthly_payout_cap" value="<?php echo $caps['month_pay'];?>">
                </div>
                <p>Max payout amount offer can post per month. Leave blank or set to 0 for no monthly payout cap.</p>
            </div>

            <div class="am-u-sm-12">
            <div class="am-u-sm-4 am-u-md-2 am-text-right">Daily Revenue:</div>
            <div  class="am-u-sm-3 am-u-end">
                <input type="text" id="revenue_cap" name="revenue_cap"  value="<?php echo $caps['daily_rev'];?>">
            </div>
            <p>Max revenue amount offer can generate per day. Leave blank or set to 0 for no revenue cap.</p>
            </div>

            <div class="am-u-sm-12">
                <div class="am-u-sm-4 am-u-md-2 am-text-right">Monthly Revenue:</div>
                <div  class="am-u-sm-3 am-u-end">
                    <input type="text" id="monthly_revenue_cap" name="monthly_revenue_cap" value="<?php echo $caps['month_rev'];?>" >
                </div>
                <p>Max revenue amount offer can generate per month. Leave blank or set to 0 for no monthly revenue cap.</p>
            </div>
            <div class="am-u-sm-4 am-u-sm-12">
                <button class="am-btn am-btn-primary am-btn-xs" id="save" name="submit" type="submit">Save</button>
            </div>
            </div>
        </div>
        <div style="padding-top: 500px"></div>
        </form>
    </div>
<script>
    $("#offerCustomCurrency").change(function(){
        var currency = $("#offerCustomCurrency").val();
        if(currency == 0){
            $("#off_curr").removeClass('hide');
        }
        if(currency == 1){
            $("#off_curr").addClass('hide');
        }
    });
</script>