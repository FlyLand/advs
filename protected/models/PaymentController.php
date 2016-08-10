<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/2/26
 * Time: 17:50
 */
class PaymentController extends Controller
{
    public function __construct(){
        parent::checkAction();//验证权限
    }

    public function actionCheck(){
        $id = Yii::app()->request->getParam('id');
        $type = Yii::app()->request->getParam('type');
        $payout = 0;
        //0 is check the invoice  1 is pay for affiliate
        if(0 == $type) {
            //is manager
            if(in_array($this->user['groupid'],array(ADMIN_GROUP_ID,AM_GROUP_ID))){
                $result = JoyCountPay::model()->updateByPk($id, array('status' => 2,'am_id'=>$this->user['userid']));
                $joyCountPay = JoyCountPay::model()->findByPk($id);
                $payout = $joyCountPay->amount_paid;
            }
        }elseif(1 == $type) {
            if(in_array($this->user['groupid'],array(ADMIN_GROUP_ID,FINANCE_GROUP_ID))){
                $invoice = JoyCountPay::model()->findByPk($id);
                $result = JoyCountPay::model()->updateByPk($id, array('status' => 3, 'finance_id' => $this->user['userid'],'amount_sent'=>$invoice['amount']));
            }
        }
        if(!empty($result))
            echo json_encode(array('status'=>1,'payout'=>$payout));
        else
            echo json_encode(array('status'=>0,'payout'=>$payout));
    }

    public function actionSavePayout(){
        $data =   Yii::app()->request->getParam('data');
        $conn   =   Yii::app()->db->beginTransaction();
        foreach($data as $key){
            $id =   $key[1];
            $payout    =   $key[2];
            if(empty($payout)){
                continue;
            }
            $payment   =   JoyCountPay::model()->findByPk($id);
            if($payment->status != 2){
                continue;
            }
            $payment->amount_paid = $payout;
            $payment->status = 3;
            if(!$payment->update()){
                echo $payment->errors;
                $conn->rollback();
                die();
            }
        }
        $conn->commit();
        echo 'success';
    }

    public function actionViewPDF(){
        $name = Yii::app()->request->getParam('name');
        if(!empty($name)){
            $this->render('payment/view_pdf',array(
                'pdf'=>$name
            ));
        }else{
            Common::jsalerturl('Not Find PDF File!');
        }
    }

    public function actionUploadPdf(){
        $ret = array('msg'=>'Failed');
        do {
            try {
                if (!empty ($_FILES ['upload_file'] ['name'])) {
                    $pdf_type = Yii::app()->request->getParam('type');
                    $id = Yii::app()->request->getParam('id');
                    if (0 == $pdf_type) {
                        $pdf_field_name = 'pdf';
                    } elseif (1 == $pdf_type) {
                        $pdf_field_name = 'postback_pdf';
                    } else {
                        $ret['msg'] = 'System Error!';
                        break;
                    }
                    $tmp_file = $_FILES ['upload_file'] ['tmp_name'];
                    $file_types = explode(".", $_FILES ['upload_file'] ['name']);
                    $file_type = $file_types [count($file_types) - 1];
                    if (strtolower($file_type) != "pdf") {
                        $ret['msg'] = 'Not PDF file!';
                        break;
                    }
                    $str = date('Ymdhis');
                    $file_name = $str . "." . $file_type;

                    if (!copy($tmp_file, PDF_SAVE_PATH . $file_name)) {
                        $ret['msg'] = 'Upload Filed!!';
                        break;
                    }
                    if (file_exists(PDF_SAVE_PATH . $file_name)) {
                        if(!JoyCountPay::model()->updateByPk($id, array($pdf_field_name => $file_name))){
                            $ret['msg'] = 'Save Error!Please try again.';
                            break;
                        }
                    }
                    $ret['msg'] = 'Upload Success';
                }else{
                    $ret['msg'] = 'No File Upload!';
                }
            }catch(Exception $e){
                $ret['msg'] = 'Error!';
            }
        }while(0);
        $this->redirect($this->createUrl('payment/invoice',$ret));
    }

