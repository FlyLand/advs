<?php
/** 
 * 系统管理逻辑
 */
class System{
	public static $tbluser			= 	'joy_system_user';
	public static $tblpower 		= 	'joy_system_power';
	public static $tblgroup 		= 	'joy_system_group';
	public static $tblgrouppower 	= 	'joy_system_grouppower';
	public static $tblsystemlog 	= 	'joy_system_log';
	
	/**
	 * 通过用户ID获取用户信息
	 */
	public static function getUserById($params){
		$ret_array = array('ret'=>1, 'msg'=>'', 'occur'=>'System_getUserById', 'error'=>'', 'data'=>'');
		do{
			try{
				if( !is_array($params) || !isset($params['userid']) || !preg_match('/^\d+$/',$params['userid']) ){
					$ret_array['ret']	=	1;
					$ret_array['msg']	=	'调用参数错误';
					break;
				}
				$userid = $params['userid'];
				
				$connect	=	CfgAR::setDbLink('db');
				if( '-3306' == $connect ){
					$ret_array['ret']	=	101;
					$ret_array['msg']	=	'服务器忙，请稍后再试';
					break;
				}
				
				$select_sql	=	sprintf("SELECT * FROM %s WHERE id = %u ",  self::$tbluser, $userid);
				$command	=	$connect->createCommand($select_sql);
				$data		=	$command->queryRow();//可能false
				
				$ret_array['ret']		=	0;
				$ret_array['data']		=	$data;
	
			}catch(Exception $e){
				$ret_array['ret'] = 100;
				$ret_array['msg'] = '服务器忙，请稍后再试';
				$ret_array['error'] = $e->getMessage();
				break;
			}
		}while(0);
		if( 0 != $ret_array['ret']){
			Common::toTxt(array('file'=>'Log_System_getUserById.txt', 'txt'=>'Input:'.var_export($params, true).'|Output:'.var_export($ret_array, true)));
		}
		return $ret_array;
	
	}
	
	/**
	 * 通过用户名获取用户信息
	 */
	public static function getUserByEmail($params){
		$ret_array = array('ret'=>1, 'msg'=>'', 'occur'=>'System_getUserByEmail', 'error'=>'', 'data'=>'');
		do{
			try{
				if( !is_array($params) || !isset($params['email']) ){
					$ret_array['ret']	=	1;
					$ret_array['msg']	=	'调用参数错误';
					break;
				}
				$email = trim($params['email']);
								
				$connect	=	CfgAR::setDbLink('db');
				if( '-3306' == $connect ){
					$ret_array['ret']	=	101;
					$ret_array['msg']	=	'服务器忙，请稍后再试';
					break;
				}
				
				$selectsql	=	sprintf("SELECT * FROM %s WHERE email = '%s' ",  self::$tbluser, $email);
				$command	=	$connect->createCommand($selectsql);
				$data		=	$command->queryRow();//可能false
				
				$ret_array['ret']		=	0;
				$ret_array['data']		=	$data;
	
			}catch(Exception $e){
				$ret_array['ret'] = 100;
				$ret_array['msg'] = '服务器忙，请稍后再试';
				$ret_array['error'] = $e->getMessage();
			}
		}while(0);
		if( 0 != $ret_array['ret']){
			Common::toTxt(array('file'=>'Log_getUserByEmail.txt', 'txt'=>'Input:'.var_export($params, true).'|Output:'.var_export($ret_array, true)));
		}
		return $ret_array;
	
	}
	
	/**
	 * 用户登陆验证逻辑
	 */
	public static function checkUserLogin($params){
		$ret_array = array('ret'=>1, 'msg'=>'', 'occur'=>'System_checkUserLogin', 'error'=>'', 'data'=>'');
		do{
			try{
				if( !is_array($params) ||  !isset($params['email']) || !isset($params['password']) ){
					$ret_array['ret']	= 	1;
					$ret_array['msg']	= 	'缺少调用参数';
					break;
				}
				$email		=	trim($params['email']);
				$password	=	trim($params['password']);
				$loginip 	=	isset($params['loginip'])	? trim($params['loginip']) : Common::getIp();
				$group	=	trim($params['group']);
				$title	=	trim($params['title']);
				if($group == 1){
					$email = $email . $title;
				}
				$reqData	= 	self::getUserByEmail(array('email'=>$email));
				if( empty($reqData) || !isset($reqData['ret']) ){
					$ret_array['ret']	= 	101;
					$ret_array['msg']	= 	'服务器忙，请稍后再试';
					break;
				}
				if( 0 != $reqData['ret']	){
					$ret_array	= 	$reqData;
					break;
				}
				if( empty($reqData['data']) ){
					$ret_array['ret']	= 	3;
					$ret_array['msg']	= 	'输入的用户未注册！';
					break;
				}
				$userinfo	=	$reqData['data'];
				if( 0 == $userinfo['status'] ){
					$ret_array['ret']	= 	4;
					$ret_array['msg'] 	= 	'账号已被禁用！';
					break;
				}
				
				$correct	=	md5($password);
				if( $correct != $userinfo['password'] ){
					$ret_array['ret'] 	= 	5;
					$ret_array['msg'] 	= 	'输入的密码错误！';
					break;
				}
				
				//更新登录记录
				$connect	=	CfgAR::setDbLink('db');
				if( '-3306' == $connect ){
					$ret_array['ret']	=	102;
					$ret_array['msg']		=	'服务器忙，请稍后再试';
					break;
				}
				
				$logintime 	= 	date('Y-m-d H:i:s');
				$update_sql	=	sprintf("UPDATE %s SET loginip='%s', lastlogin='%s', logincount=logincount+1 WHERE id=%u ", self::$tbluser, $loginip, $logintime, $userinfo['id']);
				$command	=	$connect->createCommand($update_sql);
				$effectrow	=	$command->execute();
				
				if( 0 == $effectrow ){
					$ret_array['ret']	=	103;
					$ret_array['msg']	=	'服务器忙，请稍后再试';
					break;
				}
				
				$remark = '登录系统';
				self::systemLog(array('userid'=>$userinfo['id'],'email'=>$email, 'itype'=>10001, 'stype'=>'login', 'remark'=>$remark));
				
				$ret_array['ret']	= 0;
				$ret_array['data']	= $userinfo;
			}catch(Exception $e){
				$ret_array['ret']	= 100;
				$ret_array['msg']	= '服务器忙，请稍后再试';
				$ret_array['error']	= $e->getMessage();
				break;
			}
		}while(0);
		if( 0 != $ret_array['ret']){
			Common::toTxt(array('file'=>'Log_System_checkUserLogin.txt', 'txt'=>'Input:'.var_export($params, true).'|Output:'.var_export($ret_array, true)));
		}
		return $ret_array;
		
	}
	
	/**
	 * 检查是否有开户权限
	 */
	public static function checkOpenPower($params){
		$ret_array = array('ret'=>1, 'msg'=>'', 'occur'=>'System_checkOpenPower', 'error'=>'', 'data'=>'');
		do{
			try{
				if( !is_array($params) ||  !isset($params['userid']) ){
					$ret_array['ret']	= 	1;
					$ret_array['msg']	= 	'缺少调用参数';
					break;
				}
	
				$userid	=	trim($params['userid']);
	
				$reqData	= 	self::getUserById(array('userid'=>$userid));
				if( empty($reqData) || !isset($reqData['ret']) ){
					$ret_array['ret']	= 	101;
					$ret_array['msg']		= 	'服务器忙，请稍后再试';
					break;
				}
				
				if( 0 != $reqData['ret']	){
					$ret_array	= 	$reqData;
					break;
				}
	
				if( empty($reqData['data']) ){
					$ret_array['ret']	= 	3;
					$ret_array['msg']		= 	'该用户不存在！';
					break;
				}
				$userinfo	=	$reqData['data'];
	
				if( 0 == $userinfo['ret'] ){
					$ret_array['ret']	= 	4;
					$ret_array['msg'] 		= 	'账号已被禁用！';
					break;
				}
				
				//可以通过UserId和GroupI来判断
				
				$ret_array['ret']	=	0;
				$ret_array['data']	=	$userinfo;
			}catch(Exception $e){
				$ret_array['ret']	= 100;
				$ret_array['msg']	= '服务器忙，请稍后再试';
				$ret_array['error']	= $e->getMessage();
				break;
			}
		}while(0);
		if( 0 != $ret_array['ret']){
			Common::toTxt(array('file'=>'Log_System_checkOpenPower.txt', 'txt'=>'Input:'.var_export($params, true).'|Output:'.var_export($ret_array, true)));
		}
		return $ret_array;
	
	}
	
