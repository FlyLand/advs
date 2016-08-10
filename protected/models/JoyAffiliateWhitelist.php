<?php

/**
 * This is the model class for table "joy_affiliate_whitelist".
 *
 * The followings are the available columns in table 'joy_affiliate_whitelist':
 * @property integer $id
 * @property string $context
 * @property string $token
 * @property integer $affiliate_id
 * @property integer $context_type
 * @property integer $status
 * @property string $create_time
 * @property string $last_login_time
 */
class JoyAffiliateWhitelist extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return JoyAffiliateWhitelist the static model class
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
		return 'joy_affiliate_whitelist';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('context, token, affiliate_id', 'required'),
			array('affiliate_id, context_type, status', 'numerical', 'integerOnly'=>true),
			array('context, token', 'length', 'max'=>200),
			array('create_time, last_login_time', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, context, token, affiliate_id, context_type, status, create_time, last_login_time', 'safe', 'on'=>'search'),
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
			'affiliate'=>array(self::BELONGS_TO,'JoySystemUser','affiliate_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'context' => 'Context',
			'token' => 'Token',
			'affiliate_id' => 'Affiliate',
			'context_type' => 'Context Type',
			'status' => 'Status',
			'create_time' => 'Create Time',
			'last_login_time' => 'Last Login Time',
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
		$criteria->compare('context',$this->context,true);
		$criteria->compare('token',$this->token,true);
		$criteria->compare('affiliate_id',$this->affiliate_id);
		$criteria->compare('context_type',$this->context_type);
		$criteria->compare('status',$this->status);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('last_login_time',$this->last_login_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}