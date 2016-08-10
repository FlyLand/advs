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

    <form class="am-form" action="<?php echo $this->createUrl('advertiser/update',array('id'=>$advertiser['id']));?>" method="post" id="doForm">
        <h2>Details</h2>
        <hr data-am-widget="divider" style="" class="am-divider am-divider-default"/>
        <div class="am-u-sm-12">
            <div class="am-u-sm-4 am-u-md-2 am-text-right">Company:</div>
            <label>*</label>
            <div  class="am-u-sm-3 am-u-end">
                <input type="text" id="AdvertiserCompany" name="company" class="am-form-field" value="<?php echo $advertiser['company']?>">
            </div>
        </div>
        <div class="am-u-sm-12">
            <div class="am-u-sm-4 am-u-md-2 am-text-right"> Address 1:</div>
            <div  class="am-u-sm-3 am-u-end">
                <input type="text" id="AdvertiserAddress1" name="address1" class="am-form-field" value="<?php echo $advertiser['address']?>">
            </div>
        </div>

        <div class="am-u-sm-12">
            <div class="am-u-sm-4 am-u-md-2 am-text-right">Address 2:</div>
            <div  class="am-u-sm-3 am-u-end">
                <input type="text" id="AdvertiserAddress2" name="address2" class="am-form-field" value="<?php echo $advertiser['address2']?>">
            </div>
        </div>

        <div class="am-u-sm-12">
            <div class="am-u-sm-4 am-u-md-2 am-text-right">City:</div>
            <div  class="am-u-sm-3 am-u-end">
                <input type="text" id="AdvertiserCity" name="city" class="am-form-field" value="<?php echo $advertiser['city']?>">
            </div>
        </div>

        <div class="am-u-sm-12">
            <div class="am-u-sm-4 am-u-md-2 am-text-right">Country:</div>
            <div  class="element am-u-sm-3 am-u-end">
                <input type="text" id="AdvertiserCountry" name="country" class="am-form-field" value="<?php echo $advertiser['country']?>">
            </div>
        </div>
        <div class="am-u-sm-12">
            <div class="am-u-sm-4 am-u-md-2 am-text-right">State:</div>
            <div  class="am-u-sm-3 am-u-end">
                <input type="text" id="AdvertiserState" name="state" class="am-form-field">
            </div>
        </div>
        <div class="am-u-sm-12">
            <div class="am-u-sm-4 am-u-md-2 am-text-right">Zipcode:</div>
            <div  class="am-u-sm-3 am-u-end">
                <input type="text" id="AdvertiserZipcode" name="zipcode" class="am-form-field" value="<?php echo $advertiser['zipcode']?>">
            </div>
        </div>
        <div class="am-u-sm-12">
            <div class="am-u-sm-4 am-u-md-2 am-text-right">Phone:</div>
            <div  class="am-u-sm-3 am-u-end">
                <input type="text" id="AdvertiserPhone" name="phone" class="am-form-field" value="<?php echo $advertiser['phone']?>">
            </div>
        </div>

        <h2>Add User</h2>
        <hr data-am-widget="divider" style="" class="am-divider am-divider-default"/>
        <div class="am-u-sm-12">
            <div class="am-u-sm-4 am-u-md-2 am-text-right">First Name:</div>
            <div  class="am-u-sm-3 am-u-end">
                <input type="text" id="AdvertiserUserFirstName" name="first_name" class="am-form-field" value="<?php echo $advertiser['first_name']?>">
            </div>
        </div>
        <div class="am-u-sm-12">
            <div class="am-u-sm-4 am-u-md-2 am-text-right">Last Name:</div>
            <div  class="am-u-sm-3 am-u-end">
                <input type="text" id="AdvertiserUserLastName" name="last_name" class="am-form-field" value="<?php echo $advertiser['last_name']?>">
            </div>
        </div>
        <div class="am-u-sm-12">
            <div class="am-u-sm-4 am-u-md-2 am-text-right">Title:</div>
            <div  class="am-u-sm-3 am-u-end">
                <input type="text" id="AdvertiserUserTitle" name="title" class="am-form-field" value="<?php echo $advertiser['title']?>">
            </div>
        </div>
        <div class="am-u-sm-12">
            <div class="am-u-sm-4 am-u-md-2 am-text-right">E-mail Address:</div>
            <label>*</label>
            <div  class="am-u-sm-3 am-u-end">
                <input type="text" id="AdvertiserUserEmail" name="email" class="am-form-field" value="<?php echo $advertiser['email']?>">
            </div>
        </div>

        <h2>Settings</h2>
        <hr data-am-widget="divider" style="" class="am-divider am-divider-default"/>
        <div class="am-u-sm-12">
            <div class="am-u-sm-4 am-u-md-2 am-text-right">Postback:</div>
            <div  class="am-u-sm-3 am-u-end">
                <input type="text" id="AdvertiserBackCode" name="back_code" class="am-form-field" value="<?php echo $advertiser['postback']?>">
            </div>
        </div>
        <div class="am-u-sm-12">
            <div class="am-u-sm-4 am-u-md-2 am-text-right">Account Manager:</div>
            <div  class="am-u-sm-3 am-u-end">
                <select data-am-selected id="AdvertiserAccountManagerId" name="account_manager_id" >
                    <option value=""></option>
                    <?php if($business){ ?>
                        <?php foreach($business as $val=>$key){?>
                            <option value="<?php echo $key['id']?>"><?php echo $key['company'];?></option>
                        <?php }?>
                    <?php }?>
                </select>
            </div>
        </div>
        <div class="am-u-sm-12">
            <div class="am-u-sm-4 am-u-md-2 am-text-right">Account Status:</div>
            <div  class="am-u-sm-3 am-u-end">
                <select data-am-selected id="AdvertiserStatus" name="status" >
                    <option value="1">Active</option>
                    <option value="0">Pending</option>
                </select>
            </div>
        </div>
        <div style="padding-top: 150px;padding-left: 100px;display: block" class="am-vertical-align-middle">
            <button type="button" onclick="addAdvertiser()" class="am-btn am-btn-primary">Save</button>
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
<script>
    var chkEmail = /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
    var addAdvertiser = function(){
        var company = $("#AdvertiserCompany");
        if(company.val() =='') {
            $("#alertContent").html('please input your company name!');
            $("#my-alert").modal();
            company.focus();
            return false;
        }
        if(company.val().length < 3){
            $("#alertContent").html('your company name is too short or too long !');
            $("#my-alert").modal();
            company.focus();
            return false;
        }

        var address = $("#AdvertiserAddress1");
        if(address.val() =='') {
            $("#alertContent").html('you must input one of your address at least');
            $("#my-alert").modal();
            address.focus();
            return false;
        }
        var zipcode = $("#AdvertiserZipcode");
        if(zipcode.val() =='') {
            $("#alertContent").html('please input zipcode!');
            $("#my-alert").modal();
            zipcode.focus();
            return false;
        }
        var phone = $("#AdvertiserPhone");
        if(phone.val() =='') {
            $("#alertContent").html('please input your phone');
            $("#my-alert").modal();
            phone.focus();
            return false;
        }

        var first_name = $("#AdvertiserUserFirstName");
        if(first_name.val() =='') {
            $("#alertContent").html('first name is not allow null');
            $("#my-alert").modal();
            first_name.focus();
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
        $("#doForm").submit();
    }
</script>
