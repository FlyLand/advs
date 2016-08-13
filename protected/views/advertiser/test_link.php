<?php
include_once dirname(dirname(__FILE__)) . '/sidebar.php';
?>
<div class="admin-content">
    <div class="am-cf am-padding">
        <div class="am-fl am-cf">
            <strong class="am-text-primary am-text-lg">Add: Affiliate Whitelist</strong>
        </div>
    </div>
    <div class="am-tabs am-margin" data-am-tabs="">
        <div class="am-panel am-panel-default am-form">
            <div class="am-panel-hd am-cf" data-am-collapse="{target: '#collapse-panel-1'}"><b>IP Address</b><span class="am-icon-chevron-down am-fr"></span></div>
            <div class="am-panel-bd am-collapse am-in" id="collapse-panel-1">
                <form name="test_form" id="test_form" action="<?php echo $this->createUrl('advertiser/testlink');?>" method="post">
                    <div class="am-g am-margin-top">
                        <div class="am-u-sm-4 am-u-md-2 am-text-right">Test Link:</div>
                        <div class="am-u-sm-8 am-u-md-10">
                           <input name="link" value="<?php echo empty($url) ? '' : $url;?>">
                        </div>
                    </div>
                    <div class="am-margin">
                        <a onclick="test_link()" class="am-btn am-btn-primary am-btn-sm">Test</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    var test_link = function () {
        $('#test_form').submit();
    }
</script>