	/**
	 * 用户修改密码逻辑
	 */
	public static function modifyPwd($params){
		$ret_array = array('ret'=>1, 'msg'=>'', 'occur'=>'System_modifyPwd', 'error'=>'', 'data'=>'');
		do{
			try{
				if( !is_array($params) ||  !isset($params['email']) || !isset($params['oldpwd']) || !isset($params['newpwd']) ){
					$ret_array['ret']	= 	1;
					$ret_array['msg']		= 	'缺少调用参数';
					break;
				}
				
				$email		=	trim($params['email']);
				$oldpwd		=	trim($params['oldpwd']);
				$newpwd		=	trim($params['newpwd']);
				
				if( !empty($oldpwd) ){//输入了旧密码，则验证旧密码是否正确
					$reqData	= 	self::getUserByEmail(array('email'=>$email));
					if( empty($reqData) || !isset($reqData['ret']) ){
						$ret_array['ret']	= 	101;
						$ret_array['msg']		= 	'服务器忙，请稍后再试';
						break;
					}
					if( 0 != $reqData['ret']	){
						$ret_array	= 	$reqData;
						break;
					}
					
					if( empty($reqData['data']) ){
						$ret_array['ret']	= 	3;
						$ret_array['msg']		= 	'输入的用户未注册！';
						break;
					}
					$userinfo	=	$reqData['data'];
					
					if( 0 == $userinfo['ret'] ){
						$ret_array['ret']	= 	4;
						$ret_array['msg'] 		= 	'账号已被禁用！';
						break;
					}
					
					$correct	=	md5($oldpwd);
					if( $correct != $userinfo['password'] ){
						$ret_array['ret'] 	= 	5;
						$ret_array['msg'] 		= 	'输入的密码错误！';
						break;
					}
				}else{//管理员绕过密码验证直接修改	
				}
				
				$userid		=	isset($userinfo['id']) ? $userinfo['id'] : 0;
				
				$connect	=	CfgAR::setDbLink('db');
				if( '-3306' == $connect ){
					$ret_array['ret']	=	103;
					$ret_array['msg']		=	'服务器忙，请稍后再试';
					break;
				}
				
				$dtime 		= 	date('Y-m-d H:i:s');
				$updatesql	=	sprintf("UPDATE %s SET `password` = '%s', `lastmodify` = '%s' WHERE email='%s'", self::$tbluser, md5($newpwd), $dtime, $email);
				$command	=	$connect->createCommand($updatesql);
				$effectrow	=	$command->execute();
				if( 0 == $effectrow ){
					$ret_array['ret']	=	104;
					$ret_array['msg']		=	'服务器忙，请稍后再试';
					break;
				}
				
				$remark = '修改密码成功！|IP:'.Common::getIp();
				self::systemLog(array('userid'=>$userid, 'email'=>$email, 'action'=>'修改密码', 'remark'=>$remark, 'dtime'=>$dtime));
				
				$ret_array['ret']		=	0;
			}catch(Exception $e){
				$ret_array['ret']	= 100;
				$ret_array['msg']	= '服务器忙，请稍后再试';
				$ret_array['error']	= $e->getMessage();
				break;
			}
		}while(0);
		if( 0 != $ret_array['ret']){
			Common::toTxt(array('file'=>'Log_System_modifyPwd.txt', 'txt'=>'Input:'.var_export($params, true).'|Output:'.var_export($ret_array, true)));
		}
		return $ret_array;
	
	}

	/**
	 * 获取用户列表
	 */
	public static function getUserList($params)	{
		$ret_array = array('ret'=>1, 'msg'=>'', 'occur'=>'System_getUserList', 'error'=>'', 'data'=>'');
		do{
			try{
				$canmc		=	isset($params['canmc']) 	? 	$params['canmc'] 	: true;
				$id			=	isset($params['id']) 		? 	$params['id'] 		: false;
				$email		=	isset($params['email']) 	? 	$params['email'] 	: false;
				$status		=	isset($params['status']) 	? 	$params['status'] 	: false;
				$groupid	=	isset($params['groupid']) 	? 	$params['groupid'] 	: false;
				$datalist	=	false;
	
				if( $canmc ){
					$reqData	=	CfgAR::getMem(array('link'=>'cache', 'key'=>MEM_SYSTEM_USER_LIST));
					if( !empty($reqData) && isset($reqData['ret']) && 0 == $reqData['ret'] && false !== $reqData['data'] ){
						$datalist	=	$reqData['data'];
					}
				}
	
				if( false === $datalist ){
					$connect	=	CfgAR::setDbLink('db');
					if( '-3306' == $connect ){
						$ret_array['ret']	=	101;
						$ret_array['msg']		=	'服务器忙，请稍后再试';
						break;
					}
					$selectsql	=	'SELECT * FROM '.self::$tbluser;
					$command	=	$connect->createCommand($selectsql);
					$datalist	=	$command->queryAll();
					CfgAR::setMc(array('link'=>'cache', 'key'=>MEM_SYSTEM_USER_LIST, 'data'=>$datalist, 'time'=>10));
				}
				//print_r($datalist);
				$temp	=	array();
				foreach($datalist as $item ){
					$aid	=	$item['id'];
					if( false !== $id && $id != $aid ){
						continue;
					}
					if( false !== $status && $status != $item['status'] ){
						continue;
					}
					if( false !== $email && $email != $item['email'] ){
						continue;
					}
					if( false !== $groupid && $groupid != $item['groupid'] ){
						continue;
					}
					$temp[$aid]	=	$item;
				}
	
				$ret_array['ret']	=	0;
				$ret_array['data']		=	$temp;
			}catch (Exception $e){
				$ret_array['ret']	=	100;
				$ret_array['msg']		=	'服务器忙，请稍后再试';
				$ret_array['error']		=	$e->getMessage();
			}
		}while (0);
		if(0 != $ret_array['ret']){
			Common::toTxt(array('file'=>'Log_System_getUserList.txt', 'txt'=>'Input:'.var_export($params, true).'|Output:'.var_export($ret_array, true)));
		}
	
		return $ret_array;
	}
	
