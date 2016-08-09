<?php
Class TestController extends Controller{
    public function actionCache(){
        $db = Yii::app()->db;
        $sql = "select u.company as aff_name,t.count_date as mon ,t.affid,t.amount,t.id,t.fee,m.company as am_name from joy_invoice t LEFT JOIN
joy_system_user u on u.id=t.affid LEFT JOIN
joy_system_user m on t.am_id=m.id
ORDER BY aff_name";
        $command = $db->createCommand($sql);
        $result = $command->queryAll();
        $head_arr = array('Payee',	'Month',	'ID',	'Amount Payable','Total','Balance','AM');
        /*$head = $result[0];
        $head_arr = array();
        while ($head_name = key($head)) {
            array_push($head_arr,$head_name);
            next($head);
        }*/
        Tools::download_excel($head_arr,$result);
    }

    public function actionOfferHandle(){
        $filter = new JumpFilter('CountryHandle');
        var_dump($filter->setMembers());

    }
}
?>