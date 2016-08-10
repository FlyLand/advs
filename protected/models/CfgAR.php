<?php
/**
 * 链接服务器Memcache，Mysql，Mongo接口模块
 */
class CfgAR extends CActiveRecord {
	
	/**
	 * 设置指定键的缓存信息，如果无法连接memcache则返回端口错误
	 * @param data string -- 内容
	 * @param link string -- 连接值
	 * @param key string -- 连接memcache键
	 * @param time int -- 过期时间，默认 0 不过期
	 */
	public static function setMc($params) {
		$ret_array = array('ret'=>-1, 'msg'=>'', 'occur'=>'CfgAR_setMc', 'error'=>'', 'data'=>false);
		do{
			try{
				if( !is_array($params) || !isset($params['link']) || !isset($params['key']) || !isset($params['data']) || empty($params['link']) || empty($params['key']) ){
					$ret_array['ret']	=	1;
					$ret_array['msg']	=	'服务器忙，请稍后再试!';
					break;
				}

				$link	=	$params['link'];
				$data	=	$params['data'];
				$key	=	trim($params['key']);
				$time	=	isset($params['time']) ? intval($params['time']) : 0;
				
				$result	=	Yii::app()->$link->set($key, $data, $time);

				$ret_array['ret']	=	0;
				$ret_array['data']	=	$result;
					
			}catch (Exception $e) {
				$ret_array['ret']	=	13;
				$ret_array['msg']	=	'服务器忙，请稍后再试!';
				$ret_array['error']	=	$e->getMessage();
			}
		}while(0);
		
		if( 0 != $ret_array['ret'] ){
			Common::toTxt(array('file'=>'Log_CfgAR_setMc.txt', 'txt'=>'Input:'.var_export($params, true).'|Output:'.var_export($ret_array, true)));
		}
		return $ret_array['data'];
	}
	
	/**
	 * 添加指定键的缓存信息，如果无法连接memcache则返回端口错误
	 * @param data string -- 内容
	 * @param link string -- 连接值
	 * @param key string -- 连接memcache键
	 * @param time int -- 过期时间，默认 0 不过期
	 */
	public static function addMc($params) {
		$ret_array	=	array('ret'=>1, 'msg'=>'', 'occur'=>'CfgAR_addMc', 'error'=>'', 'data'=>false);
		do{
			try{
				if( !is_array($params) || !isset($params['link']) || !isset($params['data']) || !isset($params['key']) || empty($params['link']) || empty($params['key']) ){
					$ret_array['ret']	=	1;
					$ret_array['msg']	=	'调用参数错误';//前端提示此消息，一定是开发责任
					break;
				}

				$link	=	$params['link'];
				$data	=	$params['data'];
				$key	=	$params['key'];
				$time	=	isset($params['time']) ? intval($params['time']) : 0;
				$result	=	Yii::app()->$link->add($key, $data, $time);				

				$ret_array['ret']	=	0;
				$ret_array['data']	=	$result;
			}catch (Exception $e) {
				$ret_array['ret']	=	13;
				$ret_array['msg']	=	'服务器忙，请稍后再试';
				$ret_array['error']	=	$e->getMessage();
			}
		}while(0);
		if( 0 != $ret_array['ret'] ){
			Common::toTxt(array('file'=>'Log_CfgAR_addMc.txt', 'txt'=>'Input:'.var_export($params, true).'|Output:'.var_export($ret_array, true)));
		}
		return $ret_array['data'];
	}
	
	/**
	 * 获取指定键的缓存信息，如果无法连接memcache则返回端口错误
	 * @param name string -- 连接memcache值
	 * @param key string -- 连接memcache键
	 */
	public static function getMc($params) {
		$link	=	isset($params['link']) ? $params['link'] : '';
		$key	=	isset($params['key']) ? $params['key'] : '';
		if (!empty($link) && !empty($key)) {
			try {
				return Yii::app()->$link->get($key);
			} catch (Exception $e) {
				return false;//-11211
			}
		} else {
			return false;
		}
	}
	