    public function actionCheckAll(){
        $data =   Yii::app()->request->getParam('data');
        $conn   =   Yii::app()->db->beginTransaction();
        foreach($data as $key){
            $id =   $key[1];
            $payment   =   JoyCountPay::model()->findByPk($id);
            if($payment->status == 0){
                $payment->status = 1;
            }
            if(!$payment->update()){
                echo $payment->errors;
                $conn->rollback();
                die();
            }
        }
        $conn->commit();
        echo 'success';
    }

    public function actionBuilding(){
        $result = JoyCountPay::getNotPaid($this->user['userid']);
        $this->render('payment/building',array('result'=>$result));
    }

    public function actionInvoice(){
        $msg = Yii::app()->request->getParam('msg');
        $count_date = Yii::app()->request->getParam('count_date');
        $traffic_arr = Yii::app()->request->getParam('traffic');
        $am_list_select = Yii::app()->request->getParam('am_list_select');
        $page = Yii::app()->request->getParam('page');
        if(empty($page)){
            $page = 1;
        }
        if(empty($count_date)){
            $count_date = date('Y-m',strtotime(JoyCountPay::getLastMonthLastDay(date('Y-m'))));
        }
        $count_date_s = date('Y-m-01',strtotime($count_date));
        $count_date_e = date('Y-m-t',strtotime($count_date));

        $aff_arr =array();
        $am_list = array();
        $traffic  = '';
        $am_list_select_str= '';

        switch($this->user['groupid']){
            case BUSINESS_GROUP_ID:
                $users_arr = JoySystemUser::model()->findByAttributes(array('manager_userid'=>$this->user['userid']));
                if(!empty($users_arr)){
                    $arr_id = implode(',',array_column('id',$users_arr));
                    $arr_company = implode(',',array_column('company',$users_arr));
                    $aff_arr = array_combine($arr_id,$arr_company);
                }
                break;
            case AFF_GROUP_ID:
                $aff_arr = $this->user['userid'];
                break;
            case SITE_GROUP_ID:
                $aff_arr = JoySites::model()->findAllByAttributes(array('site_id'=>$this->user['userid']));
                break;
            case ADMIN_GROUP_ID:
            case MANAGER_GROUP_ID:
            case AM_GROUP_ID:
            $aff_arr = JoySystemUser::getResults('id,company',"groupid=".SITE_GROUP_ID);
            $am_list = JoySystemUser::getResults('id,company',"groupid=".BUSINESS_GROUP_ID);
            break;
        }
        if(!empty($traffic_arr)){
            $traffic = implode(',',$traffic_arr);
        }
        if(!empty($am_list_select)){
            $am_list_select_str = implode($am_list_select);
        }
        if (!empty($traffic_arr)) {
            foreach($aff_arr as $key=>$user) {
                if (in_array($user['id'], $traffic_arr)) {
                    $aff_arr[$key]['checked'] = true;
                }
            }
        }
        if(!empty($am_list_select) && !empty($am_list)){
            foreach($am_list as $key=>$am){
                if (in_array($am['id'], $am_list_select)) {
                    $am_list[$key]['checked'] = true;
                }
            }
        }

        $count = JoyCountPay::getAllInvoiceCount($traffic,$count_date_s,$count_date_e,$am_list_select_str);
        $jpurl			=	$this->createUrl('payment/invoice',array('count_date'=>$count_date));
        $jparams		=	array();
        $page_obj			=	new Page();
        if( 0 < count($jparams) ){
            $tmp_str		=	strpos($jpurl, '?') ? '&' : '?';
            $jpurl			.=	$tmp_str.join('&', $jparams);
        }
        $page_size = 20;
        $page_control		=	$page_obj->pageCut($count,$page,$page_size);
        $query_count = $page_control['query_count'];
        $invoice = JoyCountPay::getInvoice($traffic,$count_date_s,$count_date_e,$am_list_select_str,$page_size,$query_count);
        $invoice_all = JoyCountPay::getInvoice($traffic,$count_date_s,$count_date_e,$am_list_select_str,0,0);
        $page		=	$page_control['page'];
        $fenyecode	=	$page_obj->createPage( array('url'=>$jpurl, 'size'=>$count, 'page'=>$page, 'pageSize'=>$page_size) );
        Yii::app()->cache->set("excel{$this->user['userid']}",$invoice_all,300);
        if(in_array($this->user['groupid'],$this->manager_group)){
            $this->render('payment/info',array(
                'invoice'=>$invoice,
                'msg'=>$msg,
                'count_date'=>$count_date,
                'affids'=>$aff_arr,
                'am_list'=>$am_list,
                'page'=>$page,
                'count'=>$count,
                'fenyecode'=>$fenyecode
            ));
        }
    }

