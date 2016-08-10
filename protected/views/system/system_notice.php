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
        <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">System Message</strong></div>
    </div>
    <form class="am-form" action="<?php $this->createUrl('system/addmeassage')?>" method="post" id="msg_form">
        <hr data-am-widget="divider" style="" class="am-divider am-divider-default"/>

        <div class="am-u-sm-8 ">
            <input type="hidden" name="type" value="0" >
        </div>

        <div class="am-u-sm-12" id="message_div">
            <div class="am-u-sm-8" id="to_div" style="margin-top: 3%;">
                <div class="am-u-sm-3 am-u-md-3 am-text-left">To :</div>
                <div  class="am-u-sm-9 am-u-end">
                    <select name="fromName[]" multiple  data-am-selected="{searchBox: 1,maxHeight: 200}">
                        <?php if(!empty($groups)){
                            foreach($groups as $group){
                                echo "<optgroup label='{$group['name']}'>";
                                foreach($users[$group['name']] as $user){
                                    echo "<option value='{$user['id']}'>{$user['company']}</option>";
                                }
                            }
                        }?>
                    </select>
                </div>
            </div>

            <div class="am-u-sm-8" id="msg_title_div" style="margin-top: 3%;float: left">
                <div class="am-u-sm-3 am-u-md-3 am-text-left">Title:</div>
                <div  class="am-u-sm-9 am-u-end">
                    <input class="am-form-field" type="text" id="title" name="title" value="">
                </div>
            </div>
        </div>



        <div class="am-u-sm-8" id="msg_content_div" style="margin-top: 3%;padding-left: 30px">
            <div class="am-u-sm-3 am-u-md-3 am-text-left">content :</div>
            <div  class="am-u-sm-9 am-u-end">
                <textarea id="content" name="content"  placeholder="Please input the message you want send..." autofocus></textarea>
            </div>
        </div>

        <input style="display: none" value="" name="content_input" id="content_input">

        <hr data-am-widget="divider"  class="am-divider am-divider-default"/>
        <div class="am-u-sm-10" style="margin-top: 3%">
            <button type="button" onclick="send_apply()" class="am-btn am-btn-primary am-btn-xs">Send</button>
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
    var send_apply = function(){
        var type = $('#type').val();
        var task_type = $('#task_type').val();
        $('#msg_form').submit();
    };


</script>