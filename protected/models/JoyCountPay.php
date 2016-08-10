<?php

/**
 * This is the model class for table "joy_count_pay".
 *
 * The followings are the available columns in table 'joy_count_pay':
 * @property integer $id
 * @property string $site_id
 * @property double $amount
 * @property string $invoice_date
 * @property integer $am
 * @property integer $check_am
 * @property integer $status
 * @property string $count_date
 * @property string $createtime
 */
class JoyCountPay extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return JoyCountPay the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'joy_count_pay';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('am, check_am, status', 'numerical', 'integerOnly'=>true),
			array('amount', 'numerical'),
			array('site_id', 'length', 'max'=>255),
			array('invoice_date, count_date, createtime', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, site_id, amount, invoice_date, am, check_am, status, count_date, createtime', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'site_id' => 'Site',
			'amount' => 'Amount',
			'invoice_date' => 'Invoice Date',
			'am' => 'Am',
			'check_am' => 'Check Am',
			'status' => 'Status',
			'count_date' => 'Count Date',
			'createtime' => 'Createtime',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('site_id',$this->site_id,true);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('invoice_date',$this->invoice_date,true);
		$criteria->compare('am',$this->am);
		$criteria->compare('check_am',$this->check_am);
		$criteria->compare('status',$this->status);
		$criteria->compare('count_date',$this->count_date,true);
		$criteria->compare('createtime',$this->createtime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	public static function getLastPayDate($site_id){
		$cdb = new CDbCriteria();
		if(!$site_id){
			$site_id = -1;
		}
		$cdb->addCondition('site_id='.$site_id);
		$cdb->addCondition('status=3');
		$cdb->order = 'count_date desc';
		$cdb->select = 'edate';
		$result = JoyCountTotal::model()->find($cdb);
		if(empty($result) || empty($result['edate'])){
			$sites = JoySites::getResult('affids'," and site_id=$site_id");
			if(empty($sites)){
				return '2015-12-01';

			}
			$sql = "select `time` from offer_count_self where affid in ({$sites['affids']}) ORDER BY `time` limit 1";
			$command = Yii::app()->db->createCommand($sql);
			$sql_result = $command->queryRow();
			return $sql_result['time'];
		}else{
			return $result['edate'];
		}
	}

 public static function getLastPayDate2($site_id){
                $cdb = new CDbCriteria();
                if(!$site_id){
                        $site_id = -1;
                }
                $cdb->addCondition('site_id='.$site_id);
                $cdb->addCondition('status=3');
                $cdb->order = 'count_date desc';
                $cdb->select = 'edate';
                $result = JoyCountTotal::model()->find($cdb);
                if(empty($result) || empty($result['edate'])){
                        $sites = JoySites::getResult('affids'," and site_id=$site_id");
                        if(empty($sites)){
                                return '2015-12-01';

                        }
                        $sql = "select `time` from offer_count_self where affid in ({$sites['affids']}) ORDER BY `time` limit 1";
                        $command = Yii::app()->db->createCommand($sql);
                        $sql_result = $command->queryRow();
$sql_result['time'] = date('Y-m-01',strtotime($sql_result['time']));
                        return $sql_result['time'];
                }else{
                        return $result['edate'];
                }
        }



	public static function getAffBeginDay($site_id){
		$db = Yii::app()->db;
		$sql = "select time from offer_count_self where affid = $site_id ORDER BY time ASC ";
		$command = $db->createCommand($sql);
		$result = $command->queryRow();
		return $result['time'];
	}

	public static function getSystemPayDay(){
		$db = Yii::app()->db;
		$command = $db->createCommand("select pdate from joy_paydate ORDER BY id DESC ");
		$result = $command->queryRow();
		return $result['pdate'];
	}

	public static function getInvoiceStartDay($last_pay_date){
		return date('Y-m-d',strtotime($last_pay_date));
	}
	public static function getInvoiceEndDay(){
		return self::getLastMonthLastDay(self::getSystemPayDay());
	}

	public static function getLastMonthLastDay($date){
		$year = date('Y',strtotime($date));
		$month = date('m',strtotime($date));
		if(1 == $month){
			$month = 12;
			$year = $year - 1;
		}else
			$month = $month - 1;
		return date('Y-m-t',strtotime($year .'-'. $month));
	}

	public static function getLastPayInfo($siteid){
		$last_pay_date = self::getLastPayDate($siteid);
		$cdb = new CDbCriteria();
		$count_date_start = self::getInvoiceStartDay($last_pay_date);
		$count_date_end = self::getInvoiceEndDay();
		if(!empty($last_pay_date)){
			$cdb->addCondition("t.time >= '$count_date_start' and t.time <= '$count_date_end'");
		}else{
			$cdb->addCondition("t.time <= '$count_date_end'");
		}
		$cdb->select = 'sum(revenue) as payout,sum(payout) as revenue,p.*';
		$siteid = intval($siteid);
		$cdb->addCondition("t.affid={$siteid}");
		$cdb->join = ' left join joy_payment p on p.affid = t.affid';
		return OfferCountSelf::model()->find($cdb);
	}
	public static function checkExist($site_id,$date){
		$db = Yii::app()->db;
		$sql = "select * from joy_count_pay where count_date = '$date' and site_id = $site_id";
		$command = $db->createCommand($sql);
		$result = $command->queryRow();
		if(!empty($result)){
			return true;
		}
		return false;
	}

	public static function getAllInvoiceCount($traffic,$count_date_s,$count_date_e,$am_list_str){
		$condition_1 = " and p.amount != 0.00";
		if(!empty($traffic)){
			$condition_1 .= " and p.site_id in ($traffic)";
		}
		if(!empty($am_list_str)){
			$condition_1 .= " and a.id in ($am_list_str)";
		}
		$condition_1 .= " and p.count_date >= '$count_date_s' and p.count_date <= '$count_date_e'";
		$sql = "select count(*) as count
from (select t.*,u.manager_userid,u.company from joy_count_pay t
left JOIN joy_system_user u on t.site_id = u.id)p
left JOIN joy_system_user a on p.manager_userid =a.id
where 1=1 $condition_1 ORDER BY p.id ASC ";
		$db = Yii::app()->db;
		$command = $db->createCommand($sql);
		$result = $command->queryRow();
		return $result['count'];
	}

	public static function getExcelParseData($traffic,$count_date_s,$count_date_e,$am_list_str,$page_size,$page_begin){
		$condition_1 = " and p.status != 3 and p.amount != 0.00";
		$condition_2 = " and p.amount != 0.00";
		$limit = "";
		if(!empty($page_size)){
			$limit .= " limit $page_size";
		}
		if(!empty($page_begin)){
			$limit .= " offset $page_begin";
		}

		if(!empty($traffic)){
			$condition_1 .= " and p.site_id in ($traffic)";
			$condition_2 .= " and p.site_id in ($traffic)";
		}
		if(!empty($am_list_str)){
			$condition_1 .= " and a.id in ($am_list_str)";
		}
		$condition_1 .= " and p.count_date >= '$count_date_s' and p.count_date <= '$count_date_e'";
		$condition_2 .= " and p.count_date >= '$count_date_s' and p.count_date <= '$count_date_e'";
		$db = Yii::app()->db;
		$sql = "select p.id,a.company as am,p.site_id,p.company,p.amount_paid,p.amount,p.status,p.count_date,pa.bank_name,pa.bank_address,pa.bank_account,pa.swift_code,pa.contract_code,s.affids
from (select t.*,u.manager_userid,u.company from joy_count_pay t
left JOIN joy_system_user u on t.site_id = u.id)p
left JOIN joy_system_user a on p.manager_userid =a.id
LEFT JOIN joy_payment pa on p.site_id=pa.affid
LEFT JOIN joy_sites s on p.site_id = s.site_id
where 1=1 $condition_1 ORDER BY p.site_id ASC $limit";
		$command = $db->createCommand($sql);
		$invoice['data'] = $command->queryAll();
		$select_sql = "select sum(amount) as not_paid from joy_count_pay p where status != 3 $condition_2";
		$db = Yii::app()->db;
		$connect = $db->createCommand($select_sql);
		$invoice['count'] = $connect->queryRow();
		foreach($invoice['data'] as $invoice_key=>$invoice_value){
			$result  = null;
			$affid_str = $invoice_value['affids'];
			if(!empty($affid_str)){
				$invoice_sql = "select amount,count_date,affid from joy_invoice WHERE affid in ($affid_str) AND status != 3";
				$command = $db->createCommand($invoice_sql);
				$result = $command->queryAll();
			}
			$invoice['data'][$invoice_key]['invoice'] = $result;
		}
		return $invoice;
	}

	public static function getInvoice($traffic,$count_date_s,$count_date_e,$am_list_str,$page_size,$page_begin){
		$condition_1 = " and p.amount != 0.00";
		$condition_2 = " and p.amount != 0.00";
		$condition_3 = " and p.amount != 0";
		$condition_extra = " and count_date >= '$count_date_s' and '$count_date_e'";
		$limit = "";
		if(!empty($page_size)){
			$limit .= " limit $page_size";
		}
		if(!empty($page_begin)){
			$limit .= " offset $page_begin";
		}

		if(!empty($traffic)){
			$condition_1 .= " and p.site_id in ($traffic)";
			$condition_2 .= " and p.site_id in ($traffic)";
		}
		if(!empty($am_list_str)){
			$condition_1 .= " and a.id in ($am_list_str)";
		}
		$condition_1 .= " and p.count_date >= '$count_date_s' and p.count_date <= '$count_date_e'";
		$condition_2 .= " and p.count_date >= '$count_date_s' and p.count_date <= '$count_date_e'";
		$db = Yii::app()->db;
		$sql = "select g.extra,p.id,a.company as am,p.site_id,p.company,p.amount_paid,p.amount,p.status,p.count_date,pa.bank_name,pa.bank_address,pa.bank_account,pa.swift_code,pa.contract_code,s.affids
from (select t.*,u.manager_userid,u.company from joy_count_pay t
left JOIN joy_system_user u on t.site_id = u.id)p
left JOIN joy_system_user a on p.manager_userid =a.id
LEFT JOIN joy_payment pa on p.site_id=pa.affid
LEFT JOIN joy_sites s on p.site_id = s.site_id
LEFT JOIN (SELECT sum(extra) as extra,site_id FROM joy_count_extra where 1=1 $condition_extra GROUP BY site_id)g ON g.site_id = p.site_id
where 1=1 $condition_1 ORDER BY p.site_id ASC $limit";
		$command = $db->createCommand($sql);
		$invoice['data'] = $command->queryAll();
		$select_sql = "select sum(amount) as not_paid from joy_count_pay p where status != 3 $condition_2";
		$unpaid_all = "select sum(amount) as unpaid_all from joy_count_pay p where status != 3 $condition_3";
		$paid = "select sum(amount_paid) as amount_paid from joy_count_pay p WHERE status = 3 $condition_3";
		$db = Yii::app()->db;
		$connect = $db->createCommand($select_sql);
		$command_un = $db->createCommand($unpaid_all);
		$command_paid = $db->createCommand($paid);
		$invoice['count'] = $connect->queryRow();
		$unp_all = $command_un->queryRow();
		$had_paid = $command_paid->queryRow();
		$invoice['count']['unpaid_all'] = $unp_all['unpaid_all'];
		$invoice['count']['paid'] = $had_paid['amount_paid'];
		return $invoice;
	}

	public static function getNotPaid($userid,$search=array()){
		$db = Yii::app()->db;
		$month_sql = "select sum(amount) as not_paid,u.company,p.count_date
 from joy_count_pay p LEFT JOIN joy_system_user u  on u.id=p.site_id WHERE p.status != 3   GROUP BY p.count_date";
		$command = $db->createCommand($month_sql);
		$result = $command->queryAll($command);
		return $result;
	}

	public static function getTotalPayment($groupid,$userid,$count_date_f){
		$db = Yii::app()->db;
		$condition = '';
		if($groupid == SITE_GROUP_ID){
			$condition = " and t.site_id = $userid";
		}
		$count_date = $count_date_f . '-01';
		$paid_sql = "select t.site_id,t.not_paid,s.paid,u.company,t.predict_paid,s.paid_true,t.extra from
(SELECT site_id,sum(amount) as not_paid,sum(amount_paid) as predict_paid,extra FROM joy_count_pay WHERE status != 3 and count_date = '$count_date' GROUP BY site_id)t
LEFT JOIN (SELECT site_id,sum(amount) as paid,sum(amount_paid) as paid_true FROM joy_count_pay WHERE status = 3 and count_date = '$count_date' GROUP BY site_id)s
ON t.site_id=s.site_id
LEFT JOIN joy_system_user u on u.id=t.site_id
WHERE 1=1 $condition";
		$command = $db->createCommand($paid_sql);
		$result = $command->queryAll($command);
		return $result;
	}

	public static  function PrintPDF($siteid,$count_date,$type=0){//2015-12-01
		$result = null;
		try{

		include("protected/extensions/tcpdf/config/tcpdf_include.php");
		$connection = Yii::app()->db;
		$table_name = 'joy_count_pay';
		$split_date = '2016-04-01';
		$condition = '';
		if(strtotime($count_date) > strtotime($split_date)){
			$condition = " AND count_date >= '$split_date'";
		}
/*		$sql = "
SELECT u.address,u.phone,u.company,t.site_id,t.amount,t.invoice_date,p.beneficiary,p.bank_name,p.bank_name,p.bank_address,p.bank_account,p.swift_code,ex.extra FROM `$table_name` t
LEFT JOIN joy_payment p on t.site_id=p.affid left JOIN joy_system_user u on t.site_id= u.id LEFT JOIN (SELECT sum(extra) as extra,site_id from joy_count_extra WHERE count_date = '$count_date' GROUP BY site_id)ex
ON ex.site_id = t.site_id where t.status!=3 AND t.site_id=$siteid";
*/
		if($type == 1){
			$condition1 = ' and status!=3  and status != 0 ';
		}else{
			$condition1 = '  and status!=3';
		}
	$sql = "
		select t.amount,t.invoice_date,t.site_id,u.address,u.phone,u.company,p.beneficiary,p.bank_name,p.bank_name,p.bank_address,p.bank_account,p.swift_code,ex.extra
from (select sum(amount) as amount,invoice_date,site_id  from joy_count_pay WHERE  site_id=$siteid  $condition1)t
LEFT JOIN joy_payment p on t.site_id=p.affid left JOIN joy_system_user u on t.site_id= u.id LEFT JOIN (SELECT sum(extra) as extra,site_id from joy_count_extra WHERE count_date = '$count_date' AND site_id=$siteid $condition GROUP BY site_id)ex
ON ex.site_id = t.site_id where t.site_id=$siteid
";

		$command = $connection->createCommand($sql);
		$result = $command->queryRow();
		if(empty($result['invoice_date'])){
			return null;
		}
		$start_pay_date = self::getLastPayDate2($result['site_id']);
		$end_pay_date = self::getInvoiceEndDay();

		// create new PDF document
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('Nicola Asuni');
		$pdf->SetTitle('TCPDF Example 006');
		$pdf->SetSubject('TCPDF Tutorial');
		$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

		// set default header data

		// set header and footer fonts
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		// set margins
		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);


		// ---------------------------------------------------------
		// set font
		$pdf->SetFont('droidsansfallback', '', 10);

		// add a page
		$pdf->AddPage();

		// writeHTML($html, $ln=true, $fill=false, $reseth=false, $cell=false, $align='')
		// writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true)
		$amount = $result['amount'];
		// create some HTML content
		$html = '<style>
		.headp{
			font-weight: bold;font-size: 10px;
		}
		.hspan{
			font-weight: normal;
		}
		tr{
			text-align:center;width:200px;height: 30px;
		}
	</style>
	<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Title</title>
	</head>
	<body style="line-height: 10px">
	<p class="">Invoice</p>
	<p class="headp">Invoice Number:<span class="hspan">'.$result['site_id'].'#'.date('Ymd',strtotime(self::getSystemPayDay())).'#'.$siteid.'</span></p>
	<p class="headp">From : <span class="hspan">'.$result['company'].'</span></p>
	<p class="headp">Address:<span class="hspan">'.$result['address'].'</span></p>
	<p class="headp">Tel:<span class="hspan">'.$result['phone'].'</span></p>
	<p class="headp">Billing to:<span class="hspan">JOYMEDIA TECHNOLOGY LIMITED</span></p>
	<p class="headp">Address: <span class="hspan"> Room 1508, 15/F, Office Tower Two, Grand Plaza,625 Nathan Road, Kowloon, Hong Kong</span></p>

	<table style="width: 100%;border-bottom-color: black;" border="0" cellspacing="10" cellpadding="10">
		<thead>
		<tr style="font-weight: bold;font-size: 12px">
			<td>Description</td>
			<td>Date</td>
			<td>Amount</td>
		</tr>
		</thead>
		<tr style="font-weight: normal;font-size: 12px">
			<td>Advertising Fee</td>
			<td>'.$start_pay_date.'  ~  '.$end_pay_date.'</td>
			<td>'.$amount.'</td>
		</tr>
	</table>

	<p class="headp">Payable To:<span class="hspan"></span></p>
	<p style="font-size: 10px">Please settle this invoice to the following bank account.</p>
	<p class="headp">Payee:<span class="hspan">'.$result['beneficiary'].'</span></p>
	<p class="headp">Bank Name:<span class="hspan">'.$result['bank_name'].'</span></p>
	<p class="headp">Bank Address:<span class="hspan">'.$result['bank_address'].'</span></p>
	<p class="headp">Account Number:<span class="hspan">'.$result['bank_account'].'</span></p>
	<p class="headp">Swift Code:<span class="hspan">'.$result['swift_code'].'</span></p>
	<p class="headp">Notice: <span class="hspan">Hereby certify that the information on this invoice is true and correct and all payments are payable to the information above.</span></p>
	';

		// output the HTML content
		$pdf->writeHTML($html, true, false, true, false, '');
		// ---------------------------------------------------------
		//Close and output PDF document
		$pdf_name =  $siteid. '_' . date('Ymdhis') . '.pdf';
		$pdf->Output(PDF_SAVE_PATH . $pdf_name, 'F');

		if($type == 1){
			$count_total = JoyCountTotal::model()->findByAttributes(array('count_date'=>$count_date,'site_id'=>$siteid));
			$count_total->pdf = $pdf_name;
		}else{
			$count_total = new JoyCountTotal();
			$count_total->createtime = date('Y-m-d H:i:s');
			$count_total->pdf = $pdf_name;
			$count_total->edate = $end_pay_date;
			$count_total->sdate = $start_pay_date;
			$count_total->site_id = $siteid;
			$count_total->count_date = $count_date;
		}
		$result = $count_total->save();
		}catch(Exception $e){
			var_dump(111);

			var_dump($e->getMessage());
		}
		//============================================================+
		// END OF FILE
		//============================================================+
		return $result;
	}


	public static function countPay($date){
		$sites = JoySites::getCompanySite();
		$db = Yii::app()->db;
		$sql = "select * from joy_invoice where count_date='$date'";
		$command = $db->createCommand($sql);
		$result = $command->queryAll();
//		$arr = JoySites::getCompanyData($result);
		var_dump($result);die();
		$sql = "select u.company as aff_name,t.count_date as mon ,t.affid,t.amount,t.id,m.company as am_name from joy_invoice t LEFT JOIN
joy_system_user u on u.id=t.affid LEFT JOIN
joy_system_user m on t.am_id=m.id
ORDER BY aff_name";
	}
}
