<?php
/**
 * 非Yii函数公共静态类库
 * @since 2011-11-30
 */
include("protected/extensions/PHPMailer_v5.0.2/class.phpmailer.php");
include("protected/extensions/PHPMailer_v5.0.2/class.smtp.php");
class Common {
	/**
	 * JS弹出对话框并跳转
	 * Enter description here ...
	 * @param $msg （提示信息内容）
	 * @param $url （跳转的URL地址）
	 */
	public static function jsalerturl($msg,$url=''){
		echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
		if(empty($url)){
			echo "<script>alert('".$msg."');history.back();</script>";
		}else{
			echo "<script>alert('".$msg."');window.location='".$url."'</script>";
		}
		exit;
	}
	/**
	 * js弹出框
	 * Enter description here ...
	 * @param $msg （提示内容信息）
	 */
	public static function jsalert($msg){
		echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
		echo "<script>alert('".$msg."');</script>";
		exit;
	}
		/**
	 * 加密，可逆
	 * 可接受任何字符
	 * 安全度非常高
	 * @param  $txt   加密内容
	 * @param  $key   密钥
	 */
	public static function encrypt($txt, $key = CRYPTKEY) {
		$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-@.";
		$ikey = "-x6g6ZWm2G9g_.vr0BopOq3kRIxsZ6rm";
		$nh1 = rand ( 0, 64 );
		$nh2 = rand ( 0, 64 );
		$nh3 = rand ( 0, 64 );
		$ch1 = $chars {$nh1};
		$ch2 = $chars {$nh2};
		$ch3 = $chars {$nh3};
		$nhnum = $nh1 + $nh2 + $nh3;
		$knum = 0;
		$i = 0;
		while ( isset ( $key {$i} ) )
			$knum += ord ( $key {$i ++} );
		$mdKey = substr ( md5 ( md5 ( md5 ( $key . $ch1 ) . $ch2 . $ikey ) . $ch3 ), $nhnum % 8, $knum % 8 + 16 );
		$txt = base64_encode ( $txt );
		$txt = str_replace ( array ('+', '/', '=' ), array ('-', '_', '.' ), $txt );
		$tmp = '';
		$j = 0;
		$k = 0;
		$tlen = strlen ( $txt );
		$klen = strlen ( $mdKey );
		for($i = 0; $i < $tlen; $i ++) {
			$k = $k == $klen ? 0 : $k;
			$j = ($nhnum + strpos ( $chars, $txt {$i} ) + ord ( $mdKey {$k ++} )) % 64;
			$tmp .= $chars {$j};
		}
		$tmplen = strlen ( $tmp );
		$tmp = substr_replace ( $tmp, $ch3, $nh2 % ++ $tmplen, 0 );
		$tmp = substr_replace ( $tmp, $ch2, $nh1 % ++ $tmplen, 0 );
		$tmp = substr_replace ( $tmp, $ch1, $knum % ++ $tmplen, 0 );
		return $tmp;
	}
	
	/**
	 * 修复手机号码
	 * @author cilun
	 * @param $phone 手机号码
	 */
	public static function getPhone ($phone = '') {
		$phone = str_replace("-", '', trim($phone));
		return  substr($phone,-11);
	}
	
	/**
	 * 字符串型过滤函数
	 * @param  $string   字符型
	 */
	public static function getStr($string) {
		if (!get_magic_quotes_gpc()) {
			return addslashes(trim($string));
		}
		return $string;
	}
	/**
	 * 获取IP地址
	 */
	public static function getIp() {
		if( getenv('HTTP_CLIENT_IP') && 'unknown' != getenv('HTTP_CLIENT_IP') ) {
			$IP	=	getenv('HTTP_CLIENT_IP');
		}elseif( getenv('HTTP_X_FORWARDED_FOR') && 'unknown' != getenv('HTTP_X_FORWARDED_FOR') ) {
			$IP =	getenv('HTTP_X_FORWARDED_FOR');
		}elseif( getenv('HTTP_X_FORWARDED') && 'unknown' != getenv('HTTP_X_FORWARDED') ) {
			$IP =	getenv('HTTP_X_FORWARDED');
		}elseif( getenv('HTTP_FORWARDED_FOR') && 'unknown' != getenv('HTTP_FORWARDED_FOR') ) {
			$IP =	getenv('HTTP_FORWARDED_FOR');
		}elseif( getenv('HTTP_FORWARDED') && 'unknown' !=getenv('HTTP_FORWARDED') ) {
			$IP =	getenv('HTTP_FORWARDED');
		}else{
			$IP =	$_SERVER['REMOTE_ADDR'];
		}
		return $IP;
	}
	
