<?php

/**
 * This is the model class for table "joy_offer_count".
 *
 * The followings are the available columns in table 'joy_offer_count':
 * @property integer $id
 * @property integer $offerid
 * @property integer $affid
 * @property integer $conversion
 * @property double $revenue
 * @property double $payout
 * @property integer $click_count
 * @property string $time
 */
class JoyOfferCount extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return JoyOfferCount the static model class
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
		return 'joy_offer_count';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('offerid, affid, conversion, click_count', 'numerical', 'integerOnly'=>true),
			array('revenue, payout', 'numerical'),
			array('time', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, offerid, affid, conversion, revenue, payout, click_count, time', 'safe', 'on'=>'search'),
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
			'offerid' => 'Offerid',
			'affid' => 'Affid',
			'conversion' => 'Conversion',
			'revenue' => 'Revenue',
			'payout' => 'Payout',
			'click_count' => 'Click Count',
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
		$criteria->compare('conversion',$this->conversion);
		$criteria->compare('revenue',$this->revenue);
		$criteria->compare('payout',$this->payout);
		$criteria->compare('click_count',$this->click_count);
		$criteria->compare('time',$this->time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}