	/**
	 * 分页显示时调用
	 */
	public static function getPageUser($params){
		$ret_array = array('ret'=>1, 'msg'=>'', 'occur'=>'System_getPageUser', 'error'=>'', 'data'=>'');
		do{
			try{
				if( !is_array($params) ){
					$ret_array['ret']	= 	1;
					$ret_array['msg']		= 	'缺少调用参数';
					break;
				}
				$page	=	$params['page'];
				$size	=	$params['size'];
				
				//查询相关
				$temp	=	array();
				if( isset($params['groupid']) && $params['groupid'] > 0 ){
					$temp[]	=	'groupid='.$params['groupid'];
				}
				if( isset($params['status']) && false !== $params['status'] ){
					$temp[]	=	'status='.$params['status'];
				}
				$where 	=	0 == count($temp) ?  '' : ' WHERE '.join(' AND ',$temp);
				
				$connect	=	CfgAR::setDbLink('db');
				if( '-3306' == $connect ){
					$ret_array['ret']	=	102;
					$ret_array['msg']		=	'服务器忙，请稍后再试';
					break;
				}
				
				$selectsql	=	'SELECT count(id) AS total FROM '.self::$tbluser.$where;
				$command 	= 	$connect->createCommand($selectsql);
				$datalist	=	$command->queryRow();
				
				$total	=	isset($datalist['total']) ? $datalist['total'] : 0;
				$zongye	=	ceil($total/$size);//总页面数
				
				if( $page > $zongye ){
					$page = $zongye;
				}
				
				if( $page < 1 ){
					$page	=	1;
				}
				
				$start	=	( $page - 1 ) * $size;
				
				$selectsql	=	'SELECT * FROM '.self::$tbluser.$where.' LIMIT '.$start.', '.$size;
				$command 	= 	$connect->createCommand($selectsql);
				$datalist	=	$command->queryAll();
				
				$ret_array['ret']	=	0;
				$ret_array['data']	=	array('total'=>$total, 'datalist'=>$datalist);
			}catch(Exception $e){
				$ret_array['ret']	= 	100;
				$ret_array['msg']		= 	'服务器忙，请稍后再试';
				$ret_array['error']		= 	$e->getMessage();
			}
		}while(0);
		if( 0 != $ret_array['ret']){
			Common::toTxt(array('file'=>'Log_System_getPageUser.txt', 'txt'=>'Input:'.var_export($params, true).'|Output:'.var_export($ret_array, true)));
		}
		return $ret_array;
	}
	/**
	 * 增加系统用户
	 */
	public static function addUser($params){
		$ret_array = array('ret'=>1, 'msg'=>'', 'occur'=>'System_addUser', 'error'=>'', 'data'=>'');
		do{
			try{
				if( !is_array($params) ||  !isset($params['email']) || !isset($params['password']) || !isset($params['groupid']) ){
					$ret_array['ret']	= 	1;
					$ret_array['msg']	= 	'缺少调用参数';
					break;
				}
				
				$email		=	trim($params['email']);		//用户名
				$password	=	md5($params['password']);		//登陆密码
				$groupid	=	trim($params['groupid']);		//用户组
				$status		=	$params['status'];				//状态
				$openuser	=	$params['openuser'];			//是否授权开通子账户
				$title		=	isset($params['title']) 	? 	$params['title'] : '';
				
				$op_userid	=	isset($params['op_userid']) ? $params['op_userid'] : '';
				$op_email	=	isset($params['op_email']) ? $params['op_email'] : '';
				
				$reqData	= 	self::getUserByEmail(array('email'=>$email));
				if( empty($reqData) || !isset($reqData['ret']) ){
					$ret_array['ret']	= 	101;
					$ret_array['msg']		= 	'服务器忙，请稍后再试';
					break;
				}
				if( 0 != $reqData['ret']	){
					$ret_array	= 	$reqData;
					break;
				}
					
				if( !empty($reqData['data']) ){
					$ret_array['ret']	= 	3;
					$ret_array['msg']		= 	'账号已注册，请换一个！';
					break;
				}
				
				//检查该用户是否有开户权限
				
				
				$connect	=	CfgAR::setDbLink('db');
				if( '-3306' == $connect ){
					$ret_array['ret']	=	102;
					$ret_array['msg']		=	'服务器忙，请稍后再试';
					break;
				}
				
				$createtime	=	date('Y-m-d H:i:s');
				$inser_sql	=	'INSERT INTO '.self::$tbluser.'(email, password, groupid, status, openuser, title, createtime) VALUES(:email, :password, :groupid, :status, :openuser, :title, :createtime)';
				$command 	= 	$connect->createCommand($inser_sql);
				$command->bindParam(":email", $email, PDO::PARAM_STR);
				$command->bindParam(":password", $password, PDO::PARAM_STR);
				$command->bindParam(":groupid", $groupid, PDO::PARAM_INT);
				$command->bindParam(":status", $status, PDO::PARAM_INT);
				$command->bindParam(":openuser", $openuser, PDO::PARAM_INT);
				$command->bindParam(":title", $title, PDO::PARAM_STR);
				$command->bindParam(":createtime", $createtime, PDO::PARAM_STR);
				$efferctrow	=	$command->execute();
				if( 0 == $efferctrow ){
					$ret_array['ret']	=	103;
					$ret_array['msg']		=	'服务器忙，请稍后再试';
					break;
				}
				
				$ret_array['ret']	=	0;
				
				$remark = '添加系统用户(email:'.$email.',groupid:'.$groupid.',status:'.$status.',title:'.$title.')';
				self::systemLog(array('userid'=>$op_userid,'email'=>$op_email, 'itype'=>10002, 'stype'=>'useradd', 'remark'=>$remark));
				
			}catch(Exception $e){
				$ret_array['ret']	= 100;
				$ret_array['msg']	= '服务器忙，请稍后再试';
				$ret_array['error']	= $e->getMessage();
			}
		}while(0);
		if( 0 != $ret_array['ret']){
			Common::toTxt(array('file'=>'Log_System_addUser.txt', 'txt'=>'Input:'.var_export($params, true).'|Output:'.var_export($ret_array, true)));
		}
		return $ret_array;
	}
	
	/**
	 * 修改用户信息(系统管理员才可以)
	 */
	public static function modUser($params){
		$ret_array = array('ret'=>1, 'msg'=>'', 'occur'=>'System_modUser', 'error'=>'', 'data'=>'');
		do{
			try{
				if( !is_array($params) ||  !isset($params['id']) || !isset($params['groupid']) ){
					$ret_array['ret']	= 	1;
					$ret_array['msg']		= 	'缺少调用参数';
					break;
				}
				
				$id			=	$params['id'];		//用户ID
				$groupid	=	$params['groupid'];	//用户组
				$status		=	$params['status'];	//状态
				$openuser	=	$params['openuser'];//授权开户
				$title		=	$params['title'];
				
				$op_userid	=	isset($params['op_userid']) ? $params['op_userid'] : '';
				$op_email	=	isset($params['op_email']) ? $params['op_email'] : '';
				
				if( isset($params['password']) && false != $params['password'] ){
					$password	=	trim($params['password']);
					if( strlen($password) < 6 ){
						$ret_array['ret']	= 	1;
						$ret_array['msg']		= 	'折扣必须是两位小数';
						break;
					}else{
						$password	=	md5($password); //直接MD5
					}
				}else{
					$password	=	false;
				}
		
				$connect	=	CfgAR::setDbLink('db');
				if( '-3306' == $connect ){
					$ret_array['ret']	=	102;
					$ret_array['msg']		=	'服务器忙，请稍后再试';
					break;
				}
				
				$addtime	=	date('Y-m-d H:i:s');
				if( $password ){
					$updatesql	=	'UPDATE '.self::$tbluser.' SET password=:password, groupid=:groupid, status=:status, openuser=:openuser, title=:title WHERE id=:id';
					$command 	= 	$connect->createCommand($updatesql);
					$command->bindParam(":password", $params, PDO::PARAM_STR);
					$remark = '修改系统用户信息(passworld:******,groupid:'.$groupid.',status:'.$status.',title:'.$title.',id:'.$id.')';
				}else{
					$updatesql	=	'UPDATE '.self::$tbluser.' SET groupid=:groupid, status=:status, openuser=:openuser, title=:title WHERE id=:id';
					$command 	= 	$connect->createCommand($updatesql);
					$remark = '修改系统用户信息(groupid:'.$groupid.',status:'.$status.',title:'.$title.',id:'.$id.')';
				}
				
				$command->bindParam(":groupid", $groupid, PDO::PARAM_STR);
				$command->bindParam(":status", $status, PDO::PARAM_INT);
				$command->bindParam(":openuser", $openuser, PDO::PARAM_INT);
				$command->bindParam(":title", $title, PDO::PARAM_STR);
				$command->bindParam(":id", $id, PDO::PARAM_STR);
				$efferctrow	=	$command->execute();
				
				if( 0 == $efferctrow ){
					//$ret_array['ret']	=	103;
					//$ret_array['msg']		=	'服务器忙，请稍后再试';
					//break;
				}
		
				$ret_array['ret']	=	0;
				self::systemLog(array('userid'=>$op_userid,'email'=>$op_email, 'itype'=>10002, 'stype'=>'usermod', 'remark'=>$remark));
				
			}catch(Exception $e){
				$ret_array['ret']	= 100;
				$ret_array['msg']		= '服务器忙，请稍后再试';
				$ret_array['error']		= $e->getMessage();
			}
		}while(0);
		if( 0 != $ret_array['ret']){
			Common::toTxt(array('file'=>'Log_System_modUser.txt', 'txt'=>'Input:'.var_export($params, true).'|Output:'.var_export($ret_array, true)));
		}
		return $ret_array;
	}
	
