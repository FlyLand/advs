<?php require_once dirname(dirname(__FILE__)) . '/sidebar.php';?>
<style>
</style>
<link rel="stylesheet" type="text/css" href="assets/css/simditor/simditor.css" />
<link rel="stylesheet" type="text/css" href="assets/css/simditor/simditor-checklist.css" />
<link rel="stylesheet" type="text/css" href="/assets/css/simditor/simditor-emoji.css" />

<script type="text/javascript" src="assets/js/simditor/module.js"></script>
<script type="text/javascript" src="assets/js/simditor/hotkeys.js"></script>
<script type="text/javascript" src="assets/js/simditor/uploader.js"></script>
<script type="text/javascript" src="assets/js/simditor/simditor.js"></script>
<script type="text/javascript" src="assets/js/simditor/simditor-checklist.js"></script>
<script type="text/javascript" src="assets/js/simditor/simditor-emoji.js"></script>


<div class="admin-content">
    <div class="am-cf am-padding">
        <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">Business Requests</strong></div>
    </div>
    <form class="am-form" action="<?php $this->createUrl('system/addmeassage')?>" method="post" id="msg_form">
        <hr data-am-widget="divider" style="" class="am-divider am-divider-default"/>

        <input type="hidden" name="type" value="1" >
        <div class="am-u-sm-12" id="task_div" style="display: ">
            <div class="am-u-sm-8" id="task_type_div" style="margin-top: 3%">
                <div class="am-u-sm-3 am-u-md-3 am-text-left">Type :</div>
                <div  class="am-u-sm-9 am-u-end">
                    <select name="task_type" id="task_type"  data-am-selected="{maxHeight: 200}">
                        <option value='2'>Create Affiliate</option>
                        <option value='0'>Replace Url</option>
                        <option value='1'>Apply Url</option>
                    </select>
                </div>
            </div>

            <div class="am-u-sm-8" id="aff_name_div" style="margin-top: 3%">
                <div class="am-u-sm-3 am-u-md-3 am-text-left">Affiliate's Name:</div>
                <div  class="am-u-sm-4 am-u-end">
                    <input class="am-form-field" type="text"  id="affname" name="affname" placeholder="Please enter Affiliate Name"  required />
                </div>
            </div>

            <div class="am-u-sm-8" id="aff_email_div" style="margin-top: 3%;display: none">
                <div class="am-u-sm-3 am-u-md-3 am-text-left">Affiliate's Email:</div>
                <div  class="am-u-sm-4 am-u-end">
                    <input class="am-form-field" type="text"  id="email" name="email" placeholder="Please enter Affiliate Email"  required />
                </div>
            </div>

            <div class="am-u-sm-8" id="aff_password_div" style="margin-top: 3%;display: none">
                <div class="am-u-sm-3 am-u-md-3 am-text-left">Login Password:</div>
                <div  class="am-u-sm-4 am-u-end">
                    <input class="am-form-field" type="text"  id="password" name="password" placeholder="Please enter Affiliate Password"  required />
                </div>
            </div>

            <div class="am-u-sm-8" id="aff_id_div" style="margin-top: 3%;display: none">
                <div class="am-u-sm-3 am-u-md-3 am-text-left">Affiliate ID:</div>
                <div  class="am-u-sm-4 am-u-end">
                    <input class="am-form-field" type="number"  id="affid" name="affid" placeholder="Please enter Affiliate ID"  required />
                </div>
            </div>

            <div class="am-u-sm-8" id="back_url_div" style="margin-top: 3%;display: none;float: left">
                <div class="am-u-sm-3 am-u-md-3 am-text-left">Original Url :</div>
                <div  class="am-u-sm-9 am-u-end">
                    <input class="am-form-field" type="text" id="back_url" name="back_url" value="">
                </div>
            </div>
        </div>

        <div class="am-u-sm-8" id="msg_content_div" style="margin-top: 3%">
            <div class="am-u-sm-3 am-u-md-3 am-text-left">Content :</div>
            <div  class="am-u-sm-9 am-u-end">
                <textarea id="content" name="content"  placeholder="Please input the message you want send..." autofocus></textarea>
            </div>
        </div>

        <input style="display: none" value="" name="content_input" id="content_input">

        <hr data-am-widget="divider"  class="am-divider am-divider-default"/>
        <div class="am-u-sm-10" style="margin-top: 3%">
            <button type="button" onclick="send_msg()" class="am-btn am-btn-primary am-btn-xs">Send</button>
            <button type="reset" class="am-btn am-btn-primary am-btn-xs">Cancel</button>
        </div>
    </form>
</div>
<script>
    var editor = new Simditor({
        textarea: $('#content'),
        toolbar: [ 'emoji','title', 'bold', 'italic', 'underline', 'strikethrough','checklist',
            'color', '|', 'ul', 'blockquote', 'table', '|',
            'link', 'image', 'hr', '|', 'indent', 'outdent' ],
        emoji: {
            imagePath: 'assets/i/emoji/'
        }
        //optional options
    });
</script>
<?php
if( !empty($msg) ){
    echo '<script>alert("', $msg , '");';
    echo '</script>';
}?>
<script>
    var send_msg = function(){
        var type =1;
        var task_type = $('#task_type').val();
        if(1 == type && 2 == task_type){
            var name = $('#affname').val();
            var password = $('#password').val();
            var email = $('#email').val();
            var st = "Please create an account and here is the info <p> Affiliate Name : " + name + '</p> <p>Affiliate email : ' + email + '  </p><p>Password :' + password + '</p>';
            $('#content_input').val(st);
        }
        $('#msg_form').submit();
    };

    $('#task_type').change(function(){
        var task_type = $('#task_type').val();
        if(2 == task_type){
            $('#aff_email_div').show();
            $('#aff_name_div').show();
            $('#aff_password_div').show();
            $('#back_url_div').hide();
            $('#aff_id_div').hide();
            $('#msg_content_div').hide();
        }else if(0 == task_type){
            $('#aff_email_div').hide();
            $('#aff_name_div').hide();
            $('#aff_password_div').hide();
            $('#back_url_div').show();
            $('#aff_id_div').show();
            $('#msg_content_div').show();
        }else if(1 == task_type){
            $('#aff_email_div').hide();
            $('#aff_name_div').hide();
            $('#aff_password_div').hide();
            $('#back_url_div').hide();
            $('#aff_id_div').show();
            $('#msg_content_div').show();
        }
    });
</script>