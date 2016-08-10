<?php
include_once dirname(dirname(__FILE__)).'/sidebar.php';
?>
<script type="text/javascript">
    function check(){
        if( !confirm('Are you sure to change your password?') ){
            return false;
        }
        return true;
    }
</script>
<div class="admin-content">
    <div class="am-cf am-padding">
        <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">Modify Password</strong> </div>
    </div>
    <div class="am-tabs am-margin" data-am-tabs>
        <ul class="am-tabs-nav am-nav am-nav-tabs">
            <li class="am-active"><a href="#tab1">User Info </a></li>
            <li style="float:right;margin-right:0px;"><div style="padding-left:20px;margin-bottom:5px;" onclick="javascript:location.href='<?php echo $this->createUrl('system/grouplist');?>';"><input type="button" value="return" class="am-btn am-btn-primary am-btn-xs" /></div></li>
        </ul>
        <div class="am-tabs-bd">
            <form action="<?php echo $this->createUrl('system/password');?>" method="post" onsubmit="return check();">
                <div class="am-tab-panel am-fade am-in am-active" id="tab1">
                    <div class="am-g am-margin-top">
                        <div class="am-u-sm-4 am-u-md-2 am-text-right">Your old password:</div>
                        <div class="am-u-sm-8 am-u-md-4">
                            <input type="password" name="Oldpwd" id='Oldpwd' size="30" maxlength="20" value=''><span class='highlight'>*</span>
                        </div>
                        <div class="am-hide-sm-only am-u-md-6">&nbsp;&nbsp;</div>
                    </div>
                    <div class="am-g am-margin-top">
                        <div class="am-u-sm-4 am-u-md-2 am-text-right">Your new password:</div>
                        <div class="am-u-sm-8 am-u-md-4">
                            <input type="password" name="Newpwd" id='Newpwd' size="30" maxlength="20" value=''><span class='highlight'>*</span>
                        </div>
                        <div class="am-hide-sm-only am-u-md-6">&nbsp;&nbsp;</div>
                    </div>
                    <div class="am-g am-margin-top">
                        <div class="am-u-sm-4 am-u-md-2 am-text-right">Your new password:</div>
                        <div class="am-u-sm-8 am-u-md-4">
                            <input type="password" name="Chkpwd" id='Chkpwd' size="30" maxlength="20" value=''><span class='highlight'>*</span>
                        </div>
                        <div class="am-hide-sm-only am-u-md-6">&nbsp;&nbsp;</div>
                    </div>
                </div>
                <div class="am-g am-margin-top">
                    <input type="submit" value="submit" class="am-btn am-btn-primary am-btn-xs" />
                </div>
        </div>
            </form>
        </div>
    </div>
<?php
if( !empty($msg) ){
    echo '<script>alert("', $msg , '");';
    if( 0 == $ret ){
        echo 'location.href="'.$this->createUrl('system/grouplist').'";';

    }
    echo '</script>';
}
?>
</div>