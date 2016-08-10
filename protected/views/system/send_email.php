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
        <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">Email -- Send</strong></div>
    </div>
    <form class="am-form" action="<?php $this->createUrl('system/sendemail')?>" method="post" id="doForm">
        <hr data-am-widget="divider" style="" class="am-divider am-divider-default"/>
        <div class="am-u-sm-8 ">
            <div class="am-u-sm-1 am-u-md-1 am-text-left">To:</div>
            <div  class="am-u-sm-9 am-u-end">
                <input class="am-form-field" type="text" id="fromName" name="fromName" value="">
            </div>
        </div>
        <br>
        <br>
        <br>
        <div class="am-u-sm-8">
            <div class="am-u-sm-1 am-u-md-1 am-text-left">Title:</div>
            <div  class="am-u-sm-9 am-u-end">
                <input class="am-form-field" type="text" id="title" name="title" value="">
            </div>
        </div>
        <br>
        <br>
        <br>
        <div class="am-u-sm-8">
            <div class="am-u-sm-1 am-u-md-1 am-text-left">Content:</div>
            <div  class="am-u-sm-9 am-u-end">
                <textarea id="editor" placeholder="Please input the message you want send..." autofocus></textarea>
            </div>
        </div>

        <hr data-am-widget="divider" style="" class="am-divider am-divider-default"/>
        <div class="am-u-sm-10">
            <button type="submit" class="am-btn am-btn-primary am-btn-xs">Send</button>
            <button type="reset" class="am-btn am-btn-primary am-btn-xs">Cancel</button>
        </div>
    </form>

</div>
<script>
    var editor = new Simditor({
        textarea: $('#editor'),
        toolbar: [ 'emoji','title', 'bold', 'italic', 'underline', 'strikethrough','checklist',
        'color', '|', 'ul', 'blockquote', 'table', '|',
        'link', 'image', 'hr', '|', 'indent', 'outdent' ],
        emoji: {
            imagePath: 'assets/i/emoji/'
        }
        //optional options
    });
</script>