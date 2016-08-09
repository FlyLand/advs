<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/8/27
 * Time: 9:52
 */
class ReportController extends Controller{
    public function __construct(){
        $this->checkAction();
    }
    /**
    */
public function actionDailyReportIm(){
        $date = Yii::app()->request->getParam('count_date');
        if(empty($date) || $date == date('Y-m-d')){
            $date_str = '';
            $date = date('Y-m-d');
            $count_sql = "select e.affid,e.impression,e.offerid,t.revenue,t.payout,t.coun,t.ispostbacked from (
(select count(*) as impression,affid,offerid from joy_transaction GROUP BY affid,offerid)e
left JOIN 
(SELECT ispostbacked,affid,offerid,sum(revenue) as revenue,sum(payout) as payout,count(*) as coun from joy_transaction_income WHERE createtime >= '$date 00:00:00' AND createtime <= '$date 23:59:59' GROUP BY affid,offerid,ispostbacked)t
on e.affid=t.affid and e.offerid = t.offerid)";
        }else{
            $date_str = date('Ymd',strtotime($date));
            $count_sql = "select e.affid,e.impression,e.offerid,t.revenue,t.payout,t.coun,t.ispostbacked from (
(select count(*) as impression,affid,offerid from joy_transaction$date_str GROUP BY affid,offerid)e
left JOIN 
(SELECT ispostbacked,affid,offerid,sum(revenue) as revenue,sum(payout) as payout,count(*) as coun from joy_transaction_income WHERE createtime >= '$date 00:00:00' AND createtime <= '$date 23:59:59' GROUP BY affid,offerid,ispostbacked)t
on e.affid=t.affid and e.offerid = t.offerid)";
        }
        $table_name = "joy_transaction$date_str";
        $table_select = "SHOW TABLES LIKE '$table_name' ";
        $table_command = Yii::app()->db->createCommand($table_select);
        $table_result = $table_command->queryRow();
        if($table_result){
            $all_transaction_sql = "select * from $table_name";
            $transaction_command = Yii::app()->db->createCommand($all_transaction_sql);
            $all_transaction = $transaction_command->queryAll();
            $all_income_sql = "select * from joy_transaction_income  WHERE createtime >= '$date 00:00:00' AND createtime <= '$date 23:59:59' ";
            $income_command = Yii::app()->db->createCommand($all_income_sql);
            $all_income = $income_command->queryAll();
            Yii::app()->cache->set($this->user['userid'] . 'daily_transaction',$all_transaction);
            Yii::app()->cache->set($this->user['userid'] . 'daily_income',$all_income);
            $command = Yii::app()->db->createCommand($count_sql);
            $count_result = $command->queryAll();
        }else{
            $all_transaction = null;
            $count_result = null;
        }
        $this->render('report/all_transaction',array(
            'transaction'=>$all_transaction,
            'count_date'=>$date,
            'count_result'=>$count_result,
        ));
    }
    public function actionDailyReport(){
        $type = Yii::app()->request->getParam('type');
        $start_date = $end_date = date('Y-m-d');
        $data = array('select'=>array(),'table'=>array());
        if($type){
            $condition = ' 1=1 ';
            $u1 = '';
            $u2 = '';
            $u3 = '';
            $off = '';
            $ig = '';
            $company = Yii::app()->request->getParam('company');
            $affiliate_id = Yii::app()->request->getParam('affiliate_id');
            $reference_id = Yii::app()->request->getParam('reference_id');
            $affiliate_source = Yii::app()->request->getParam('affiliate_source');
            $affiliate_manager = Yii::app()->request->getParam('affiliate_manager');
            $sub1 = Yii::app()->request->getParam('sub1');
            $sub2 = Yii::app()->request->getParam('sub2');
            $sub3 = Yii::app()->request->getParam('sub3');
            $sub4 = Yii::app()->request->getParam('sub4');
            $sub5 = Yii::app()->request->getParam('sub5');
            $offer = Yii::app()->request->getParam('offer');
            $goal = Yii::app()->request->getParam('goal');
            $goal_id = Yii::app()->request->getParam('goal_id');
            $offer_url = Yii::app()->request->getParam('offer_url');
            $advertiser = Yii::app()->request->getParam('advertiser');
            $advertiser_id = Yii::app()->request->getParam('advertiser_id');
            $advertiser_manager = Yii::app()->request->getParam('advertiser_manager');
            $payout_type = Yii::app()->request->getParam('payout_type');
            $category = Yii::app()->request->getParam('category');
            $cost = Yii::app()->request->getParam('cost');
            $profit = Yii::app()->request->getParam('profit');
            $clicks = Yii::app()->request->getParam('clicks');
            $conversions = Yii::app()->request->getParam('conversions');

            $fields = ' t.offerid ,';
            array_push($data['table'],'offer_id');
            if(empty($clicks) && empty($conversions) && empty($cost) && empty($profit)){
                $conversions = 'on';
                $cost = 'on';
                $profit = 'on';
                $clicks = 'on';
            }

            if(!empty($company)){
                array_push($data['select'],'company');
                array_push($data['table'],'Affiliate');
                $fields .= 'u1.company as aff,';
            }
            if(!empty($affiliate_id)){
                array_push($data['select'],'affiliate_id');
                array_push($data['table'],'AffiliateId');
                $fields .= 't.affid,';
            }
            if(!empty($reference_id)){
                array_push($data['select'],'reference_id');
                array_push($data['table'],'ReferenceId');
            }
            if(!empty($affiliate_source)){
                array_push($data['select'],'affiliate_source');
                array_push($data['table'],'AffiliateSource');
            }
            if(!empty($affiliate_manager)){
                array_push($data['select'],'affiliate_manager');
                array_push($data['table'],'AffiliateManager');
                $fields .= 'u3.company as manager,';
            }
            if(!empty($sub1)){
                array_push($data['select'],'sub1');
                array_push($data['table'],'Sub1');
            }
            if(!empty($sub2)){
                array_push($data['select'],'sub2');
                array_push($data['table'],'Sub2');
            }
            if(!empty($sub3)){
                array_push($data['select'],'sub3');
                array_push($data['table'],'Sub3');
            }
            if(!empty($sub4)){
                array_push($data['select'],'sub4');
                array_push($data['table'],'Sub4');
            }
            if(!empty($sub5)){
                array_push($data['select'],'sub5');
                array_push($data['table'],'Sub5');
            }
            if(!empty($offer)){
                array_push($data['select'],'offer');
                array_push($data['table'],'Offer');
                $fields .= 'off.name,';
            }
            if(!empty($goal)){
                array_push($data['select'],'goal');
                array_push($data['table'],'Goal');
            }
            if(!empty($goal_id)){
                array_push($data['select'],'goal_id');
                array_push($data['table'],'Goal_Id');
            }
            if(!empty($offer_url)){
                array_push($data['select'],'offer_url');
                array_push($data['table'],'Offer_url');
            }
            if(!empty($advertiser)){
                array_push($data['select'],'advertiser');
                array_push($data['table'],'Advertiser');
                $fields .= 'u2.company as adv,';
            }
            if(!empty($advertiser_id)){
                array_push($data['select'],'advertiser_id');
                array_push($data['table'],'AdvertiserId');
                $fields .= 't.advid,';
            }
            if(!empty($advertiser_manager)){
                array_push($data['select'],'advertiser_manager');
                array_push($data['table'],'AdvertiserManager');
            }
            $campaign = Yii::app()->request->getParam('campaign');
            if(!empty($campaign)){
                array_push($data['select'],'campaign');
                array_push($data['table'],'Campaign');
            }
            if(!empty($category)){
                array_push($data['select'],'category');
                array_push($data['table'],'Category');
            }
            if(!empty($payout_type)){
                array_push($data['select'],'payout_type');
                array_push($data['table'],'PayoutType');

            }
            $revenue_type = Yii::app()->request->getParam('revenue_type');
            if(!empty($revenue_type)){
                array_push($data['select'],'revenue_type');
                array_push($data['table'],'RevenueType');
            }
            $country = Yii::app()->request->getParam('country');
            if(!empty($country)){
                array_push($data['select'],'country');
                array_push($data['table'],'Country');
                $fields .= 'off.geo_targeting as country,';
            }
            $browser = Yii::app()->request->getParam('browser');
            if(!empty($browser)){
                array_push($data['select'],'browser');
                array_push($data['table'],'Browser');
            }
            $impressions = Yii::app()->request->getParam('impressions');
            if(!empty($impressions)){
                array_push($data['select'],'impressions');
                array_push($data['table'],'Impressions');
            }
            if(!empty($clicks)){
                array_push($data['select'],'clicks');
                array_push($data['table'],'Clicks');
                $fields .= 'count(*) as sum_click,';
            }
            $revenue= Yii::app()->request->getParam('revenue');
            if(!empty($revenue)){
                array_push($data['select'],'revenue');
                array_push($data['table'],'Revenue');
                $fields .= 'ig.sum_rev,';
            }
            if(!empty($conversions)){
                array_push($data['select'],'conversions');
                array_push($data['table'],'Conversions');
                $fields .='ig.sum_transaction,';
            }
            $sales = Yii::app()->request->getParam('sales');
            if(!empty($sales)){
                array_push($data['select'],'sales');
                array_push($data['table'],'Sales');
            }
            $unq = '';
            $unique_clicks = Yii::app()->request->getParam('unique_clicks');
            $unique_clicks_field = '';
            if(!empty($unique_clicks)){
                array_push($data['select'],'unique_clicks');
                array_push($data['table'],'Unique_clicks');
                $unq = 'LEFT JOIN (SELECT count(DISTINCT(clientip))as sum_uqe_transaction,
                i2.offerid FROM joy_transaction_income i2 GROUP BY i2.offerid)uq on uq.offerid=t.offerid ';
                $fields .= 'uq.sum_uqe_transaction,';
            }
            if(!empty($cost)){
                array_push($data['select'],'cost');
                array_push($data['table'],'Cost');
                $fields .= 'ig.sum_payout,';
            }
            if(!empty($profit)){
                array_push($data['select'],'profit');
                array_push($data['table'],'Profit');
                $fields .=' ig.sum_rev - ig.sum_payout,';
            }
            $CTR = Yii::app()->request->getParam('CTR');
            if(!empty($CTR)){
                array_push($data['select'],'CTR');
                array_push($data['table'],'CTR');
            }
            $CPC = Yii::app()->request->getParam('CPC');
            $RPC = Yii::app()->request->getParam('RPC');
            $CR = Yii::app()->request->getParam('CR');
            $CPA = Yii::app()->request->getParam('CPA');
            $RPA = Yii::app()->request->getParam('RPA');
            $CPM = Yii::app()->request->getParam('CPM');
            $RPM = Yii::app()->request->getParam('RPM');
            $year = Yii::app()->request->getParam('year');
            if(!empty($year)){
                array_push($data['select'],'year');
                array_push($data['table'],'Year');
            }
            $date = Yii::app()->request->getParam('date');
            if(!empty($date)){
                array_push($data['select'],'date');
                array_push($data['table'],'Date');
                $fields .= 't.createtime,';
            }
            $month = Yii::app()->request->getParam('month');
            if(!empty($month)){
                array_push($data['select'],'month');
                array_push($data['table'],'Month');
            }
            $hour = Yii::app()->request->getParam('hour');
            if(!empty($hour)){
                array_push($data['select'],'hour');
                array_push($data['table'],'Hour');
            }
            $week = Yii::app()->request->getParam('week');
            if(!empty($week)){
                array_push($data['select'],'week');
                array_push($data['table'],'Week');
            }
          /*  $affiliates = Yii::app()->request->getParam('affiliates');
            if(!empty($affiliates)){
                array_push($data['select'],'affiliates');
                array_push($data['table'],'Affiliates');
            }
            $advertisers = Yii::app()->request->getParam('advertisers');
            if(!empty($advertisers)){
                array_push($data['select'],'advertisers');
                array_push($data['table'],'Advertisers');
            }
            $payout_type = Yii::app()->request->getParam('payout_type');
            if(!empty($payout_type)){
                array_push($data['select'],'payout_type');
                array_push($data['table'],'Payout_type');
            }
            $creatives = Yii::app()->request->getParam('creatives');
            if(!empty($creatives)){
                array_push($data['select'],'creatives');
                array_push($data['table'],'Creatives');
            }
            $currencies = Yii::app()->request->getParam('currencies');
            if(!empty($currencies)){
                array_push($data['select'],'currencies');
                array_push($data['table'],'Currencies');
            }
            $advertiser_managers = Yii::app()->request->getParam('advertiser_managers');
            if(!empty($advertiser_managers)){
                array_push($data['select'],'advertiser_managers');
                array_push($data['table'],'Advertiser_managers');
            }
            $offers = Yii::app()->request->getParam('offers');
            if(!empty($offers)){
                array_push($data['select'],'offers');
                array_push($data['table'],'Offers');
            }
            $revenue_type = Yii::app()->request->getParam('revenue_type');
            if(!empty($revenue_type)){
                array_push($data['select'],'revenue_type');
                array_push($data['table'],'Revenue_type');
            }
            $browsers = Yii::app()->request->getParam('browsers');
            if(!empty($browsers)){
                array_push($data['select'],'browsers');
                array_push($data['table'],'Browsers');
            }
            $campaigns = Yii::app()->request->getParam('campaigns');
            if(!empty($campaigns)){
                array_push($data['select'],'campaigns');
                array_push($data['table'],'Campaigns');
            }
            $countries = Yii::app()->request->getParam('countries');
            if(!empty($countries)){
                array_push($data['select'],'countries');
                array_push($data['table'],'Countries');
            }*/

            //���벻Ϊ0
            $non_zero_revenue = Yii::app()->request->getParam('non_zero_revenue');
            if(!empty($non_zero_revenue)){
                $condition .= ' and ig.sum_rev!=0 ';
            }
            //�߼����жϣ�����Statistics��δѡ��һ�У���Ĭ��ѡ��Impressions/Clicks/Revenue/Conversions/Cost/Profit
            if(!empty(Yii::app()->request->getParam('startDate'))){
                $start_date = Yii::app()->request->getParam('startDate');
                $end_date = Yii::app()->request->getParam('endDate');
            }
            //��ȡ�������е��ύ����
            $input_aff = Yii::app()->request->getParam('input_aff');
            //��ѯ������aff����������
            if(!empty($input_aff)){
                $affs = JoySystemUser::model()->findAll("name like '%$input_aff%'");
                $aff_str = '';
                foreach($affs as $aff){
                    $aff_str = $aff_str == '' ? $aff['id'] : ',' . $aff['id'];
                }
                if($aff_str != ''){
                    $condition .= " and t.affid in ($aff_str)";
                }
            }

            $condition .= "and t.createtime > '$start_date 00:00:00' and t.createtime < '$end_date 23:59:59'";
            //�ж��Ƿ��ѯu1,u2��left join
            if(isset($affiliates) || !empty($company)){
                $u1 = ' LEFT JOIN joy_system_user u1 ON u1.id=t.affid ';
            }
            if(isset($advertisers) || isset($advertiser)){
                $u2 = ' LEFT JOIN joy_system_user u2 on u2.id=t.advid ';
            }
            if(isset($affiliate_manager)){
                $u3 = ' LEFT JOIN joy_system_user u3 ON u1.manager_userid=u3.id ';
                $u1 = ' LEFT JOIN joy_system_user u1 ON u1.id=t.affid ';
            }
            if(isset($offer) || isset($country)){
                $off = ' LEFT JOIN joy_offers off on off.id=t.offerid ';
            }
            $istrue = '';
            if(in_array($this->user['groupid'],array(4,5))){
                $istrue = 'where ispostbacked=1';
            }
            if(isset($revenue) || isset($cost) || isset($non_zero_revenue)){
                $ig = 'LEFT JOIN
        (SELECT '.$unique_clicks_field . ' count(*) as sum_transaction,SUM(i.revenue) as sum_rev,SUM(i.payout) as sum_payout,i.offerid FROM joy_transaction_income i '.$istrue. ' GROUP BY i.offerid)ig
        on ig.offerid=t.offerid';
            }
            $fields = substr($fields,0,strlen($fields)-1);
            $sql_query = "select $fields from joy_transaction t $ig $unq $u1 $u2 $u3 $off where $condition GROUP BY t.offerid";
            $connection = Yii::app()->db;
            $command = $connection->createCommand($sql_query);
            $data['data'] = $command->queryAll();
            Yii::app()->session['query_list'] = $data['data'];
            Yii::app()->session['headarr'] = $data['table'];
        }
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        $this->render('report/daily_report',$data);
    }

    public function actionAjaxAdvlist(){
        $maxRows = Yii::app()->request->getParam('maxRows');
        $name_startsWith = Yii::app()->request->getParam('name_startsWith');
        $rows = array();
        $criteria=new CDbCriteria;
        $criteria->addCondition('groupid = 4');
        $criteria->addCondition("company like '%$name_startsWith%'");
        $criteria->limit = $maxRows;
        $advertisers = JoySystemUser::model()->findAll($criteria);
        foreach ($advertisers as $i => $advertiser) {
            $rows[$i]['label'] = $advertiser['company'];
        }
        echo json_encode($rows);
    }
    public function actionAjaxAdvmgrlist(){
        $maxRows = Yii::app()->request->getParam('maxRows');
        $name_startsWith = Yii::app()->request->getParam('name_startsWith');
        $rows = array();
        $criteria=new CDbCriteria;
        $criteria->addCondition('groupid = 3');
        $criteria->addCondition("company like '%$name_startsWith%'");
        $criteria->limit = $maxRows;
        $advertiser_mgrs = JoySystemUser::model()->findAll($criteria);
        foreach ($advertiser_mgrs as $i => $advertiser_mgr) {
            $rows[$i]['label'] = $advertiser_mgr['company'];
        }
        echo json_encode($rows);
    }
    public function actionAjaxAfflist(){
        $maxRows = Yii::app()->request->getParam('maxRows');
        $name_startsWith = Yii::app()->request->getParam('name_startsWith');
        $rows = array();
        $criteria=new CDbCriteria;
        $criteria->addCondition('groupid = 5');
        $criteria->addCondition("company like '%$name_startsWith%'");
        $criteria->limit = $maxRows;
        $affs = JoySystemUser::model()->findAll($criteria);
        foreach ($affs as $i => $aff) {
            $rows[$i]['label'] = $aff['company'];
        }
        echo json_encode($rows);
    }
    //ajaxȡ��offer��ʾ
    public function actionAjaxOffers(){
        $maxRows = Yii::app()->request->getParam('maxRows');
        $name_startsWith = Yii::app()->request->getParam('name_startsWith');
        $rows = array();
        $criteria=new CDbCriteria;
        $criteria->addCondition("name like '%$name_startsWith%'");
        $criteria->limit = $maxRows;
        $offers = joy_offers::model()->findAll($criteria);
        foreach ($offers as $i => $offer) {
            $rows[$i]['value'] = $offer['id'];
            $rows[$i]['label'] = $offer['name'];
        }
        echo json_encode($rows);
    }
    public function actionAjaxCountries(){
        $offers = joy_offers::model()->model()->findAllByAttributes(array('name'));
    }
    public function actionAjaxPayoutType(){
        $offers = joy_offers::model()->model()->findAllByAttributes(array('name'));
    }
    public function actionAjaxRevenue(){
        $offers = joy_offers::model()->model()->findAllByAttributes(array('name'));
    }
    public function actionAjaxCurrencies(){
        $offers = joy_offers::model()->model()->findAllByAttributes(array('name'));
    }
    public function actionAffReport(){
        $affid = Yii::app()->request->getParam('id');
        $sql_query = "select offerid,sum(click_count) as sum_click,sum(conversion) as sum_con,offers.name,offers.payout as payout,sum(t.payout) as sys_payout from joy_offer_count t
        LEFT JOIN joy_offers offers on t.offerid=offers.id
        WHERE t.affid=$affid
        GROUP BY t.offerid";
        //需要替换payout
//        $sql_query = "select t.offerid,count(*) as sum_click,ig.sum_con,offers.name,offers.payout as payout,ig.sum_con*offers.payout as sys_payout from joy_transaction t
//        LEFT JOIN joy_offers offers on t.offerid=offers.id
//        LEFT JOIN (SELECT income.offerid,COUNT(*) as sum_con FROM joy_transaction_income income WHERE ispostbacked=1 and affid=$affid GROUP BY income.offerid)ig on ig.offerid=t.offerid
//        WHERE t.affid=$affid
//        GROUP BY t.offerid";
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql_query);
        $rs = $command->queryAll();
        $rows = array();
        foreach($rs as $key){
            $key['id'] = $key['offerid'];
            array_push($rows,Common::instantPayout($key,$affid));
        }
        $this->render('report/aff_report',array('data'=>$rows));
    }

    //����excel
    public function actionDownloadasexcel(){
        $rs_query = Yii::app()->session['query_list'];
        $headArr = Yii::app()->session['headarr'];
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
        foreach($headArr as $v){
            $colum = chr($key);
            $objPHPExcel->setActiveSheetIndex(0) ->setCellValue($colum.'1', $v);
            $key += 1;
        }
        $exc_colum = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
        foreach($rs_query as $key=>$value) {
            $i = 0;
            foreach($value as $arr_key=>$arr_value){
              $objPHPExcel->getActiveSheet(0)->setCellValue($exc_colum[$i] . ($key + 3), $arr_value);
                $i++;
          }
            $row    =   $key + 3;
            $objPHPExcel->getActiveSheet()->mergeCells("M$row:O$row");
        }
        $user = JoySystemUser::model()->findByPk($this->user['userid']);
        $fileName   =   $user['company'] . 'Report';
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

    public function actionEdit(){
        if(in_array($this->user['groupid'],$this->manager_group)) {
            $id = Yii::app()->request->getParam('id');
            $revenue = Yii::app()->request->getParam('revenue');
            $impression = Yii::app()->request->getParam('impression');
            $jump_update = OfferCountSelf::model()->findByPk($id);
            $result = array('rs' => 0);
            if (!empty($jump_update)) {
                if(!empty($impression)){
                    $jump_update->click_count = $impression;
                }
                if(!empty($revenue)){
                    $jump_update->revenue = $revenue;
                }
                if ($jump_update->save()) {
                    $result['rs'] = 1;
                } else {
                    $result['rs'] = 0;
                }
            }
            echo json_encode($result);
        }
    }

    public function actionDl(){
        if(in_array($this->user['groupid'],$this->manager_group)) {
            $edate = Yii::app()->request->getParam('edate');
            $sdate = Yii::app()->request->getParam('sdate');
            $display_type = Yii::app()->request->getParam('display_type');
            $cdb = new CDbCriteria();
            if(0 == $display_type){
                $time = Yii::app()->request->getParam('time');
                $cdb->addCondition("time='$time'");
            }elseif(1 == $display_type){
                $name = Yii::app()->request->getParam('project_name');
                $cdb->addCondition("project_name='$name'");
                $cdb->addCondition("time >= '$sdate' and time <= '$edate'");
            }elseif(2 == $display_type){
                $affid = Yii::app()->request->getParam('affid');
                $affid = intval($affid);
                $cdb->addCondition("affid = $affid");
                $cdb->addCondition("time >= '$sdate' and time <= '$edate'");
            }elseif(3 == $display_type){
                $siteid = Yii::app()->request->getParam('affid');
                $site_arr = JoySites::getResult('affids',"and site_id = $siteid");
                $affids = -1;
                if(!empty($site_arr)){
                    $affids = $site_arr['affids'];
                }
                $cdb->addCondition("affid in ($affids)");
                $cdb->addCondition("time >= '$sdate' and time <= '$edate'");
            }
            if(OfferCountSelf::model()->deleteAll($cdb)){
                $this->redirect($this->createUrl('report/crreport',array(
                    'display_type'=>$display_type,
                    'sdate'=>$sdate,
                    'edate'=>$edate
                )));
            }else{
                Common::jsalerturl('Error!Please flush the page.');
            }
        }
    }

    public function actionCRReport(){
        $arr = '';
        $traffic = Yii::app()->request->getParam('traffic');


        if(!empty($traffic)){
            $traffic = implode(',',$traffic);
        }
        $display_type = Yii::app()->request->getParam('display_type');
        $sdate = Yii::app()->request->getParam('sdate');
        $edate = Yii::app()->request->getParam('edate');
        $site_selected = Yii::app()->request->getParam('sites');
        $site_selected_str = '';
        $cdb_all = new CDbCriteria();
        if(empty($site_selected)){
            $site_all = JoySites::getCompanySite();
            $site_all_arr = array_keys($site_all);
        }else{
            $site_selected_str = implode(',',$site_selected);
        }
        if($this->user['groupid'] == SITE_GROUP_ID) {
            $site_selected_str = $this->user['userid'];
            $joy_user = new JoySystemUser();
            $relevance = $joy_user->getRelevance($this->user['groupid'], $this->user['userid']);
            if (!empty($relevance)) {
                $sites_arr = explode(',', $relevance['affids']);
                if(!empty($traffic)){
                    $traffic_arr = explode(',',$traffic);
                }
                foreach ($sites_arr as $key => $site) {
                    $condition = " id=$site";
                    $fileds = 'id';
                    $result = JoySystemUser::getResult($fileds, $condition);
                    if(!empty($result)){
                        $arr[$key] = $result[0];
                    }
                    if(!empty($traffic_arr)){
                        if(in_array($site,$traffic_arr)){
                            $arr[$key]['checked'] = true;
                        }
                    }
                }
            }

            if(isset($traffic)){
                $cdb_all->addCondition("affid in ($traffic)");
            }else{
                $str = -1;
                if(!empty($relevance)){
                    $str = implode($relevance);
                }
                $cdb_all->addCondition("affid in ($str)");
            }
        }elseif (in_array($this->user['groupid'],array(ADMIN_GROUP_ID,AM_GROUP_ID,MANAGER_GROUP_ID))){
            $arr = JoySystemUser::getResults('id',' groupid = ' . AFF_GROUP_ID);
            if(!empty($traffic)){
                $cdb_all->addCondition("affid in ($traffic)");
            }else {
                if (!empty($site_selected) && !empty($site_selected[0])) {
                    $site_find_str = '';
                    foreach ($site_selected as $site) {
                        $affids = JoySites::getResult('affids', "and site_id = $site");
                        if (empty($affids)) {
                            $affids['affids'] = -1;
                        }
                        $site_find_str .= $affids['affids'] . ',';
                    }
                    $site_find_str = substr($site_find_str, 0, strlen($site_find_str) - 1);
                    $cdb_all->addCondition("affid in ($site_find_str)");
                    if (!empty($traffic_arr)) {
                        foreach ($arr as $key => $user) {
                            if (in_array($user['id'], $traffic_arr)) {
                                $arr[$key]['checked'] = true;
                            }
                        }
                    }
                }
            }
        }elseif($this->user['groupid'] == BUSINESS_GROUP_ID){

            $res2= JoySites::getSitesInformationWithBusiness($this->user['userid']);
            $arr2 = array_column($res2,'aff');
            $arr=array();
            foreach($arr2 as $v) {foreach($v as $vv) {$arr[]['id']=$vv;}}
            $res_siteid  = JoySystemUser::getResults('id',"manager_userid = {$this->user['userid']} and groupid = " . SITE_GROUP_ID);
            if(!empty($arr)){
                $all_affid_arr = array_column($arr,'id');
                $res_siteid_all=array_column($res_siteid,'id');
                if(!empty($traffic)){


                    $traffic_arr = explode(',',$traffic);

                    foreach($traffic_arr as $item=>$value){

                        $condition = "id=$value";
                        $fileds = 'id';
                        $result = JoySystemUser::getResult($fileds, $condition);

                        if(!empty($result)){
                            $arr[$item] = $result[0];
                        }
                        if(in_array($value,$all_affid_arr)){
                            $arr[$item]['checked'] = true;
                        }
                    }
                }
            }

            if(isset($traffic)){

                $cdb_all->addCondition("affid in ($traffic)");
            }else{
                $all_affid_str = '-1';
                if(!empty($res_siteid_all)){
                    foreach($res_siteid_all as $affid){
                        $affids_arr = JoySites::getResults('affids','  site_id =' . $affid);
                        if(!empty($affids_arr)){
                            $affids_str_arr = array_column($affids_arr,'affids');
                            $affids_str = implode(',',$affids_str_arr);
                            $all_affid_str .= ',' . $affids_str;
                        }
                    }

                }

                $cdb_all->addCondition(" affid in ($all_affid_str) ");



            }
        }
        if(empty($sdate)){
            $sdate = date('Y-m-01');
            $edate = date('Y-m-t');
        }
        if(in_array($this->user['groupid'],array(AFF_GROUP_ID))){
            $cdb_all->addCondition("affid = {$this->user['userid']}");
        }
        $sites = array();
        if($this->user['groupid'] == BUSINESS_GROUP_ID){
            $sites = JoySites::getSitesInformationWithBusiness($this->user['userid']);

        }elseif(in_array($this->user['groupid'],$this->manager_group)){
            $sites = JoySites::getSitesInformation();
        }elseif($this->user['groupid'] == SITE_GROUP_ID){
            $sites = JoySites::getSitesInformation($this->user['userid']);
        }

        if(0 == $display_type){

            $cdb_all->select = "project_name,sum(conversion) as conversion,sum(click_count) as click_count,sum(payout) as payout,sum(revenue) as revenue,time";
            $cdb_all->group = 't.time';
            $cdb_all->order = "t.time desc";
        }elseif(1 == $display_type){
            $cdb_all->select = "project_name,sum(conversion) as conversion,sum(click_count) as click_count,sum(payout) as payout,sum(revenue) as revenue";
            $cdb_all->group = 't.project_name';
            $cdb_all->order = "t.project_name desc";
        }elseif(2 == $display_type){
            $cdb_all->select = "affid,sum(conversion) as conversion,sum(click_count) as click_count,sum(payout) as payout,sum(revenue) as revenue";
            $cdb_all->group = 't.affid';
            $cdb_all->order = 't.affid';
        }elseif(3 == $display_type){
            if(!empty($sites)){
                $db = Yii::app()->db;
                foreach($sites as $site=>$item){
                    $affids = implode(',',$item['aff']);

                    if(empty($affids)){
                        continue;
                    }
                    $sql = "select sum(conversion) as conversion,sum(click_count) as click_count,sum(payout) as payout,sum(revenue) as revenue,time,project_name from offer_count_self t
                where t.time >= '$sdate' and t.time <= '$edate' and affid in ($affids)";
                    $command = $db->createCommand($sql);
                    $result = $command->queryRow();
                    $all_report[$site]['siteid'] = $site;
                    $all_report[$site] += $result;
                }
            }
        }

        $cdb_all->addCondition(" t.time >= '$sdate' and t.time <= '$edate' ");
        $cdb_all->order = "t.time desc";

        if(empty($all_report)){
            $all_report = OfferCountSelf::model()->findAll($cdb_all);
        }
        $select_count = OfferCountSelf::model()->count();
        $page = new CPagination($select_count);
        $page->pageSize= 10;
        $page->applyLimit($cdb_all);
        $page->route   =   "report/crreport";
        Yii::app()->cache->set('crreport'.$this->user['userid'] . 'report',$all_report,500);
        Yii::app()->cache->set('crreport'.$this->user['userid'] . 'display_type',$display_type,500);
        $this->render('report/crreport',array(
            'sites'=>$sites,
            'sites_selected'=>$site_selected_str,
            'all_report'=>$all_report,
            'channel'=>$this->user['userid'],
            'affs'=>$arr,
            'sdate'=>$sdate,
            'edate'=>$edate,
            'display_type'=>$display_type,
            'relevance'=>$traffic,
        ));
    }

    public function actionDownloadCrExcel(){
        $report = Yii::app()->cache->get('crreport'.$this->user['userid'] . 'report');
        $display_type = Yii::app()->cache->get('crreport'.$this->user['userid'] . 'display_type');
        if(empty($report)){
            Common::jsalerturl('The data is go away,try again please');
            die();
        }
        switch($display_type){
            case 0:
                $head = array('Date');
                break;
            case 1:
                $head = array('Project');
                break;
            case 2:
                $head = array('Affiliate ID');
                break;
            case 3:
                $head = array('Site ID');
                break;
            default:
                $head = array();
        }
        array_push($head,'Impression');
        array_push($head,'Payout');
        array_push($head,'Revenue');
        array_push($head,'Profit');
        Tools::download_cr_excel($head,$report,$display_type);
    }

    public function actionRealTimeReport(){
        $display_type = Yii::app()->request->getParam('display_type');
        $sdate = Yii::app()->request->getParam('sdate');
        $edate = Yii::app()->request->getParam('edate');
        $db = Yii::app()->db;
        $condition = '';
        if(empty($sdate)){
            $sdate = date('Y-m-d');
        }
        if(empty($edate)){
            $edate = date('Y-m-d');
        }
        if($display_type == 0){
            $data_sql = "select t.affid,t.impression,s.`count` from ((select affid,count(affid) as impression from joy_transaction GROUP BY affid)t
LEFT JOIN (SELECT sum(`count`) as `count`,affid FROM joy_access_count WHERE date_time >= '$sdate' AND date_time <= '$edate' GROUP BY (affid+0))s ON t.affid=s.affid)";
            $data_command = $db->createCommand($data_sql);
            $data_result = $data_command->queryAll();
            foreach($data_result as $item){
                $site_id = JoySites::getSiteIdWithAff($item['affid'],1);
                $result[$site_id][$item['affid']] = $item;
            }
        }
        $this->render('report/daily_report_detail', array(
                'data'=>$result,
                'display_type'=>$display_type,
                'sdate'=>$sdate,
                'edate'=>$edate,
            )
        );
    }

    public function actionDailyReportDetailMore(){
        $affid = Yii::app()->request->getParam('affid');
        $db = Yii::app()->db;
        $sql = "select affid,original_affid,offerid,original_offerid,count(*) as `count` from joy_transaction WHERE original_affid = $affid";
        $sql_command = $db->createCommand($sql);
        $result = $sql_command->queryAll();
        $this->render('report/detail',array(
            'data'=>$result
        ));
    }

    public function actionReportDetail(){
        $params = Yii::app()->request->getParam('params');
        $display_type = Yii::app()->request->getParam('display_type');
        $sdate = Yii::app()->request->getParam('sdate');
        $edate = Yii::app()->request->getParam('edate');
        $traffic = Yii::app()->request->getParam('relevance');
        $sort = Yii::app()->request->getParam('sort');
        if(empty($sort)){
            $sort = 0;
        }
        $relevance = array('affids'=>'');
        if(in_array($this->user['groupid'],$this->manager_group)){
            $page_module = array(
                'Date','Project','Impression','Payout',' Revenue','Aff ID'
            );
        }elseif(SITE_GROUP_ID == $this->user['groupid']){
            $page_module = array(
                'Date','Project','Impression','Revenue','Aff ID'
            );
        }elseif(AFF_GROUP_ID == $this->user['groupid']){
            $page_module = array(
                'Date','Project','Impression','Revenue'
            );
        }
        $cdb = new CDbCriteria();
        if($this->user['groupid'] == SITE_GROUP_ID){
            if(!empty($traffic)){
                //检测传输过来的traffic是否在此账号之下
                $traffic_arr = explode(',',$traffic);
                $joy_user = new JoySystemUser();
                $relevance = $joy_user->getRelevance($this->user['groupid'],$this->user['userid']);
                if(!empty($relevance)){
                    $relevance_arr = explode(',',$relevance['affids']);
                    $result_arr = array_diff($traffic_arr,$relevance_arr);
                }
                if(!empty($result_arr)){
                    Common::jsalerturl('error!');
                    exit();
                }
                $cdb->addCondition("affid in ({$traffic})");
            }else{
                $joy_user = new JoySystemUser();
                $relevance = $joy_user->getRelevance($this->user['groupid'],$this->user['userid']);
                $rel = -1;
                if($relevance){
                    $rel = $relevance['affids'];
                }
                $cdb->addCondition("affid in ($rel)");
            }
        }elseif(BUSINESS_GROUP_ID == $this->user['groupid']){
            if(!empty($traffic)) {
                $cdb->addCondition("affid in ($traffic)");
            }else{
                $all_affid_str= '-1';
                $affiliate_ids  = JoySystemUser::getResults('id',"manager_userid = {$this->user['userid']} and groupid = " . SITE_GROUP_ID);
                if(!empty($affiliate_ids)) {
                    foreach($affiliate_ids as $affid){
                        $affids_arr = JoySites::getResults('affids','  site_id =' . $affid['id']);
                        if(!empty($affids_arr)){
                            $affids_str_arr = array_column($affids_arr,'affids');
                            $affids_str = implode(',',$affids_str_arr);
                            $all_affid_str .= ',' . $affids_str;
                        }
                    }
                }
                $cdb->addCondition("affid in ($all_affid_str)");
            }
        }elseif(in_array($this->user['groupid'],$this->manager_group)){
            if(!empty($traffic)) {
                $cdb->addCondition("affid in ($traffic)");
            }else{
                if(!empty($sites)){
                    $site_arr = explode(',',$sites);
                    $aff_str = '';
                    foreach($site_arr as $site){
                        $result = JoySites::getResult('affids',"and site_id=$site");
                        if(!empty($result)){
                            $aff_str .= $result['affids'] . ',';
                        }
                    }
                    $aff_str = substr($aff_str,0,strlen($aff_str) - 1);
                    $cdb->addCondition("affid in ($aff_str)");
                }
            }
        }
        if(0 == $display_type){
            $cdb->addCondition("time = '$params'");
        }elseif(1 == $display_type){
            $cdb->addCondition("project_name = '$params'");
        }elseif(2 == $display_type){
            $cdb->addCondition("affid = '$params'");
        }elseif(3 == $display_type){
            $affids_str = JoySites::getResult('affids',"and site_id=$params");
            $affids = -1;
            if(!empty($affids_str)){
                $affids = $affids_str['affids'];
            }else{

            }
            $cdb->addCondition("t.affid in ($affids)");
        }
        if($page_module[$sort] == 'Project'){
            $cdb->order = "t.project_name desc";
        }elseif($page_module[$sort] == 'Impression'){
            $cdb->order = "t.click_count desc";
        }elseif($page_module[$sort] == 'Payout'){
            $cdb->order = "t.payout desc";
        }elseif($page_module[$sort] == 'Revenue'){
            $cdb->order = "t.revenue desc";
        }elseif($page_module[$sort] == 'Aff ID'){
            $cdb->order = "t.affid desc";
        }else{
            $cdb->order = "t.time desc";
        }
        $cdb->addCondition("time >= '$sdate' and time <= '$edate'");
        if($this->user['groupid'] == AFF_GROUP_ID){
            $affid = $this->user['userid'];
            $cdb->addCondition("affid = $affid");
        }
        $offer_counts = OfferCountSelf::model()->findAll($cdb);
        $route = $this->createUrl('report/reportdetail') . "&params=$params&sdate=$sdate&edate=$edate&relevance={$relevance['affids']}&display_type=$display_type&sort=";
        $this->render('report/report_detail', array(
            'counts' => $offer_counts,
            'route' => $route,
            'sort' => $sort,
            'page_module'=>$page_module
        ));
    }

    //import the data for the table offer_count_self;
    public function actionExcelExport()
    {
        try {
            do {
                if (!empty ($_FILES ['upfile'] ['name'])) {
                    $data_date = Yii::app()->request->getParam('date');
                    $data_type = Yii::app()->request->getParam('type');
                    include_once 'StandardController.php';
                    $tmp_file = $_FILES ['upfile'] ['tmp_name'];
                    $file_types = explode(".", $_FILES ['upfile'] ['name']);
                    $file_type = $file_types [count($file_types) - 1];
                    if (strtolower($file_type) != "xls" && strtolower($file_type) != 'csv') {
                        Common::jsalert('Please Upload .xls File!');
                    }
                    $savePath = '/data/nginx/www/offermgr_new/upload/excel/';
//                    $savePath = 'F:/phps_new/offermgr_new/upload/excel/';
                    $str = date('Ymdhis');
                    $file_name = $str . "." . $file_type;

                    if (!copy($tmp_file, $savePath . $file_name)) {
                        Common::jsalert('upload failed!');
                    }
                    if(strtolower($file_type) == "xls"){
                        $data = Tools::read($savePath . $file_name);
                        if (!empty($data)) {
                            $this->parseDataForxls($data,$data_type,$data_date);
                        }
                    }
                } else {
                    $date_today = date('Y-m-d');
                    $this->render('report/export_excel',array('date_today'=>$date_today));
                }
            }while(0);
        }catch (Exception $e){
//            var_dump($e->getMessage());
            Common::jsalerturl('Error about the data!');
        }
    }

    function parseDataForxls($data,$data_type,$data_date){
        $result = 1;
        $offercount = array();
        if(empty($data_date)){
            $data_date = date('Y-m-d');
        }
        $params['data_date'] = $data_date;
        $transaction = Yii::app()->db->beginTransaction();
        try {
            switch ($data_type) {
                case 0:
                    $params['type'] = 'Invasion Android';
                    foreach($data as $key=>$item){
                        if($key==1){
                            continue;
                        }
                        $date = $item[0];
                        $date_param = explode('/',$date);
                        $params['data_date'] = $date_param[2] . '-' . $date_param[1] . '-'.$date_param[0];
                        $params['affid'] = $item[1];
                        $params['impression'] = $item[3];
                        $params['revenue'] = $item[4];
                        array_push($offercount,$this->setData($params));
                    }
                    break;
                case 1:
                    $params['type'] = 'A1';
                    foreach($data as $key=>$item){
                        if($key == 1){
                            continue;
                        }
                        //判断是否为正常渠道
                        $affid = $item[1];
                        if(strlen($affid) != strlen(intval($affid))){
                            $affid = 1;
                        }
                        $params['affid'] = $affid;
                        $params['impression'] = intval($item[3]);
                        $params['revenue'] = floatval($item[8]);
                        array_push($offercount,$this->setData($params));
                    }
                    break;
                case 2:
                    $params['type'] = 'A2';
                    foreach ($data as $key => $item) {
                        if ($key == 1) {
                            continue;
                        }
                        //判断是否为正常渠道
                        $affid = $item[0];
                        if(strlen($affid) != strlen(intval($affid))){
                            $affid = 1;
                        }
                        $params['affid'] = $affid;
                        $params['impression']  = intval(str_replace(' ','',$item[1]));
                        $params['revenue'] = floatval($item[4]);
                        array_push($offercount,$this->setData($params));
                    }
                    break;
                case 3:
                    $params['type'] = 'A3';
                    foreach ($data as $key => $item) {
                        if($key == 1) {
                            continue;
                        }
                        $index = strpos($item[0],'(');
                        $end = strpos($item[0],')');
                        //获取括号中的值
                        if($index && $end){
                            $affid = substr($item[0],$index + 1,$end - $index - 1);
                        }else{
                            $affid = 1;
                        }
                        if(strpos($affid,'test')){
                            $affid = intval($affid);
                        }
                        if(0 == intval($affid)){
                            $affid = 1;
                        }
                        $params['affid'] = $affid;
                        $params['impression'] = intval($item[1]);
                        $params['revenue'] = floatval($item[5]);
                        array_push($offercount,$this->setData($params));
                    }
                    break;
                case 4:
                    $params['type'] = 'A4';
                    $count = count($data);
                    foreach ($data as $key => $item) {
                        if ($key == 1 || $count == $key) {
                            continue;
                        }
                        $params['affid'] = intval(str_replace('="','',$item[0]));
                        $params['impression'] = intval(str_replace('="','',$item[1]));
                        $params['revenue'] = floatval(str_replace('="','',$item[4]));
                        array_push($offercount,$this->setData($params));
                    }
                    break;
                case 5:
                    $params['type'] = 'Search';
                    $aff_arr = array();
                    foreach($data as $key=>$item){
                        if(in_array($key,array(1,2,4))){
                            continue;
                        }
                        if($key == 3){
                            foreach($item as $aff=>$value){
                                if(!empty($value)){
                                    $aff_arr[$aff] = $value;
                                }
                            }
                            continue;
                        }
                        $timestamp = PHPExcel_Shared_Date::ExcelToPHP(trim($item[0]));
                        foreach($aff_arr as $aff=>$value){
                            $params['data_date'] = date('Y-m-d',$timestamp);
                            $params['impression'] = intval($item[$aff]);
                            $params['revenue'] = floatval($item[$aff+1]);
                            $params['affid'] = $value;
                            array_push($offercount,$this->setData($params));
                        }
                    }
                break;
            }
        }catch (Exception $e){
            $transaction->rollback();
            var_dump($e->getMessage());
            die();
        }
        $this->render('report/upload_detail',array('data'=>$offercount));
    }

    public function actionSaveExcel(){
        $result = 0;
        $key = Yii::app()->request->getParam('key');
        $req_data = Yii::app()->cache->get($key);
        if(!empty($req_data)){
            $transaction = Yii::app()->db->beginTransaction();
            $data = unserialize($req_data);
            try{
                $date = $data[0]['time'];
                $type = $data[0]['project_name'];
                $exist_data = OfferCountSelf::model()->findByAttributes(array('time'=>$date,'project_name'=>$type));
                if(empty($exist_data)){
                    foreach($data as $item){
                        $item->save();
                    }
                    $result = 1;
                    $transaction->commit();
                }else{
                    $result = 0;
                }
            }catch(Exception $e){
                $transaction->rollback();
                $result = 0;
            }
        }
        if($result == 1){
            $this->render('report/export_excel',array('date_today'=>date('Y-m-d')));
        }else{
            Common::jsalerturl('Failed');
        }
    }


    public function setData($params){
        $offercount = OfferCountSelf::model()->findByAttributes(array('affid'=>$params['affid'],'time'=>$params['data_date'],'project_name'=>$params['type']));
        if (empty($offercount)) {
            $offercount = new OfferCountSelf();
            $offercount->affid = $params['affid'];
            if($params['affid'] == 290 && $params['type'] == 'A1'){
                $offercount->revenue = $params['revenue'] * 0.49;
            }elseif($params['type'] == 'Invasion Android'){
                $offercount->revenue = $params['revenue'] * 0.9;
            }else{
                $offercount->revenue = $params['revenue'] * 0.7;
            }
            $offercount->click_count = $params['impression'];
            $offercount->payout = $params['revenue'];
            $offercount->time = $params['data_date'];
            $offercount->project_name = $params['type'];
        } else {
            $offercount->click_count = $params['impression'] + $offercount['click_count'];
            $offercount->payout = $params['revenue'] + $offercount['payout'];
            if($params['type'] == 'Invasion Android'){
                $offercount->revenue = $params['revenue'] * 0.9;
            }else{
                $offercount->revenue = $params['revenue'] * 0.7;
            }
        }
        return $offercount;
    }
}
