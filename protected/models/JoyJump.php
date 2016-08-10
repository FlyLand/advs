<?php

/**
 * This is the model class for table "joy_jump".
 *
 * The followings are the available columns in table 'joy_jump':
 * @property integer $id
 * @property integer $offerid
 * @property string $affid
 * @property integer $country_status
 * @property integer $type
 * @property string $countries
 * @property string $offer_url
 * @property integer $status
 * @property string $user_id
 * @property string $time
 */
class JoyJump extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return JoyJump the static model class
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
		return 'joy_jump';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('offerid, country_status, type, status', 'numerical', 'integerOnly'=>true),
			array('countries, user_id,affid', 'length', 'max'=>255),
			array('offer_url, time', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, offerid, affid, country_status, type, countries, offer_url, status, user_id, time', 'safe', 'on'=>'search'),
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
				'users'=>array(self::BELONGS_TO,'JoySystemUser','user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'offerid' => 'Offerid',
			'affid' => 'Affid',
			'country_status' => 'Country Status',
			'type' => 'Type',
			'countries' => 'Countries',
			'offer_url' => 'Offer Url',
			'status' => 'Status',
			'user_id' => 'User',
			'time' => 'Time',
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
		$criteria->compare('offerid',$this->offerid);
		$criteria->compare('affid',$this->affid);
		$criteria->compare('country_status',$this->country_status);
		$criteria->compare('type',$this->type);
		$criteria->compare('countries',$this->countries,true);
		$criteria->compare('offer_url',$this->offer_url,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('time',$this->time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}