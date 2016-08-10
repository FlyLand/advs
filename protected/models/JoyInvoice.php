<?php

/**
 * This is the model class for table "joy_invoice".
 *
 * The followings are the available columns in table 'joy_invoice':
 * @property integer $id
 * @property double $amount
 * @property integer $status
 * @property string $affid
 * @property string $createtime
 * @property integer $am_id
 * @property string $invoice_date
 * @property string $count_date
 */
class JoyInvoice extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return JoyInvoice the static model class
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
		return 'joy_invoice';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('status, am_id', 'numerical', 'integerOnly'=>true),
			array('amount', 'numerical'),
			array('affid', 'length', 'max'=>200),
			array('createtime, invoice_date, count_date', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, amount, status, affid, createtime, am_id, invoice_date, count_date', 'safe', 'on'=>'search'),
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
			'amount' => 'Amount',
			'status' => 'Status',
			'affid' => 'Affid',
			'createtime' => 'Createtime',
			'am_id' => 'Am',
			'invoice_date' => 'Invoice Date',
			'count_date' => 'Count Date',
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
		$criteria->compare('amount',$this->amount);
		$criteria->compare('status',$this->status);
		$criteria->compare('affid',$this->affid,true);
		$criteria->compare('createtime',$this->createtime,true);
		$criteria->compare('am_id',$this->am_id);
		$criteria->compare('invoice_date',$this->invoice_date,true);
		$criteria->compare('count_date',$this->count_date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public static function getInvoice($ids,$groupid){
		$condition = '';
		if(!empty($ids)){
			$condition = " and site_id = $ids";
		}else{
			if(in_array($groupid,array(SITE_GROUP_ID,BUSINESS_GROUP_ID))){
				return array('data'=>null);
			}
		}
		if(in_array($groupid,array(AFF_GROUP_ID,SITE_GROUP_ID))){
			$condition .= " and status != 0";
		}
		$invoice['data'] = JoyCountPay::model()->findAll("1=1  $condition");
		$select_sql = "select sum(amount) as not_paid from joy_invoice where amount_sent  is  null $condition";
		$db = Yii::app()->db;
		$connect = $db->createCommand($select_sql);
		$invoice['count'] = $connect->queryRow();
		return $invoice;
	}

	public static function getInvoiceStartDay($last_pay_date){
		return date('Y-m-d',strtotime($last_pay_date));
	}
	public static function getInvoiceEndDay(){
		return self::getLastMonthLastDay(self::getSystemPayDay());
	}

	public static function getMonthInfo($affid = null){
		$last_pay_date = JoyInvoice::getSystemLastPayDay();
		$count_date_start = date('Y-m-01',strtotime($last_pay_date));
		$count_date_end = self::getInvoiceEndDay();
		$condition ='';
		if(!empty($affid)){
			$condition = " and t.affid=$affid";
		}
		$condition .= " and t.time >= '$count_date_start' and t.time <= '$count_date_end'";
		$sql = "select sum(t.revenue) as payout,sum(t.payout) as revenue,t.affid,p.beneficiary,p.bank_name,p.bank_address,p.bank_account,p.swift_code
from offer_count_self t left join joy_payment p on
p.affid = t.affid where 1=1  $condition GROUP BY t.affid";
		$db = Yii::app()->db;
		$command = $db->createCommand($sql);
		if(!empty($affid)){
			return $command->queryRow();
		}else{
			return $command->queryAll();
		}
	}

	public static function getLastPayInfo($affid){
		$last_pay_date = JoyInvoice::getLastPayDate($affid);
		$cdb = new CDbCriteria();
		$count_date_start = self::getInvoiceStartDay($last_pay_date);
		$count_date_end = self::getInvoiceEndDay();
		if(!empty($last_pay_date)){
			$cdb->addCondition("t.time >= '$count_date_start' and t.time <= '$count_date_end'");
		}else{
			$cdb->addCondition("t.time <= '$count_date_end'");
		}
		$cdb->select = 'sum(revenue) as payout,sum(payout) as revenue,p.*';
		$affid = intval($affid);
		$cdb->addCondition("t.affid={$affid}");
		$cdb->join = ' left join joy_payment p on p.affid = t.affid';
		return OfferCountSelf::model()->find($cdb);
	}

	/**
	 * @param $affid integer
	 * @return  string
	 */
	public static function getLastPayDate($affid){
		$cdb = new CDbCriteria();
		if(!$affid){
			$affid = 0;
		}
		$cdb->addCondition('affid='.$affid);
		$cdb->order = 'pay_date desc';
		$cdb->select = 'pay_date';
		$result = JoyInvoice::model()->find($affid);
		if(empty($result) || empty($result['pay_date']) || self::getSystemPayDay() == $result['pay_date']){
			$result['pay_date'] = self::getAffBeginDay($affid);
			return $result['pay_date'];
		}else{
			return $result['pay_date'];
		}
	}

	public static function getAffBeginDay($affid){
		$db = Yii::app()->db;
		$sql = "select time from offer_count_self where affid = $affid ORDER BY time ASC ";
		$command = $db->createCommand($sql);
		$result = $command->queryRow();
		return $result['time'];
	}

	public static function getLastMonthFirstDay($date){
		$year = date('Y',strtotime($date));
		$month = date('m',strtotime($date));
		if(1 == $month){
			$month = 12;
			$year = $year - 1;
		}else
			$month = $month - 1;
		return $year . '-' . $month .'-01';
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

	public static function getSystemLastPayDay(){
		$db = Yii::app()->db;
		$command = $db->createCommand("select ledate from joy_paydate ORDER BY id DESC ");
		$result = $command->queryRow();
		return $result['ledate'];
	}

	public static function getSystemPayDay(){
		$db = Yii::app()->db;
		$command = $db->createCommand("select pdate from joy_paydate ORDER BY id DESC ");
		$result = $command->queryRow();
		return $result['pdate'];
	}

	public static function checkExist($affid,$date){
		$db = Yii::app()->db;
		$sql = "select * from joy_invoice where count_date = '$date' and affid = $affid";
		$command = $db->createCommand($sql);
		$result = $command->queryRow();
		if(!empty($result)){
			return true;
		}
		return false;
	}

	public static function getResult($fields = '*',$condition = ''){
		$db = Yii::app()->db;
		$sql = "select $fields from joy_invoice where 1=1 $condition";
		$command = $db->createCommand($sql);
		return $command->queryAll();
	}

	public static  function PrintPDF($id){
		include("protected/extensions/tcpdf/config/tcpdf_include.php");
		$connection = Yii::app()->db;
		$table_name = 'joy_count_pay';
		$sql = "SELECT u.address,u.phone,u.company,u.id as affid,t.amount,t.invoice_date,p.beneficiary,p.bank_name,p.bank_name,p.bank_address,p.bank_account,p.swift_code FROM `$table_name` t LEFT JOIN joy_payment p on t.site_id=p.affid left JOIN joy_system_user u on t.site_id= u.id where t.id=$id";
		$command = $connection->createCommand($sql);
		$result = $command->queryRow();
		var_dump($result);die();
		if(empty($result['invoice_date'])){
			return null;
		}
		$start_pay_date = JoyInvoice::getLastPayDate($result['affid']);
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
	<p class="headp">Invoice Number:<span class="hspan">'.$result['affid'].'#'.date('Ymd',strtotime(self::getSystemPayDay())).'#'.$id.'</span></p>
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
			<td>'.$result['amount'].'</td>
		</tr>
	</table>

	<p class="headp">Payable To:<span class="hspan"></span></p>
	<p style="font-size: 10px">Please settle this invoice to the following bank account.</p>
	<p class="headp">Payee:<span class="hspan">'.$result['company'].'</span></p>
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
		$pdf_name =  date('Ymdhis') . '.pdf';
		$pdf->Output(PDF_SAVE_PATH . $pdf_name, 'F');
		$result = JoyInvoice::model()->updateByPk($id,array('pdf'=>$pdf_name));

		//============================================================+
		// END OF FILE
		//============================================================+
		return $result;
	}
}