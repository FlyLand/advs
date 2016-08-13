<?php
include_once dirname(dirname(__FILE__)) . '/sidebar.php';
?>
<script type="text/javascript">
function check(){
	var dom_name	=	document.getElementById('Name');
	if( /^\s*$/.test(dom_name.value) ){
		alert('用户组名不能为空');
		dom_name.focus();
		return false;
	}
	
	var dom_status	=	document.getElementById('Status');
	if( '-' == dom_status.value ){
		alert('请选择状态');
		dom_status.focus();
		return false;
	}
	
	if( !confirm('你确定要修改用户组信息吗？') ){
		return false;
	}
	return true;
}
</script>
<div class="admin-content">
  	<div class="am-cf am-padding">
		<div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">修改用户组</strong> </div>
  	</div>
  	<div class="am-tabs am-margin" data-am-tabs>
		<ul class="am-tabs-nav am-nav am-nav-tabs">
			<li class="am-active"><a href="#tab1">用户组信息</a></li>
			<li style="float:right;margin-right:0px;"><div style="padding-left:20px;margin-bottom:5px;" onclick="javascript:location.href='<?php echo $this->createUrl('system/grouplist');?>';"><input type="button" value="返回上一步" class="am-btn am-btn-primary am-btn-xs" /></div></li>
		</ul>
		<div class="am-tabs-bd">
		   <?php 
		      if( 0 == $ret ){
	       ?>
            <form action="<?php echo $this->createUrl('system/groupmod', array('Id'=>$data['groupinfo']['id']));?>" method="post" onsubmit="return check();">
                <input type="hidden" name="r" id="r" value="system/groupmod" />
                <input type="hidden" name="Id" id='Id' value='<?php echo $data['groupinfo']['id']; ?>' >
                <div class="am-tab-panel am-fade am-in am-active" id="tab1">
                    <div class="am-g am-margin-top">
                    	<div class="am-u-sm-4 am-u-md-2 am-text-right">用户组名</div>
                    	<div class="am-u-sm-8 am-u-md-4">
                    		<input type="text" name="Name" id='Name' size="30" maxlength="20" value='<?php echo $data['groupinfo']['name']; ?>' readonly><span class='highlight'>*</span>
                    	</div>
                        <div class="am-hide-sm-only am-u-md-6">&nbsp;&nbsp;</div>
                    </div>
                    <div class="am-g am-margin-top">
                    	<div class="am-u-sm-4 am-u-md-2 am-text-right">当前状态</div>
                    	<div class="am-u-sm-8 am-u-md-4">
                    		<select type="text" name="Status" id="Status"><option value='-'>--请选择--</option>
                    		<?php
                            	$statuslist	=	array(1=>'正常',0=>'冻结');
                            	foreach( $statuslist as $key => $desc ){
                            		if( $key == $data['groupinfo']['status']){
                            			echo '<option value="', $key ,'" selected>', $desc ,'</option>';
                            		}else{
                            			echo '<option value="', $key ,'">', $desc ,'</option>';
                            		}
                            	}
                            ?></select><span class='highlight'>*</span>
                    	</div>
                    	<div class="am-hide-sm-only am-u-md-6">&nbsp;&nbsp;</div>
                    </div>
                    <div class="am-g am-margin-top">
                    	<div class="am-u-sm-4 am-u-md-2 am-text-right">&nbsp;</div>
                    	<div class="am-u-sm-8 am-u-md-4">
                    		<input type="submit" value="修改用户组"  class="am-btn am-btn-primary am-btn-xs" />
                    	</div>
                        <div class="am-hide-sm-only am-u-md-6">&nbsp;&nbsp;</div>
                    </div>
                </div>
            </form>
    		<?php 
    			}
    			if( !empty($msg) ){
    				echo '<script>alert("', $msg , '");';
    				if( 0 == $ret ){
    					echo 'location.href="'.$this->createUrl('system/grouplist').'";';
    				}
    				echo '</script>';
    			}
    		?>
	   </div>
	  </div>
</div>