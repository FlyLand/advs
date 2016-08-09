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
        //0 is send the invoice  1 is pay for affiliate  2 is check the pdf
        if(0 == $type) {
            //is manager
            if(in_array($this->user['groupid'],array(ADMIN_GROUP_ID,BUSINESS_GROUP_ID))){
                $result = JoyCountPay::model()->updateByPk($id, array('status' => 2,'am_id'=>$this->user['userid']));
                $joyCountPay = JoyCountPay::model()->findByPk($id);
                $payout = $joyCountPay->amount_paid;
            }
        }elseif(1 == $type) {
            if(in_array($this->user['groupid'],array(ADMIN_GROUP_ID,FINANCE_GROUP_ID))){
                $invoice = JoyCountPay::model()->findByPk($id);
                $result = JoyCountPay::model()->updateByPk($id, array('status' => 4, 'finance_id' => $this->user['userid'],'amount_sent'=>$invoice['amount']));
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
        $count_date = '';
        do {
            try {
                if (!empty ($_FILES ['upload_file'] ['name'])) {
                    $pdf_type = Yii::app()->request->getParam('type');
                    $id = Yii::app()->request->getParam('id');
                    $count_date = Yii::app()->request->getParam('count_date');
                    if (0 == $pdf_type) {
                        $pdf_field_name = 'pdf';
                    } elseif (1 == $pdf_type) {
                        $pdf_field_name = 'pdf_back';
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
                        if(!JoyCountTotal::model()->updateByPk($id, array($pdf_field_name => $file_name))){
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
        $this->redirect($this->createUrl('payment/paymenttotal',array('count_date'=>$count_date)));
    }

    public function actionCheckAll(){
        $data =   Yii::app()->request->getParam('data');
        $conn   =   Yii::app()->db->beginTransaction();
        if(!empty($data)) {
            foreach ($data as $key) {
                $id = $key[0];
                $payment = JoyCountPay::model()->findByPk($id);
                $sites = JoySites::getCompanySite($payment['site_id']);
                $count_date = $payment['count_date'];
                foreach ($sites as $siteid => $aff_arr) {
                    foreach ($aff_arr as $aff) {
                        $invoice = JoyInvoice::model()->findByAttributes(array('count_date' => $count_date, 'affid' => $aff));
                        if (!empty($invoice)) {
                            $invoice->status = 1;
                            $invoice->save();
                        }
                    }

                }
                if ($payment->status == 0) {
                    $payment->status = 1;
                }
                if (!$payment->save()) {
                    echo $payment->errors;
                    $conn->rollback();
                    die();
                }
            }
            $conn->commit();
            foreach ($data as $key) {
                $id = $key[0];
                $payment = JoyCountPay::model()->findByPk($id);
                $sites = JoySites::getCompanySite($payment['site_id']);
                $count_date = $payment['count_date'];
                foreach ($sites as $siteid => $aff_arr) {
                    JoyCountPay::PrintPDF($siteid, $count_date, 1);
                }
            }
            echo 'success';
        }else{
            echo 'failed!';
        }
    }


    public function actionBuilding(){
        $result = JoyCountTotal::model()->findAllByAttributes(array('site_id'=>$this->user['userid']));
        $this->render('payment/building',array(
            'result'=>$result
        ));
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
            $am_list_select_str = implode(',',$am_list_select);
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
//        $invoice_all = JoyCountPay::getInvoice($traffic,$count_date_s,$count_date_e,$am_list_select_str,0,0);
        $invoice_all = JoyCountPay::getExcelParseData($traffic,$count_date_s,$count_date_e,$am_list_select_str,0,0);
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
            $am_list_select_str = implode(',',$am_list_select);
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
        $jpurl			=	$this->createUrl('payment/sitepayoutinfo',array('count_date'=>$count_date,'am_list_select'=>$am_list_select));
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
        $invoice_all = JoyCountPay::getExcelParseData($traffic,$count_date_s,$count_date_e,$am_list_select_str,0,0);
//        $invoice_all = JoyCountPay::getInvoice($traffic,$count_date_s,$count_date_e,$am_list_select_str,0,0);
        $page		=	$page_control['page'];
        $fenyecode	=	$page_obj->createPage( array('url'=>$jpurl, 'size'=>$count, 'page'=>$page, 'pageSize'=>$page_size) );
        $total_payment = JoyCountPay::getTotalPayment($this->user['groupid'],$this->user['userid'],$count_date);
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

    public function actionExtra(){
        $invoice_id = Yii::app()->request->getParam('invoice_id');
        $extra = Yii::app()->request->getParam('extra');
        $remark = Yii::app()->request->getParam('remark');
        $type = Yii::app()->request->getParam('type');
        $db = Yii::app()->db;
        if($type == 'add'){
            $invoice = JoyInvoice::model()->findByPk($invoice_id);
            if(empty($invoice)){
                return null;
            }
            $count_date = $invoice['count_date'];
            $affid = $invoice['affid'];
            $sql = "select site_id from joy_sites where find_in_set($affid,affids)";
            $command = $db->createCommand($sql);
            $site_arr = $command->queryRow();
            if(empty($site_arr)){
                return null;
            }
            $extra_model = new JoyCountExtra();
            $extra_model->extra = $extra;
            $extra_model->remark = $remark;
            $extra_model->invoice_id = $invoice_id;
            $extra_model->site_id = $site_arr['site_id'];
            $extra_model->count_date = $count_date;
            $extra_result = $extra_model->save();
            if($extra_result){
                $pay = JoyCountPay::model()->findByAttributes(array('count_date'=>$count_date,'site_id'=>$site_arr['site_id']));
                if(empty($pay)){
                    return null;
                }
                $pay->amount = $pay->amount + $extra;
                $pay_result = $pay->save();
                if($pay_result){
                    Common::jsalerturl('Success!');
                }
            }
       }
    }

    public function actionExtraDetail(){
        $site_id = Yii::app()->request->getParam('site_id');
        $count_date = Yii::app()->request->getParam('count_date');
        $extras = JoyCountExtra::model()->findAllByAttributes(array('site_id'=>$site_id,'count_date'=>$count_date));
        $this->render('payment/extra_detail',array(
            'extras'=>$extras
        ));
    }

    public function actionPaymentTotal(){
        $count_date = Yii::app()->request->getParam('count_date');
        if(empty($count_date)){
            $count_date = date('Y-m');
        }
        $total_payment = JoyCountTotal::getTotal($count_date . '-01',$this->user['groupid'],$this->user['userid']);       
        if(in_array($this->user['groupid'],array(FINANCE_GROUP_ID,ADMIN_GROUP_ID))){
            Yii::app()->cache->set($this->user['userid'] . 'download',$total_payment,1000);
        }
        $total_record = JoyCountRecord::getRecord($count_date . '-01',$this->user['groupid'],$this->user['userid']);
        $cdb = new CDbCriteria();
        $cdb->order = 'createtime desc';
        $this->render('payment/total_payment',array(
            'count_date'=>$count_date,
            'total_payment'=>$total_payment,
            'total_record'=>$total_record
        ));
    }

    public function actionSendAndPay(){
        $id = Yii::app()->request->getParam('id');
        $type = Yii::app()->request->getParam('type');
        $fee = Yii::app()->request->getParam('fee');
        $result = array();
        //0 is check the invoice  1 is pay for affiliate
        if(0 == $type) {
            //is manager
            if(in_array($this->user['groupid'],array(ADMIN_GROUP_ID,BUSINESS_GROUP_ID))){
                $result = JoyCountTotal::model()->updateByPk($id, array('status' => 1,'am_id'=>$this->user['userid']));
            }
        }elseif(1 == $type) {
            if(in_array($this->user['groupid'],array(ADMIN_GROUP_ID,FINANCE_GROUP_ID))){
                $total = JoyCountTotal::model()->findByPk($id);
                if(!empty($total)){
                    $db = Yii::app()->db;
                    $sql = "select p.id,p.amount,p.count_date,e.extra from joy_count_pay p LEFT JOIN (SELECT sum(extra) as extra,site_id from joy_count_extra GROUP BY site_id)e ON e.site_id = p.site_id WHERE p.site_id={$total['site_id']} AND p.count_date <= '{$total['edate']}' AND p.count_date >= '{$total['sdate']}' AND p.status=1";
                    $command = $db->createCommand($sql);
                    $query_result = $command->queryAll();
                    $amount_paid = 0;
                    foreach($query_result as $item) {
                        $amount_paid = $item['amount']  + $item['extra'];
                        array_push($result,JoyCountPay::model()->updateByPk($item['id'], array('status' => 2, 'amount_paid' => $amount_paid)));
                    }
                    $total->status = 3;
                    if(!empty($fee)){
                        $total->fee = FEE;
                    }
                    if(!empty($extra)){
                        $total->extra = $extra;
                    }
                    $total_result = $total->save();
                    if($total_result){
                        $record = new JoyCountRecord();
                        $record->site_id = $total['site_id'];
                        $record->amount_paid = $amount_paid;
                        $record->amount = $amount_paid;
                        $record->count_date = $total['count_date'];
                        if($fee){
                            $record->fee = FEE;
                        }
                        $record->createtime = date('Y-m-d H:i:s');
                        $record->finance_id = $this->user['userid'];
                        $result = $record->save();
                    }
                }
            }
        }elseif(2 == $type){
            if(in_array($this->user['groupid'],array(ADMIN_GROUP_ID,BUSINESS_GROUP_ID))){
                $joyCountPay = JoyCountTotal::model()->findByPk($id);
                if($joyCountPay->status == 1){
                    $joyCountPay->status = 2;
                    $result = $joyCountPay->save();
                }
            }
        }
        if(!empty($result))
            echo json_encode(array('status'=>1));
        else
            echo json_encode(array('status'=>0));
    }


    public function actionSaveTotalPayment(){
        $data =   Yii::app()->request->getParam('data');
        $site_id = Yii::app()->request->getParam('site');
        $conn   =   Yii::app()->db->beginTransaction();
        if(empty($site_id) && $site_id != 0){
            foreach($data as $key){
                $site_id =   $key[1];
                $pre_paid    =   $key[2];
                $not_paid = $key[3];
                if(empty($pre_paid)){
                    continue;
                }
                if($not_paid != $pre_paid){
                    continue;
                }
                $payment   =   JoyCountPay::model()->findAllByAttributes(array('site_id'=>$site_id,'status'=>3));
                $payment->amount_paid = $pre_paid;
                $payment->status = 3;
                if(!$payment->update()){
                    echo $payment->errors;
                    $conn->rollback();
                    die();
                }
            }
            $conn->commit();
            echo 'success';
        }else{
            $fee_value = Yii::app()->request->getParam('fee');
            $invoices = JoyCountPay::model()->findAll(array('condition'=>"status != :status and site_id=$site_id",'params'=>array(':status'=>3)));
            $amount_all = 0;
            $amount_paid_all = 0;
            $fee = 0;
            if($fee_value){
                $fee = 20;
            }
            $invoice_ids = '';
            $pay_ids = '';

            if(!empty($invoices)){
                foreach($invoices as $invoice){
                    $count = JoyCountPay::model()->findByPk($invoice['id']);
                    $count->status = 3;
                    $count->amount_paid = $invoice['amount'];
                    $amount_paid_all += $invoice['amount'];
                    $amount_all += $invoice['amount'];
                    $pay_ids .= $invoice['id'] . ',';
                    if(!$count->save()){
                        $conn->rollback();
                        echo "Failed";
                        exit(1);
                    }
                }
            }
            $paydate = date('Y-m-d H:i:s');
            $affids = JoySites::getResult('affids'," and site_id=$site_id");
            if(empty($affids)){
                echo 'Complete';
                exit(1);
            }
            $aff_arr = explode(',',$affids['affids']);
            foreach($aff_arr as $aff){
                $result = JoyInvoice::model()->findAllByAttributes(array('affid'=>$aff,'status'=>" <> 3"));
                foreach($result as $invoice){
                    $invoice->status = 3;
                    $invoice->pay_date = $paydate;
                    $invoice->amount_sent = $invoice->amount;
                    $invoice_ids .= $invoice['id'] . ',';
                    if(!$invoice->save()){
                        $conn->rollback();
                        echo "Failed";
                        exit(1);
                    }
                }
            }
            $record = new JoyCountRecord();
            $record->site_id = $site_id;
            $record->createtime = date('Y-m-d H:i:s');
            $record->fee = $fee;
            $record->invoice_ids = $invoice_ids;
            $record->count_pay_ids = $pay_ids;
            $record->amount = $amount_all;
            $record->amount_paid = $amount_paid_all;
            $record->head = $this->user['userid'];
            $record->save();
            $conn->commit();
            echo "success";
        }
    }

    public function actionDetail(){
        $site_id = Yii::app()->request->getParam('sid');
        $count_date = Yii::app()->request->getParam('cd');
        $site_str = JoySites::getResult('affids'," and site_id = $site_id");
        if(empty($site_str)){
            $affids = -1;
        }else{
            $affids = $site_str['affids'];
        }
        $sql = "select * from joy_invoice i where i.affid in ($affids)
 and count_date >= '$count_date-01' and count_date <= '$count_date-31'";
        $sql_paid = "select sum(amount),sum(amount_sent) from joy_invoice GROUP BY affid";
        $command = Yii::app()->db->createCommand($sql);
        $result = $command->queryAll();
        if(!empty($result)){
            foreach($result as $key=>$rs){
                $extra_sql = "select sum(extra) as extra from joy_count_extra WHERE invoice_id = {$rs['id']}";
                $extra_command = Yii::app()->db->createCommand($extra_sql);
                $row = $extra_command->queryRow();
                if(!empty($row)){
                    $result[$key]['extra'] = $row['extra'];
                }
            }
        }
        $this->render('payment/detail',array('data'=>$result));
    }

    public function actionDownloadMonthExcel(){
        $data = Yii::app()->cache->get($this->user['userid'] . 'download');
        $headArr = array('Payment ID','Payee','Amount Paid','Pay Date','Bank Name','Transaction Number');
        Tools::downloadMonthExcel($data,$headArr);
    }

    public function actionUploadMonthExcel(){
        $msg = 'Error';
        try {
            do {
                if (!empty ($_FILES ['upfile'] ['name'])) {
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
                            $result = $this->paymentDetail($data);
                            if($result){
                                $msg = 'Success';
                            }else{
                                $msg = 'No File Upload!';
                            }
                        }
                    }
                }else{
                    $msg = 'No File Upload!';
                }
            }while(0);
        }catch(Exception $e){
            var_dump($e->getMessage());die();
        }
        $this->redirect($this->createUrl('payment/paymenttotal'));
    }

    public function paymentDetail($data){
        $result = false;
        foreach($data as $key=>$item){
            if($key == 1){
                continue;
            }
            $payment_id = $item[0];
            $params['amount_paid'] = $item[2];
            $params['timestamp'] = PHPExcel_Shared_Date::ExcelToPHP(trim($item[3]));
            $params['bank_name'] = $item[4];
            $params['swift_num'] = $item[5];
            $timestramp = PHPExcel_Shared_Date::ExcelToPHP(trim($item[3]));
            $params['timestamp'] = date('Y-m-d',$timestramp);
            $total = JoyCountTotal::model()->findByPk($payment_id);
            if(empty($total)){
                Common::jsalerturl('Error!');
            }
            $siteid = $total['site_id'];
            $count_date = $total['count_date'];
            $result = JoyCountTotal::setPaymentPaid($siteid,$count_date,$this->user['userid'],$params);
        }
        return $result;
    }

    public function actionOutPutExcel(){
        if(in_array($this->user['groupid'],$this->manager_group)){
            $invoice = Yii::app()->cache->get("excel{$this->user['userid']}");
//            Yii::app()->cache->delete("excel{$this->user['userid']}");
            $sites = JoySites::getCompanySite();
            $head_arr = array('CN','Payee',	'Month','Site ID','Affiliate ID','Amount Payable','Total','Bank Name','Bank Address','Account NO','Swift Code');
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
                $row_count = count($item['invoice']);
                $t = Tools::excel_payment_with_site($head_arr,$item,$row_count,$objPHPExcel,$t);
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