	/**
	 * 系统日志信息
	 */
	public static function systemLog($params){
		$ret_array = array('ret'=>1, 'msg'=>'', 'occur'=>'System_systemLog', 'error'=>'', 'data'=>'');
		do{
			try{
				if( !is_array($params) || !isset($params['userid']) || !isset($params['remark']) ){
					$ret_array['ret']	= 	1;
					$ret_array['msg']		= 	'缺少调用参数';
					break;
				}
				
				$connect	=	CfgAR::setDbLink('db');
				if( '-3306' == $connect ){
					$ret_array['ret']	=	102;
					$ret_array['msg']		=	'服务器忙，请稍后再试';
					break;
				}
				
				$remark		=	$params['remark'];	//日志内容
				$userid		=	isset($params['userid'])	?	$params['userid']	:	'';		//用户ID
				$email		=	isset($params['email']) 	? 	$params['email'] 	:	'';		//用户名
				$itype		=	isset($params['itype']) 	? 	$params['itype']	: 	0;							//操作类型：整数
				$stype		=	isset($params['stype']) 	? 	$params['stype']   	: 	'';							//操作类型：字符串
				$amount		=	isset($params['amount']) 	? 	$params['amount'] 	: 	0;							//数量或是金额
				$ip			=	isset($params['ip']) 		? 	$params['ip'] 		: 	Common::getIp();			//IP地址
				$dtime		=	isset($params['dtime']) 	? 	$params['dtime'] 	: 	date('Y-m-d H:i:s');
		
				$insertsql	=	'INSERT INTO '.self::$tblsystemlog.'(userid,email,itype,stype,remark,amount,ip,dtime) VALUES(:userid,:email,:itype,:stype,:remark,:amount,:ip,:dtime)';
				$command 	= 	$connect->createCommand($insertsql);
				$command->bindParam(":userid", $userid, PDO::PARAM_INT);
				$command->bindParam(":email", $email, PDO::PARAM_STR);
				$command->bindParam(":itype", $itype, PDO::PARAM_INT);
				$command->bindParam(":stype", $stype, PDO::PARAM_STR);
				$command->bindParam(":remark", $remark, PDO::PARAM_STR);
				$command->bindParam(":amount", $amount, PDO::PARAM_INT);
				$command->bindParam(":ip", $ip, PDO::PARAM_STR);
				$command->bindParam(":dtime", $dtime, PDO::PARAM_STR);
				$efferctrow	=	$command->execute();
		
				if( 0 == $efferctrow ){
					$ret_array['ret']	=	103;
					$ret_array['msg']		=	'服务器忙，请稍后再试';
					break;
				}
		
				$ret_array['ret']	=	0;
			}catch(Exception $e){
				$ret_array['ret']	= 	100;
				$ret_array['msg']		= 	'服务器忙，请稍后再试';
				$ret_array['error']		= 	$e->getMessage();
			}
		}while(0);
		if( 0 != $ret_array['ret']){
			Common::toTxt(array('file'=>'Log_System_systemLog.txt', 'txt'=>'Input:'.var_export($params, true).'|Output:'.var_export($ret_array, true)));
		}
		return $ret_array;
	}
	
	/**
	 * 系统日志信息
	 */
	public static function getSystemLog($params){
		$ret_array = array('ret'=>1, 'msg'=>'', 'occur'=>'System_getSystemLog', 'error'=>'', 'data'=>'');
		do{
			try{
				if( !is_array($params) ){
					$ret_array['ret']	= 	1;
					$ret_array['msg']		= 	'缺少调用参数';
					break;
				}
				$page	=	$params['page'];
				$size	=	$params['size'];
				
				//查询相关
				$temp	=	array();
				if( isset($params['userid']) && false != $params['userid'] ){
					$temp[]	=	'userid='.$params['userid'];
				}
				if( isset($params['itype']) ){
					$temp[]	=	'itype='.$params['itype'];
				}
				if( isset($params['stype']) ){
					$temp[]	=	'stype='.$params['stype'];
				}
				if( isset($params['stime']) && false != $params['stime'] ){
					$temp[]	=	'dtime >=\''.$params['stime'].'\'';
				}
				if( isset($params['etime']) && false != $params['etime'] ){
					$temp[]	=	'dtime <=\''.$params['etime'].'\'';
				}
				$where 	=	0 == count($temp) ?  '' : ' WHERE '.join(' AND ',$temp);
				
				$connect	=	CfgAR::setDbLink('db');
				if( '-3306' == $connect ){
					$ret_array['ret']	=	102;
					$ret_array['msg']		=	'服务器忙，请稍后再试';
					break;
				}
				
				$selectsql	=	'SELECT count(id) AS total FROM '.self::$tblsystemlog.$where;
				$command 	= 	$connect->createCommand($selectsql);
				$datalist	=	$command->queryRow();
				
				$total	=	isset($datalist['total']) ? $datalist['total'] : 0;
				$zongye	=	ceil($total/$size);//总页面数
				
				if( $page > $zongye ){
					$page = $zongye;
				}
				
				if( $page < 1 ){
					$page	=	1;
				}
				
				$start	=	( $page - 1 ) * $size;
				
				$selectsql	=	'SELECT * FROM '.self::$tblsystemlog.$where.' ORDER BY dtime DESC LIMIT '.$start.', '.$size;
				$command 	= 	$connect->createCommand($selectsql);
				$datalist	=	$command->queryAll();
				
				$ret_array['ret']	=	0;
				$ret_array['data']		=	array('total'=>$total, 'datalist'=>$datalist);
			}catch(Exception $e){
				$ret_array['ret']	= 	100;
				$ret_array['msg']		= 	'服务器忙，请稍后再试';
				$ret_array['error']		= 	$e->getMessage();
			}
		}while(0);
		if( 0 != $ret_array['ret']){
			Common::toTxt(array('file'=>'Log_System_getSystemLog.txt', 'txt'=>'Input:'.var_export($params, true).'|Output:'.var_export($ret_array, true)));
		}
		return $ret_array;
	}
	
	/**
	 * 获取权限列表
	 */
	public static function getPowerList($params)	{
		$ret_array = array('ret'=>1, 'msg'=>'', 'occur'=>'System_getPowerList', 'error'=>'', 'data'=>'');
		do{
			try{
				$canmc		=	isset($params['canmc']) 	? 	$params['canmc'] 	: true;
				$id			=	isset($params['id']) 		? 	$params['id'] 		: false;
				$action		=	isset($params['action']) 	? 	$params['action'] 	: false;
				$parentid	=	isset($params['parentid']) 	? 	$params['parentid'] : false;
				$datalist	=	false;
	
				if( $canmc ){
					$reqData	=	CfgAR::getMem(array('link'=>'cache', 'key'=>MEM_SYSTEM_POWER_LIST));
					if( !empty($reqData) && isset($reqData['ret']) && 0 == $reqData['ret'] && false !== $reqData['data'] ){
						$datalist	=	$reqData['data'];
					}
				}
	
				if( false === $datalist ){
					$connect	=	CfgAR::setDbLink('db');
					if( '-3306' == $connect ){
						$ret_array['ret']	=	101;
						$ret_array['msg']		=	'服务器忙，请稍后再试';
						break;
					}
					$selectsql	=	'SELECT * FROM '.self::$tblpower.' ORDER BY weight DESC';
					$command	=	$connect->createCommand($selectsql);
					$datalist	=	$command->queryAll();
					CfgAR::setMc(array('link'=>'cache', 'key'=>MEM_SYSTEM_POWER_LIST, 'data'=>$datalist, 'time'=>10));
				}
	
				$temp	=	array();
				foreach($datalist as $item ){
					$aid	=	$item['id'];
					if( false !== $id && $id != $item['id'] ){
						continue;
					}
						
					if( false !== $parentid && $parentid != $item['parentid'] ){
						continue;
					}
						
					if( false !== $action && $action != $item['action'] ){
						continue;
					}
					$temp[$aid]	=	$item;
				}
	
				$ret_array['ret']	=	0;
				$ret_array['data']		=	$temp;
			}catch (Exception $e){
				$ret_array['ret']	=	100;
				$ret_array['msg']		=	'服务器忙，请稍后再试';
				$ret_array['error']		=	$e->getMessage();
			}
		}while (0);
		if(0 != $ret_array['ret']){
			Common::toTxt(array('file'=>'Log_System_getPowerList.txt', 'txt'=>'Input:'.var_export($params, true).'|Output:'.var_export($ret_array, true)));
		}
	
		return $ret_array;
	}
	
