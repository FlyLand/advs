<?php

/** 
 * @author Administrator
 * 
 * 
 */
class Helper extends CController{
	//TODO - Insert your code here
	private $_name = '';
	private $_filePath = '';
	function __construct() {
	
		//TODO - Insert your code here
	}
	public function import($name){
		
		$this->_name = $name;
		$this->fileName();
		if(method_exists($this, $this->_name)){
			$fun = $this->_name;
			$$fun = $_data = $this->$fun();
		}
		include_once($this->_filePath);
		//include_once(dirname(dirname(__FILE__)).'/views/helper/'.$this->_name.'.php');
	}
	
	/**
	 * 
	 * Enter description here ...
	 */
	private function fileName(){
		$name = explode('.',$this->_name);
		$this->_name = $name[0];
		
		$filePath = Yii::app()->viewPath.'/helper/'.$this->_name.'.php';
		
		if(file_exists($filePath)){
			$this->_filePath = $filePath;
		}else{
			echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
			echo "文件不存在：".$filePath;
			exit;
		}
	}
	/**
	 * 
	 */
	function __destruct() {
	
		//TODO - Insert your code here
	}
}

?>