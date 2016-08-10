<?php

/**
 * This is the model class for table "joy_count_record".
 *
 * The followings are the available columns in table 'joy_count_record':
 * @property integer $id
 * @property double $fee
 * @property integer $site_id
 * @property string $createtime
 * @property double $amount_paid
 * @property double $amount
 * @property double $extra
 * @property integer $finance_id
 * @property string $count_date
 * @property integer $total_id
 * @property string $swift_number
 * @property string $pay_date
 * @property string $bank_name
 */
class JoyCountRecord extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return JoyCountRecord the static model class
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
		return 'joy_count_record';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('site_id, finance_id, total_id', 'numerical', 'integerOnly'=>true),
			array('fee, amount_paid, amount, extra', 'numerical'),
			array('swift_number, bank_name', 'length', 'max'=>200),
			array('createtime, count_date, pay_date', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, fee, site_id, createtime, amount_paid, amount, extra, finance_id, count_date, total_id, swift_number, pay_date, bank_name', 'safe', 'on'=>'search'),
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
			'fee' => 'Fee',
			'site_id' => 'Site',
			'createtime' => 'Createtime',
			'amount_paid' => 'Amount Paid',
			'amount' => 'Amount',
			'extra' => 'Extra',
			'finance_id' => 'Finance',
			'count_date' => 'Count Date',
			'total_id' => 'Total',
			'swift_number' => 'Swift Number',
			'pay_date' => 'Pay Date',
			'bank_name' => 'Bank Name',
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
		$criteria->compare('fee',$this->fee);
		$criteria->compare('site_id',$this->site_id);
		$criteria->compare('createtime',$this->createtime,true);
		$criteria->compare('amount_paid',$this->amount_paid);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('extra',$this->extra);
		$criteria->compare('finance_id',$this->finance_id);
		$criteria->compare('count_date',$this->count_date,true);
		$criteria->compare('total_id',$this->total_id);
		$criteria->compare('swift_number',$this->swift_number,true);
		$criteria->compare('pay_date',$this->pay_date,true);
		$criteria->compare('bank_name',$this->bank_name,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	public static function getRecord($count_date,$groupid,$userid){
		$condition = '';
		$db = Yii::app()->db;
		if($groupid == BUSINESS_GROUP_ID){
			$sites = JoySystemUser::getResults('id'," manager_userid = $userid");
			if(!empty($sites)){
				$ids = implode(',',array_column($sites,'id'));
			}else{
				$ids = $sites['id'];
			}
			$condition .= " and t.site_id in ($ids)";
		}
		if($groupid == SITE_GROUP_ID){
			$condition .= " and t.site_id = $userid";
		}
		$sql = "select t.*,u.company,f.company as finance from joy_count_record t
 LEFT JOIN joy_system_user u ON u.id=t.site_id
 LEFT JOIN joy_system_user f on f.id =t.finance_id
 WHERE t.count_date = '$count_date'
$condition";
		$command = $db->createCommand($sql);
		$result = $command->queryAll();
		if(empty($result)){
			return null;
		}
		foreach($result as $key=>$item){
			$total_id = $item['total_id'];
			$total = JoyCountTotal::model()->findByPk($total_id);
			if(empty($total)){
				continue;
			}
			$amount_sql = "select sum(p.amount) as amount from joy_count_pay p WHERE p.count_date >= '{$total['sdate']}' AND p.count_date <= '{$total['edate']}' and site_id={$item['site_id']}";

			$amount_command = $db->createCommand($amount_sql);
			$amount_result = $amount_command->queryRow();
			if(!empty($amount_result)){
				$result[$key]['amount'] = $amount_result['amount'];
			}else{
				$result[$key]['amount'] = 0;
			}
		}
		return $result;
	}
}