	/**
	 * 添加权限
	 */
	public static function addPower($params) {
		$ret_array = array('ret'=>1, 'msg'=>'', 'occur'=>'System_addPower', 'error'=>'', 'data'=>'');
		do{
			try{
				$pname		=	$params['pname'];
				$action		=	$params['action'];
				$status		=	$params['status'];
				$parentid	=	$params['parentid'];
				$weight		=	$params['weight'];
				$dtime		=	date('Y-m-d H:i:s');
				
				$op_userid	=	isset($params['op_userid']) ? $params['op_userid'] : '';
				$op_email	=	isset($params['op_email']) ? $params['op_email'] : '';
	
				//同一个父类下面不能相同
				$reqData	=	self::getPowerList(array('parentid'=>$parentid, 'action'=>$action));
				if( empty($reqData) || !isset($reqData['ret']) ){
					$ret_array['ret']	= 	101;
					$ret_array['msg']		= 	'服务器忙，请稍后再试';
					break;
				}
				if( 0 != $reqData['ret']	){
					$ret_array	= 	$reqData;
					break;
				}
	
				if( !empty($reqData['data']) ){
					$ret_array['ret']	= 	102;
					$ret_array['msg']		= 	'所属组已配置该权限';
					break;
				}
	
				$connect	=	CfgAR::setDbLink('db');
				if('-3306'	==	$connect){
					$ret_array['ret']	=	101;
					$ret_array['msg']	=	'服务器忙，请稍后再试';
					break;
				}
	
				$insertsql	=	'INSERT INTO '.self::$tblpower.'(pname, action, status, parentid, weight, addtime) VALUES(:pname, :action, :status, :parentid, :weight, :addtime)';
				$command 	= 	$connect->createCommand($insertsql);
				$command->bindParam(":pname", $pname, PDO::PARAM_STR);
				$command->bindParam(":action", $action, PDO::PARAM_STR);
				$command->bindParam(":status", $status, PDO::PARAM_INT);
				$command->bindParam(":parentid", $parentid, PDO::PARAM_INT);
				$command->bindParam(":weight", $weight, PDO::PARAM_INT);
				$command->bindParam(":addtime", $dtime, PDO::PARAM_STR);
				$efferctrow	=	$command->execute();
	
				if( 0 == $efferctrow ){
					$ret_array['ret']	=	103;
					$ret_array['msg']		=	'服务器忙，请稍后再试';
					break;
				}
				CfgAR::delMem(array('link'=>'cache', 'key'=>MEM_SYSTEM_POWER_LIST));
	
				$ret_array['ret']	=	0;
				
				$remark	=	'添加权限(name:'.$pname.',action:'.$action.',status:'.$status.',parentid:'.$parentid.')';
				self::systemLog(array('userid'=>$op_userid,'email'=>$op_email, 'itype'=>10003, 'stype'=>'poweradd', 'remark'=>$remark));
			}catch (Exception $e){
				$ret_array['ret']	=	100;
				$ret_array['msg']		=	'服务器忙，请稍后再试';
				$ret_array['error']		=	$e->getMessage();
			}
		}while (0);
		if(0 != $ret_array['ret']){
			Common::toTxt(array('file'=>'Log_System_addPower.txt', 'txt'=>'Input:'.var_export($params, true).'|Output:'.var_export($ret_array, true)));
		}
		return $ret_array;
	
	}
	
	/**
	 * 修改权限
	 */
	public static function modPower($params){
		$ret_array = array('ret'=>1, 'msg'=>'', 'occur'=>'System_modPower', 'error'=>'', 'data'=>'');
		do{
			try{
				$id			=	$params['id'];
				$pname		=	$params['pname'];
				$action		=	$params['action'];
				$parentid	=	$params['parentid'];
				$status		=	$params['status'];
				$weight		=	$params['weight'];
				$dtime		=	date('Y-m-d H:i:s');
				
				$op_userid	=	isset($params['op_userid']) ? $params['op_userid'] : '';
				$op_email	=	isset($params['op_email']) ? $params['op_email'] : '';
	
				$connect	=	CfgAR::setDbLink('db');
				if('-3306'	==	$connect){
					$ret_array['ret']	=	103;
					$ret_array['msg']		=	'服务器忙，请稍后再试';
					break;
				}
	
				$updatesql	=	'UPDATE '.self::$tblpower.' SET pname=:pname, action =:action, status=:status, parentid=:parentid, weight=:weight, lastmodify=:lastmodify  WHERE id = :id ';
				$command 	= 	$connect->createCommand($updatesql);
				$command->bindParam(":pname", $pname, PDO::PARAM_STR);
				$command->bindParam(":action", $action, PDO::PARAM_STR);
				$command->bindParam(":status", $status, PDO::PARAM_INT);
				$command->bindParam(":parentid", $parentid, PDO::PARAM_INT);
				$command->bindParam(":weight", $weight, PDO::PARAM_INT);
				$command->bindParam(":lastmodify", $dtime, PDO::PARAM_STR);
				$command->bindParam(":id", $id, PDO::PARAM_INT);
				$efferctrow	=	$command->execute();
	
				if( 0 == $efferctrow ){
					$ret_array['ret']	=	104;
					$ret_array['msg']		=	'服务器忙，请稍后再试';
					break;
				}
	
				CfgAR::delMem(array('link'=>'cache', 'key'=>MEM_SYSTEM_POWER_LIST));
	
				$ret_array['ret']	=	0;
				$ret_array['msg']		=	'权限修改成功';
				
				$remark	=	'修改权限(name:'.$pname.',action:'.$action.',status:'.$status.',parentid:'.$parentid.',id:'.$id.')';
				self::systemLog(array('userid'=>$op_userid,'email'=>$op_email, 'itype'=>10003, 'stype'=>'powermod', 'remark'=>$remark));
			}catch (Exception $e){
				$ret_array['ret']	=	100;
				$ret_array['msg']	=	'服务器忙，请稍后再试';
				$ret_array['error']	=	$e->getMessage();
				break;
			}
		}while (0);
		if(0 != $ret_array['ret']){
			Common::toTxt(array('file'=>'Log_System_modPower.txt', 'txt'=>'Input:'.var_export($params, true).'|Output:'.var_export($ret_array, true)));
		}
		return $ret_array;
	}

	/**
	 * 获取组列表
	 */
	public static function getGroupList($params)	{
		$ret_array = array('ret'=>1, 'msg'=>'', 'occur'=>'System_getGroupList', 'error'=>'', 'data'=>'');
		do{
			try{
				$canmc		=	isset($params['canmc']) 	? 	$params['canmc'] 	: true;
				$id			=	isset($params['id']) 		? 	$params['id'] 		: false;
				$name		=	isset($params['name']) 		? 	$params['name'] 	: false;
				$datalist	=	false;
	
				if( $canmc ){
					$reqData	=	CfgAR::getMem(array('link'=>'cache', 'key'=>MEM_SYSTEM_GROUP_LIST));
					if( !empty($reqData) && isset($reqData['ret']) && 0 == $reqData['ret'] && false !== $reqData['data'] ){
						$datalist	=	$reqData['data'];
					}
				}
	
				if( false === $datalist ){
					$connect	=	CfgAR::setDbLink('db');
					if( '-3306' == $connect ){
						$ret_array['ret']	=	101;
						$ret_array['msg']		=	'服务器忙，请稍后再试';
						break;
					}
					$selectsql	=	'SELECT * FROM '.self::$tblgroup;
					$command	=	$connect->createCommand($selectsql);
					$datalist	=	$command->queryAll();
					CfgAR::setMc(array('link'=>'cache', 'key'=>MEM_SYSTEM_GROUP_LIST, 'data'=>$datalist, 'time'=>10));
				}
	
				$temp	=	array();
				foreach($datalist as $item ){
					$aid		=	$item['id'];
					if( false !== $id && $id != $aid ){
						continue;
					}
	
					if( false !== $name && $name != $item['name'] ){
						continue;
					}
					$temp[$aid]	=	$item;
				}
	
				$ret_array['ret']	=	0;
				$ret_array['data']		=	$temp;
			}catch (Exception $e){
				$ret_array['ret']	=	100;
				$ret_array['msg']		=	'服务器忙，请稍后再试';
				$ret_array['error']		=	$e->getMessage();
			}
		}while (0);
		if(0 != $ret_array['ret']){
			Common::toTxt(array('file'=>'Log_System_getGroupList.txt', 'txt'=>'Input:'.var_export($params, true).'|Output:'.var_export($ret_array, true)));
		}
	
		return $ret_array;
	}
	
