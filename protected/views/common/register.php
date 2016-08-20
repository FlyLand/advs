<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>Joy Dream -- Register</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="format-detection" content="telephone=no">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <link rel="alternate icon" type="image/png" href="assets/i/favicon.png">
    <link rel="stylesheet" href="assets/css/amazeui.min.css"/>
    <style>
        .header {
            text-align: center;
        }
        .header h1 {
            font-size: 200%;
            color: #333;
            margin-top: 30px;
        }
        .header p {
            font-size: 14px;
        }
        .am-form div{
            padding-top: 10px;
        }
        .am-form label{
            color: red;
        }
    </style>
</head>
<body>
<div class="header">
    <link rel="stylesheet" href="assets/css/amazeui.min.css"/>
    <link rel="stylesheet" href="assets/css/admin.css">
    <link rel="stylesheet" href="assets/css/ingrid.css" type="text/css" media="screen" />
    <script src="assets/js/jquery.js"></script>
    <script type="text/javascript" src="assets/js/jquery.ingrid.js"></script>
    <!--[if lt IE 9]>
    <script src="http://libs.baidu.com/jquery/1.11.1/jquery.min.js"></script>
    <script src="http://cdn.staticfile.org/modernizr/2.8.3/modernizr.js"></script>
    <script src="assets/js/polyfill/rem.min.js"></script>
    <script src="assets/js/polyfill/respond.min.js"></script>
    <script src="assets/js/amazeui.legacy.js"></script>
    <![endif]-->
    <!--[if (gte IE 9)|!(IE)]><!-->
    <!--[if (gte IE 9)|!(IE)]><!-->
    <script src="assets/js/amazeui.min.js"></script>
    <!--<![endif]-->
    <script src="assets/js/app.js"></script>
</div>
<div class="admin-content">
    <div class="am-u-lg-8 am-u-md-8 am-u-sm-left">
        <h1>Joy Dream HasOffer:Affiliate Sign Up </h1>
        <hr>
        <div class="admin-content">
            <div class="am-cf am-padding">
                <p class="am-u-lg-3 am-u-md-2 am-u-end">
                    Apply to our network by submitting your information below.
                    * - required field</p>
            </div>
        <am-tabs am-margin>
            <form class="am-form" action="<?php echo $this->createUrl('standard/register');?>" method="post" id="doForm">
                <div class="am-fl am-cf"><h1><strong class="am-text-primary am-text-lg"></strong>Account Details/<small>Personal information</small></h1></div>
                <hr data-am-widget="divider" style="" class="am-divider am-divider-default"/>
                <div class="am-u-sm-12">
                    <div class="am-u-sm-4 am-u-md-2 am-text-right">Company:</div>
                    <label>*</label>
                    <div  class="am-u-sm-3 am-u-end">
                        <input type="text" id="AdvertiserCompany" name="company" class="am-input-sm">
                        <div class="am-hide-sm-only am-u-md-6"></div>
                    </div>
                </div>
                <div class="am-u-sm-12">
                    <div class="am-u-sm-4 am-u-md-2 am-text-right"> Address 1:</div>
                    <div  class="am-u-sm-3 am-u-end">
                        <input type="text" id="AdvertiserAddress1" name="address1" class="am-form-field">
                    </div>
                </div>

                <div class="am-u-sm-12">
                    <div class="am-u-sm-4 am-u-md-2 am-text-right">Address 2:</div>
                    <div  class="am-u-sm-3 am-u-end">
                        <input type="text" id="AdvertiserAddress2" name="address2" class="am-form-field">
                    </div>
                </div>

                <div class="am-u-sm-12">
                    <div class="am-u-sm-4 am-u-md-2 am-text-right">City:</div>
                    <div  class="am-u-sm-3 am-u-end">
                        <input type="text" id="AdvertiserCity" name="city" class="am-form-field">
                    </div>
                </div>

                <div class="am-u-sm-12">
                    <div class="am-u-sm-4 am-u-md-2 am-text-right">Country:</div>
                    <div  class="element am-u-sm-3 am-u-end">
                        <input type="text" id="AdvertiserCountry" name="country" class="am-form-field">
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
                        <input type="text" id="AdvertiserZipcode" name="zipcode" class="am-form-field">
                    </div>
                </div>
                <div class="am-u-sm-12">
                    <div class="am-u-sm-4 am-u-md-2 am-text-right">Phone:</div>
                    <label>*</label>
                    <div  class="am-u-sm-3 am-u-end">
                        <input type="text" id="AdvertiserPhone" name="phone" class="am-form-field">
                    </div>
                </div>

                <h2>User Details</h2>
                <hr data-am-widget="divider" style="" class="am-divider am-divider-default"/>
                <div class="am-u-sm-12">
                    <div class="am-u-sm-4 am-u-md-2 am-text-right">First Name:</div>
                    <div  class="am-u-sm-3 am-u-end">
                        <input type="text" id="AdvertiserUserFirstName" name="first_name" class="am-form-field">
                    </div>
                </div>
                <div class="am-u-sm-12">
                    <div class="am-u-sm-4 am-u-md-2 am-text-right">Last Name:</div>
                    <div  class="am-u-sm-3 am-u-end">
                        <input type="text" id="AdvertiserUserLastName" name="last_name" class="am-form-field">
                    </div>
                </div>
                <div class="am-u-sm-12">
                    <div class="am-u-sm-4 am-u-md-2 am-text-right">Title:</div>
                    <label>*</label>
                    <div  class="am-u-sm-3 am-u-end">
                        <input type="text" id="AdvertiserUserTitle" name="title" class="am-form-field">
                    </div>
                </div>
                <div class="am-u-sm-12">
                    <div class="am-u-sm-4 am-u-md-2 am-text-right">E-mail Address:</div>
                    <label>*</label>
                    <div  class="am-u-sm-3 am-u-end">
                        <input type="text" id="AdvertiserUserEmail" name="email" class="am-form-field">
                        <p>It's the username when you sign in JoyDream</p>
                    </div>
                </div>
                <div class="am-u-sm-12">
                    <div class="am-u-sm-4 am-u-md-2 am-text-right">Password:</div>
                    <label>*</label>
                    <div  class="am-u-sm-3 am-u-end">
                        <input type="password" id="AdvertiserPassword" name="password" class="am-form-field" placeholder="Password">
                    </div>
                </div>
                <div class="am-u-sm-12">
                    <div class="am-u-sm-4 am-u-md-2 am-text-right">Confirm Password:</div>
                    <label>*</label>
                    <div  class="am-u-sm-3 am-u-end">
                        <input type="password" id="AdvertiserPasswordConfirmation" name="password_confirmation" class="am-form-field" placeholder="Password">
                    </div>
                </div>
                <div class="am-vertical-align-middle" style="padding-top: 150px;padding-left: 100px; display: block;">
                    <button type="button" onclick="register_aff()" class="am-btn am-btn-primary">Sign In</button>
                </div>
            </form>
        </div>
        </am-tabs>
        <div class="am-modal am-modal-alert" tabindex="-1" id="my-alert">
            <div class="am-modal-dialog">
                <div class="am-modal-hd">Joy Dream Offer</div>
                <div class="am-modal-bd" id="alertContent">

                </div>
                <div class="am-modal-footer">
                    <span class="am-modal-btn">OK</span>
                </div>
            </div>
        </div>
        <hr>
    </div>
</div>
</body>
</html>
<script>
    var chkEmail = /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
    var register_aff = function(){
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
        var re_password = $("#AdvertiserPasswordConfirmation");
        if(password.val() != re_password.val() ){
            $("#alertContent").html('there has an error about your password');
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