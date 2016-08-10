<?php
include_once dirname(dirname(__FILE__)).'/sidebar.php';

$powerconf	=	require BASE_DIR.'/protected/config/powerconf.php';
?>
<script type="text/javascript">
function checkform(){
	var dom_action	=	document.getElementById('Action');
	if( !/\//.test(dom_action.value) ){
		alert('请选择权限名称');
		dom_action.focus();
		return false;
	}
	
	var dom_status	=	document.getElementById('Status');
	if( '-' == dom_status.value ){
		alert('请选择状态');
		dom_status.focus();
		return false;
	}
	if( !confirm('你确定要修改权限吗？') ){
		return false;
	}
	
	return true;
}
</script>
<?php
 $this->breadcrumbs = array(
 	array(
 		'name'=>'系统管理',
 		'url'=>$this->createUrl('system/grouppowerlist'),
 			),
     '组权限管理'
 );
?>
<div class="admin-content">
  	<div class="am-cf am-padding">
		<div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">修改权限</strong> </div>
  	</div>
  	<div class="am-tabs am-margin" data-am-tabs>
		<ul class="am-tabs-nav am-nav am-nav-tabs">
			<li class="am-active"><a href="#tab1">权限信息</a></li>
			<li style="float:right;margin-right:0px;"><div style="padding-left:20px;margin-bottom:5px;" onclick="javascript:location.href='<?php echo $this->createUrl('system/grouppowerlist');?>';"><input type="button" value="返回上一步" class="am-btn am-btn-primary am-btn-xs" /></div></li>
		</ul>
		<div class="am-tabs-bd">
            <form action="<?php echo $this->createUrl('system/powermod', array('Id'=>$data['powerinfo']['id']));?>" method="post" onsubmit='return checkform();'>
                <input type="hidden" name="r" id="r" value="system/powermod" />
                <input type="hidden" name="Id" id='Id' value='<?php echo $data['powerinfo']['id']; ?>' >
                <input type="hidden" name="Parentid" id='Parentid' value='<?php echo $data['powerinfo']['parentid']; ?>' >
                <div class="am-tab-panel am-fade am-in am-active" id="tab1">
                    <div class="am-g am-margin-top">
                    	<div class="am-u-sm-4 am-u-md-2 am-text-right">权限名称</div>
                    	<div class="am-u-sm-8 am-u-md-4">
                    		<select name="Action" id="Action">
                    		<?php
                            	foreach( $powerconf as $action => $item ){
									if( $data['powerinfo']['action'] == $action ){
										echo '<option value="', $action, '" selected>', $item['name'], '</option>';
									}else if( 0 < $item['canshow'] ){
										echo '<option value="', $action, '">', $item['name'], '</option>';
									}
								}
                            ?></select><span class='highlight'>*</span>
                    	</div>
                    	<div class="am-hide-sm-only am-u-md-6">&nbsp;&nbsp;</div>
                    </div>
                    <div class="am-g am-margin-top">
                    	<div class="am-u-sm-4 am-u-md-2 am-text-right">状态</div>
                    	<div class="am-u-sm-8 am-u-md-4">
                    		<select  name="Status" id="Status"><option value='-'>--请选择--</option>
                    		<?php
                            	$statuslist	=	array(1=>'正常',0=>'冻结');
                            	foreach( $statuslist as $key => $desc ){
                            		if( $key == $data['powerinfo']['status']){
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
                    	<div class="am-u-sm-4 am-u-md-2 am-text-right">显示权值</div>
                    	<div class="am-u-sm-8 am-u-md-4">
                    		<input type="text" name="Weight" id="Weight" size="30" value="<?php echo $data['powerinfo']['weight']; ?>" /><span class='highlight'>*</span>
                    	</div>
                        <div class="am-hide-sm-only am-u-md-6">&nbsp;&nbsp;</div>
                    </div>
                    <div class="am-g am-margin-top">
                    	<div class="am-u-sm-4 am-u-md-2 am-text-right">&nbsp;</div>
                    	<div class="am-u-sm-8 am-u-md-4">
                    		<input type="submit" value="修改权限"  class="am-btn am-btn-primary am-btn-xs" />
                    	</div>
                        <div class="am-hide-sm-only am-u-md-6">&nbsp;&nbsp;</div>
                    </div>
                </div>
            </form>
    		<?php 
    			if( !empty($msg) ){
    				echo '<script>alert("', $msg , '");';
    				echo '</script>';
    			}
    		?>
	   </div>
	  </div>
</div>
