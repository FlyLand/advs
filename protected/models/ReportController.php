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
    //ajaxȡ��bissness��ʾ
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
    //ajaxȡ��������ʾ
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
    //ajaxȡ�ù�����ʾ
    public function actionAjaxCountries(){
        $offers = joy_offers::model()->model()->findAllByAttributes(array('name'));
    }
    //ajaxȡ��������ʾ
    public function actionAjaxPayoutType(){
        $offers = joy_offers::model()->model()->findAllByAttributes(array('name'));
    }
    //ajaxȡ��������ʾ
    public function actionAjaxRevenue(){
        $offers = joy_offers::model()->model()->findAllByAttributes(array('name'));
    }
    //ajaxȡ��������ʾ
    public function actionAjaxCurrencies(){
        $offers = joy_offers::model()->model()->findAllByAttributes(array('name'));
    }
    //Affiliate Report
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
        $cdb_all = new CDbCriteria();

        if($this->user['groupid'] == SITE_GROUP_ID) {
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
                $str = 0;
                if(!empty($relevance)){
                    $str = implode($relevance);
                }
                $cdb_all->addCondition("affid in ($str)");
            }
        }elseif (in_array($this->user['groupid'],array(ADMIN_GROUP_ID,AM_GROUP_ID,MANAGER_GROUP_ID))){
            $arr = JoySystemUser::getResults('id','1=1');
            if(!empty($traffic)){
                $cdb_all->addCondition("affid in ($traffic)");
                $traffic_arr = explode(',',$traffic);
            }
            if (!empty($traffic_arr)) {
                foreach($arr as $key=>$user) {
                    if (in_array($user['id'], $traffic_arr)) {
                        $arr[$key]['checked'] = true;
                    }
                }
            }
        }elseif($this->user['groupid'] == BUSINESS_GROUP_ID){
            $arr  = JoySystemUser::getResults('id',"manager_userid = {$this->user['userid']}");
            if(!empty($arr)){
                $all_affid_arr = array_column($arr,'id');
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
                $str = 0;
                if(!empty($all_affid_arr)){
                    $str = implode(',',$all_affid_arr);
                }
                $cdb_all->addCondition("affid in ($str)");
            }
        }
        if(empty($sdate)){
            $sdate = date('Y-m-01');
            $edate = date('Y-m-t');
        }
        if(in_array($this->user['groupid'],array(5))){
            $cdb_all->addCondition("affid = {$this->user['userid']}");
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
        }
        $cdb_all->addCondition("t.time >= '$sdate' and t.time <= '$edate'");
        $all_report = OfferCountSelf::model()->findAll($cdb_all);
        $select_count = OfferCountSelf::model()->count();
        $page = new CPagination($select_count);
        $page->pageSize= 10;
        $page->applyLimit($cdb_all);
        $page->route   =   "report/crreport";
        $this->render('report/crreport',array(
            'all_report'=>$all_report,
            'channel'=>$this->user['userid'],
            'sites'=>$arr,
            'sdate'=>$sdate,
            'edate'=>$edate,
            'display_type'=>$display_type,
            'relevance'=>$traffic,
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
                $cdb->addCondition("affid in ({$relevance['affids']})");
            }
        }elseif(BUSINESS_GROUP_ID == $this->user['groupid']){
            if(!empty($traffic)) {
                $cdb->addCondition("affid in ($traffic)");
            }else{
                $all_affid_str= 0;
                $affiliate_ids  = JoySystemUser::getResults('id',"manager_userid = {$this->user['userid']}");
                if(!empty($affiliate_ids)) {
                    $all_affid_str = implode(',',array_column($affiliate_ids, 'id'));
                }
                $cdb->addCondition("affid in ($all_affid_str)");
            }
        }elseif(in_array($this->user['groupid'],$this->manager_group)){
            if(!empty($traffic)) {
                $cdb->addCondition("affid in ($traffic)");
            }
        }
        if(0 == $display_type){
            $cdb->addCondition("time = '$params'");
        }elseif(1 == $display_type){
            $cdb->addCondition("project_name = '$params'");
        }elseif(2 == $display_type){
            $cdb->addCondition("affid = '$params'");
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
                        Common::jsalert('error!');
                    }
                    $savePath = '/data/nginx/www/offermgr_new/upload/excel/';
