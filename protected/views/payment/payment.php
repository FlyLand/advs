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
$email_read_only = '';
if($this->user['groupid'] == AFF_GROUP_ID){
    $readonly = empty($payment) ? '' : 'readonly';
    $email_read_only = isset($payment['email']) && !empty($payment['email']) ? 'readonly' : '';
    $select2 = !empty($payment) && $payment['type'] == 1 ? 'selected' : '';
    $select1 = empty($select2) ? 'selected' : '';
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
                            <select id="payment_type" name="payment_type">
                                <option <?php echo $select1;?>  value="0">Telegraphic Transfer</option>
                                <option  <?php echo $select2;?> value="1">Paypal</option>
                            </select>
                        </div>
                        <div class="am-hide-sm-only am-u-md-6">&nbsp;&nbsp;</div>
                    </div>
                    <div class="am-g am-margin-top" id="beneficiary_div">
                        <div class="am-u-sm-4 am-u-md-2 am-text-right">Beneficiary Name:</div>
                        <div class="am-u-sm-8 am-u-md-4">
                            <input class="am-form-field" value="<?php echo empty($payment) ? '' : $payment['beneficiary'];?>" <?php echo $readonly;?> type="text"  id="beneficiary"  name="beneficiary" placeholder="Please enter your beneficiary name:"  required />
                        </div>
                        <div class="am-hide-sm-only am-u-md-6">&nbsp;&nbsp;</div>
                    </div>
                    <div class="am-g am-margin-top" id="bank_name_div">
                        <div class="am-u-sm-4 am-u-md-2 am-text-right">Bank Name:</div>
                        <div class="am-u-sm-8 am-u-md-4">
                            <input class="am-form-field" type="text" value="<?php echo empty($payment) ? '' : $payment['bank_name'];?>"  id="bankname" name="bankname" <?php echo $readonly;?> placeholder="Please enter your bank name:"  required />
                        </div>
                        <div class="am-hide-sm-only am-u-md-6">&nbsp;&nbsp;</div>
                    </div>
                    <div class="am-g am-margin-top" id="bank_address_div">
                        <div class="am-u-sm-4 am-u-md-2 am-text-right">Bank Address:</div>
                        <div class="am-u-sm-8 am-u-md-4">
                            <input class="am-form-field" type="text"  id="bankadd" value="<?php echo empty($payment) ? '' : $payment['bank_address'];?>" <?php echo $readonly;?> name="bankadd" placeholder="Please enter your bank address:"  required />
                        </div>
                        <div class="am-hide-sm-only am-u-md-6">&nbsp;&nbsp;</div>
                    </div>
                    <div class="am-g am-margin-top" id="bank_account_div">
                        <div class="am-u-sm-4 am-u-md-2 am-text-right">Bank Account:</div>
                        <div class="am-u-sm-8 am-u-md-4">
                            <input class="am-form-field" type="number"  id="bankacc" value="<?php echo empty($payment) ? '' : $payment['bank_account'];?>" <?php echo $readonly;?> name="bankacc" placeholder="Please enter your bank account:"  required />
                        </div>
                        <div class="am-hide-sm-only am-u-md-6">&nbsp;&nbsp;</div>
                    </div>
                    <div class="am-g am-margin-top" id="bank_email_div" style="display: none;">
                        <div class="am-u-sm-4 am-u-md-2 am-text-right">Email:</div>
                        <div class="am-u-sm-8 am-u-md-4">
                            <input class="am-form-field" type="text"  id="bank_email" value="<?php echo empty($payment) ? '' : $payment['email'];?>" <?php echo $email_read_only;?> name="bank_email" placeholder="Please enter your bank email:"  required />
                        </div>
                        <div class="am-hide-sm-only am-u-md-6">&nbsp;&nbsp;</div>
                    </div>
                    <div class="am-g am-margin-top" id="swift_code_code">
                        <div class="am-u-sm-4 am-u-md-2 am-text-right">SWIFT Code:</div>
                        <div class="am-u-sm-8 am-u-md-4">
                            <input class="am-form-field" type="text"  id="swift_code" value="<?php echo empty($payment) ? '' : $payment['swift_code'];?>" <?php echo $readonly;?> name="swift_code" placeholder="Please enter your bank account:"  required />
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
        $('#payment_type').change(function(){
            var payment_type = $('#payment_type').val();
            if(payment_type == 1){
                $('#swift_code_div').hide();
                $('#bank_account_div').hide();
                $('#bank_address_div').hide();
                $('#bank_name_div').hide();
                $('#beneficiary_div').hide();
                $('#bank_email_div').show();
                $('#swift_code_code').hide();
            }else{
                $('#swift_code_div').show();
                $('#bank_account_div').show();
                $('#bank_address_div').show();
                $('#bank_name_div').show();
                $('#beneficiary_div').show();
                $('#bank_email_div').hide();
                $('#swift_code_code').show();
            }
        });
        function pay_submit(){
            <?php if(empty($payment)){ ?>
            if(confirm('Are you sure to submit?You can only contact manager when you want to change the info next time')){
                var bank_acc = $('#bankacc').val();
                if(luhmCheck(bank_acc)) {
                    $('#alertContent').html('Please check your account');
                    $('#my-alert').modal();
                    $('#bankacc').focus();
                    return false;
                }
                $('#payment_form').submit();
            }
            <?php }else{ ?>
            $('#alertContent').html('Please Contact Your Manager If You Have To Change The Information!');
            $('#my-alert').modal();
            $('#bankacc').focus();
            <?php } ?>
        }

            function luhmCheck(bankno){
                if (bankno.length < 16 || bankno.length > 19) {
                    return false;
                }
                var num = /^\d*$/;
                if (!num.exec(bankno)) {
                    return false;
                }
                //开头6位
                var strBin="10,18,30,35,37,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,58,60,62,65,68,69,84,87,88,94,95,98,99";
                if (strBin.indexOf(bankno.substring(0, 2))== -1) {
                    return false;
                }
                var lastNum=bankno.substr(bankno.length-1,1);

                var first15Num=bankno.substr(0,bankno.length-1);
                var newArr=new Array();
                for(var i=first15Num.length-1;i>-1;i--){
                    newArr.push(first15Num.substr(i,1));
                }
                var arrJiShu=new Array();
                var arrJiShu2=new Array();

                var arrOuShu=new Array();
                for(var j=0;j<newArr.length;j++){
                    if((j+1)%2==1){
                        if(parseInt(newArr[j])*2<9)
                            arrJiShu.push(parseInt(newArr[j])*2);
                        else
                            arrJiShu2.push(parseInt(newArr[j])*2);
                    }
                    else
                        arrOuShu.push(newArr[j]);
                }

                var jishu_child1=new Array();
                var jishu_child2=new Array();
                for(var h=0;h<arrJiShu2.length;h++){
                    jishu_child1.push(parseInt(arrJiShu2[h])%10);
                    jishu_child2.push(parseInt(arrJiShu2[h])/10);
                }

                var sumJiShu=0;
                var sumOuShu=0;
                var sumJiShuChild1=0;
                var sumJiShuChild2=0;
                var sumTotal=0;
                for(var m=0;m<arrJiShu.length;m++){
                    sumJiShu=sumJiShu+parseInt(arrJiShu[m]);
                }

                for(var n=0;n<arrOuShu.length;n++){
                    sumOuShu=sumOuShu+parseInt(arrOuShu[n]);
                }

                for(var p=0;p<jishu_child1.length;p++){
                    sumJiShuChild1=sumJiShuChild1+parseInt(jishu_child1[p]);
                    sumJiShuChild2=sumJiShuChild2+parseInt(jishu_child2[p]);
                }
                sumTotal=parseInt(sumJiShu)+parseInt(sumOuShu)+parseInt(sumJiShuChild1)+parseInt(sumJiShuChild2);

                var k= parseInt(sumTotal)%10==0?10:parseInt(sumTotal)%10;
                var luhm= 10-k;

                if(lastNum==luhm){
                    return true;
                }
                else{
                    return false;
                }
            }
</script>
<?php if(!empty($msg)){
    echo '<script>';
    echo "   $('#alertContent').html('$msg');
            $('#my-alert').modal();";
    echo '</script>';
}?>