	/**
	 * 获取缓存内容
	 * @param unknown_type $params
	 * @return boolean
	 */
	public static function getMem($params) {
		$ret_array = array('ret'=>-1, 'msg'=>'', 'occur'=>'CfgAR_getMem', 'error'=>'', 'data'=>'');
		do{
			try{
				if( !is_array($params) || !isset($params['link']) || !isset($params['key']) ){
					$ret_array['ret']	=	1;
					$ret_array['msg']	=	'服务器忙，请稍后再试!';
					break;
				}

				$link	=	 $params['link'];
				$key	=	 $params['key'];
				$data	=	 Yii::app()->$link->get($key);//如果Memcache中没有找到，则返回false

				$ret_array['ret']	=	0;
				$ret_array['data']	=	$data;
			}catch (Exception $e) {
				$ret_array['ret']	=	13;
				$ret_array['msg']	=	'服务器忙，请稍后再试!';
				$ret_array['error']	=	$e->getMessage();
			}
		}while(0);
		if( 0 != $ret_array['ret'] ){
			Common::toTxt(array('file'=>'Log_CfgAR_getMem.txt', 'txt'=>'Input:'.var_export($params, true).'|Output:'.var_export($ret_array, true)));
		}
		return $ret_array;
	}
	/**
	 * 获取Memcache进程池的缓存信息，如果无法连接memcache则返回端口错误
	 * @author cilun
	 * @copyright fzd
	 * @param name string -- 连接memcache值
	 * @param key string -- 连接memcache键
	 */
	public static function getExtendedStats($params = '') {
		$link	=	isset($params['link'])	?	$params['link']	:	'';
		$key	=	isset($params['key'])	?	$params['key']	:	'';
		$ret	=	'';
		if (!empty($link) && !empty($key)) {
			try {
				$ret	=	Yii::app()->$link->getExtendedStats($key);
			} catch (Exception $e) {
			}
		}
		return	$ret;
	}
	/**
	 * 删除指定键的缓存信息，如果无法连接memcache则返回端口错误
	 * @param name string -- 连接memcache值
	 * @param key string -- 连接memcache键
	 */
	public static function delMc( $params = array() ){
		$result	=	self::delMem($params);
		if( 0 != $result['ret'] ){
			return false;
		}
		return $result['data'];
	}

	public static function delMem($params=array()){
		$ret_array	=	array('ret'=>1, 'msg'=>'服务器忙，请稍后再试', 'occur'=>'CfgAR::delMem', 'error'=>'', 'data'=>false);
		do{
			try{
				if( !is_array($params) || !isset($params['link']) || !isset($params['key']) ){
					$ret_array['msg']	=	'调用参数缺失';
					break;
				}

				$link	=	trim($params['link']);
				$key	=	trim($params['key']);
				$more	=	isset($params['more']) ? $params['more']: false;

				$result	=	Yii::app()->$link->delete($key);
				if( $more ){
					$temp	=	is_bool($more) ? '1+2' : $more;
					$prefix	=	explode('+', $temp);
					foreach($prefix as $fpre){
						//if( 2 == $fpre && !preg_match('/'.UID_USERINFO.'|WCG_CALL|CHT_SMSCODEqq|SHOW_PHONE_LISTS_CACHE|SIXIN_MSG_USER/', $key) ){//如果不是用户信息、短信或WCG的信息
						//	continue;
						//}
						$params['action']	=	'del';
						$filename	=	LOGS_ROOT.'cache/'.$fpre.'_'.date('YmdHis').$key.'.txt';
						$filename	=	preg_replace(array('/:/', '/\s/'), '', $filename);
						file_put_contents($filename,  '<?php return '.var_export($params, true).'; ?>');
					}
				}

				$ret_array['ret']	=	0;
				$ret_array['data']	=	$result;
			}catch( Exception $e ){
				$ret_array['ret']	=	0;
				$ret_array['error']	=	$e->getMessage();	
			}

		}while(0);
		if( 0 != $ret_array['ret'] ){
			Common::toTxt(array('file'=>'Log_Common_delMem.txt', 'txt'=>'Input:'.var_export($params, true).'|Output:'.var_export($ret_array, true)));
		}
		return $ret_array;
	}
	
	/**
	 * 删除指定端口的缓存信息，如果无法连接memcache则返回端口错误
	 * @param name string -- 连接memcache值
	 * @param key string -- 连接memcache键
	 */
	public static function fusMc($params = array()) {
		$link = isset($params['link']) ? $params['link'] : '';
		if (!empty($link)) {
			try {
				return Yii::app()->$link->flush();
			} catch (Exception $e) {
				return false;//-11211
			}
		} else {
			return false;
		}
	}
	
