<?php require_once dirname(dirname(__FILE__)) . '/sidebar.php';?>
<style>
    .am-form div{
        padding-top: 10px;
    }
    .am-form label{
        color: red;
    }
</style>
<!-- content start -->
<div class="admin-content">
    <div class="am-cf am-padding">
        <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">Create Advertiser</strong> </div>
    </div>

    <form class="am-form" action="<?php echo $this->createUrl('system/personedit');?>" method="post" id="edit_form">
        <h2>Details</h2>
        <hr data-am-widget="divider" style="" class="am-divider am-divider-default"/>
        <div class="am-u-sm-12">
            <div class="am-u-sm-4 am-u-md-2 am-text-right">Company:</div>
            <label>*</label>
            <div  class="am-u-sm-3 am-u-end">
                <input type="text" id="AdvertiserCompany" name="company" class="am-form-field" value="<?php echo $affiliate['company']?>">
            </div>
        </div>
        <div class="am-u-sm-12">
            <div class="am-u-sm-4 am-u-md-2 am-text-right"> Address 1:</div>
            <div  class="am-u-sm-3 am-u-end">
                <input type="text" id="AdvertiserAddress1" name="address1" class="am-form-field" value="<?php echo $affiliate['address']?>">
            </div>
        </div>
        <div class="am-u-sm-12">
            <div class="am-u-sm-4 am-u-md-2 am-text-right">Phone:</div>
            <div  class="am-u-sm-3 am-u-end">
                <input type="text" id="AdvertiserPhone" name="phone" class="am-form-field" value="<?php echo $affiliate['phone']?>">
            </div>
        </div>

        <hr data-am-widget="divider" style="" class="am-divider am-divider-default"/>

        <div class="am-u-sm-12">
            <div class="am-u-sm-4 am-u-md-2 am-text-right">E-mail Address:</div>
            <label></label>
            <div  class="am-u-sm-3 am-u-end">
                <input type="text" id="AdvertiserUserEmail" name="email" readonly class="am-form-field" value="<?php echo $affiliate['email']?>">
            </div>
            <p>if you want to change your email,please connect the AM.</p>
        </div>

        <?php if($this->user['groupid'] == AFF_GROUP_ID){ ?>
            <hr data-am-widget="divider" style="" class="am-divider am-divider-default"/>
            <div class="am-u-sm-12">
                <div class="am-u-sm-4 am-u-md-2 am-text-right">Postback:</div>
                <div  class="am-u-sm-3 am-u-end">
                    <input type="text" id="AdvertiserBackCode" name="postback" class="am-form-field" value="<?php echo $affiliate['postback']?>">
                </div>
            </div>
        <?php } ?>

        <div style="padding-top: 150px;padding-left: 100px;display: block" class="am-vertical-align-middle">
            <button type="button" onclick="editpersion()" class="am-btn am-btn-primary">Save</button>
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
<?php
if( !empty($msg) ){
    echo '<script>alert("', $msg , '");';
    if( 0 == $ret ){
        echo 'location.href="'.$this->createUrl('system/grouplist').'";';
    }
    echo '</script>';
}
?>
<script>
    var chkEmail = /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
    var editpersion = function(){
        var company = $("#AdvertiserCompany");
        if(company.val() =='') {
            $("#alertContent").html('please input your company name!');
            $("#my-alert").modal();
            company.focus();
            return false;
        }
        if(company.val().length < 3 || company.val().length > 20){
            $("#alertContent").html('your company name is too short or too long !');
            $("#my-alert").modal();
            company.focus();
            return false;
        }
        var phone = $("#AdvertiserPhone");
        if(phone.val() =='') {
            $("#alertContent").html('please input your phone');
            $("#my-alert").modal();
            phone.focus();
            return false;
        }

        var email = $("#AdvertiserUserEmail");
        if(email.val() =='') {
            $("#alertContent").html('please input you email');
            $("#my-alert").modal();
            email.focus();
            return false;
        }else if(!chkEmail.test(email.val())){
            $("#alertContent").html('please check your email!');
            $("#my-alert").modal();
            email.focus();
            return false;
        }
        $("#edit_form").submit();
    }
</script>