	/**
	 * 添加组信息
	 */
	public static function addGroup($params) {
		$ret_array = array('ret'=>1, 'msg'=>'', 'occur'=>'System_addGroup', 'error'=>'', 'data'=>'');
		do{
			try{
				$name		=	$params['name'];
				$status		=	$params['status'];
				$dtime		=	date('Y-m-d H:i:s');
				
				$op_userid	=	isset($params['op_userid']) ? $params['op_userid'] : '';
				$op_email	=	isset($params['op_email']) ? $params['op_email'] : '';
	
				//同一个父类下面不能相同
				$reqData	=	self::getGroupList(array('name'=>$name));
				if( empty($reqData) || !isset($reqData['ret']) ){
					$ret_array['ret']	= 	101;
					$ret_array['msg']		= 	'服务器忙，请稍后再试';
					break;
				}
				if( 0 != $reqData['ret']	){
					$ret_array	= 	$reqData;
					break;
				}
	
				if( !empty($reqData['data']) ){
					$ret_array['ret']	= 	102;
					$ret_array['msg']		= 	'已存在相同名字的用户组';
					break;
				}
	
				$connect	=	CfgAR::setDbLink('db');
				if('-3306'	==	$connect){
					$ret_array['ret']	=	101;
					$ret_array['msg']	=	'服务器忙，请稍后再试';
					break;
				}
	
				$insertsql	=	'INSERT INTO '.self::$tblgroup.'(name, status, addtime) VALUES(:name, :status, :addtime)';
				$command 	= 	$connect->createCommand($insertsql);
				$command->bindParam(":name", $name, PDO::PARAM_STR);
				$command->bindParam(":status", $status, PDO::PARAM_INT);
				$command->bindParam(":addtime", $dtime, PDO::PARAM_STR);
				$efferctrow	=	$command->execute();
	
				if( 0 == $efferctrow ){
					$ret_array['ret']	=	103;
					$ret_array['msg']		=	'服务器忙，请稍后再试';
					break;
				}
				CfgAR::delMem(array('link'=>'cache', 'key'=>MEM_SYSTEM_GROUP_LIST));
				
				$remark = '添加用户组(name:'.$name.',status:'.$status.')';
				self::systemLog(array('userid'=>$op_userid,'email'=>$op_email, 'itype'=>10005, 'stype'=>'groupadd', 'remark'=>$remark));
				
				$ret_array['ret']	=	0;
			}catch (Exception $e){
				$ret_array['ret']	=	100;
				$ret_array['msg']		=	'服务器忙，请稍后再试';
				$ret_array['error']		=	$e->getMessage();
			}
		}while (0);
		if(0 != $ret_array['ret']){
			Common::toTxt(array('file'=>'Log_System_addGroup.txt', 'txt'=>'Input:'.var_export($params, true).'|Output:'.var_export($ret_array, true)));
		}
		return $ret_array;
	
	}
	
	/**
	 * 修改组信息
	 */
	public static function modGroup($params){
		$ret_array = array('ret'=>1, 'msg'=>'', 'occur'=>'System_modGroup', 'error'=>'', 'data'=>'');
		do{
			try{
				
				$id			=	$params['id'];
				$name		=	$params['name'];
				$status		=	$params['status'];
				$dtime		=	date('Y-m-d H:i:s');
				
				$op_userid	=	isset($params['op_userid']) ? $params['op_userid'] : '';
				$op_email	=	isset($params['op_email']) ? $params['op_email'] : '';
				
				$reqData	=	self::getGroupList(array('id'=>$id));
				if( empty($reqData) || !isset($reqData['ret']) ){
					$ret_array['ret']	= 	101;
					$ret_array['msg']		= 	'服务器忙，请稍后再试';
					break;
				}
				if( 0 != $reqData['ret']	){
					$ret_array	= 	$reqData;
					break;
				}	
				$oldinfo	=	$reqData['data'];
				if( empty($oldinfo) ){
					$ret_array['ret']	= 	102;
					$ret_array['msg']		= 	'指定用户组不存在';
					break;
				}
				$oldinfo	=	array_shift($oldinfo);
				
				if( $name != $oldinfo['name'] ){//修改了用户组名
					$reqData	=	self::getGroupList(array('name'=>$name));
					if( empty($reqData) || !isset($reqData['ret']) ){
						$ret_array['ret']	= 	101;
						$ret_array['msg']		= 	'服务器忙，请稍后再试';
						break;
					}
					if( 0 != $reqData['ret']	){
						$ret_array	= 	$reqData;
						break;
					}
					if( !empty($reqData['data']) ){//已存在相同的用户组名
						$ret_array['ret']	= 	102;
						$ret_array['msg']		= 	'已存在相同名字的用户组';
						break;
					}
					
					exit;
				}else if($status == $oldinfo['status']){
					$ret_array['ret']	=	0;
					break;
				}
				
				$connect	=	CfgAR::setDbLink('db');
				if('-3306'	==	$connect){
					$ret_array['ret']	=	103;
					$ret_array['msg']		=	'服务器忙，请稍后再试';
					break;
				}
				
				$updatesql	=	'UPDATE '.self::$tblgroup.' SET name=:name, status=:status, lastmodify=:lastmodify  WHERE id = :id ';
				
				$command 	= 	$connect->createCommand($updatesql);
				
				$command->bindParam(":name", $name, PDO::PARAM_STR);
				$command->bindParam(":status", $status, PDO::PARAM_INT);
				$command->bindParam(":lastmodify", $dtime, PDO::PARAM_STR);
				$command->bindParam(":id", $id, PDO::PARAM_INT);
				$efferctrow	=	$command->execute();
	
				if( 0 == $efferctrow ){
					$ret_array['ret']	=	104;
					$ret_array['msg']		=	'服务器忙，请稍后再试';
					break;
				}
				
				CfgAR::delMem(array('link'=>'cache', 'key'=>MEM_SYSTEM_GROUP_LIST));
				
				$remark = '修改用户组(name:'.$name.',status:'.$status.',id:'.$id.')';
				self::systemLog(array('userid'=>$op_userid,'email'=>$op_email, 'itype'=>10005, 'stype'=>'groupmod', 'remark'=>$remark));
				
				$ret_array['ret']	=	0;
			}catch (Exception $e){
				$ret_array['ret']	=	100;
				$ret_array['msg']		=	'服务器忙，请稍后再试';
				$ret_array['error']		=	$e->getMessage();
			}
		}while (0);
		//if(0 != $ret_array['ret']){
			Common::toTxt(array('file'=>'Log_System_modGroup.txt', 'txt'=>'Input:'.var_export($params, true).'|Output:'.var_export($ret_array, true)));
		//}
		return $ret_array;
	}
	
	/**
	 * 删除用户组
	 */
	public static function delGroup($params){
		$ret_array = array('ret'=>1, 'msg'=>'', 'occur'=>'System_modGroup', 'error'=>'', 'data'=>'');
		do{
			try{
				$id			=	$params['id'];
				
				$op_userid	=	isset($params['op_userid']) ? $params['op_userid'] : '';
				$op_email	=	isset($params['op_email']) ? $params['op_email'] : '';
				
				$reqData	=	self::getUserList(array('groupid'=>$id));
				if( empty($reqData) || !isset($reqData['ret']) ){
					$ret_array['ret']	= 	101;
					$ret_array['msg']		= 	'服务器忙，请稍后再试';
					break;
				}
				if( 0 != $reqData['ret']	){
					$ret_array	= 	$reqData;
					break;
				}
				
				$userlist	=	$reqData['data'];
				if( !empty($userlist) ){
					$ret_array['ret']	= 	102;
					$ret_array['msg']		= 	'用户组已添加用户，不允许直接删除';
					break;
				}
				
				$connect	=	CfgAR::setDbLink('db');
				if('-3306'	==	$connect){
					$ret_array['ret']	=	103;
					$ret_array['msg']		=	'服务器忙，请稍后再试';
					break;
				}
				
				$deletesql	=	'DELETE FROM '.self::$tblgroup.' WHERE id = :id ';
				$command 	= 	$connect->createCommand($deletesql);
				$command->bindParam(":id", $id, PDO::PARAM_INT);
				$efferctrow	=	$command->execute();
				
				if( 0 == $efferctrow ){
					$ret_array['ret']	=	104;
					$ret_array['msg']		=	'删除不成功';
					break;
				}
				
				CfgAR::delMem(array('link'=>'cache', 'key'=>MEM_SYSTEM_GROUP_LIST));
				
				$remark = '删除用户组(id:'.$id.')';
				self::systemLog(array('userid'=>$op_userid,'email'=>$op_email, 'itype'=>10005, 'stype'=>'groupdel', 'remark'=>$remark));
	
				$ret_array['ret']	=	0;
			}catch (Exception $e){
				$ret_array['ret']	=	100;
				$ret_array['msg']		=	'服务器忙，请稍后再试';
				$ret_array['error']		=	$e->getMessage();
			}
		}while (0);
		//if(0 != $ret_array['ret']){
			Common::toTxt(array('file'=>'Log_System_delGroup.txt', 'txt'=>'Input:'.var_export($params, true).'|Output:'.var_export($ret_array, true)));
		//}
		return $ret_array;
	}
	