	/**
	 * 设置数据库链接信息，如果无法连接数据库则返回端口错误
	 * @param key string -- 连接数据库键
	 */
	public static function setDbLink($link) {
		if (!empty($link)) {
			try {
				return Yii::app()->$link;
			} catch (Exception $e) {
				return '-3306';
			}
		} else {
			return '-3306';
		}
	}
	
	/**
	 * 设置MongoDB数据库链接信息，如果无法连接数据库则返回端口错误
	 * @param key string -- 连接数据库键
	 */
	public static function setMgDbLink($link) {
		if (!empty($link)) {
			try {
				return $connection = Yii::app()->$link->getDbInstance();
			} catch (Exception $e) {
				return '-27017';
			}
		} else {
			return '-27017';
		}
	}
	
	/**
	 * 设置json信息
	 * @param data string -- 内容
	 */
	public static function enJson($data = '') {
		if (!empty($data)) {
			$args 			= array();
			if( isset($data['ret']) ){
				$args['ret'] 	= $data['ret'];
				$args['msg'] 	= isset( $data['msg'] ) ? $data['msg'] : '';
				$args['data'] 	= isset( $data['data'] ) ? $data['data'] : '';
			}else{
				$args	=	$data;
			}
			try {
				if( isset( $_GET['callback'] ) && !empty( $_GET['callback'] ) ){
					header('Content-type: text/javascript');
					return $_GET['callback'].'('. CJSON::encode($args) .')';
				}else{
					return CJSON::encode($args);
				}
			} catch (Exception $e) {
				return false;
			}
		} else {
			return false;
		}
	}
	
	/**
	 * 解码json信息
	 * @param data string -- 内容
	 */
	public static function deJson($data = '') {
		if (!empty($data)) {
			try {
				return CJSON::decode($data);
			} catch (Exception $e) {
				return false;
			}
		} else {
			return false;
		}
	}

	
	/**
	 * add设置 session信息
	 * @author cilun
	 * @copyright fzd
	 * @param data string -- 内容
	 */
	public static function addSession($params = '') {
		$key	=	isset($params['key'])	?	$params['key']	:	'';
		$data	=	isset($params['data'])	?	$params['data']	:	'';
		$ret	=	'';
		if (!empty($key)) {
			try {
				$ret	=	Yii::app()->session->add($key,$data);
			} catch (Exception $e) {
			}
		}
		return	$ret;
	}
	
	/**
	 * 设置 session信息
	 * @author cilun
	 * @copyright fzd
	 * @param data string -- 内容
	 */
	public static function setSession($params = '') {
		$key	=	isset($params['key'])	?	$params['key']	:	'';
		$data	=	isset($params['data'])	?	$params['data']	:	'';
		$ret	=	'';
		if (!empty($key)) {
			try {
				$ret	=	Yii::app()->session["$key"] = $data;
			} catch (Exception $e) {
			}
		}
		return	$ret;
	}
	
	/**
	 * 获取 session信息
	 * @author cilun
	 * @copyright fzd
	 * @param data string -- 内容
	 */
	public static function getSession($params = '') {
		$key	=	isset($params['key'])	?	$params['key']	:	'';
		$ret	=	'';
		if (!empty($key)) {
			try {
				$ret	= Yii::app()->session["$key"];
			} catch (Exception $e) {
			}
		}
		return	$ret;
	}
	
	/**
	 * unset session信息
	 * @author cilun
	 * @copyright fzd
	 * @param data string -- 内容
	 */
	public static function unsetSession($params = '') {
		$key	=	isset($params['key'])	?	$params['key']	:	'';
		if (!empty($key)) {
			try {
				unset(Yii::app()->session["$key"]);
			} catch (Exception $e) {
				return '';
			}
		} else {
			return '';
		}
	}
	
	/**
	 * 清空 session信息
	 * @author cilun
	 * @copyright fzd
	 * @param data string -- 内容
	 */
	public static function clearSession($params = '') {
		try {
			Yii::app()->session->clear();
		} catch (Exception $e) {
			return '';
		}
	}
	
	/**
	 * 销毁 session信息
	 * @author cilun
	 * @copyright fzd
	 * @param data string -- 内容
	 */
	public static function destroySession($params = '') {
		try {
			Yii::app()->session->destroy();
		} catch (Exception $e) {
			return '';
		}
	}
	
	/**
	 * 获取 session id 的信息
	 * @author cilun
	 * @copyright fzd
	 * @param data string -- 内容
	 */
	public static function sessionID() {
		try {
			return Yii::app()->session->sessionID;
		} catch (Exception $e) {
			return '';
		}
	}
	
}