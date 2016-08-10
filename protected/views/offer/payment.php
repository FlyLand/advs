<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/2/26
 * Time: 17:52
 */
?>
<?php
include_once dirname(dirname(__FILE__)).'/sidebar.php';
$readonly = '';
if($this->user['userid'] == AFF_GROUP_ID){
    $readonly = empty($payment) ? '' : 'readonly';
}
?>
<!-- content start -->
<div class="admin-content">
    <div class="am-cf am-padding">
        <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">Payment Info</strong> </div>
    </div>
    <form class="am-form" id="payment_form" action="<?php echo $this->createUrl('affiliates/payment',array('action_type'=>'add'));?>" method="post">
        <div class="am-tabs am-margin" data-am-tabs>
            <ul class="am-tabs-nav am-nav am-nav-tabs">
                <li class="am-active"><a href="#tab1">Payment Info</a></li>
            </ul>
            <div class="am-tabs-bd">
                <div class="am-tab-panel am-fade am-in am-active" id="tab1">
                    <div class="am-g am-margin-top">
                        <div class="am-u-sm-4 am-u-md-2 am-text-right">Payment Type:</div>
                        <div class="am-u-sm-8 am-u-md-4">
                            <select name="payment_type">
                                <option value="0">Telegraphic Transfer</option>
                                <option value="1">Paypal</option>
                            </select>
                        </div>
                        <div class="am-hide-sm-only am-u-md-6">&nbsp;&nbsp;</div>
                    </div>
                    <div class="am-g am-margin-top">
                        <div class="am-u-sm-4 am-u-md-2 am-text-right">Beneficiary Name:</div>
                        <div class="am-u-sm-8 am-u-md-4">
                            <input class="am-form-field" value="<?php echo empty($payment) ? '' : $payment['beneficiary'];?>" <?php echo $readonly;?> type="text"  id="beneficiary"  name="beneficiary" placeholder="Please enter your beneficiary name:"  required />
                        </div>
                        <div class="am-hide-sm-only am-u-md-6">&nbsp;&nbsp;</div>
                    </div>
                    <div class="am-g am-margin-top">
                        <div class="am-u-sm-4 am-u-md-2 am-text-right">Bank Name:</div>
                        <div class="am-u-sm-8 am-u-md-4">
                            <input class="am-form-field" type="text" value="<?php echo empty($payment) ? '' : $payment['bank_name'];?>"  id="bankname" name="bankname" <?php echo $readonly;?> placeholder="Please enter your bank name:"  required />
                        </div>
                        <div class="am-hide-sm-only am-u-md-6">&nbsp;&nbsp;</div>
                    </div>
                    <div class="am-g am-margin-top">
                        <div class="am-u-sm-4 am-u-md-2 am-text-right">Bank Address:</div>
                        <div class="am-u-sm-8 am-u-md-4">
                            <input class="am-form-field" type="text"  id="bankadd" value="<?php echo empty($payment) ? '' : $payment['bank_address'];?>" <?php echo $readonly;?> name="bankadd" placeholder="Please enter your bank address:"  required />
                        </div>
                        <div class="am-hide-sm-only am-u-md-6">&nbsp;&nbsp;</div>
                    </div>
                    <div class="am-g am-margin-top">
                        <div class="am-u-sm-4 am-u-md-2 am-text-right">Bank Account:</div>
                        <div class="am-u-sm-8 am-u-md-4">
                            <input class="am-form-field" type="text"  id="bankacc" value="<?php echo empty($payment) ? '' : $payment['bank_address'];?>" <?php echo $readonly;?> name="bankacc" placeholder="Please enter your bank account:"  required />
                        </div>
                        <div class="am-hide-sm-only am-u-md-6">&nbsp;&nbsp;</div>
                    </div>
                    <div class="am-g am-margin-top">
                        <div class="am-u-sm-4 am-u-md-2 am-text-right">SWIFT Code:</div>
                        You can get this information from your bank.
                        <div class="am-u-sm-8 am-u-md-4">
                        </div>
                        <div class="am-hide-sm-only am-u-md-6">&nbsp;&nbsp;</div>
                    </div>
                </div>
            </div>
            <button type="button" onclick="pay_submit();">Submit</button>
        </div>
    </form>
</div>
<div class="am-modal am-modal-alert" tabindex="-1" id="my-alert">
    <div class="am-modal-dialog">
        <div class="am-modal-hd">OFFER</div>
        <div class="am-modal-bd" id="alertContent">

        </div>
        <div class="am-modal-footer">
            <span class="am-modal-btn">OK</span>
        </div>
    </div>
</div>
<!-- content end -->
<script>
    function pay_submit(){
        if(confirm('Are you sure to submit?You can only contact manager when you want to change the info next time')){
            $('#payment_form').submit();
        }
    }
</script>
<?php if(!empty($msg)){
    echo '<script>';
    echo "   $('#alertContent').html('$msg');
            $('#my-alert').modal();";
    echo '</script>';
}?>