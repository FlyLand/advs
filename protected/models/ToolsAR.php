<?php

class ToolsAR extends CActiveRecord {
	
	public static function getNationByIp($ip) {
		include_once(dirname(dirname(__FILE__)).'/extensions/ipLocation.php');
		$p = new ipLocation(dirname(dirname(__FILE__)).'/extensions/CoralWry.dat');
		$area =  $p->substr_address($ip);
		return $area;
	
	}
	
}

?>