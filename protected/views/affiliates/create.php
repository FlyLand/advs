<?php require_once dirname(dirname(__FILE__)) . '/sidebar.php';?>
<style>
    .am-form div{
        padding-top: 10px;
    }
    .am-form label{
        color: red;
    }
</style>
<div class="admin-content">
    <div class="am-cf am-padding">
        <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">Affiliate Edit</strong> </div>
    </div>
    <div class="am-g am-g-fixed">
        <form class="am-form" action="<?php echo $this->createUrl('affiliates/create');?>" method="post" id="doForm">
            <div class="am-u-md-12">
                <div class="am-panel am-panel-default">
                    <div class="am-panel-hd am-cf" data-am-collapse="{target: '#collapse-panel-4'}">Details<span class="am-icon-chevron-down am-fr" ></span></div>
                    <div id="collapse-panel-4" class="am-panel-bd am-collapse am-in">
                        <div class="am-g">
                            <div class="am-u-sm-12">
                                <div class="am-u-sm-5 am-u-md-5 am-text-right">Company:</div>
                                <label>*</label>
                                <div  class="am-u-sm-3 am-u-end">
                                    <input type="text" id="AffiliateCompany" name="company" class="am-form-field">
                                </div>
                            </div>
                            <div class="am-u-sm-12">
                                <div class="am-u-sm-5 am-u-md-5 am-text-right"> Address 1:</div>
                                <div  class="am-u-sm-3 am-u-end">
                                    <input type="text" id="AffiliateAddress1" name="address1" class="am-form-field">
                                </div>
                            </div>

                            <div class="am-u-sm-12">
                                <div class="am-u-sm-5 am-u-md-5 am-text-right">Address 2:</div>
                                <div  class="am-u-sm-3 am-u-end">
                                    <input type="text" id="AffiliateAddress2" name="address2" class="am-form-field">
                                </div>
                            </div>

                            <div class="am-u-sm-12">
                                <div class="am-u-sm-5 am-u-md-5 am-text-right">City:</div>
                                <div  class="am-u-sm-3 am-u-end">
                                    <input type="text" id="AffiliateCity" name="city" class="am-form-field">
                                </div>
                            </div>
                            <div class="am-u-sm-12">
                                <div class="am-u-sm-5 am-u-md-5 am-text-right">E-mail Address:</div>
                                <label>*</label>
                                <div  class="am-u-sm-3 am-u-end">
                                    <input type="text" id="AffiliateUserEmail" name="email" class="am-form-field">
                                </div>
                            </div>
                            <div class="am-u-sm-12">
                                <div class="am-u-sm-5 am-u-md-5 am-text-right">Password:</div>
                                <label>*</label>
                                <div  class="am-u-sm-3 am-u-end">
                                    <input type="password" id="AffiliatePassword" name="password" class="am-form-field" placeholder="Password">
                                </div>
                            </div>
                            <div class="am-u-sm-12">
                                <div class="am-u-sm-5 am-u-md-5 am-text-right">Confirm Password:</div>
                                <label>*</label>
                                <div  class="am-u-sm-3 am-u-end">
                                    <input type="password" id="AffiliatePasswordConfirmation" name="password_confirmation" class="am-form-field" placeholder="Password">
                                </div>
                            </div>
                            <div class="am-u-sm-12">
                                <div class="am-u-sm-5 am-u-md-5 am-text-right">Country:</div>
                                <div  class="element am-u-sm-3 am-u-end">
                                    <input type="text" id="AffiliateCountry" name="country" class="am-form-field">
                                </div>
                            </div>
                            <div class="am-u-sm-12">
                                <div class="am-u-sm-5 am-u-md-5 am-text-right">Zipcode:</div>
                                <div  class="am-u-sm-3 am-u-end">
                                    <input type="text" id="AffiliateZipcode" name="zipcode" class="am-form-field">
                                </div>
                            </div>
                            <div class="am-u-sm-12">
                                <div class="am-u-sm-5 am-u-md-5 am-text-right">Phone:</div>
                                <div  class="am-u-sm-3 am-u-end">
                                    <input type="number" id="AffiliatePhone" name="phone" class="am-form-field">
                                </div>
                            </div>
                            <div class="am-u-sm-12">
                                <div class="am-u-sm-5 am-u-md-5 am-text-right">Account Manager:</div>
                                <div  class="am-u-sm-3 am-u-end">
                                    <select data-am-selected id="AffiliateAccountManagerId" name="account_manager_id" >
                                        <option value=""></option>
                                        <?php if($AM){ ?>
                                            <?php foreach($AM as $key=>$val){?>
                                                <option value="<?php echo $val['id']?>"><?php echo $val['company'];?></option>
                                            <?php }?>
                                        <?php }?>
                                    </select>
                                </div>
                            </div>
                            <div class="am-u-sm-12">
                                <div class="am-u-sm-5 am-u-md-5 am-text-right">Account Status:</div>
                                <div  class="am-u-sm-3 am-u-end">
                                    <select data-am-selected id="AdvertiserStatus" name="status" >
                                        <option value="1">Active</option>
                                        <option value="0">Pending</option>
                                    </select>
                                </div>
                            </div>
                            <div class="am-u-sm-12">
                                <div class="am-u-sm-5 am-u-md-5 am-text-right">
                                    <button type="button" onclick="addAffiliate()" class="am-btn am-btn-primary">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </div>
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
    var addAffiliate = function(){
        $("#doForm").submit();
    }
</script>