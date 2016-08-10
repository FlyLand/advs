<?php
/**
 * 安全接口模块
 * @tutorial 用户模块的接口，通讯录模块的接口等
 * @author cilun
 * @since 2011.8.23
 */
class SecurityAR extends CActiveRecord {
	public static $skey_word 	= 	'dfj54gpw54ojg4w2s545eg';
	
	/**
	 * 获取用户的安全信息
	 */
	public static function getSkey($params) {
		$ret_array = array('ret'=>-1, 'msg'=>'', 'occur'=>'SecurityAR_getSkey', 'error'=>'', 'data'=>'');
		do{
			try{
				if( !is_array($params) || !isset($params['phone']) || !isset($params['btype']) ){
					$ret_array['msg']	= 	'参数类型错误';
					break;
				}
				if( !preg_match('/\s*\d{11,11}\s*/', $params['phone']) || !is_int($params['btype']) ){
					$ret_array['msg']	= 	'参数类型错误';
					break;
				}
				$phone		=	trim($params['phone']);
				$btype		=	intval($params['btype']);
				$mackey		=	'SKEY_'.$btype.'_'.$phone;
				
				//获取用户的安全码信息
				$skey_info	=	CfgAR::getMc(array('link'=>'usermc', 'key'=>$mackey));	
				if( is_array($skey_info) && isset($skey_info['skey_md5']) ){//从缓存中获取到了用户信息
					$ret_array['ret']	= 	0;
					$ret_array['data']	= 	$skey_info;
					//break;
				}
				
				$skey_file	=	DIRLOGS.'skey/'.$phone.'.dat';
				if( file_exists($skey_file) ){
					$file_data	=	substr(trim(file_get_contents($skey_file)), 25);
					$skey_info	=	json_decode( $file_data, true);
					if( $skey_info ){//转化成功
						CfgAR::setMc(array('link'=>'usermc', 'key'=>$mackey, 'data'=>$skey_info, 'time'=>86400));
						$ret_array['ret']	= 	0;
						$ret_array['data']	= 	$skey_info;
						break;
					}
				}
				
				$result = SecurityAR::newSkey(array('phone'=>$phone, 'btype'=>$btype));
				$ret_array['ret']		= 	0;//操作成功
				$ret_array['data']		=	$result['data'];
			}catch(Exception $e){
				$ret_array['error']		=	$e->getMessage();
				break;
			}
		}while(0);
		if( 0 != $ret_array['ret'] ){
			Common::toTxt(array('file'=>'Log_SecurityAR_getKey.txt', 'txt'=>var_export($ret_array,true)));
		}
		return $ret_array;
	}
	
	/**
	 * 产生新的用户安全信息 
	 */
	public static function newSkey($params){
		$ret_array = array('ret'=>-1, 'msg'=>'', 'occur'=>'SecurityAR_newSkey', 'error'=>'', 'data'=>'');
		do{
			if( !is_array($params) || !isset($params['phone']) || !isset($params['btype']) ){
				$ret_array['msg']	= 	'参数类型错误';
				break;
			}
			
			if( !preg_match('/\s*\d{11,11}\s*/', $params['phone']) || !is_int($params['btype']) ){
				$ret_array['msg']	= 	'参数类型错误';
				break;
			}
			
			$phone		=	trim($params['phone']);
			$btype		=	intval($params['btype']);
			$mackey		=	'SKEY_'.$btype.'_'.$phone;
			$skey_file	=	'skey/'.$phone.'.dat';
			$lasttime	=	time();//当前时间
			$result		=	SecurityAR::getRandChar(array('count'=>10));
			$skey_rand	=	$result['data'];
			$skey_md5	=	md5($phone.self::$skey_word.$skey_rand);
			$skey_info	=	array('phone'=>$phone, 'skey_rand'=>$skey_rand,  'skey_word'=>self::$skey_word, 'skey_md5'=>$skey_md5, 'mktime'=>time());
			SecurityAR::writeOnce(array('file'=>$skey_file, 'txt'=>json_encode($skey_info)));
			CfgAR::setMc(array('link'=>'usermc', 'key'=>$mackey, 'data'=>$skey_info, 'time'=>86400));
			$ret_array['ret']	= 	0;//操作成功
			$ret_array['data']	=	$skey_info;
		}while(0);
		return $ret_array;
	}
	
	/**
	 * 随机生成指定长度的字符串
	 */
	public static function getRandChar($params){
		$ret_array = array('ret'=>-1, 'msg'=>'', 'occur'=>'SecurityAR_getRandChar', 'error'=>'', 'data'=>'');
		do{
			if( !is_array($params) || !isset($params['count']) ){
				$ret_array['msg']	= 	'参数类型错误';
				break;
			}
			if( !is_int($params['count']) ){
				$ret_array['msg']	= 	'参数类型错误';
				break;
			}
			$count		=	$params['count'];
			$character 	= 	array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9');	
			$temp		=	array();
			do{
				$rand	=	rand(0, $count-1);
				$vale	=	$character[$rand];
				if( in_array($vale, $temp) ){
					continue;
				}
				$temp[]	=	$vale;
			}while( count($temp) < $count );
			$ret_array['ret']	=	0;
			$ret_array['data']	=	join('',$temp);
		}while(0);
		return $ret_array;
	}
	
