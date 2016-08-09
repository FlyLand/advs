<?php
/**
 *  预处理统计数据
 * 
 * 
 * 
 * 
 * 
 */
class SyncCommand extends CConsoleCommand {
	public function actionIndex() {
		ini_set ( "memory_limit", "256M" );
		MongoCursor::$timeout = - 1;
		// 预处理每天的数据
		// echo "start...\n";
		$day = date ( 'Y-m-d', strtotime ( '-1 day' ) );
		
		Sync::model ()->syncCharge ( $day ); // 预处理前一天的的充值数据
		echo "end syncCharge\n";
		Sync::model ()->syncTotal ( $day ); // 预处理总统计
		echo "end syncTotal\n";
		Sync::model ()->syncDayTotal ( $day );
		echo "end syncDayTotal\n";
		Sync::model ()->syncFeecback ( $day );
		echo "end syncFeecback\n";
		Sync::model ()->syncPlatform ( $day );
		echo "end syncPlatform\n";
		
		Sync::model ()->syncCurDayReg( $day );
		echo "end syncCurDayReg\n\n";
		
		echo "start syncCallUnpayed\t ".date('Y-m-d H:i:s')."\n" ;
		Sync::model ()->syncCallUnpayed ( $day );
		echo "end syncCallUnpayed\t ".date('Y-m-d H:i:s')."\n";
		
		echo "start SyncWeekRegRec\t ".date('Y-m-d H:i:s')."\n" ;
		Sync::model ()->SyncWeekRegRec ( $day );
		echo "end SyncWeekRegRec\t ".date('Y-m-d H:i:s')."\n";
		
// 		Sync::model ()->SendReport();
// 		echo "\nend SendReport\n";
		echo "\nend actionIndex\n";
	}
	
	public function actionDay($day) {
		ini_set ( "memory_limit", "256M" );
		MongoCursor::$timeout = - 1;
		if (empty ( $day )) {
			$day = date ( 'Y-m-d', strtotime ( '-1 day' ) );
		}
		Sync::model ()->syncTotal ( $day );
		echo "end syncTotal\n";
		/*
		 * Sync::model()->syncDayTotal($day); echo "end syncDayTotal\n";
		 * Sync::model()->syncCharge($day); echo "end syncCharge\n";
		 * Sync::model()->syncFeecback($day); echo "end syncFeecback\n";
		 * Sync::model()->syncPlatform($day); echo "end syncPlatform\n";
		 */
	}
	public function actionDays() {
		ini_set ( "memory_limit", "256M" );
		MongoCursor::$timeout = - 1;
		$days = array ( );
		for($i=1;$i<32;$i++){
			if($i<10) $s = '0'.$i;
			else $s= $i;
			$days[]='2013-08-'.$s;
		}
		foreach ( $days as $day ) {
			Sync::model()->syncCharge($day);
			echo "end syncCharge\n";
			Sync::model()->syncTotal($day);
			echo "end syncTotal\n";
			Sync::model()->syncDayTotal($day);
			echo "end syncDayTotal\n";
			Sync::model()->syncFeecback($day);
			echo "end syncFeecback\n";
			Sync::model()->syncPlatform($day);
			echo "end syncPlatform\n";
			Sync::model()->syncCurDayReg($day);
			echo "end syncCurDayReg\n";
			Sync::model()->syncCallUnpayed($day);
			echo "end syncCallUnpayed\n";
			Sync::model()->SyncWeekRegRec($day);
			echo "end SyncWeekRegRec\n";
// 			Sync::model ()->SendReport ( $day );
// 			echo "end SendReport\n";
			echo "$day\n";
		}
	}
	
	public function actionBtype14() {
		ini_set ( "memory_limit", "256M" );
		MongoCursor::$timeout = - 1;
		// 预处理每天的数据
		// echo "start...\n";
		$day = date ( 'Y-m-d', strtotime ( '-1 day' ) );
	
		SyncB14::model ()->syncCharge ( $day ); // 预处理前一天的的充值数据
		echo "end syncCharge\n";
		SyncB14::model ()->syncTotal ( $day ); // 预处理总统计
		echo "end syncTotal\n";
		SyncB14::model ()->syncDayTotal ( $day );
		echo "end syncDayTotal\n";
		SyncB14::model ()->syncFeecback ( $day );
		echo "end syncFeecback\n";
		SyncB14::model ()->syncPlatform ( $day );
		echo "end syncPlatform\n";
	
		SyncB14::model ()->syncCurDayReg( $day );
		echo "end syncCurDayReg\n\n";
	
		echo "start syncCallUnpayed\t ".date('Y-m-d H:i:s')."\n" ;
		SyncB14::model ()->syncCallUnpayed ( $day );
		echo "end syncCallUnpayed\t ".date('Y-m-d H:i:s')."\n";
	
		echo "start SyncWeekRegRec\t ".date('Y-m-d H:i:s')."\n" ;
		SyncB14::model ()->SyncWeekRegRec ( $day );
		echo "end SyncWeekRegRec\t ".date('Y-m-d H:i:s')."\n";
	
		echo "\nend actionIndex\n";
	}
	
}
?>