	/**
	 * 获取用户组_权限关系列表
	 */
	public static function getGroupPowerList($params)	{
		$ret_array = array('ret'=>1, 'msg'=>'', 'occur'=>'System_getGroupPowerList', 'error'=>'', 'data'=>'');
		do{
			try{
				$canmc		=	isset($params['canmc']) 	? 	$params['canmc'] 	: true;
				$id			=	isset($params['id']) 		? 	$params['id'] 		: false;
				$groupid	=	isset($params['groupid']) 	? 	$params['groupid'] 	: false;
				$pname		=	isset($params['pname']) 	? 	$params['pname'] 	: false;
				$datalist	=	false;
	
				if( $canmc ){
					$reqData	=	CfgAR::getMem(array('link'=>'cache', 'key'=>MEM_SYSTEM_GROUP_POWER_LIST));
					if( !empty($reqData) && isset($reqData['ret']) && 0 == $reqData['ret'] && false !== $reqData['data'] ){
						$datalist	=	$reqData['data'];
					}
				}
	
				if( false === $datalist ){
					$connect	=	CfgAR::setDbLink('db');
					if( '-3306' == $connect ){
						$ret_array['ret']	=	101;
						$ret_array['msg']		=	'服务器忙，请稍后再试';
						break;
					}
					$selectsql	=	'SELECT * FROM '.self::$tblgrouppower.' ORDER BY weight DESC';
					$command	=	$connect->createCommand($selectsql);
					$datalist	=	$command->queryAll();
					CfgAR::setMc(array('link'=>'cache', 'key'=>MEM_SYSTEM_GROUP_POWER_LIST, 'data'=>$datalist, 'time'=>10));
				}
				
				$temp	=	array();
				foreach($datalist as $item ){
					$aid	=	$item['id'];
					if( false !== $id && $id != $item['id'] ){
						continue;
					}
					if( false !== $groupid && $groupid != $item['groupid'] ){
						continue;
					}
					if( false !== $pname && $pname != $item['pname'] ){
						continue;
					}
					$temp[$aid]	=	$item;
				}
	
				$ret_array['ret']	=	0;
				$ret_array['data']		=	$temp;
			}catch (Exception $e){
				$ret_array['ret']	=	100;
				$ret_array['msg']		=	'服务器忙，请稍后再试';
				$ret_array['error']		=	$e->getMessage();
			}
		}while (0);
		if(0 != $ret_array['ret']){
			Common::toTxt(array('file'=>'Log_System_getGroupPowerList.txt', 'txt'=>'Input:'.var_export($params, true).'|Output:'.var_export($ret_array, true)));
		}
	
		return $ret_array;
	}
	
	/**
	 * 添加用户组_权限关系
	 */
	public static function addGroupPower($params) {
		$ret_array = array('ret'=>1, 'msg'=>'', 'occur'=>'System_addGroupPower', 'error'=>'', 'data'=>'');
		do{
			try{
				$pname		=	$params['pname'];
				$groupid	=	$params['groupid'];
				$weight		=	$params['weight'];
				$dtime		=	date('Y-m-d H:i:s');
				
				$op_userid	=	isset($params['op_userid']) ? $params['op_userid'] : '';
				$op_email	=	isset($params['op_email']) ? $params['op_email'] : '';
	
				//同一个父类下面不能相同
				$reqData	=	self::getGroupPowerList(array('groupid'=>$groupid,'pname'=>$pname));
				if( empty($reqData) || !isset($reqData['ret']) ){
					$ret_array['ret']	= 	101;
					$ret_array['msg']		= 	'服务器忙，请稍后再试';
					break;
				}
				if( 0 != $reqData['ret']	){
					$ret_array	= 	$reqData;
					break;
				}
	
				if( !empty($reqData['data']) ){
					$ret_array['ret']	= 	102;
					$ret_array['msg']		= 	'该用户组已添加了权限组名称，不能重复添加';
					break;
				}
	
				$connect	=	CfgAR::setDbLink('db');
				if('-3306'	==	$connect){
					$ret_array['ret']	=	101;
					$ret_array['msg']	=	'服务器忙，请稍后再试';
					break;
				}
	
				$insertsql	=	'INSERT INTO '.self::$tblgrouppower.'(groupid, weight, pname) VALUES(:groupid, :weight, :pname)';
				$command 	= 	$connect->createCommand($insertsql);
				$command->bindParam(":groupid", $groupid, PDO::PARAM_INT);
				$command->bindParam(":weight", $weight, PDO::PARAM_INT);
				$command->bindParam(":pname", $pname, PDO::PARAM_STR);
				$efferctrow	=	$command->execute();
	
				if( 0 == $efferctrow ){
					$ret_array['ret']	=	103;
					$ret_array['msg']		=	'服务器忙，请稍后再试';
					break;
				}
				CfgAR::delMem(array('link'=>'cache', 'key'=>MEM_SYSTEM_GROUP_POWER_LIST));
	
				$ret_array['ret']	=	0;
				$remark	=	'添加权限组(groupid:'.$groupid.',pname:'.$pname.',weight:'.$weight.')';
				self::systemLog(array('userid'=>$op_userid,'email'=>$op_email, 'itype'=>10006, 'stype'=>'grouppoweradd', 'remark'=>$remark));
			}catch (Exception $e){
				$ret_array['ret']	=	100;
				$ret_array['msg']		=	'服务器忙，请稍后再试';
				$ret_array['error']		=	$e->getMessage();
			}
		}while (0);
		if(0 != $ret_array['ret']){
			Common::toTxt(array('file'=>'Log_System_addGroupPower.txt', 'txt'=>'Input:'.var_export($params, true).'|Output:'.var_export($ret_array, true)));
		}
		return $ret_array;
	
	}
	
	/**
	 * 修改用户组_权限关系
	 */
	public static function modGroupPower($params){
		$ret_array = array('ret'=>1, 'msg'=>'', 'occur'=>'System_modGroupPower', 'error'=>'', 'data'=>'');
		do{
			try{
				$id			=	$params['id'];
				$pname		=	trim($params['pname']);
				$weight		=	$params['weight'];
				$dtime		=	date('Y-m-d H:i:s');
				
				$op_userid	=	isset($params['op_userid']) ? $params['op_userid'] : '';
				$op_email	=	isset($params['op_email']) ? $params['op_email'] : '';
				
				$connect	=	CfgAR::setDbLink('db');
				if('-3306'	==	$connect){
					$ret_array['ret']	=	103;
					$ret_array['msg']		=	'服务器忙，请稍后再试';
					break;
				}
				$selectsql	=	'SELECT * FROM '.self::$tblgrouppower.' WHERE id = :id ';
				$command 	= 	$connect->createCommand($selectsql);
				$command->bindParam(":id", $id, PDO::PARAM_INT);
				$datalist	=	$command->queryRow();
				
				if( empty($datalist) ){
					$ret_array['ret']	=	103;
					$ret_array['msg']		=	'该权限组不存在';
					break;
				}
				if ( $pname != $datalist['pname'] ){//同一个父类下面不能相同
					$reqData	=	self::getGroupPowerList(array('groupid'=>$datalist['groupid'],'pname'=>$pname));
					if( empty($reqData) || !isset($reqData['ret']) ){
						$ret_array['ret']	= 	101;
						$ret_array['msg']		= 	'服务器忙，请稍后再试';
						break;
					}
					if( 0 != $reqData['ret']	){
						$ret_array	= 	$reqData;
						break;
					}
					
					if( !empty($reqData['data']) ){
						$ret_array['ret']	= 	102;
						$ret_array['msg']		= 	'该用户组已添加了权限组名称，不能重复添加';
						break;
					}
				}
				$updatesql	=	'UPDATE '.self::$tblgrouppower.' SET weight=:weight, pname=:pname WHERE id = :id ';
				$command 	= 	$connect->createCommand($updatesql);
				$command->bindParam(":weight", $weight, PDO::PARAM_INT);
				$command->bindParam(":pname", $pname, PDO::PARAM_STR);
				$command->bindParam(":id", $id, PDO::PARAM_INT);
				$efferctrow	=	$command->execute();
	
				if( 0 == $efferctrow ){
					//$ret_array['ret']	=	104;
					//$ret_array['msg']		=	'服务器忙，请稍后再试';
					//break;
				}
	
				CfgAR::delMem(array('link'=>'cache', 'key'=>MEM_SYSTEM_GROUP_POWER_LIST));
	
				$ret_array['ret']	=	0;
				$remark	=	'修改权限组(weight:'.$weight.',pname:'.$pname.',id:'.$id.')';
				self::systemLog(array('userid'=>$op_userid,'email'=>$op_email, 'itype'=>10006, 'stype'=>'grouppowermod', 'remark'=>$remark));
			}catch (Exception $e){
				$ret_array['ret']	=	100;
				$ret_array['msg']		=	'服务器忙，请稍后再试';
				$ret_array['error']		=	$e->getMessage();
				break;
			}
		}while (0);
		if(0 != $ret_array['ret']){
			Common::toTxt(array('file'=>'Log_System_modGroupPower.txt', 'txt'=>'Input:'.var_export($params, true).'|Output:'.var_export($ret_array, true)));
		}
		return $ret_array;
	}
	