    public function actionSitePayoutInfo(){
        $msg = Yii::app()->request->getParam('msg');
        $count_date = Yii::app()->request->getParam('count_date');
        $traffic_arr = Yii::app()->request->getParam('traffic');
        $am_list_select = Yii::app()->request->getParam('am_list_select');
        $page = Yii::app()->request->getParam('page');
        if(empty($page)){
            $page = 1;
        }
        if(empty($count_date)){
            $count_date = date('Y-m',strtotime(JoyCountPay::getLastMonthLastDay(date('Y-m'))));
        }
        $count_date_s = date('Y-m-01',strtotime($count_date));
        $count_date_e = date('Y-m-t',strtotime($count_date));

        $aff_arr =array();
        $am_list = array();
        $traffic  = '';
        $am_list_select_str= '';

        switch($this->user['groupid']){
            case BUSINESS_GROUP_ID:
                $users_arr = JoySystemUser::model()->findByAttributes(array('manager_userid'=>$this->user['userid']));
                if(!empty($users_arr)){
                    $arr_id = implode(',',array_column('id',$users_arr));
                    $arr_company = implode(',',array_column('company',$users_arr));
                    $aff_arr = array_combine($arr_id,$arr_company);
                }
                break;
            case ADMIN_GROUP_ID:
            case MANAGER_GROUP_ID:
            case AM_GROUP_ID:
                $aff_arr = JoySystemUser::getResults('id,company',"groupid=".SITE_GROUP_ID);
                $am_list = JoySystemUser::getResults('id,company',"groupid=".BUSINESS_GROUP_ID);
                break;
        }
        if(!empty($traffic_arr)){
            $traffic = implode(',',$traffic_arr);
        }
        if(!empty($am_list_select)){
            $am_list_select_str = implode($am_list_select);
        }
        if (!empty($traffic_arr)) {
            foreach($aff_arr as $key=>$user) {
                if (in_array($user['id'], $traffic_arr)) {
                    $aff_arr[$key]['checked'] = true;
                }
            }
        }
        if(!empty($am_list_select) && !empty($am_list)){
            foreach($am_list as $key=>$am){
                if (in_array($am['id'], $am_list_select)) {
                    $am_list[$key]['checked'] = true;
                }
            }
        }

        $count = JoyCountPay::getAllInvoiceCount($traffic,$count_date_s,$count_date_e,$am_list_select_str);
        $jpurl			=	$this->createUrl('payment/sitepayoutinfo',array('count_date'=>$count_date));
        $jparams		=	array();
        $page_obj			=	new Page();
        if( 0 < count($jparams) ){
            $tmp_str		=	strpos($jpurl, '?') ? '&' : '?';
            $jpurl			.=	$tmp_str.join('&', $jparams);
        }
        $page_size = 20;
        $page_control		=	$page_obj->pageCut($count,$page,$page_size);
        $query_count = $page_control['query_count'];
        $invoice = JoyCountPay::getInvoice($traffic,$count_date_s,$count_date_e,$am_list_select_str,$page_size,$query_count);
        $invoice_all = JoyCountPay::getInvoice($traffic,$count_date_s,$count_date_e,$am_list_select_str,0,0);
        $page		=	$page_control['page'];
        $fenyecode	=	$page_obj->createPage( array('url'=>$jpurl, 'size'=>$count, 'page'=>$page, 'pageSize'=>$page_size) );
        $total_payment = JoyCountPay::getTotalPayment();
            Yii::app()->cache->set("excel{$this->user['userid']}",$invoice_all,300);
        if(in_array($this->user['groupid'],$this->manager_group)){
            $this->render('payment/payout_info',array(
                'total_payment'=>$total_payment,
                'invoice'=>$invoice,
                'msg'=>$msg,
                'count_date'=>$count_date,
                'sites'=>$aff_arr,
                'am_list'=>$am_list,
                'page'=>$page,
                'count'=>$count,
                'fenyecode'=>$fenyecode
            ));
        }
    }

