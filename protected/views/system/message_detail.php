<?php
include_once dirname(dirname(__FILE__)).'/sidebar.php';
?>
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
        <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">Message Detail</strong></div>
    </div>
    <div class="am-g am-g-fixed">
        <div class="am-u-md-12">
            <div class="am-panel am-panel-default">
                <div class="am-panel-hd am-cf">
                    <div class="am-u-sm-12 am-u-md-12">
                        <div class="am-u-sm-6 am-u-md-6">
                            <div class="am-u-sm-3 am-u-md-3"> From :</div>
                            <div class="am-u-sm-9 am-u-md-9">
                                <?php echo $send_name;?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="am-panel-hd am-cf">
                    <div class="am-u-sm-12 am-u-md-12">
                        <div class="am-u-sm-6 am-u-md-6">
                            <div class="am-u-sm-3 am-u-md-3"> Title :</div>
                            <div class="am-u-sm-9 am-u-md-9">
                                <?php echo $message['title'];?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="am-panel am-panel-default">
                <div class="am-panel-hd am-cf" data-am-collapse="{target: '#collapse-panel-4'}">Content<span class="am-icon-chevron-down am-fr" ></span></div>
                <div class="am-u-sm-12" id="msg_content_div" style="margin-top: 3%">
                    <div  class="am-u-sm-12 am-u-end">
                        <textarea id="content"  name="content"  placeholder="Please input the message you want send..." autofocus>
                            <?php echo $message['content']?>
                        </textarea>
                    </div>
                </div>
            </div>
    </div>
</div>
    <div class="am-cf am-padding">
        <div style="padding-right:10%;margin-top: 3%;float: right" onclick="javascript:location.href='<?php echo $this->createUrl('system/message');?>';"><input type="button" value="Back" class="am-btn am-btn-primary am-btn-xs" /></div>
    </div>
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