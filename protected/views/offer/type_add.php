<?php
include_once dirname(dirname(__FILE__)).'/sidebar.php';
?>
<script type="text/javascript">
    function check(){
        <?php if(isset($type)){
            $hint   =   'Are you sure to update this offer type';
        }else{
            $hint   =   'Are you sure to add this offer type?';
        }?>
        if( !confirm(<?php echo $hint?>) ){
            return false;
        }
        return true;
    }
</script>
<div class="admin-content">
    <div class="am-cf am-padding">
        <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">Offer--Type Add</strong> </div>
    </div>
    <div class="am-tabs am-margin" data-am-tabs>
        <ul class="am-tabs-nav am-nav am-nav-tabs">
            <li class="am-active"><a href="#tab1">Type Info </a></li>
            <li style="float:right;margin-right:0px;"><div style="padding-left:20px;margin-bottom:5px;" onclick="javascript:location.href='<?php echo $this->createUrl('offer/typemanager');?>';"><input type="button" value="return" class="am-btn am-btn-primary am-btn-xs" /></div></li>
        </ul>
        <div class="am-tabs-bd">
            <form action="<?php
            if(isset($type)){
                echo $this->createUrl('offer/typemanager',array('type'=>'update','id'=>$type['id']));
            }else{
                echo $this->createUrl('offer/typemanager',array('type'=>'add'));
            }?>" method="post" onsubmit="return check();">
                <div class="am-tab-panel am-fade am-in am-active" id="tab1">
                    <div class="am-g am-margin-top">
                        <div class="am-u-sm-4 am-u-md-2 am-text-right">Type Name -en:</div>
                        <div class="am-u-sm-8 am-u-md-4">
                            <input type="text" name="name_en" id='name_en'  value='<?php if(isset($type)){
                                echo $type['type_name_en'];}?>'><span class='highlight'>*</span>
                        </div>
                        <div class="am-hide-sm-only am-u-md-6">&nbsp;&nbsp;</div>
                    </div>
                    <div class="am-g am-margin-top">
                        <div class="am-u-sm-4 am-u-md-2 am-text-right">Key words:</div>
                        <div class="am-u-sm-8 am-u-md-4">
                            <input type="text" name="keywords" id='keywords'   value='<?php if(isset($type)){echo $type['key_words'];} ?>'
                            ><span class='highlight'>*</span>
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
        echo 'location.href="'.$this->createUrl('offer/typemanager').'";';
    }
    echo '</script>';
}
?>
</div>