    public function actionDetail(){
        $site_id = Yii::app()->request->getParam('siteid');
        $count_date = Yii::app()->request->getParam('count_date');
        $data = array();
        if($this->user['groupid'] == SITE_GROUP_ID){
            $site_id = $this->user['userid'];
        }
        if(empty($site_id)){
            $site_id = -1;
        }
        $site_arr = JoySites::getCompanySite($site_id);
        if(!empty($site_arr)){
            foreach($site_arr[$site_id] as $affid){
                $data[$affid] = JoyInvoice::model()->findByAttributes(array('count_date'=>$count_date,'affid'=>$affid));
            }
        }
        $this->render('payment/detail',array(
            'data'=>$data
        ));
    }

    public function actionOutPutExcel(){
        if(in_array($this->user['groupid'],$this->manager_group)){
            $invoice = Yii::app()->cache->get("excel{$this->user['userid']}");
            Yii::app()->cache->delete("excel{$this->user['userid']}");
            $sites = JoySites::getCompanySite();
            $head_arr = array('CN','Payee',	'Month',	'ID',	'Amount Payable','Total','Bank Name','Bank Address','Account NO','Swift Code');
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
            $footer = array(
                '申请人','复核人','部门审批','财务审批','总经理审批','财务付款'
            );
            $key = ord("A");
            foreach($head_arr as $v){
                $colum = chr($key);
                $objPHPExcel->setActiveSheetIndex(0) ->setCellValue($colum.'1', $v);
                $key += 1;
            }
            $t= 0;
            if(empty($invoice)){
                Common::jsalerturl("Sorry,Here is no data please try again!");
            }
            foreach($invoice['data'] as $item){
                $row_count = 0;
                $count_date = $item['count_date'];
                $arr = array();
                if(isset($sites[$item['site_id']])){
                    foreach($sites[$item['site_id']] as $siteid=>$affid){
                        $data = JoyInvoice::getResult("*"," and affid=$affid and count_date='$count_date'");
                        if(!empty($data)){
                            $arr[$siteid][$affid] = $data;
                            $row_count += count($data);
                        }
                    }
                }
                $t = Tools::excel_download_payment($head_arr,$arr,$item,$row_count,$objPHPExcel,$t);
            }

            $objPHPExcel->getActiveSheet()->mergeCells('B' . ($t + 2) . ':' . 'F' . ($t + 2));
            $objPHPExcel->getActiveSheet()->mergeCells('H' . ($t + 2) . ':' . 'J' . ($t + 2));
            $objPHPExcel->getActiveSheet()->setCellValue('A'. ($t + 2), $footer[0]);
            $objPHPExcel->getActiveSheet()->setCellValue('G'. ($t + 2), $footer[1]);
            $t++;
            $objPHPExcel->getActiveSheet()->mergeCells('B' . ($t + 2) . ':' . 'F' . ($t + 2));
            $objPHPExcel->getActiveSheet()->mergeCells('H' . ($t + 2) . ':' . 'J' . ($t + 2));
            $objPHPExcel->getActiveSheet()->setCellValue('A'. ($t + 2), $footer[2]);
            $objPHPExcel->getActiveSheet()->setCellValue('G'. ($t + 2), $footer[3]);
            $t++;
            $objPHPExcel->getActiveSheet()->mergeCells('B' . ($t + 2) . ':' . 'F' . ($t + 2));
            $objPHPExcel->getActiveSheet()->mergeCells('H' . ($t + 2) . ':' . 'J' . ($t + 2));
            $objPHPExcel->getActiveSheet()->setCellValue('A'. ($t + 2), $footer[4]);
            $objPHPExcel->getActiveSheet()->setCellValue('G'. ($t + 2), $footer[5]);

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
//        $fileName = date('His');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="'.$fileName.'.xlsx"');
            header('Cache-Control: max-age=0');

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save('php://output');
        }
    }
    public  function actionDownloadPayment(){
        $payment = Yii::app()->cache->get('payment' . $this->user['userid']);
        if(empty($payment)){
            Common::jsalerturl('Please try again!');
        }
        $head = array('Company','Affiliate ID','Beneficiary','Bank Name','	Bank Address','	Bank Account','Swift Code');
        Tools::downloadAffiliatesPayment($head,$payment);
    }
}