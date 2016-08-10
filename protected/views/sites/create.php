<?php require_once dirname(dirname(__FILE__)) . '/sidebar.php';?>
<style>
    div{
        padding-top: 10px;
    }
    .am-form label{
        color: red;
    }
</style>
<div class="admin-content">
    <div class="am-cf am-padding">
        <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">Site Edit</strong> </div>
    </div>
    <div class="am-g am-g-fixed">
        <form id="doForm" action="<?php echo $this->createUrl('site/create');?>" method="post">
            <div class="am-u-md-12">
                <div class="am-panel am-panel-default">
                    <div class="am-panel-hd am-cf" data-am-collapse="{target: '#collapse-panel-4'}">Details<span class="am-icon-chevron-down am-fr" ></span></div>
                    <div id="collapse-panel-4" class="am-panel-bd am-collapse am-in">
                        <div class="am-g">
                            <div class="am-u-sm-12">
                                <div class="am-u-sm-5 am-u-md-5 am-text-right">Company:</div>
                                <label>*</label>
                                <div  class="am-u-sm-3 am-u-end">
                                    <input type="text" id="AdvertiserCompany" name="company" class="am-form-field" value="">
                                </div>
                            </div>
                            <div class="am-u-sm-12">
                                <div class="am-u-sm-5 am-u-md-5 am-text-right"> Address 1:</div>
                                <div  class="am-u-sm-3 am-u-end">
                                    <input type="text" id="AdvertiserAddress1" name="address1" class="am-form-field" value="">
                                </div>
                            </div>

                            <div class="am-u-sm-12">
                                <div class="am-u-sm-5 am-u-md-5 am-text-right">E-mail Address:</div>
                                <label>*</label>
                                <div  class="am-u-sm-3 am-u-end">
                                    <input type="text" id="AdvertiserUserEmail" name="email"  class="am-form-field" value="">
                                </div>
                            </div>

                            <div class="am-u-sm-12">
                                <div class="am-u-sm-5 am-u-md-5 am-text-right">Country:</div>
                                <div  class="element am-u-sm-3 am-u-end">
                                    <input type="text" id="AdvertiserCountry" name="country" class="am-form-field" value="">
                                </div>
                            </div>

                            <div class="am-u-sm-12">
                                <div class="am-u-sm-5 am-u-md-5 am-text-right">Zipcode:</div>
                                <div  class="am-u-sm-3 am-u-end">
                                    <input type="text" id="AdvertiserZipcode" name="zipcode" class="am-form-field" value="">
                                </div>
                            </div>

                            <div class="am-u-sm-12">
                                <div class="am-u-sm-5 am-u-md-5 am-text-right">Phone:</div>
                                <div  class="am-u-sm-3 am-u-end">
                                    <input type="text" id="AdvertiserPhone" name="phone" class="am-form-field" value="">
                                </div>
                            </div>

                            <div class="am-u-sm-12">
                                <div class="am-u-sm-5 am-u-md-5 am-text-right">Account Manager:</div>
                                <div  class="am-u-sm-3 am-u-end">
                                    <select data-am-selected id="AdvertiserAccountManagerId" name="account_manager_id" >
                                        <option value=""></option>
                                        <?php if($business){ ?>
                                            <?php foreach($business as $val=>$key){?>
                                                <option value="<?php echo $key['id']?>"><?php echo  $key['id']. '    ' . $key['company']?></option>
                                            <?php }?>
                                        <?php }?>
                                    </select>
                                </div>
                            </div>
                            <div class="am-u-sm-12">
                                <div class="am-u-sm-5 am-u-md-5 am-text-right">Account Status:</div>
                                <div  class="am-u-sm-3 am-u-end">
                                    <select data-am-selected name="status" >
                                        <option value="1">Active</option>
                                        <option value="0">Pending</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="am-u-md-12">
                <div class="am-panel am-panel-default">
                    <div class="am-panel-hd am-cf" data-am-collapse="{target: '#collapse-panel-2'}">Affiliates<span class="am-icon-chevron-down am-fr" ></span></div>
                    <div id="collapse-panel-2" class="am-panel-bd am-collapse am-in">
                        <div class="am-g">
                            <table class="am-table am-table-striped am-table-hover">
                                <thead>
                                <tr>
                                    <td>Affiliate Id</td>
                                    <td>Operation</td>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if(!empty($affids)){
                                    $aff_arr = explode(',',$affids);
                                    foreach($aff_arr as $aff){
                                        echo "<tr>";
                                        echo "<td>$aff</td>";
                                        echo "<td><button type='button' class='am-btn am-btn-default am-btn-xs am-text-danger am-hide-sm-only' onclick='dl_rel({$site['id']},$aff)'>Delete</td>";
                                        echo "</tr>";
                                    }
                                }?>
                                </tbody>
                            </table>
                            <div class="am-u-sm-12">
                                <div class="am-u-sm-5 am-u-md-5 am-text-right">Add Relevance</div>
                                <div  class="am-u-sm-3 am-u-end">
                                    <select id="affiliates" name="affiliates[]" multiple data-am-selected="{btnWidth: 300, btnSize: 'sm', btnStyle: 'secondary',maxHeight: 200,searchBox: 1}"  style="display: none;padding-top: 20px">
                                        <?php if(!empty($affiliates)){
                                            foreach($affiliates as $affiliate){
                                                if(!empty($affiliate)){
                                                    $checked = '';
                                                    if(isset($affiliate['checked'])) $checked = 'selected';
                                                    echo "<option $checked value='{$affiliate['id']}'>{$affiliate['id']}</option>";
                                                }
                                            }
                                        }?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="am-u-md-12">
                <div class="am-panel am-panel-default">
                    <div class="am-panel-hd am-cf" data-am-collapse="{target: '#collapse-panel-1'}">Setting<span class="am-icon-chevron-down am-fr" ></span></div>
                    <div id="collapse-panel-1" class="am-panel-bd am-collapse am-in">
                        <div class="am-g">
                            <div class="am-u-sm-12">
                                <div class="am-u-sm-5 am-u-md-5 am-text-right">Password:</div>
                                <label>*</label>
                                <div  class="am-u-sm-3 am-u-end">
                                    <input type="text" id="AdvertiserPassword" name="password"  class="am-form-field" value="">
                                </div>
                            </div>
                            <div class="am-u-sm-12">
                                <div class="am-u-sm-5 am-u-md-5 am-text-right">
                                    <button type="button" onclick="createSite()" class="am-btn-m am-btn-primary">Save</button>
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
    var createSite = function(){
        var company = $("#AdvertiserCompany");
        if(company.val() =='') {
            $("#alertContent").html('please input your company name!');
            $("#my-alert").modal();
            company.focus();
            return false;
        }
        if(company.val().length < 3 ){
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
        /*
         var zipcode = $("#AdvertiserZipcode");
         if(zipcode.val() =='') {
         $("#alertContent").html('please input zipcode!');
         $("#my-alert").modal();
         zipcode.focus();
         return false;
         }
         */
        var phone = $("#AdvertiserPhone");
        if(phone.val() =='') {
            $("#alertContent").html('please input your phone');
            $("#my-alert").modal();
            phone.focus();
            return false;
        }

        var password = $("#AdvertiserPassword");
        if(password.val() =='') {
            $("#alertContent").html('password is not allow null');
            $("#my-alert").modal();
            password.focus();
            return false;
        }
        if(password.val().length < 3 ){
            $("#alertContent").html('your password is too simple');
            $("#my-alert").modal();
            password.focus();
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
        }
        if(!chkEmail.test(email.val())){
            $("#alertContent").html('please check your email!');
            $("#my-alert").modal();
            email.focus();
            return false;
        }
        $("#doForm").submit();
    }
</script>
