<?php

/**
 * This is the model class for table "joy_payment".
 *
 * The followings are the available columns in table 'joy_payment':
 * @property integer $id
 * @property integer $affid
 * @property integer $type
 * @property string $beneficiary
 * @property string $bank_name
 * @property string $bank_address
 * @property string $bank_account
 * @property string $swift_code
 * @property string $createtime
 * @property string $updatetime
 * @property integer $status
 * @property integer $am_id
 * @property string $pee
 * @property integer $finance_id
 * @property string $email
 * @property string $contract_code
 */
class JoyPayment extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return JoyPayment the static model class
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
		return 'joy_payment';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('affid', 'required'),
			array('id, affid, type', 'numerical', 'integerOnly'=>true),
			array('bank_name, bank_address, bank_account, swift_code', 'length', 'max'=>255),
			array('beneficiary', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, affid, type, beneficiary, bank_name, bank_address, bank_account, swift_code', 'safe', 'on'=>'search'),
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
			'company'=>array(self::BELONGS_TO,'JoySystemUser','affid')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'affid' => 'Affid',
			'type' => 'Type',
			'beneficiary' => 'Beneficiary',
			'bank_name' => 'Bank Name',
			'bank_address' => 'Bank Address',
			'bank_account' => 'Bank Account',
			'swift_code' => 'Swift Code',
			'email'=>'Email',
			'createtime' => 'Createtime',
			'updatetime'=>'Updatetime',
			'status'=>'Status',
			'am_id'=>'Am Id',
			'finance_id' => 'Finance Id',
			'contract_code'=>'Contract Code',
			'pee'=>'Pee'
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
		$criteria->compare('affid',$this->affid);
		$criteria->compare('type',$this->type);
		$criteria->compare('beneficiary',$this->beneficiary,true);
		$criteria->compare('bank_name',$this->bank_name,true);
		$criteria->compare('bank_address',$this->bank_address,true);
		$criteria->compare('bank_account',$this->bank_account,true);
		$criteria->compare('swift_code',$this->swift_code,true);
		$criteria->compare('createtime',$this->createtime,true);
		$criteria->compare('updatetime',$this->updatetime,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('am_id',$this->am_id,true);
		$criteria->compare('finance_id',$this->finance_id,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('contract_code',$this->contract_code,true);
		$criteria->compare('pee',$this->pee,true);
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public static function getPaymentInfo($userid,$groupid){
		if(in_array($groupid,array(ADMIN_GROUP_ID,AM_GROUP_ID,MANAGER_GROUP_ID,FINANCE_GROUP_ID))){
			$payment = JoyPayment::model()->findAll();
		}elseif($groupid == SITE_GROUP_ID){
			$payment = JoyPayment::model()->findByAttributes(array('affid'=>$userid));
		}elseif($groupid == BUSINESS_GROUP_ID){
			$str = 0;
			$userid_ar = JoySystemUser::getResults('id'," manager_userid = $userid");
			if(!empty($userid_ar)){
				$str = implode(',',array_column($userid_ar,'id'));
			}
			$cdb = new CDbCriteria();
			$cdb->addCondition("affid in ($str)");
			$payment = JoyPayment::model()->findAll($cdb);
		}
		return $payment;
	}
}