	/**
	 * 整型过滤函数
	 * @param  $number   整型
	 */
	public static function getInt($number) {
		return intval(trim($number));
	}
	/**
	 *	获取URL提交过来的参数值，忽略大小写，没有的话，返回默认值
	 */
	public static function getParams($reqname=false, $default=false, $method='GET'){
		$value	=	$default;
		do{
			if( empty($reqname) ){
				break;
			}
				
			if( 'GET' == strtoupper($method) ){
				$fetch	=	$_GET;
			}else if('POST' == strtoupper($method) ){
				$fetch	=	$_POST;
			}else{
				$fetch	=	$_REQUEST;
			}
	
			if( isset($fetch[$reqname]) ){
				$value	=	rawurldecode($fetch[$reqname]);
				break;
			}
	
			$another	=	ucfirst($reqname);
			if( $reqname == $another ){//首字母大小写转换，看是否有该参数
				if(function_exists('lcfirst') === false) {
					function lcfirst($str) {
						$str[0] = strtolower($str[0]);
						return $str;
					}
				}
				$another	=	lcfirst($reqname);
			}
	
			if( isset($fetch[$another]) ){
				$value	=	rawurldecode($fetch[$another]);
				break;
			}
			//没有此参数
		}while(0);
		return $value;
	}
	/**
	 * 匹配手机号码
	 * @author cilun
	 * @param $phone 手机号码
	 */
	public static function matchPhone ($phone = '') {
		return preg_match("/^(0|86|086|\+86|12520){0,1}(13[0-9]|147|15[0-9]|18[0-9])[0-9]{8}$/" , $phone);
	}
	
	public static function matchCalless ($phone = '') {
		return preg_match("/^(?:(?:0[0-9]{2,3}[0-9]{7,8})|(?:1[3|4|5|8][0-9]{9}))$/" , $phone);
	}
	
	public static function matchPasswd ($pwd = '') {
		return preg_match("/^[a-zA-Z0-9_!@#$%\^\&\*]{6,16}$/" , $pwd);
	}
	/**
	 * 匹配用户名
	 * @param string $pwd
	 * @return boolean
	 */
	public static function matchUserName ($uname = '') {
		return preg_match("/^[a-zA-Z0-9_]{3,20}$/" , $uname);
	}
	
