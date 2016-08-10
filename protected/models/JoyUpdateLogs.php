<?php

/**
 * This is the model class for table "joy_update_logs".
 *
 * The followings are the available columns in table 'joy_update_logs':
 * @property integer $id
 * @property integer $offer_id
 * @property double $payout
 * @property string $offer_url
 * @property string $type
 * @property string $platform
 * @property string $geo_targeting
 * @property string $description
 * @property double $revenue
 * @property string $name
 * @property string $time
 */
class JoyUpdateLogs extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return JoyUpdateLogs the static model class
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
		return 'joy_update_logs';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('offer_id', 'numerical', 'integerOnly'=>true),
			array('payout, revenue', 'numerical'),
			array('offer_url', 'length', 'max'=>200),
			array('type, platform, description', 'length', 'max'=>255),
			array('geo_targeting', 'length', 'max'=>800),
			array('time', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, offer_id, payout, offer_url, type, platform, geo_targeting, description, revenue, time', 'safe', 'on'=>'search'),
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
			'offer_id' => 'Offer',
			'payout' => 'Payout',
			'offer_url' => 'Offer Url',
			'type' => 'Type',
			'platform' => 'Platform',
			'geo_targeting' => 'Geo Targeting',
			'description' => 'Description',
			'revenue' => 'Revenue',
			'name' => 'Name',
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
		$criteria->compare('offer_id',$this->offer_id);
		$criteria->compare('payout',$this->payout);
		$criteria->compare('offer_url',$this->offer_url,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('platform',$this->platform,true);
		$criteria->compare('geo_targeting',$this->geo_targeting,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('revenue',$this->revenue);
		$criteria->compare('revenue',$this->name);
		$criteria->compare('time',$this->time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}