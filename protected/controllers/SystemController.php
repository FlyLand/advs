<?php
/**
 * @author nine
 * @since 2014.04.21
 * 
 */
class SystemController extends Controller{
	/**
	 * 构造函数，在New对象的时候自动调用
	 */
	public function __construct(){
		parent::checkAction();
	}
	/**
	 * 后台首页
	 * @author nicky
	 */
	public function actionIndex()
	{
		$ret_array	 =	array( 'ret'=>-1, 'msg'=>'', 'data'=>array() );
		do{
			try{
				$this->checkUserStatus();
				
				$reqData	=	System::getUserList(array('id'=>$this->user['userid'], 'canmc'=>false));//用户登录检查
				if( empty($reqData) || !isset($reqData['ret']) ){
					$ret_array['ret']	= 101;
					$ret_array['msg']	 = '服务器忙，请稍后再试';
					break;
				}
				if( 0 != $reqData['ret']){
					$ret_array	= $reqData;
					break;
				}
				$userinfo	=	array_shift($reqData['data']);
		
				$ret_array['ret']	 =	0;
				$ret_array['data']	 =	$userinfo;
			}catch(Exception $e){
				$ret_array['']	= 106;
				$ret_array['msg']	 = $e->getMessage();
			}
		}while(0);
		$this->render('system/index' , $ret_array);
	}
	/**
	 * 登录
	 * @author nicky
	 */
	public function actionLogin()
	{
		$model=new LoginForm;
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login()){
				$this->redirect('index');
				$user = JoySystemUser::model()->findByPk($this->user->id);
				$reqData	=	System::getGroupPowerDetail(array('groupid'=>$user['groupid'], 'datatype'=>'simple'));//用户登录检查
				$powerinfo	=	$reqData['data'];
				$actioninfo	=	array();
				foreach($powerinfo as $item ){
					$actioninfo = array_merge($actioninfo, array_keys($item['actions']));
				}
				$session_data				=	array();
				$session_data['userid']		=	$this->user->id;
				$session_data['email']		=	$user['email'];
				$session_data['powerinfo']	=	$powerinfo;
				$session_data['actioninfo']	=	$actioninfo;
				$session_data['groupid']	=	$user['groupid'];
				$session_data['openuser']	=	$user['openuser'];
				$session_data['showmenu']	=	'';
				$session_data['dtime']		=	date('Y-m-d H:i:s');

				CfgAR::setMc(array('link'=>CACHE,'key'=>Admin_MEM_PIX.$this->getSessionId(),'data'=>$session_data,'time'=>MEM_USER_LOGIN_TIME));
			}
		}
		// display the login form
		$this->render('comm/login',array('model'=>$model));
	}
	/**
	 * 账户登出
	 */
	function actionLogout(){
		CfgAR::delMc(array('link'=>CACHE,'key'=>Admin_MEM_PIX.$this->getSessionId()));
		header('Location:'.$this->createUrl('system/index'));
	}
	/**
	 * 我的信息
	 */
	function actionMyInfo(){
		$this->checkUserStatus( array('url'=>'system/myinfo') );
		$ret_array	=	array('ret'=>1, 'msg'=>'', 'occur'=>'System_actionMyInfo', 'error'=>'', 'data'=>array());
		do{
			try{
				$reqData	=	System::getUserList(array('canmc'=>false, 'id'=>$this->user['userid']));
				if( empty($reqData) || !isset($reqData['ret']) ){
					$ret_array['ret']	=	1;
					$ret_array['msg']		=	'服务器忙，请稍后再试';
					break;
				}
					
				if( 0 != $reqData['ret'] ){
					$ret_array	=	$reqData;
					break;
				}
				$userlist	=	$reqData['data'];
				
				$reqData	=	System::getGroupList(array('canmc'=>false));
				if( empty($reqData) || !isset($reqData['ret']) ){
					$ret_array['ret']	=	1;
					$ret_array['msg']		=	'服务器忙，请稍后再试';
					break;
				}
					
				if( 0 != $reqData['ret'] ){
					$ret_array	=	$reqData;
					break;
				}
				$grouplist	=	$reqData['data'];
				
				$ret_array['ret']	=	0;
				$ret_array['data']		=	array('userlist'=>$userlist, 'grouplist'=>$grouplist);
			}catch(Exception $e){
				$ret_array['ret']	=	13;
				$ret_array['msg']		=	'程序运行中出现异常';
				$ret_array['error']		=	$e->getMessage();
			}
		}while(0);
		$this->render('system/myinfo', $ret_array);
	}
	/**
	 * 修改密码
	 */
	function actionPassword(){
		$this->checkUserStatus( array('url'=>'system/password') );
		$ret_array	=	array('ret'=>1, 'msg'=>'', 'occur'=>'System_actionPassword', 'error'=>'', 'data'=>array());
		do{
			try{
				if( isset($_POST['Oldpwd']) && isset($_POST['Newpwd']) && isset($_POST['Chkpwd']) ){
					$oldpwd		=	Common::getParams('Oldpwd','','POST');
					$newpwd		=	Common::getParams('Newpwd','','POST');
					$chkpwd		=	Common::getParams('Chkpwd','','POST');
					if( !preg_match('/[A-Za-z0-9]{6,20}/', $oldpwd) ){
						$ret_array['ret']	=	1;
						$ret_array['msg']		=	'旧密码输入错误，至少是6位数字或字母';
						break;
					}
					if( !preg_match('/[A-Za-z0-9]{6,20}/', $newpwd) ){
						$ret_array['ret']	=	1;
						$ret_array['msg']		=	'新密码输入错误，至少是6位数字或字母';
						break;
					}
					
					if( $chkpwd	!= $newpwd ){
						$ret_array['ret']	=	1;
						$ret_array['msg']		=	'两次输入的密码不相同，请重新输入';
						break;
					}
					
//					$reqData	=	System::modifyPwd(array('email'=>$this->user['email'], 'oldpwd'=>$oldpwd, 'newpwd'=>$newpwd));
					$user	=	JoySystemUser::model()->findByAttributes(array('email'=>$this->user['email']));
					if(!$user){
						Common::jsalerturl('The Server Error!Please Try Again.',$this->createUrl('system/login'));
					}
					if($user['password']	!==	md5($oldpwd)){
						$ret_array['ret']	=	1;
						$ret_array['msg']		=	'the password is error！';
						break;
					}
					$user->password	=	md5($newpwd);
					if(!$user->update()){
						$ret_array['ret']	=	1;
						$ret_array['msg']		=	'server error！please try again!';
						break;
					}
					$ret_array['ret']	=	0;
					Common::jsalerturl('the password has been changed,please login in with your new password!',$this->createUrl('system/logout'));
				}
			}catch(Exception $e){
				$ret_array['ret']	=	13;
				$ret_array['msg']		=	'程序运行中出现异常';
				$ret_array['error']		=	$e->getMessage();
			}
		}while(0);
		$this->render('system/password', $ret_array);
	}
	/**
	 * 用户列表页面
	 */
	function actionUserList(){
		$this->checkUserStatus( array('url'=>'system/userlist') );
		
		$ret_array	=	array('ret'=>1, 'msg'=>'', 'occur'=>'System_actionIndex', 'error'=>'', 'data'=>array());
		do{
			try{
				$groupid	=	0;
				$status		=	1;
				$page		=	1;
				$size		=	30;
								
				$jpurl		=	$this->createUrl('system/userlist');
				$jparams	=	array();
				
				if( isset( $_GET['page'] ) && 0 < intval($_GET['page']) ){
					$page		=	intval($page);
					//$jparams[]	=	'page='.$page;
				}
				if( isset($_GET['groupid']) && intval($_GET['groupid']) > 0 ){
					$groupid	=	intval($_GET['groupid']);
					$jparams[]	=	'groupid='.$groupid;
				}
				if( isset($_GET['status']) && preg_match('/^\d+$/', trim($_GET['status']) ) ){
					$status		=	intval($_GET['status']);
					$jparams[]	=	'status='.$status;
				}
				
				if( 0 < count($jparams) ){
					$tmp_str	=	strpos($jpurl, '?') ? '&' : '?';
					$jpurl	.=	$tmp_str.join('&', $jparams);
				}
				
				
				$reqData	=	System::getPageUser(array('groupid'=>$groupid, 'status'=>$status, 'page'=>$page, 'size'=>$size));
				if( empty($reqData) || !isset($reqData['ret']) ){
					$ret_array['ret']	=	1;
					$ret_array['msg']		=	'服务器忙，请稍后再试';
					break;
				}
					
				if( 0 != $reqData['ret'] ){
					$ret_array	=	$reqData;
					break;
				}
				$userinfo	=	$reqData['data'];//array('zongye'=>'总页数', 'total'=>'总记录数', 'datalist'=>数组记录)
				
				$page_obj	=	new Page();
				$fenyecode	=	$page_obj->createPage( array('url'=>$jpurl, 'size'=>$userinfo['total'], 'page'=>$page, 'pageSize'=>$size) );
				
				$reqData	=	System::getGroupList(array('canmc'=>false));
				if( empty($reqData) || !isset($reqData['ret']) ){
					$ret_array['ret']	=	1;
					$ret_array['msg']		=	'服务器忙，请稍后再试';
					break;
				}
					
				if( 0 != $reqData['ret'] ){
					$ret_array	=	$reqData;
					break;
				}
				$grouplist	=	$reqData['data'];
				
				$userinfo['grouplist']	=	$grouplist;
				$userinfo['fenyecode']	=	$fenyecode;
				$userinfo['params']		=	array('groupid'=>$groupid, 'status'=>$status);
				
				$ret_array['ret']	=	0;
				$ret_array['data']		=	$userinfo;
			}catch(Exception $e){
				$ret_array['ret']	=	13;
				$ret_array['msg']		=	'程序运行中出现异常';
				$ret_array['error']		=	$e->getMessage();
			}
		}while(0);
		$this->render('system/userlist', $ret_array);
	}

	/**
	 * 添加用户页面
	 */
	function actionUserAdd(){
		$this->checkUserStatus( array('url'=>'system/useradd') );
		$ret_array	=	array('ret'=>1, 'msg'=>'', 'occur'=>'system_actionUserAdd', 'error'=>'', 'data'=>array());
		do{
			try{
				//判断当前用户是否可以开户
				if(isset($this->user['openuser']) && 0 == $this->user['openuser'] ){
					$ret_array['ret']	= 	5;
					$ret_array['msg'] 		= 	'账号未授权开通子账户！';
					break;
				}
				if( ADMIN_GROUP_ID == $this->user['groupid'] ){//管理员组
					$reqData	=	System::getGroupList(array('canmc'=>false));
					if( empty($reqData) || !isset($reqData['ret']) ){
						$ret_array['ret']	=	1;
						$ret_array['msg']		=	'服务器忙，请稍后再试';
						break;
					}
				
					if( 0 != $reqData['ret'] ){
						$ret_array	=	$reqData;
						break;
					}
					$grouplist	=	$reqData['data'];
				}else if( AGENT_GROUP_ID == $this->user['groupid'] ){//代理商组
					$grouplist	=	array(
							array('id'=>AGENT_USER_GROUP_ID, 'name'=>AGENT_USER_GROUP_NAME)
					);
				}else{//其它用户应该不会进入
					$grouplist	=	array();
				}
				//
				
				if( isset( $_POST['Email']) ){
					$email		=	Common::getParams('Email',false,'POST');
					$password	=	Common::getParams('Password',false,'POST');
					$groupid	=	Common::getParams('Groupid',false,'POST');
					$status		=	Common::getParams('Status',false,'POST');
					$openuser	=	0;//ADMIN_GROUP_ID == $this->user['groupid'] ? Common::getParams('Openuser',0,'POST') : 0;
					$title		=	Common::getParams('Title','','POST');
					
					
					$reqData	=	System::addUser(array('email'=>$email, 'password'=>$password, 'groupid'=>$groupid, 'status'=>$status, 'openuser'=>$openuser, 'title'=>$title, 'op_userid'=>$this->user['userid'], 'op_email'=>$this->user['email']));
					if( empty($reqData) || !isset($reqData['ret']) ){
						$ret_array['ret']	=	1;
						$ret_array['msg']		=	'服务器忙，请稍后再试';
						break;
					}
					if(0 == $reqData['ret']){
						$ret_array['ret']	=	0;
						$ret_array['data']	=	array('grouplist'=>$grouplist);
						$ret_array['msg']	=	'添加用户成功';
						break;
					}
					$ret_array	=	$reqData;
				}
				
				$ret_array['data']		=	array('grouplist'=>$grouplist);
				
			}catch(Exception $e){
				$ret_array['ret']	=	13;
				$ret_array['msg']		=	'程序运行中出现异常';
				$ret_array['error']		=	$e->getMessage();
			}
		}while(0);
		$this->render('system/useradd', $ret_array);
	}
	
	/**
	 * 修改用户页面
	 */
	function actionUserMod(){
		$this->checkUserStatus( array('url'=>'system/usermod') );
		
		$ret_array	=	array('ret'=>1, 'msg'=>'', 'occur'=>'system_actionUserMod', 'error'=>'', 'data'=>array());
		do{
			try{
				//判断当前用户是否可以开户
				if( 0 == $this->user['openuser'] ){
					$ret_array['ret']	= 	5;
					$ret_array['msg'] 		= 	'账号未授权开通子账户！';
					break;
				}
				
				
				$reqData	=	System::getUserList(array('canmc'=>false, 'id'=>$_GET['Id']));
				if( empty($reqData) || !isset($reqData['ret']) ){
					$ret_array['ret']	=	1;
					$ret_array['msg']		=	'服务器忙，请稍后再试';
					break;
				}
					
				if( 0 != $reqData['ret'] ){
					$ret_array	=	$reqData;
					break;
				}
				if( empty($reqData['data']) ){
					$ret_array['ret']	=	5;
					$ret_array['msg']		=	'用户不存在';
					break;
				}
				$userinfo	=	array_shift($reqData['data']);
				
				if( ADMIN_GROUP_ID == $this->user['groupid'] ){//管理员组
					$reqData	=	System::getGroupList(array('canmc'=>false));
					if( empty($reqData) || !isset($reqData['ret']) ){
						$ret_array['ret']	=	1;
						$ret_array['msg']		=	'服务器忙，请稍后再试';
						break;
					}
				
					if( 0 != $reqData['ret'] ){
						$ret_array	=	$reqData;
						break;
					}
					$grouplist	=	$reqData['data'];
				}else if( AGENT_GROUP_ID == $this->user['groupid'] ){//代理商组
					$grouplist	=	array(
							array('id'=>AGENT_USER_GROUP_ID, 'name'=>AGENT_USER_GROUP_NAME)
					);
				}else{//其它用户应该不会进入
					$grouplist	=	array();
				}
				
				if( isset( $_POST['Email']) ){
					$id			=	Common::getParams('Id',false,'POST');
					$email		=	Common::getParams('Email',false,'POST');
					$password	=	Common::getParams('Password',false,'POST');
					$groupid	=	Common::getParams('Groupid',false,'POST');
					$status		=	Common::getParams('Status',false,'POST');
					$openuser	=	0;//ADMIN_GROUP_ID == $this->user['groupid'] ? Common::getParams('Openuser',0,'POST') : $userinfo['openuser'];//保持不变
					$title		=	Common::getParams('Title','','POST');
					
					$reqData	=	System::modUser(array('id'=>$id, 'groupid'=>$groupid, 'status'=>$status, 'openuser'=>$openuser, 'title'=>$title, 'op_userid'=>$this->user['userid'], 'op_email'=>$this->user['email']));
					if( empty($reqData) || !isset($reqData['ret']) ){
						$ret_array['ret']	=	1;
						$ret_array['msg']		=	'服务器忙，请稍后再试';
						break;
					}
	
					if( 0 == $reqData['ret'] ){
						$ret_array['ret']	=	0;
						$ret_array['msg']		=	'修改用户成功';
						$ret_array['data']		=	array('grouplist'=>$grouplist, 'userinfo'=>$userinfo);
						break;
					}
					$ret_array	=	$reqData;
				}
				$ret_array['data']		=	array('grouplist'=>$grouplist, 'userinfo'=>$userinfo);
			}catch(Exception $e){
				$ret_array['ret']	=	13;
				$ret_array['msg']		=	'程序运行中出现异常';
				$ret_array['error']		=	$e->getMessage();
			}
		}while(0);
		//Core::toTxt(array('file'=>'Log_SystemController_actionUserMod.txt', 'txt'=>'Input:'.var_export($_POST, true).'|Output:'.var_export($ret_array, true)));
		$this->render('system/usermod', $ret_array);
	}

	/**
	 * 用户组列表页面
	 */
	function actionGroupList(){
		$this->checkUserStatus( array('url'=>'system/grouplist') );
		$ret_array	=	array('ret'=>1, 'msg'=>'', 'occur'=>'system_actionIndex', 'error'=>'', 'data'=>array());
		do{
			try{
				$reqData	=	System::getGroupList(array('canmc'=>false));
				if( empty($reqData) || !isset($reqData['ret']) ){
					$ret_array['ret']	=	1;
					$ret_array['msg']		=	'服务器忙，请稍后再试';
					break;
				}
					
				if( 0 != $reqData['ret'] ){
					$ret_array	=	$reqData;
					break;
				}
				$grouplist	=	$reqData['data'];
	
				$ret_array['ret']	=	0;
				$ret_array['data']		=	array('grouplist'=>$grouplist);
			}catch(Exception $e){
				$ret_array['ret']	=	13;
				$ret_array['msg']		=	'程序运行中出现异常';
				$ret_array['error']		=	$e->getMessage();
			}
		}while(0);
		$this->render('system/grouplist', $ret_array);
	}
	
	/**
	 * 添加用户组页面
	 */
	function actionGroupAdd(){
		$this->checkUserStatus( array('url'=>'system/groupadd') );
		$ret_array	=	array('ret'=>1, 'msg'=>'', 'occur'=>'system_actionGroupAdd', 'error'=>'', 'data'=>array());
		do{
			try{
				if( isset( $_POST['Name']) ){
					$name		=	Common::getParams('Name',false,'POST');
					$status		=	Common::getParams('Status',false,'POST');
						
					$reqData	=	System::addGroup(array('name'=>$name, 'status'=>$status, 'op_userid'=>$this->user['userid'], 'op_email'=>$this->user['email']));
					if( empty($reqData) || !isset($reqData['ret']) ){
						$ret_array['ret']	=	1;
						$ret_array['msg']		=	'服务器忙，请稍后再试';
						break;
					}
	
					if( 0 != $reqData['ret'] ){
						$ret_array	=	$reqData;
						break;
					}
					$ret_array['msg']		=	'添加用户组成功';
				}
				
				$ret_array['ret']	=	0;
			}catch(Exception $e){
				$ret_array['ret']	=	13;
				$ret_array['msg']		=	'程序运行中出现异常';
				$ret_array['error']		=	$e->getMessage();
			}
		}while(0);
		$this->render('system/groupadd', $ret_array);
	}
	
	/**
	 * 修改用户组页面
	 */
	function actionGroupMod(){
		$this->checkUserStatus( array('url'=>'system/groupmod') );
		$ret_array	=	array('ret'=>1, 'msg'=>'', 'occur'=>'system_actionGroupMod', 'error'=>'', 'data'=>array());
		do{
			try{
				if( isset( $_POST['Name']) ){
					$id			=	Common::getParams('Id',false,'POST');
					$name		=	Common::getParams('Name',false,'POST');
					$status		=	Common::getParams('Status',false,'POST');
					
					$reqData	=	System::modGroup(array('id'=>$id, 'name'=>$name, 'status'=>$status, 'op_userid'=>$this->user['userid'], 'op_email'=>$this->user['email']));
					if( empty($reqData) || !isset($reqData['ret']) ){
						$ret_array['ret']	=	1;
						$ret_array['msg']		=	'服务器忙，请稍后再试';
						break;
					}
	
					if( 0 != $reqData['ret'] ){
						$ret_array	=	$reqData;
						break;
					}
					$ret_array['msg']		=	'修改用户组成功';
				}
				
				$reqData	=	System::getGroupList(array('canmc'=>false, 'id'=>$_GET['Id']));
				if( empty($reqData) || !isset($reqData['ret']) ){
					$ret_array['ret']	=	1;
					$ret_array['msg']		=	'服务器忙，请稍后再试';
					break;
				}
					
				if( 0 != $reqData['ret'] ){
					$ret_array	=	$reqData;
					break;
				}
				if( empty($reqData['data']) ){
					$ret_array['ret']	=	5;
					$ret_array['msg']		=	'用户组不存在';
					break;
				}
				$groupinfo	=	array_shift($reqData['data']);
				
				$ret_array['ret']	=	0;
				$ret_array['data']		=	array('groupinfo'=>$groupinfo);
			}catch(Exception $e){
				$ret_array['ret']	=	13;
				$ret_array['msg']		=	'程序运行中出现异常';
				$ret_array['error']		=	$e->getMessage();
			}
		}while(0);
		Common::toTxt(array('file'=>'Log_SystemController_actionGroupMod.txt', 'txt'=>'Input:'.var_export($_POST, true).'|Output:'.var_export($ret_array, true)));
		$this->render('system/groupmod', $ret_array);
	}

	/**
	 * 删除用户组
	 */
	function actionGroupDel(){
		$ret_array	=	array('ret'=>1, 'msg'=>'', 'occur'=>'system_actionGroupDel', 'error'=>'', 'data'=>array());
		$this->checkUserStatus( array('url'=>'system/grouplist', 'type'=>'json') );
		do{
			try{
				if( isset( $_GET['Id']) ){//用户组下面没有用户才可以删除
					$id			=	intval($_GET['Id']);
					if( $id < 1 ){
						$ret_array['ret']	=	1;
						$ret_array['msg']		=	'参数错误';
						break;
					}
					
					$reqData	=	System::delGroup(array('id'=>$id, 'op_userid'=>$this->user['userid'], 'op_email'=>$this->user['email']));
					if( empty($reqData) || !isset($reqData['ret']) ){
						$ret_array['ret']	=	1;
						$ret_array['msg']		=	'服务器忙，请稍后再试';
						break;
					}
					
					if( 0 != $reqData['ret'] ){
						$ret_array	=	$reqData;
						break;
					}
					$ret_array['msg']		=	'用户组删除成功';
				}
				$ret_array['ret']	=	0;
			}catch(Exception $e){
				$ret_array['ret']	=	13;
				$ret_array['msg']		=	'程序运行中出现异常';
				$ret_array['error']		=	$e->getMessage();
			}
		}while(0);
		Common::jsalerturl($ret_array['msg'], $this->createUrl('system/grouplist'));
	}
	
	/**
	 * 权限列表页面
	 */
	function actionPowerList(){
		$this->checkUserStatus( array('url'=>'system/powerlist') );
		$ret_array	=	array('ret'=>1, 'msg'=>'', 'occur'=>'system_actionPowerList', 'error'=>'', 'data'=>array());
		do{
			try{
				$parentid	=	Common::getParams('Parentid',0,'');
				$powerid	=	Common::getParams('Id',0,'');
				if( 0 == $parentid && $powerid > 0 ){
					$parentid	=	$powerid;
				}
				$reqData	=	System::getPowerList(array('canmc'=>false));
				if( empty($reqData) || !isset($reqData['ret']) ){
					$ret_array['ret']	=	1;
					$ret_array['msg']		=	'服务器忙，请稍后再试';
					break;
				}
					
				if( 0 != $reqData['ret'] ){
					$ret_array	=	$reqData;
					break;
				}
				
				$datalist	=	$reqData['data'];
				$powerlist	=	array();
				$topmenu	=	false;
				
				
				foreach( $datalist as $item){
					if( $parentid == $item['id'] ){
						$topmenu	=	$item;
						break;
					}
				}
				
				foreach( $datalist as $item){
					if( $parentid == $item['parentid'] ){
						$powerlist[$item['id']]	=	$item;
					}
				}
				
				$ret_array['ret']	=	0;
				$ret_array['data']		=	array('powerlist'=>$powerlist, 'topmenu'=>$topmenu, 'parentid'=>$parentid);
			}catch(Exception $e){
				$ret_array['ret']	=	13;
				$ret_array['msg']		=	'程序运行中出现异常';
				$ret_array['error']		=	$e->getMessage();
			}
		}while(0);
		$this->render('system/powerlist', $ret_array);
	}
	
	/**
	 * 添加权限页面
	 */
	function actionPowerAdd(){
		$this->checkUserStatus( array('url'=>'system/poweradd') );
		$ret_array	=	array('ret'=>1, 'msg'=>'', 'occur'=>'system_actionPowerAdd', 'error'=>'', 'data'=>array());
		do{
			try{
				$parentid		=	Common::getParams('Parentid', 0, 'GET');//URL上传递的
				if( isset( $_POST['Pname']) ){
					$pname		=	Common::getParams('Pname',false,'POST');
					$action		=	Common::getParams('Action',false,'POST');
					$status		=	Common::getParams('Status',1,'POST');
					$parentid	=	Common::getParams('Parentid',0,'POST');
					$weight		=	Common::getParams('Weight',10000,'POST');
	
					$reqData	=	System::addPower(array('pname'=>$pname, 'action'=>$action, 'status'=>$status, 'parentid'=>$parentid, 'weight'=>$weight, 'op_userid'=>$this->user['userid'], 'op_email'=>$this->user['email']));
					if( empty($reqData) || !isset($reqData['ret']) ){
						$ret_array['ret']	=	1;
						$ret_array['msg']		=	'服务器忙，请稍后再试';
						break;
					}
	
					if( 0 != $reqData['ret'] ){
						$ret_array	=	$reqData;
						break;
					}
					$ret_array['msg']		=	'添加权限成功';
				}
	
				$reqData	=	System::getPowerList(array('canmc'=>false));
				if( empty($reqData) || !isset($reqData['ret']) ){
					$ret_array['ret']	=	1;
					$ret_array['msg']		=	'服务器忙，请稍后再试';
					break;
				}
					
				if( 0 != $reqData['ret'] ){
					$ret_array	=	$reqData;
					break;
				}
				$powerlist	=	$reqData['data'];
	
				$ret_array['ret']	=	0;
				$ret_array['data']		=	array('powerlist'=>$powerlist, 'parentid'=>$parentid);
			}catch(Exception $e){
				$ret_array['ret']	=	13;
				$ret_array['msg']		=	'程序运行中出现异常';
				$ret_array['error']		=	$e->getMessage();
			}
		}while(0);
		$this->render('system/poweradd', $ret_array);
	}
	
	/**
	 * 修改权限页面
	 */
	function actionPowerMod(){
		$this->checkUserStatus( array('url'=>'system/powermod') );
		$ret_array	=	array('ret'=>1, 'msg'=>'', 'occur'=>'system_actionUserMod', 'error'=>'', 'data'=>array());
		do{
			try{
				if( isset( $_POST['Action']) &&  isset( $_POST['Status']) ){
					$id			=	Common::getParams('Id',false,'POST');
					$action		=	Common::getParams('Action',false,'POST');
					$parentid	=	Common::getParams('Parentid',false,'POST');
					$status		=	Common::getParams('Status',1,'POST');
					$weight		=	Common::getParams('Weight',10000,'POST');
						
					$powerconf	=	require BASE_DIR.'/protected/config/powerconf.php';
					if( !isset($powerconf[$action]) ){
						$ret_array['ret']	=	1;
						$ret_array['msg']		=	'权限配置文件没有对应信息';
						break;
					}
					$pname		=	$powerconf[$action]['name'];
					if( 0 == $powerconf[$action]['canshow'] ){
						$weight	=	0;
					}
	
					$reqData	=	System::modPower(array('id'=>$id, 'pname'=>$pname, 'action'=>$action, 'parentid'=>$parentid, 'status'=>$status, 'weight'=>$weight, 'op_userid'=>$this->user['userid'], 'op_email'=>$this->user['email']));
					if( empty($reqData) || !isset($reqData['ret']) ){
						$ret_array['ret']	=	1;
						$ret_array['msg']		=	'服务器忙，请稍后再试';
						break;
					}
					$ret_array	=	$reqData;
				}
	
				$reqData	=	System::getPowerList(array('canmc'=>false, 'id'=>$_GET['Id']));
				if( empty($reqData) || !isset($reqData['ret']) ){
					$ret_array['ret']	=	1;
					$ret_array['msg']		=	'服务器忙，请稍后再试';
					break;
				}
					
				if( 0 != $reqData['ret'] ){
					$ret_array	=	$reqData;
					break;
				}
				if( empty($reqData['data']) ){
					$ret_array['ret']	=	5;
					$ret_array['msg']		=	'用户不存在';
					break;
				}
	
				$powerinfo	=	array_shift($reqData['data']);
	
				$ret_array['ret']	=	0;
				$ret_array['data']		=	array('powerinfo'=>$powerinfo);
			}catch(Exception $e){
				$ret_array['ret']	=	13;
				$ret_array['msg']		=	'程序运行中出现异常';
				$ret_array['error']		=	$e->getMessage();
			}
		}while(0);
		//Core::toTxt(array('file'=>'Log_systemController_actionUserMod.txt', 'txt'=>'Input:'.var_export($_POST, true).'|Output:'.var_export($ret_array, true)));
		$this->render('system/powermod', $ret_array);
	}

	/**
	 * 组权限列表页面
	 */
	function actionGroupPowerList(){
		$this->checkUserStatus( array('url'=>'system/grouppowerlist') );
		$ret_array	=	array('ret'=>1, 'msg'=>'', 'occur'=>'system_actionGroupPowerList', 'error'=>'', 'data'=>array());
		do{
			try{
				$gid		=	Common::getParams('Gid',false,'');
				$pid		=	Common::getParams('Pid',false,'');
				
				if( false !== $pid ){
					$reqData	=	System::getGroupPowerList(array('canmc'=>false,'id'=>$pid));
					if( empty($reqData) || !isset($reqData['ret']) ){
						$ret_array['ret']	=	1;
						$ret_array['msg']		=	'服务器忙，请稍后再试';
						break;
					}
						
					if( 0 != $reqData['ret'] ){
						$ret_array	=	$reqData;
						break;
					}
					$groupinfo	=	array_shift($reqData['data']);
					
					$reqData	=	System::getPowerList(array('canmc'=>false,'parentid'=>$pid));
					if( empty($reqData) || !isset($reqData['ret']) ){
						$ret_array['ret']	=	1;
						$ret_array['msg']		=	'服务器忙，请稍后再试';
						break;
					}
					
					if( 0 != $reqData['ret'] ){
						$ret_array	=	$reqData;
						break;
					}
					$powerlist	=	$reqData['data'];
					
					$ret_array['ret']	=	0;
					$ret_array['data']		=	array('groupinfo'=>$groupinfo,'powerlist'=>$powerlist);
				}else if( false !== $gid ){
					$reqData	=	System::getGroupPowerList(array('canmc'=>false,'groupid'=>$gid));
					if( empty($reqData) || !isset($reqData['ret']) ){
						$ret_array['ret']	=	1;
						$ret_array['msg']		=	'服务器忙，请稍后再试';
						break;
					}
					
					if( 0 != $reqData['ret'] ){
						$ret_array	=	$reqData;
						break;
					}
					$grouppowerlist	=	$reqData['data'];
					
					$ret_array['ret']	=	0;
					$ret_array['data']		=	array('grouppowerlist'=>$grouppowerlist);
				}else{
					$reqData	=	System::getGroupList(array('canmc'=>false));
					if( empty($reqData) || !isset($reqData['ret']) ){
						$ret_array['ret']	=	1;
						$ret_array['msg']		=	'服务器忙，请稍后再试';
						break;
					}
						
					if( 0 != $reqData['ret'] ){
						$ret_array	=	$reqData;
						break;
					}
					$grouplist	=	$reqData['data'];
		
					$ret_array['ret']	=	0;
					$ret_array['data']		=	array('grouplist'=>$grouplist);
				}
			}catch(Exception $e){
				$ret_array['ret']	=	13;
				$ret_array['msg']		=	'程序运行中出现异常';
				$ret_array['error']		=	$e->getMessage();
			}
		}while(0);
		$this->render('system/grouppowerlist', $ret_array);
	}
	
	/**
	 * 添加组权限页面
	 */
	function actionGroupPowerAdd(){
		$this->checkUserStatus( array('url'=>'system/grouppoweradd') );
		$ret_array	=	array('ret'=>1, 'msg'=>'', 'occur'=>'system_actionGroupPowerAdd', 'error'=>'', 'data'=>array());
		do{
			try{	
				if( isset( $_POST['Pname']) && isset( $_POST['Groupid']) ){//添加权限组
					$groupid	=	Common::getParams('Groupid',false,'POST');
					$weight		=	Common::getParams('Weight',false,'POST');
					$pname		=	Common::getParams('Pname',false,'POST');
					
					$reqData	=	System::addGroupPower(array('groupid'=>$groupid, 'pname'=>$pname, 'weight'=>$weight, 'op_userid'=>$this->user['userid'], 'op_email'=>$this->user['email']));
					if( empty($reqData) || !isset($reqData['ret']) ){
						$ret_array['msg']	=	'服务器忙，请稍后再试';
					}else if( 0 != $reqData['ret'] ){
						$ret_array['msg']	=	$reqData['msg'];
					}else{
						$ret_array['msg']	=	'添加权限组成功';
					}
				}
				if( isset( $_POST['Action']) && isset( $_POST['Parentid']) ){//添加权限项
					$action		=	Common::getParams('Action',false,'POST');
					$parentid	=	Common::getParams('Parentid',false,'POST');
					$status		=	Common::getParams('Status',false,'POST');
					$weight		=	Common::getParams('Weight',false,'POST');
					
					$powerconf	=	require BASE_DIR.'/protected/config/powerconf.php';
					if( !isset($powerconf[$action]) ){
						$ret_array['ret']	=	1;
						$ret_array['msg']		=	'权限配置文件没有对应信息';
						break;
					}
					$pname		=	$powerconf[$action]['name'];
					
					if( 0 == $powerconf[$action]['canshow'] ){
						$weight	=	0;
					}
					
					$reqData	=	System::addPower(array('pname'=>$pname, 'action'=>$action, 'status'=>$status, 'parentid'=>$parentid, 'weight'=>$weight, 'op_userid'=>$this->user['userid'], 'op_email'=>$this->user['email']));
					if( empty($reqData) || !isset($reqData['ret']) ){
						$ret_array['msg']	=	'服务器忙，请稍后再试';
					}else if( 0 != $reqData['ret'] ){
						$ret_array['msg']	=	$reqData['msg'];
					}else{
						$ret_array['msg']	=	'添加权限项成功';
					}
				}
				if( isset($_GET['Pid']) ){//添加用户组下的权限组
					
				}
				if(isset($reqData) && isset($reqData['ret'])){
					$gid	=	isset($_GET['Gid']) ? isset($_GET['Gid']) : 0;
					$pid	=	isset($_GET['Pid']) ? isset($_GET['Pid']) : 0;
					
					if(!empty($gid)){
						$url	=	$this->createUrl('system/grouppowerlist', array('Gid'=>$gid));
						$this->redirect($url);
						exit;
					}elseif(!empty($pid)){
						$url	=	$this->createUrl('system/grouppowerlist', array('Pid'=>$pid));
						$this->redirect($url);
						exit;
					}
				}
				
				$ret_array['ret']	=	0;
			}catch(Exception $e){
				$ret_array['ret']	=	13;
				$ret_array['msg']		=	'程序运行中出现异常';
				$ret_array['error']		=	$e->getMessage();
			}
		}while(0);
		$this->render('system/grouppoweradd', $ret_array);
	}
	
	/**
	 * 修改组权限页面
	 */
	function actionGroupPowerMod(){
		$this->checkUserStatus( array('url'=>'system/grouppowerlist') );
		$ret_array	=	array('ret'=>1, 'msg'=>'', 'occur'=>'system_actionUserMod', 'error'=>'', 'data'=>array());
		do{
			try{	
				if( isset( $_POST['Pname']) && isset($_POST['Id']) ){
					$id			=	Common::getParams('Id',false,'POST');
					$weight		=	Common::getParams('Weight',false,'POST');
					$pname		=	Common::getParams('Pname',false,'POST');
					
					$reqData	=	System::modGroupPower(array('id'=>$id, 'weight'=>$weight, 'pname'=>$pname, 'op_userid'=>$this->user['userid'], 'op_email'=>$this->user['email']));
					if( empty($reqData) || !isset($reqData['ret']) ){
						$ret_array['msg']	=	'服务器忙，请稍后再试';
					}else if( 0 != $reqData['ret'] ){
						$ret_array['msg']	=	$reqData['msg'];
					}else{
						$ret_array['msg']	=	'修改权限组成功';
					}
				}
	
				$reqData	=	System::getGroupPowerList(array('canmc'=>false, 'id'=>$_GET['Pid']));
				if( empty($reqData) || !isset($reqData['ret']) ){
					$ret_array['ret']	=	1;
					$ret_array['msg']		=	'服务器忙，请稍后再试';
					break;
				}
					
				if( 0 != $reqData['ret'] ){
					$ret_array	=	$reqData;
					break;
				}
				if( empty($reqData['data']) ){
					$ret_array['ret']	=	5;
					$ret_array['msg']		=	'权限组不存在';
					break;
				}
				$grouppowerinfo	=	array_shift($reqData['data']);
	
				$ret_array['ret']	=	0;
				$ret_array['data']		=	array('grouppowerinfo'=>$grouppowerinfo);
			}catch(Exception $e){
				$ret_array['ret']	=	13;
				$ret_array['msg']		=	'程序运行中出现异常';
				$ret_array['error']		=	$e->getMessage();
			}
		}while(0);
		//Common::toTxt(array('file'=>'Log_SystemController_actionUserMod.txt', 'txt'=>'Input:'.var_export($_POST, true).'|Output:'.var_export($ret_array, true)));
		$this->render('system/grouppowermod', $ret_array);
	}
	
	/**
	 * 删除组权限页面
	 */
	function actionGroupPowerDel(){
		$ret_array	=	array('ret'=>1, 'msg'=>'', 'occur'=>'system_actionGroupDel', 'error'=>'', 'data'=>array());
		$this->checkUserStatus( array('url'=>'system/grouppowerlist', 'type'=>'json') );
		do{
			try{
				$jumpurl	=	$this->createUrl('system/grouppowerlist');
				if( isset( $_GET['Id']) ){//用户组下面没有用户才可以删除
					$id			=	intval($_GET['Id']);
					if( $id < 1 ){
						$ret_array['ret']	=	1;
						$ret_array['msg']		=	'参数错误';
						break;
					}
					
					$reqData	=	System::getPowerList(array('canmc'=>false, 'id'=>$id));
					if( empty($reqData) || !isset($reqData['ret']) ){
						$ret_array['ret']	=	1;
						$ret_array['msg']		=	'服务器忙，请稍后再试';
						break;
					}
						
					if( 0 != $reqData['ret'] ){
						$ret_array	=	$reqData;
						break;
					}
					if( 0 == count($reqData['data']) ){//用户组没有配置权限
						$ret_array['ret']	=	1;
						$ret_array['msg']		=	'权限项不存在';
						break;
					}
					$powerinfo	=	array_shift($reqData['data']);
					
					$tmp_str	=	strpos($jumpurl, '?') ? '&' : '?';
					$jumpurl	.=	$tmp_str.'Pid='.$powerinfo['parentid'];
					
					$reqData	=	System::delGroupPower(array('id'=>$id, 'op_userid'=>$this->user['userid'], 'op_email'=>$this->user['email']));
					if( empty($reqData) || !isset($reqData['ret']) ){
						$ret_array['ret']	=	1;
						$ret_array['msg']		=	'服务器忙，请稍后再试';
						break;
					}
					
					if( 0 != $reqData['ret'] ){
						$ret_array	=	$reqData;
						break;
					}
					$ret_array['msg']		=	'权限项删除成功';
					
				}else if( isset( $_GET['Pid']) ){
					$pid			=	intval($_GET['Pid']);
					if( $pid < 1 ){
						$ret_array['ret']	=	1;
						$ret_array['msg']		=	'参数错误';
						break;
					}
					
					$reqData	=	System::getGroupPowerList(array('canmc'=>false, 'id'=>$pid));
					if( empty($reqData) || !isset($reqData['ret']) ){
						$ret_array['ret']	=	1;
						$ret_array['msg']		=	'服务器忙，请稍后再试';
						break;
					}
					
					if( 0 != $reqData['ret'] ){
						$ret_array	=	$reqData;
						break;
					}
					if( 0 == count($reqData['data']) ){//用户组没有配置权限
						$ret_array['ret']	=	1;
						$ret_array['msg']		=	'权限组不存在';
						break;
					}
					$grouppowerinfo	=	array_shift($reqData['data']);
					
					$tmp_str	=	strpos($jumpurl, '?') ? '&' : '?';
					$jumpurl	.=	$tmp_str.'Gid='.$grouppowerinfo['id'];
					
					$reqData	=	System::delGroupPower(array('pid'=>$pid));
					if( empty($reqData) || !isset($reqData['ret']) ){
						$ret_array['ret']	=	1;
						$ret_array['msg']		=	'服务器忙，请稍后再试';
						break;
					}
						
					if( 0 != $reqData['ret'] ){
						$ret_array	=	$reqData;
						break;
					}
					$ret_array['msg']		=	'权限组删除成功';
					
				}else{
					
				}
				$ret_array['ret']	=	0;
			}catch(Exception $e){
				$ret_array['ret']	=	13;
				$ret_array['msg']		=	'程序运行中出现异常';
				$ret_array['error']		=	$e->getMessage();
			}
		}while(0);
		Common::jsalerturl($ret_array['msg'], $jumpurl);
	}

	/**
	 * 系统日志页面
	 */
	function actionSystemLog(){
		$ret_array	=	array('ret'=>1, 'msg'=>'', 'occur'=>'system_actionSystemLog', 'error'=>'', 'data'=>array());
		do{
			try{
				$reqData	=	System::getUserList(array('canmc'=>false));
				if( empty($reqData) || !isset($reqData['ret']) ){
					$ret_array['ret']	=	1;
					$ret_array['msg']		=	'服务器忙，请稍后再试';
					break;
				}
					
				if( 0 != $reqData['ret'] ){
					$ret_array	=	$reqData;
					break;
				}
				$userlist	=	$reqData['data'];
				
				$page		=	Common::getParams('page',false, 1);
				$page		=	intval($page);
				$size		=	30;
				$userid		=	false;
				$stime		=	false;
				$etime		=	false;
				$jpurl		=	$this->createUrl('system/systemlog');
				$jparams	=	array();
				
				if( isset( $_GET['userid']) && 0 < intval($_GET['userid']) ){
					$userid		=	intval($_GET['userid']);
					$jparams[]	=	'userid='.$userid;
				}
				
				if( isset( $_GET['stime']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $_GET['stime']) ){
					$stime		=	$_GET['stime'];
					$jparams[]	=	'stime='.$stime;
				}
				
				if( isset( $_GET['etime']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $_GET['etime']) ){
					$etime		=	$_GET['etime'];
					$jparams[]	=	'etime='.$etime;
				}
				
				if( false != $stime && false != $etime && $stime > $etime ){
					$stime		=	$_GET['etime'];
					$etime		=	$_GET['stime'];
				}
				if( 0 < count($jparams) ){
					$tmp_str	=	strpos($jpurl, '?') ? '&' : '?';
					$jpurl		.=	$tmp_str.join('&', $jparams);
				}
				
				
				$reqData	=	System::getSystemLog(array('page'=>$page, 'size'=>$size, 'userid'=>$userid, 'stime'=>$stime, 'etime'=>$etime));
				if( empty($reqData) || !isset($reqData['ret']) ){
					$ret_array['ret']	=	1;
					$ret_array['msg']		=	'服务器忙，请稍后再试';
					break;
				}
					
				if( 0 != $reqData['ret'] ){
					$ret_array	=	$reqData;
					break;
				}
				$datalist	=	$reqData['data'];
				
				$page_obj	=	new Page();
				$fenyecode	=	$page_obj->createPage( array('url'=>$jpurl, 'size'=>$datalist['total'], 'page'=>$page, 'pageSize'=>$size) );
				
				$datalist['userid']		=	$userid;
				$datalist['stime']		=	$stime;
				$datalist['etime']		=	$etime;
				$datalist['page']		=	$page;
				$datalist['size']		=	$size;
				$datalist['fenye']		=	$fenyecode;
				$datalist['userlist']	=	$userlist;
					
				$ret_array['ret']	=	0;
				$ret_array['data']		=	$datalist;
				
			}catch(Exception $e){
				$ret_array['ret']	=	13;
				$ret_array['msg']		=	'程序运行中出现异常';
				$ret_array['error']		=	$e->getMessage();
			}
		}while(0);
		$this->render('system/systemlog', $ret_array);
	}

	/**
	 * 系统参数配置
	 */
	function actionConfig(){
		$ret_array	=	array('ret' => -1, 'msg' => '' );
		do{
			try{
				$configfile	=	BASE_DIR.'/protected/config/systemconfig.php';
				if( file_exists($configfile) ){
					$systemconfig	=	require $configfile;
				}else{
					$systemconfig	=	array();
				}
				$userid		=	$this->user['userid'];
		
				if( isset( $_POST[ 'Feerate' ] ) || isset( $_POST[ 'ServiceNumber' ] ) ){//提交了
					if( USER_GROUP_ADMIN != $this->user['groupid'] ){
						$ret_array['ret']	=	1;
						$ret_array['msg']	=	'您没有设定系统参数的权限';
						break;
					}
					
					$changeitem	=	array();
					foreach( $_POST as $name => $val ){
						$name	=	strtolower($name);
						$val	=	trim($val);
						if( isset($systemconfig[$name]) && $val == $systemconfig[$name] ){
							continue;
						}
						$changeitem[]	=	$name.':'.$val;
						$systemconfig[$name] = $val;
					}
					if( count($changeitem) > 0 ){
						$content	=	'<?php return '.var_export($systemconfig, true).';?>';
						if( !file_put_contents($configfile, $content) ){
							$ret_array['ret']	=	1;
							$ret_array['msg']	=	'保存系统配置数据失败，请重试';
							break;
						}
						
						Common::toTxt( array('file'=>'Log_SyncController_actionConfig.txt', 'txt' => 'Uid:'.$userid.'，修改配置信息为: '.join('|',$changeitem) ) );
					}
					$ret_array['msg']	=	'参数设定成功';
				}
		
				$ret_array['ret']	=	0;
			}catch ( Exception $e ){
				$ret_array['ret']	=	13;
				$ret_array['msg']	=	$e->getMessage();;
			}
		}while(0);
		$ret_array['data']	=	$systemconfig;
		$this->render('system/config', $ret_array );
	}

	//个人信息
	public function actionPersonEdit(){
		$data = array('msg'=>'','ret'=>0,'data'=> '');
		$user_id = $this->user['userid'];
		do{
			$company = Yii::app()->request->getParam('company');
			$phone = Yii::app()->request->getParam('phone');
			$address = Yii::app()->request->getParam('address1');
			$postback = Yii::app()->request->getParam('postback');
			if(!empty($company)){
				$postback = empty($postback) ? '' : $postback;
				$address = empty($address) ? '' : $address;
				$phone = empty($phone) ? '' : $phone;
				$data_update['company'] = $company;
				$data_update['phone'] = $phone;
				$data_update['address'] = $address;
				$data_update['postback'] = $postback;
				if(!JoySystemUser::model()->updateByPk($user_id,$data_update)){
					$data['ret'] = 0;
					$data['msg'] = 'update error!';
					break;
				}
				$data['ret'] = 1;
				$data['msg'] = 'update success!';
			}
		}while(0);
		$data['affiliate'] = JoySystemUser::model()->findByPk($user_id);
		$this->render('system/personedit',$data);
	}

	//收发邮件
	public function actionSendEmail(){
		$this->render('system/send_email');
	}

	public function actionMessage(){
		$msg_id = Yii::app()->request->getParam('msg_id');
		if($msg_id){
			$userid = $this->user['userid'];
			$data['message'] = JoySystemMessage::model()->findByPk($msg_id);
			$data['send_name'] = JoyMessageMgr::getSendName($msg_id);
			//将这个msg状态设置为1
			$msg_mgr = JoyMessageMgr::model()->findByAttributes(array('sendid'=>$userid,'msgid'=>$msg_id));
			$msg_mgr->status = 1;
			$msg_mgr->update();
			$this->render('system/message_detail',$data);
			die();
		}
		//查询是否mgr中有未读过的信息
		$cdb = new CDbCriteria();
		$cdb->addCondition('sendid=' . $this->user['userid']);
		$cdb->order = 'status Asc';
		$data['messages'] = JoyMessageMgr::model()->findAll($cdb);
		$this->render('system/message',$data);
	}

	public function actionAddMessage(){
		$select_type = Yii::app()->request->getParam('type');
		$message_type = Yii::app()->request->getParam('msg_type');
		$params['content'] = Yii::app()->request->getParam('content');
		$params['title'] = Yii::app()->request->getParam('title');
		$params['sendids'] = Yii::app()->request->getParam('fromName');
		$params['affid'] = Yii::app()->request->getParam('affid');
		$params['back_url'] = Yii::app()->request->getParam('back_url');
		$params['content'] = Yii::app()->request->getParam('content');
		$params['task_type'] = Yii::app()->request->getParam('task_type');
		$params['content_input'] = Yii::app()->request->getParam('content_input');
		if(!isset($message_type)){
			$message_type = $select_type;
		}
		if(isset($select_type)){
			$data['msg'] = JoyMessageMgr::sendMessage($select_type,$this->user['userid'],$params['sendids'],$params);
		}
		$data['groups']  = JoySystemGroup::model()->findAll();
		//查询所有用户
		foreach ($data['groups'] as $group) {
			$criteria = new CDbCriteria();
			$criteria->addCondition("groupid = {$group['id']}");
			$data['users'][$group['name']] = JoySystemUser::model()->findAll($criteria);
		}
		if(0 == $message_type){
			$this->render('system/system_notice', $data);
		}else if(1 == $message_type){
			$this->render('system/channel_notice', $data);
		}

	}

	public  function actionTimeStamp(){
		$cdb = new CDbCriteria();
		$cdb->order = 't.createtime';
		if($this->user['groupid'] != 1){
			$cdb->addCondition("userid = {$this->user['userid']}");
		}
		$record = JoyRecord::model()->with('users','recordTypes')->findAll($cdb);
		$time_arr = array();
		foreach($record as $listen){
			$key = $listen['createtime'];
			if(array_key_exists($key,$time_arr)){
				array_push($time_arr[$key],$listen['content']);
			}else{
				$time_arr[$key][0] = $listen['content'];
			}
		}
		$this->render('helper/time_sort',array(
			'records'=>$time_arr,
		));
	}
}
?>