	/**
	 * 匹配YYYY-MM-DD HH:II:SS时间
	 * @author nicky
	 * @param $para datetime参数
	 * @return boolean $ret
	 */
	public static function matchDateTime($para = '') {
		$ret = false;
		if ( preg_match('/^2[0-9]{3}\-[0-1][0-9]\-[0-3][0-9]\s{1}[0-2][0-9]:[0-5][0-9]:[0-5][0-9]$/', $para) ) {
			$ret = true;
		}
		return $ret;
	}
	/**
	 * curl post 数据到指定url中
	 * @param  $params   数组
	 */
	public static function curlPost($params = '') {
		$url = $params['url'];
//		$curlPost = $params['post'];
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
//		curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}
	
	/**
	 * curl get 数据到指定url中
	 * @param  $params   数组
	 */
	public static function curlGet($params = '') {
		$url = $params['url'];
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		//curl_setopt($curl, CURLOPT_HEADER, 1);
		curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
		//在需要用户检测的网页里需要增加下面两行
//		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
//		curl_setopt($curl, CURLOPT_USERPWD, US_NAME.":".US_PWD);
		$data = curl_exec($curl);
		curl_close($curl);
//		$data = str_replace("\r\n","",$data);
		return $data;
	}
	
	/**
	 * 以省略形式显示字符串
	 * @author kid
	 * @since 2012-12-17
	 * @param $str,$len
	 */
	public static function subString($text, $start=0, $limit=10,$laststr='...',$charset="GBK")
	{
		if (function_exists('mb_substr')) 
		{
			$more = (mb_strlen($text) > $limit) ? TRUE : FALSE;
			$text = mb_substr($text, 0, $limit, $charset);
			
			return $more?$text.$laststr:$text;
		} elseif (function_exists('iconv_substr')) 
		{
			$more = (iconv_strlen($text) > $limit) ? TRUE : FALSE;
			$text = iconv_substr($text, 0, $limit, $charset);
			//return array($text, $more);
			return $more?$text.$laststr:$text;
		} else 
		{
			preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/", $text, $ar);   
			if(func_num_args() >= 3) {   
				if (count($ar[0])>$limit) {
					$more = TRUE;
					$text = join("",array_slice($ar[0],0,$limit)); 
				} else {
					$more = FALSE;
					$text = join("",array_slice($ar[0],0,$limit)); 
				}
			} else {
				$more = FALSE;
				$text =  join("",array_slice($ar[0],0)); 
			}
			//return array($text, $more);
			return $more?$text.$laststr:$text;
		}
	}
	
	
	/**
	 * 用正则替换字符串
	 * @param string $str
	 * @param string $pattern
	 * @return $newStr
	 */
	public static function getTxt($str , $pattern='/<span[^>]*>/'){
		preg_match_all($pattern,$str, $matches );
		if(!empty($matches[0])){
			$str	=	str_replace('</span>','',str_replace($matches[0][0], '', $str));
		}
		return addslashes($str);
	}
	
	public static function getTxtP($str , $pattern='/<p[^>]*>/'){
		preg_match_all($pattern,$str, $matches );
		if(!empty($matches[0])){
			$str	=	str_replace('</p>','<br/>',str_replace($matches[0][0], '', $str));
		}
		return addslashes($str);
	}
	
	/**
	 * 用正则替换字符串
	 * @author jack 2013-1-9	
	 * @param unknown_type $str
	 * @param unknown_type $newStr
	 * @param unknown_type $pattern
	 * @return mixed
	 */
	public static function repalceImg($str , $pattern='/<img(.*)src="([^"]+)"[^>]+>/isU'){
		$thumbPath	=	Yii::app()->request->baseUrl.'/images/thumbs.jpg';
		if(!empty($str)){
			$str	=	stripslashes($str);
			preg_match_all($pattern,$str, $matches );
			
			//处理$str中的图片
			
			$matches	=	Common::getNeedTxt( $str );
			if( !empty( $matches[0] ) ){
				for( $i=0;$i<count($matches[0]);$i++ ){
					$imgPath = isset($matches[2][$i])?$matches[2][$i]:'';
					$imgHtml = isset($matches[0][$i])?$matches[0][$i]:'';
					$newHtml = "<a href='{$imgPath}' target='_blank'><img class='cThumb-sp' src='{$thumbPath}'></a>";
					$str=str_replace($imgHtml,$newHtml,$str);
				}
			}
		}
		return $str;
	}
	
	/**
	 * 用正则替换字符串
	 * @author jack 2013-1-9
	 * @param unknown_type $str
	 * @param unknown_type $newStr
	 * @param unknown_type $pattern
	 * @return mixed
	 */
	public static function getNeedTxt($str , $pattern='/<img(.*)src="([^"]+)"[^>]+>/isU'){
		preg_match_all($pattern,$str, $matches );
		return $matches;
	}
	
	/**
	 * logs 日志 信息写入txt文件
	 * @author cilun
	 * @param $arr 参数为数组
	 * @param file 日志文件
	 * @param txt 内容
	 * @param isarr 是否打印数组
	 */
	public static function toTxt($arr = '') {
		SecurityAR::toTxt($arr);
	}
	
	public static function parseApk($apkname) {
		include_once  dirname(dirname(__FILE__)).'/extensions/parseApk/apk_parser.php';
		$p = new ApkParser();
		$p->open($apkname);
		return $p;
	}
	
	public static function socketGet($address, $path, $port) {
		$data = '';
		$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		if($socket === false ) {
			echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
			return $data;
		}
		$result = socket_connect($socket, $address, $port);
		if($result) {
	
			$in = "GET {$path} HTTP/1.1\r\n";
			$in .= "HOST: {$address} \r\n";
			$in .= "Connection: close\r\n\r\n";
	
			socket_write($socket, $in, strlen($in));
	
			while ($out = socket_read($socket, 8192)) {
				$data .= $out;
			}
			socket_close($socket);
		}
		return $data;
	}
	public static function get_jump_url($url) {
		$url1 = $url = str_replace(' ','',$url);
		while(true){//do.while循环：先执行一次，判断后再是否循环
	
			if(empty($url)) {
				$url = $url1;
				break;
			}
			if(strpos($url,'play.google.com') >-1){//跳转到google play 就退出
				break;
			} else if (strpos($url, 'market://details') >-1) {
				$url = str_replace('market://', 'https://play.google.com/store/apps/', $url);
				break;
			} else {
				$url1 = $url;
				preg_match('/http[s]?:\/\/([^\/]*)\//',$url, $addr);
				if(isset($addr[1])) {
					$path = str_replace($addr[0], '/', $url);
					$header = self::socketGet($addr[1],$path, 80);
					preg_match('/Location:\s(.*?)\s/i',$header,$tdl);
					$url= isset($tdl[1]) ? $tdl[1] :  null ;
				} else {
					break;
				}
			}
		}
		return $url;
	}
	/**
	 * 随机生成指定长度的字符串
	 */
	public static function GetRandChar($params){
		$value = false;
		do{
			if( !is_array($params) || !isset($params['count']) ){
				break;
			}
	
			$length		=	$params['count'];
			$type		=	isset($params['type']) ? $params['type'] : 1;
	
			if( 1 == $type ){
				$character 	= 	array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
			}
			else if( 2 == $type ){
				$character 	= 	array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z');
			}
			else{
				$character 	= 	array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
			}
			$temp		=	array();
			$count		=	count($character);
			for($i=0;$i<$length;$i++){
				$rand	=	rand(0, $count-1);
				$temp[]	=	$character[$rand];
			}
			$value	=	join('',$temp);//小心首字母可能是0，误认为是八进制
		}while(0);
		return $value;
	}

	public static function genToken( $len = 16, $md5 = true ) {
		# Seed random number generator
		mt_srand( (double)microtime()*1000000 );
		# Array of characters, adjust as desired
		$chars = array(
			'Q', '@', '8', 'y', '%', '^', '5', 'Z', '(', 'G', '_', 'O', '`',
			'S', '-', 'N', '<', 'D', '{', '}', '[', ']', 'h', ';', 'W', '.',
			'/', '|', ':', '1', 'E', 'L', '4', '&', '6', '7', '#', '9', 'a',
			'A', 'b', 'B', '~', 'C', 'd', '>', 'e', '2', 'f', 'P', 'g', ')',
			'?', 'H', 'i', 'X', 'U', 'J', 'k', 'r', 'l', '3', 't', 'M', 'n',
			'=', 'o', '+', 'p', 'F', 'q', '!', 'K', 'R', 's', 'c', 'm', 'T',
			'v', 'j', 'u', 'V', 'w', ',', 'x', 'I', '$', 'Y', 'z', '*'
		);
		# Array indice friendly number of chars;
		$numChars = count($chars) - 1; $token = '';
		# Create random token at the specified length
		for ( $i=0; $i<$len; $i++ )
			$token .= $chars[ mt_rand(0, $numChars) ];
		# Should token be run through md5?
		if ( $md5 ) {
			# Number of 32 char chunks
			$chunks = ceil( strlen($token) / 32 ); $md5token = '';
			# Run each chunk through md5
			for ( $i=1; $i<=$chunks; $i++ )
				$md5token .= md5( substr($token, $i * 32 - 32, 32) );
			# Trim the token
			$token = substr($md5token, 0, $len);
		} return $token;
	}

	/**
	 *邮件发送函数
	 * @param	$fromName	邮件地址
	 * @param	$title		邮件主题
	 * @param	$content	邮件内容
	 * @param	$user_name	发件人邮箱
	 * @param	$password	发件人密码
	 * @param	$acceptName	对收件人称呼
	 * @param	$host		stmp	//若传则默认为stmp.邮箱.com
	 * @param	$replay		array(email,name);	邮件回复人
	 */
	public static function sendMail($fromName,$title,$content,$user_name,$password,$acceptName,$host=null,$replay=null){
		try {
			$mail = new PHPMailer(true);
			$mail->IsSMTP();
			$mail->CharSet = 'UTF-8'; //设置邮件的字符编码，这很重要，不然中文乱码
			$mail->SMTPAuth = true; //开启认证
			$mail->Port = 25;
			$mail->Username = $user_name;
			if($host){
				$mail->Host = $host;
			}else{
				$mail->Host	= 'smtp.'.substr($user_name,strpos($user_name,'@') + 1);
			}
			$mail->Password = $password;
//$mail->IsSendmail(); //如果没有sendmail组件就注释掉，否则出现“Could not execute: /var/qmail/bin/sendmail ”的错误提示
			if($replay){
				$mail->AddReplyTo($replay['email'], $replay['name']);//回复地址
			}else{
				$mail->AddReplyTo($user_name);//回复地址
			}

			$mail->From = $user_name;//设置发件人
			$mail->FromName = $acceptName;//接收人称呼
			$to = $fromName;
			$mail->AddAddress($to);
			$mail->Subject = $title;
			$mail->Body = $content;
			$mail->WordWrap = 80; // 设置每行字符串的长度
//$mail->AddAttachment("f:/test.png"); //可以添加附件
			$mail->IsHTML(true);
			$mail->Send();
			return true;
		}catch (Exception $e){
			return $e->getMessage();
		}
	}

	//替换掉offer的payout
	public static function instantPayout($offer,$affid){
		if(empty($offer)){
			return array();
		}
		//查询cut表中是否设置payout
		$cut = JoyOfferCut::model()->findByAttributes(array('aff_id'=>$affid,'offer_id'=>$offer['id']));
		if(!empty($cut)){
			if(!empty($cut['payout']) && $cut['payout'] != '' && $cut['payout'] != 0){
				$offer->payout = $cut['payout'];
			}
		}
		return $offer;
	}

	/**
	 * 操作记录
	 * @param	$userid	操作者id,
	 * @param	$typeid		记录类型,
	 * @param	$jumpid	跳转id,
	 */
	public static function record($params){
		$record = new JoyRecord();
		$record->createtime = date('Y-m-d');
		$record->userid = $params['userid'];
		$record->typeid = $params['typeid'];
		$joyjump = JoyJump::model()->findByPk($params['jumpid']);
		$user = JoySystemUser::model()->findByPk($params['userid']);
		switch($params['typeid']){
			//删除跳转
			case 1:
				$content = "{$user['title']} 删除了{$joyjump['affid']} 渠道从 {$joyjump['offerid']}跳转到 {$joyjump['offer_url']}";
				break;
			//添加跳转操作
			case 2:
				$content = "{$user['title']} 添加了{$joyjump['affid']} 渠道从 {$joyjump['offerid']}跳转到 {$joyjump['offer_url']}";
				break;
			case 3:
				$content = "{$user['title']} 更新了{$joyjump['affid']} 渠道从 {$joyjump['offerid']}跳转到 {$joyjump['offer_url']}";
				break;
		}
		if(!empty($content)){
			$record->content = $content;
			$record->save();
		}
	}
	public static function download_excel($head,$list,$params = null){
		/** Error reporting */
		error_reporting(E_ALL);
		ini_set('display_errors', TRUE);
		ini_set('display_startup_errors', TRUE);
		date_default_timezone_set('Europe/London');
		if (PHP_SAPI == 'cli')
			die('This example should only be run from a Web Browser');
		/** Include PHPExcel */
		Yii::$enableIncludePath = false;
		Yii::import('application.extensions.PHPExcel.PHPExcel', 1);
		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();
		$key = ord("A");
		foreach($head as $v){
			$colum = chr($key);
			$objPHPExcel->setActiveSheetIndex(0) ->setCellValue($colum.'1', $v);
			$key += 1;
		}
		$exc_colum = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
		foreach($list as $key=>$value) {
			$i = 0;
			foreach($value as $arr_key=>$arr_value){
				$objPHPExcel->getActiveSheet(0)->setCellValue($exc_colum[$i] . ($key + 3), $arr_value);
				$i++;
			}
			$row    =   $key + 3;
			$objPHPExcel->getActiveSheet()->mergeCells("M$row:O$row");
		}
		$fileName   =   date('YmdHis') . 'Report';
		$objPHPExcel->getActiveSheet()->setTitle('Simple');
		$objPHPExcel->setActiveSheetIndex(0);
		// Set document properties
		$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
			->setLastModifiedBy("Maarten Balliauw")
			->setTitle("Office 2007 XLSX Test Document")
			->setSubject("Office 2007 XLSX Test Document")
			->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
			->setKeywords("office 2007 openxml php")
			->setCategory("Test result file");
		// Redirect output to a client��s web browser (Excel2007)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$fileName.'.xlsx"');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
	}

	public static function signUrl($url, $app_secret)
	{
		$sign = null;
		$params = array();
		$url_parse = parse_url($url);
		if (isset($url_parse['query'])) {
			$query_arr = explode('&', $url_parse['query']);
			if (!empty($query_arr)) {
				foreach ($query_arr as $p) {
					if (strpos($p, '=') !== false) {
						list($k, $v) = explode('=', $p);
						$params[$k] = urldecode($v);
					}
				}
			}
		}
		$str = '';
		ksort($params);
		foreach ($params as $k => $v) {
			$str .= "{$k}={$v}";
		}
		$str .= $app_secret;
		$sign = md5($str);
		return $url . "&sign={$sign}";
	}
	public static function getPublisherId($affid,$country,$offerid){
		return $affid.'_'.bin2hex($country. '_' . $offerid);
	}
	public static function publisherToArray($hex){
		$params['affid'] = substr($hex,0,strpos($hex,'_'));
		$country_with_offerid = substr($hex,strpos($hex,'_') + 1);
		$country_with_offerid_str = hex2bin($country_with_offerid);
		$params['country'] = substr($country_with_offerid_str,0,strpos($country_with_offerid_str,'_'));
		$params['offerid'] = substr($country_with_offerid_str,strpos($country_with_offerid_str,'_') + 1);
		return $params;
	}
}
