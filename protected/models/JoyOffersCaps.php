<?php

/**
 * This is the model class for table "joy_offers_caps".
 *
 * The followings are the available columns in table 'joy_offers_caps':
 * @property integer $id
 * @property integer $offer_id
 * @property integer $daily_con
 * @property integer $month_con
 * @property double $daily_pay
 * @property double $month_pay
 * @property double $daily_rev
 * @property double $month_rev
 */
class JoyOffersCaps extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return JoyOffersCaps the static model class
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
		return 'joy_offers_caps';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('offer_id', 'required'),
			array('offer_id, daily_con, month_con', 'numerical', 'integerOnly'=>true),
			array('daily_pay, month_pay, daily_rev, month_rev', 'numerical'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, offer_id, daily_con, month_con, daily_pay, month_pay, daily_rev, month_rev', 'safe', 'on'=>'search'),
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
			'daily_con' => 'Daily Con',
			'month_con' => 'Month Con',
			'daily_pay' => 'Daily Pay',
			'month_pay' => 'Month Pay',
			'daily_rev' => 'Daily Rev',
			'month_rev' => 'Month Rev',
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
		$criteria->compare('daily_con',$this->daily_con);
		$criteria->compare('month_con',$this->month_con);
		$criteria->compare('daily_pay',$this->daily_pay);
		$criteria->compare('month_pay',$this->month_pay);
		$criteria->compare('daily_rev',$this->daily_rev);
		$criteria->compare('month_rev',$this->month_rev);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}