//                    $savePath = 'F:/phps_new/offermgr_new/upload/excel/';
                    $str = date('Ymdhis');
                    $file_name = $str . "." . $file_type;

                    if (!copy($tmp_file, $savePath . $file_name)) {
                        Common::jsalert('upload failed!');
                    }
                    //区分CSV和excel格式
                    if(strtolower($file_type) == "xls"){
                        $data = $this->read($savePath . $file_name);
                        if (!empty($data)) {
                            $this->parseDataForxls($data,$data_type,$data_date);
                        }
                    }
                    Common::jsalerturl('success!');
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

    public  function read($filename,$encode='utf-8'){
        Yii::$enableIncludePath = false;
        Yii::import('application.extensions.PHPExcel.PHPExcel', 1);
        $objReader = PHPExcel_IOFactory::createReader('Excel5');
        $objReader->setReadDataOnly(true);
        $objPHPExcel = $objReader->load($filename);
        $objWorksheet = $objPHPExcel->getActiveSheet();
        $highestRow = $objWorksheet->getHighestRow();
        $highestColumn = $objWorksheet->getHighestColumn();
        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
        $excelData = array();
        for ($row = 1; $row <= $highestRow; $row++) {
            for ($col = 0; $col < $highestColumnIndex; $col++) {
                $excelData[$row][] =(string)$objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
            }
        }
        return $excelData;
    }
    function parseDataForxls($data,$data_type,$data_date){
        $result = 1;
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
                        $offercount = $this->setData($params);
                        if (!$offercount->save()) {
                            $result = 0;
                        }
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
                        $offercount = $this->setData($params);
                        if (!$offercount->save()) {
                            $result = 0;
                        }
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
                        $offercount = $this->setData($params);
                        if (!$offercount->save()) {
                            $result = 0;
                        }
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
                        $offercount = $this->setData($params);
                        if (!$offercount->save()) {
                            $result = 0;
                        }
                    }
                    break;
                case 4:
                    $params['type'] = 'A4';
                    foreach ($data as $key => $item) {
                        if ($key == 1) {
                            continue;
                        }
                        $params['affid'] = intval(str_replace('="','',$item[0]));
                        $params['impression'] = intval(str_replace('="','',$item[1]));
                        $params['revenue'] = floatval(str_replace('="','',$item[4]));
                        $offercount = $this->setData($params);
                        if (!$offercount->save()) {
                            $result = 0;
                        }
                    }
                    break;
            }
        }catch (Exception $e){
            $transaction->rollback();
            var_dump($e->getMessage());
            Common::jsalerturl('Export error!');
            die();
        }
        if ($result == 1) {
            $transaction->commit();
            Common::jsalerturl('success!');
        }else{
            Common::jsalerturl('error!');
        }
    }
    public function setData($params){
        $offercount = OfferCountSelf::model()->findByAttributes(array('affid'=>$params['affid'],'time'=>$params['data_date'],'project_name'=>$params['type']));
        if (empty($offercount)) {
            $offercount = new OfferCountSelf();
            $offercount->affid = $params['affid'];
            $offercount->click_count = $params['impression'];
            $offercount->payout = $params['revenue'];
            if($params['type'] == 'Invasion Android'){
                $offercount->revenue = $params['revenue'] * 0.9;
            }else{
                $offercount->revenue = $params['revenue'] * 0.7;
            }
            $offercount->time = $params['data_date'];
            $offercount->project_name = $params['type'];
        } else {
            $offercount->click_count = $params['impression'] + $offercount['click_count'];
            $offercount->payout = $params['revenue'] + $offercount['payout'];
            $offercount->revenue = $params['revenue'] * 0.7 + $offercount['revenue'];
        }
        return $offercount;
    }
}