	/**
	 * 写数据到文件,覆盖之前的内容
	 */
	public static function writeOnce($params){
		$ret_array = array('ret'=>-1, 'msg'=>'', 'occur'=>'SecurityAR_writeOnce', 'error'=>'', 'data'=>'');
		do{
			try{
				if( !is_array($params) || !isset($params['file']) || !isset($params['txt']) ){
					$ret_array['msg']	= 	'参数类型错误';
					break;
				}
				if( !is_string($params['file']) ){
					$ret_array['msg']	= 	'参数类型错误';
					break;
				}
				$params['cls']	=	true;	
				$ret_array		=	SecurityAR::writeFile($params);
			}catch(Exception $e){
				$ret_array['error']	= 	$e->getMessage();
			}
		}while(0);
		if( 0 != $ret_array['ret'] ){
			Common::toTxt(array('file'=>'Log_SecurityAR_writeOnce.txt', 'txt'=>var_export($ret_array,true)));
		}
		return $ret_array;
	}
	
	/**
	 * 增量写入文件
	 */
	public static function toTxt($params){
		$ret_array = array('ret'=>-1, 'msg'=>'', 'occur'=>'SecurityAR_writeOnce', 'error'=>'', 'data'=>'');
		do{
			try{
				if( !is_array($params) || !isset($params['file']) || !isset($params['txt']) ){
					$ret_array['msg']	= 	'参数类型错误';
					break;
				}
				if( !is_string($params['file']) ){
					$ret_array['msg']	= 	'参数类型错误';
					break;
				}
				$params['cls']	=	false;
				$ret_array		=	SecurityAR::writeFile($params);
			}catch(Exception $e){
				$ret_array['error']	= 	$e->getMessage();
			}
		}while(0);
		if( 0 != $ret_array['ret'] ){
			Common::toTxt(array('file'=>'Log_SecurityAR_toTxt.txt', 'txt'=>var_export($ret_array,true)));
		}
		return $ret_array;
	}
	
	/**
	 * 写数据文件
	 */
	public static function writeFile($params){
		$ret_array = array('ret'=>-1, 'msg'=>'', 'occur'=>'SecurityAR_writeFile', 'error'=>'', 'data'=>'');
		do{
			try{
				if( !is_array($params) || !isset($params['file']) || !isset($params['txt'])  || !isset($params['cls']) ){
					$ret_array['msg']	= 	'参数类型错误';
					break;
				}
				if( !is_string($params['file']) || !is_bool($params['cls']) ){
					$ret_array['msg']	= 	'参数类型错误';
					break;
				}
				$write_file			=	trim($params['file']);
				if( is_object($params['txt']) || is_array($params['txt']) ){
					$write_txt		=	"Date:".date('Y-m-d H:i:s')."|".var_export($params['txt'], true)."\r\n";
				}else{
					$write_txt		=	"Date:".date('Y-m-d H:i:s')."|".$params['txt']."\r\n";;//文件内容
				}
				$fmode				=	$params['cls'] ? 'w' : 'a';//清空文件或追加写入 
				$absolute_dir		=	dirname(DIRLOGS.$write_file);
				if( !is_dir($absolute_dir) ){//文件目录还没建立
					$absolute_dir 	= 	DIRLOGS;//这里不建议赋值DIRLOGS
					$explodes		=	explode('/', $write_file);
					for( $i=0, $count = count($explodes)-1; $i < $count; $i++ ){
						if( '' == trim($explodes[$i]) ){//防止传入的文件名是/xxx/yyy.txt格式
							continue;
						}
						if( !is_dir($absolute_dir.$explodes[$i]) ){
							mkdir($absolute_dir.$explodes[$i]);
						}
						$absolute_dir .= $explodes[$i].'/';
					}
				}
				
				$fopen	= 	fopen(DIRLOGS.$write_file, $fmode);
				$fbyte	=	fwrite($fopen, $write_txt);
				fclose($fopen);
				if( !$fbyte || strlen($write_txt) != $fbyte ){
					$ret_array['msg']	= 	'写入文件失败，文件名:'.$write_file.', 内容是:'.$write_txt;
					break;
				}
				$ret_array['ret']	= 	0;
			}catch(Exception $e){
				$ret_array['error']	= 	$e->getMessage();
			}
		}while(0);
		if( 0 != $ret_array['ret'] ){
			Common::toTxt(array('file'=>'Log_SecurityAR_writeFile.txt', 'txt'=>var_export($ret_array,true)));
		}
		return $ret_array;
	}
}