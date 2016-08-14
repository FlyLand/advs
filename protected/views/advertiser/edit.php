<div class="row">
    <div class="col-sm-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>Affiliate Edit</h5>
                <div class="ibox-tools">
                    <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                    <a class="dropdown-toggle" data-toggle="dropdown" href="form_basic.html#">
                        <i class="fa fa-wrench"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="form_basic.html#">选项1</a>
                        </li>
                        <li><a href="form_basic.html#">选项2</a>
                        </li>
                    </ul>
                    <a class="close-link">
                        <i class="fa fa-times"></i>
                    </a>
                </div>
            </div>
            <div class="ibox-content">
                <form method="get" action="<?php echo $this->createUrl('advertiser/update',array('id'=>$advertiser['id']));?>" class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Company</label>
                        <div class="col-sm-6">
                            <input type="text" name="company" class="form-control" value="<?php echo $advertiser['company']?>">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Address1:</label>
                        <div class="col-sm-6">
                            <input type="text"  name="address1" class="form-control" value="<?php echo $advertiser['address']?>">
                          </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Address2:</label>
                        <div class="col-sm-6">
                            <input type="text"  name="address2" class="form-control" value="<?php echo $advertiser['address2']?>">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">City:</label>
                        <div class="col-sm-6">
                            <input type="text"  name="city" class="form-control" value="<?php echo $advertiser['city']?>">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Country:</label>
                        <div class="col-sm-6">
                            <input type="text" id="AdvertiserCountry" name="country" class="form-control" value="<?php echo $advertiser['country']?>">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">State:</label>
                        <div class="col-sm-6">
                            <input type="text" id="AdvertiserState" name="state" class="form-control">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Zipcode:</label>
                        <div class="col-sm-6">
                            <input type="text" id="AdvertiserZipcode" name="zipcode" class="form-control" value="<?php echo $advertiser['zipcode']?>">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Phone:</label>
                        <div class="col-sm-6">
                            <input type="text" id="AdvertiserPhone" name="phone" class="form-control" value="<?php echo $advertiser['phone']?>">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">First Name:</label>
                        <div class="col-sm-6">
                            <input type="text" id="AdvertiserUserFirstName" name="first_name" class="form-control" value="<?php echo $advertiser['first_name']?>">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Last Name:</label>
                        <div class="col-sm-6">
                            <input type="text"  name="last_name" class="form-control" value="<?php echo $advertiser['last_name']?>">
                        </div>
                    </div>

                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Email:</label>
                        <div class="col-sm-6">
                            <input type="text" id="AdvertiserUserEmail" name="email" class="form-control" value="<?php echo $advertiser['email']?>">
                        </div>
                    </div>

                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Account Manager:</label>
                        <<div class="col-sm-6">
                            <select  name="account_manager_id" class="form-control m-b">
                                <option value=""></option>
                                <?php if($business){ ?>
                                    <?php foreach($business as $val=>$key){?>
                                        <option value="<?php echo $key['id']?>"><?php echo $key['company'];?></option>
                                    <?php }?>
                                <?php }?>
                            </select>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <div class="col-sm-4 col-sm-offset-2">
                            <button class="btn btn-primary" onclick="addAdvertiser()" type="button">Save</button>
                        </div>
                    </div>
                </form>
            </div>
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
