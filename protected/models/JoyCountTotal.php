<?php

/**
 * This is the model class for table "joy_count_total".
 *
 * The followings are the available columns in table 'joy_count_total':
 * @property integer $id
 * @property string $sdate
 * @property string $edate
 * @property string $count_date
 * @property integer $site_id
 * @property integer $status
 * @property string $pdf
 * @property string $pdf_back
 * @property string $createtime
 * @property integer $finance_id
 */
class JoyCountTotal extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return JoyCountTotal the static model class
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
		return 'joy_count_total';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('site_id, status, finance_id', 'numerical', 'integerOnly'=>true),
			array('pdf, pdf_back', 'length', 'max'=>255),
			array('sdate, edate, count_date, createtime', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, sdate, edate, count_date, site_id, status, pdf, pdf_back, createtime, finance_id', 'safe', 'on'=>'search'),
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
			'sdate' => 'Sdate',
			'edate' => 'Edate',
			'count_date' => 'Count Date',
			'site_id' => 'Site',
			'status' => 'Status',
			'pdf' => 'Pdf',
			'pdf_back' => 'Pdf Back',
			'createtime' => 'Createtime',
			'finance_id' => 'Finance',
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
		$criteria->compare('sdate',$this->sdate,true);
		$criteria->compare('edate',$this->edate,true);
		$criteria->compare('count_date',$this->count_date,true);
		$criteria->compare('site_id',$this->site_id);
		$criteria->compare('status',$this->status);
		$criteria->compare('pdf',$this->pdf,true);
		$criteria->compare('pdf_back',$this->pdf_back,true);
		$criteria->compare('createtime',$this->createtime,true);
		$criteria->compare('finance_id',$this->finance_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public static function getTotal($count_date,$groupid,$userid){
		$db = Yii::app()->db;
		$condition = '';
		if($groupid == SITE_GROUP_ID){
			$sql = "select t.*,s.extra from joy_count_total t LEFT JOIN  (SELECT sum(extra) as extra,site_id from joy_count_extra WHERE count_date = '$count_date' GROUP BY site_id)s ON t.site_id = s.site_id WHERE t.site_id = $userid AND t.status != 0  and t.count_date = '$count_date'";
			$command = $db->createCommand($sql);
			$result = $command->queryAll();
			foreach($result as $key=>$item){
				$sql_select = "select sum(amount) as amount,sum(extra) as extra from joy_count_pay WHERE  count_date <= '{$item['edate']}' AND count_date >= '{$item['sdate']}' AND site_id = '{$item['site_id']}' AND status = 1";
				$command_select = $db->createCommand($sql_select);
				$row = $command_select->queryRow();
				$result[$key]['amount'] = $row['amount'];
			}
		}else{
			if($groupid == BUSINESS_GROUP_ID){
				$sites = JoySystemUser::getResults('id'," manager_userid = $userid");
				if(!empty($sites)){
					$ids = implode(',',array_column($sites,'id'));
				}else{
					$ids = $sites['id'];
				}
				$condition .= " and site_id in ($ids)";
			}elseif(FINANCE_GROUP_ID == $groupid){
				$condition .= " and status = 2";
			}
			$sql = "select t.*,e.extra,p.beneficiary,p.bank_name,p.bank_address,p.bank_account,p.swift_code from (select * from joy_count_total  WHERE count_date = '$count_date' $condition)t
LEFT JOIN (select sum(extra) as extra,site_id from joy_count_extra GROUP by site_id)e ON e.site_id=t.site_id
LEFT JOIN joy_payment p ON p.affid = t.site_id";

			$command = $db->createCommand($sql);
			$result = $command->queryAll();
			foreach($result as $key=>$item){
				$sql_juge = "select id from joy_count_pay where status = 1 and count_date = '$count_date'";
				$juge_command = $db->createCommand($sql_juge);
				if(!empty($juge_command->queryRow())){
					$sql_select = "select sum(amount) as amount,sum(amount_paid) as amount_paid from joy_count_pay WHERE (status = 1 or status=2) AND count_date <= '{$item['edate']}' AND count_date >= '{$item['sdate']}' AND site_id = '{$item['site_id']}'";
					$sql_user = JoySystemUser::getResult('company'," id = {$item['site_id']}");
					$command_select = $db->createCommand($sql_select);
					$row = $command_select->queryRow();
				}else{
					$row['amount'] = 0;
				}
				$result[$key]['amount'] = $row['amount'];
				if(!empty($sql_user)){
					$result[$key]['company'] = $sql_user[0]['company'];
				}else{
					$result[$key]['company'] = '';
				}
			}
		}
		return $result;
	}

	public static function setPaymentPaid($siteid,$count_date,$userid,$upload_params = array()){
		$sites = JoySites::getCompanySite($siteid);
		$total = JoyCountTotal::model()->findByAttributes(array('site_id'=>$siteid,'count_date'=>$count_date));
		$timestamp = '';
		$bank_name = '';
		$swift_num = '';
		$amount_paid = '';
		$result = false;
		if(isset($upload_params['timestamp'])){
			$timestamp = $upload_params['timestamp'];
		}
		if(isset($upload_params['bank_name'])){
			$bank_name = $upload_params['bank_name'];
		}
		if(isset($upload_params['swift_num'])){
			$swift_num = $upload_params['swift_num'];
		}
		if(isset($upload_params['amount_paid'])){
			$amount_paid = $upload_params['amount_paid'];
		}
		$total->status = 3;
		if($total->save()){
			$count_pay = JoyCountPay::model()->findByAttributes(array('site_id'=>$siteid,'count_date'=>$count_date));
			$count_pay->status = 3;
			if($count_pay->save()){
				foreach($sites as $site=>$aff_arr){
					foreach($aff_arr as $aff){
						$invoice = JoyInvoice::model()->findByAttributes(array('affid'=>$aff,'count_date'=>$count_date));
						if(empty($invoice)){
							break;
						}
						$invoice->status = 2;
						$invoice->save();
					}
				}
			}
			$record = new JoyCountRecord();
			$record->site_id = $siteid;
			$record->amount_paid = $amount_paid;
			$amount_sql = "select sum(amount) as amount from joy_count_pay WHERE count_date >= '{$total['sdate']}' AND  count_date <= '{$total['edate']}' AND site_id = {$siteid}";
			$command = Yii::app()->db->createCommand($amount_sql);
			$amount_result = $command->queryRow($command);
			$amount = 0;
			if(!empty($amount_result)){
				$amount = $amount_result['amount'];
			}
			$record->amount = $amount;
			$record->count_date = $count_date;
			$record->createtime = date('Y-m-d H:i:s');
			$record->finance_id = $userid;
			$record->pay_date = $timestamp;
			$record->bank_name = $bank_name;
			$record->swift_number = $swift_num;
			$record->total_id = $total['id'];
			$record->extra = JoyCountExtra::getExtraSum($siteid,$total['sdate'],$total['edate']);
			$result = $record->save();
		}
		return $result;
	}
}
