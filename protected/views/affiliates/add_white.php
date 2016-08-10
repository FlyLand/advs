<?php
include_once dirname(dirname(__FILE__)).'/sidebar.php';
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
                <form name="white_form" id="white_form" action="<?php echo $this->createUrl('affiliateapi/addwhite',array('type'=>'add'));?>" method="post">
                    <div class="am-g am-margin-top">
                        <div class="am-u-sm-4 am-u-md-2 am-text-right">Affiliates:</div>
                        <div class="am-u-sm-8 am-u-md-10">
                            <select name="affiliate" id="affiliate" data-am-selected="{btnWidth: 300, btnSize: 'sm', btnStyle: 'secondary',maxHeight: 200}" style="display: none;">
                                <option value=""></option>
                                <?php foreach($affiliates as $affiliate){ ?>
                                    <option value="<?php echo $affiliate['id']?>"><?php echo $affiliate['company']?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="am-g am-margin-top">
                        <div class="am-u-sm-4 am-u-md-2 am-text-right">Type:</div>
                        <div class="am-u-sm-8 am-u-md-10">
                            <select name="content_type" id="content_type" data-am-selected="{btnWidth: 300, btnSize: 'sm', btnStyle: 'secondary'}" style="display: none;">
                                <option value="1">IP Address</option>
                            </select>
                        </div>
                    </div>

                    <div class="am-g am-margin-top">
                        <div class="am-u-sm-4 am-u-md-2 am-text-right">IP Address:</div>
                        <div class="am-u-sm-8 am-u-md-4 am-u-end col-end">
                            <input type="text" class="am-input-sm" name="content" id="content"  />
                        </div>
                        <a  class="am-btn am-btn-primary am-btn-xs"  onclick="getToken()">Create Token</a>
                    </div>

                    <div class="am-g am-margin-top">
                        <div class="am-u-sm-4 am-u-md-2 am-text-right">Token:</div>
                        <div class="am-u-sm-8 am-u-md-4 am-u-end col-end">
                            <input  type="text" class="am-input-sm" name="token" id="token" readonly="readonly" />
                        </div>
                    </div>

                    <div class="am-g am-margin-top">
                        <div class="am-u-sm-4 am-u-md-2 am-text-right">Status:</div>
                        <div class="am-u-sm-8 am-u-md-10">
                            <select name="status" id="status" data-am-selected="{btnWidth: 300, btnSize: 'sm', btnStyle: 'secondary'}" style="display: none;">
                                <option value="1">Open</option>
                                <option value="0">Close</option>
                            </select>
                        </div>
                    </div>

                    <div class="am-margin">
                        <input type="hidden" name="form" id="form" value="<?php echo  $form;?>" />
                        <a onclick="save()" class="am-btn am-btn-primary am-btn-sm">save</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var reg = /^([0,1]?\d{0,2}|2[0-4]\d|25[0-5])\.([0,1]?\d{0,2}|2[0-4]\d|25[0-5])\.([0,1]?\d{0,2}|2[0-4]\d|25[0-5])\.([0,1]?\d{0,2}|2[0-4]\d|25[0-5])$/;
    var getToken    =   function(){
        var ip  =   $('#content').val();
        var aff =   $('#affiliate').val();
        if(aff == ''){
            alert('you must select a affiliate');
            return false;
        }
        if(!reg.test(ip)){
            alert('please check your IP address');
            return false;
        }
        $.ajax({
            type: 'POST',
            url: '<?php echo $this->createUrl('affiliateapi/gettoken');?>',
            data: {
              form:'<?php echo $form;?>',
                ip:ip,
                aff_id:aff
            },
            datatype:'json',
            success: function(data){
                console.log(data);
                var ret_data = eval('('+data+')');
                console.log(ret_data);
                if( 0 != ret_data.ret ){
                    alert(ret_data.msg);
                    return false;
                }else{
                    $('#token').val(ret_data.data);
                }
            },
        });
    }

    var save    = function () {
        var aff =   $('#affiliate').val();
        var content =   $('#content').val();
        var token   =   $('#token').val();

        if(!aff || aff == ''){
            alert('you have to select an affiliate!');
            return false;
        }
        if(!content || !reg.test(content)){
            alert('check your IP please!');
            return false;
        }
        if(!token){
            alert('you have to apply a token to give to the affiliate');
            return false;
        }
        console.log(11111);
        $('#white_form').submit();
    }
    
</script>