	/**
	 * 删除用户组_权限关系
	 */
	public static function delGroupPower($params){
		$ret_array = array('ret'=>1, 'msg'=>'', 'occur'=>'System_delGroupPower', 'error'=>'', 'data'=>'');
		do{
			try{	
				$op_userid	=	isset($params['op_userid']) ? $params['op_userid'] : '';
				$op_email	=	isset($params['op_email']) ? $params['op_email'] : '';
				
				$connect	=	CfgAR::setDbLink('db');
				if('-3306'	==	$connect){
					$ret_array['ret']	=	103;
					$ret_array['msg']		=	'服务器忙，请稍后再试';
					break;
				}
				if( isset($params['id']) ){
					$id			=	$params['id'];
					
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
					
					$deletesql	=	'DELETE FROM '.self::$tblpower.' WHERE id = :id ';
					$command 	= 	$connect->createCommand($deletesql);
					$command->bindParam(":id", $id, PDO::PARAM_INT);
					$efferctrow	=	$command->execute();
		
					if( 0 == $efferctrow ){
						$ret_array['ret']	=	104;
						$ret_array['msg']		=	'服务器忙，请稍后再试';
						break;
					}
					$remark		=	'删除权限项(pname:'.$powerinfo['pname'].',parentid:'.$powerinfo['parentid'].',status:'.$powerinfo['status'].', id:'.$id.')';
				}else if( isset($params['pid']) ){
					$parentid	=	$params['pid'];
					
					$reqData	=	System::getPowerList(array('canmc'=>false, 'parentid'=>$parentid));
					if( empty($reqData) || !isset($reqData['ret']) ){
						$ret_array['ret']	=	1;
						$ret_array['msg']		=	'服务器忙，请稍后再试';
						break;
					}
					
					if( 0 != $reqData['ret'] ){
						$ret_array	=	$reqData;
						break;
					}
					if( 0 < count($reqData['data']) ){//用户组没有配置权限
						$ret_array['ret']	=	1;
						$ret_array['msg']		=	'权限组下面已添加权限项，不能删除';
						break;
					}
					
					$reqData	=	System::getGroupPowerList(array('canmc'=>false, 'id'=>$parentid));
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
										
					$deletesql	=	'DELETE FROM '.self::$tblgrouppower.' WHERE id = :id ';
					$command 	= 	$connect->createCommand($deletesql);
					$command->bindParam(":id", $parentid, PDO::PARAM_INT);
					$efferctrow	=	$command->execute();
		
					if( 0 == $efferctrow ){
						$ret_array['ret']	=	104;
						$ret_array['msg']		=	'服务器忙，请稍后再试';
						break;
					}
					$remark		=	'删除权限组(pname:'.$grouppowerinfo['pname'].',groupid:'.$grouppowerinfo['groupid'].', id:'.$parentid.')';
				}else{
					$ret_array['ret']	=	103;
					$ret_array['msg']		=	'不支持此类型操作';
					break;
				}
				
				CfgAR::delMem(array('link'=>'cache', 'key'=>MEM_SYSTEM_GROUP_POWER_LIST));
				self::systemLog(array('userid'=>$op_userid,'email'=>$op_email, 'itype'=>10006, 'stype'=>'grouppowermod', 'remark'=>$remark));
				
				$ret_array['ret']	=	0;
			}catch (Exception $e){
				$ret_array['ret']	=	100;
				$ret_array['msg']		=	'服务器忙，请稍后再试';
				$ret_array['error']		=	$e->getMessage();
				break;
			}
		}while (0);
		//if(0 != $ret_array['ret']){
			Common::toTxt(array('file'=>'Log_System_delGroupPower.txt', 'txt'=>'Input:'.var_export($params, true).'|Output:'.var_export($ret_array, true)));
		//}
		return $ret_array;
	}

	/**
	 * 获取指定组的所有权限
	 */
	public static function getGroupPowerDetail($params){
		$ret_array = array('ret'=>1, 'msg'=>'', 'occur'=>'System_getGroupPowerDetail', 'error'=>'', 'data'=>'');
		do{
			try{
				$groupid	=	$params['groupid'];
				$datatype	=	$params['datatype'];	//simple, detail
				
				$reqData	=	System::getGroupPowerList(array('canmc'=>false, 'groupid'=>$groupid));
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
					$ret_array['ret']	=	0;
					$ret_array['data']		=	array();
					break;
				}
				$grouppowerlist	=	$reqData['data'];
				
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
				
				$temp		=	array();
				foreach( $grouppowerlist as $item ){//每个可操作的权限组
					$parentid	=	$item['id'];
					$pname		=	$item['pname'];
					$tmpitem	=	array('name'=>$pname, 'actions'=>array());
					foreach( $powerlist as $it ){
						if( $parentid == $it['parentid'] ){
							if( 'simple' == $datatype ){
								$tmpitem['actions'][$it['action']]	=	array('pname'=>$it['pname'], 'weight'=>$it['weight']);
							}else{
								$tmpitem['actions'][]	=	$it;
							}
						}
					}
					$temp[$parentid]	=	$tmpitem;
				}
				
				$ret_array['ret']	=	0;
				$ret_array['data']		=	$temp;
				
			}catch (Exception $e){
				$ret_array['ret']	=	100;
				$ret_array['msg']		=	'服务器忙，请稍后再试';
				$ret_array['error']		=	$e->getMessage();
				break;
			}
		}while (0);
		if(0 != $ret_array['ret']){
			Common::toTxt(array('file'=>'Log_System_getGroupPowerDetail.txt', 'txt'=>'Input:'.var_export($params, true).'|Output:'.var_export($ret_array, true)));
		}
		return $ret_array;
	}
	
	/**
	 * 获取指定的配置参数
	 */
	public static function getConfig($params=array()){
		$ret_array	=	array( 'ret' => 1, 'msg' => '', 'occur'=>'System_getConfigByName', 'error'=>'', 'data' =>'' );
		do{//这里设计成同步操作逻辑
			try{
				$name		=	isset($params['name']) ? $params['name'] : false;
				$configfile	=	BASE_DIR.'/protected/config/systemconfig.php';
				if( !file_exists($configfile) ){
					$ret_array['ret']	=	10;
					$ret_array['msg']	=	'配置文件不存在!';
					break;
				}
				$systemconfig	=	require $configfile;
				
				if( $name ){
					if( isset($systemconfig[$name]) ){
						$ret_array['ret']	=	0;
						$ret_array['data']	=	$systemconfig[$name];
					}else{
						$ret_array['ret']	=	10;
						$ret_array['msg']	=	'缺少响应的配置参数';
					}
				}else{
					$ret_array['ret']	=	0;
					$ret_array['data']	=	$systemconfig;
				}
			}catch ( Exception $e ){
				$ret_array['ret']	=	13;
				$ret_array['msg']	=	$e->getMessage();
			}
		}while(0);
		if( empty($ret_array) || !isset($ret_array['ret']) || 0 != $ret_array['ret'] ){
			Common::toTxt( array( 'file' => 'Log_System_getConfigByName.txt', 'txt' => 'Output:'.var_export($ret_array, true) ) );
			exit;//直接将整个程序结束，防止外部错误
		}
		return $ret_array['data'];
	}
	
	/**
	 * 所有接口的签名方式
	 */
	public static function Sign($params=array(), $signkey){
		$retstr = '';
		do{
			if( !is_array($params) ){
				break;
			}
			unset($params['Sign']);
			unset($params['sign']);
			
			if( empty($params) ){
				break;
			}
			
			ksort( $params );//对数组按照key进行排序,保留原来的key值
			$temp	=	array();
			foreach( $params as $key => $value ){
				if( is_array($value) ){
					$tmp = array();
					foreach($value as $val ){
						$tmp[] = $val;	
					}
					$value = '["'.join('","',$tmp).'"]';
				}
				array_push($temp, $value);
			}
			$md5str	=	join('',$temp).$signkey;
			$retstr	=	md5($md5str);
			//echo $md5str, '       ', $retstr;
			//exit;
		}while(0);
		return $retstr;
	}

}
