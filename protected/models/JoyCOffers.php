<?php

/**
 * This is the model class for table "joy_offer_pixels".
 *
 * The followings are the available columns in table 'joy_offer_pixels':
 * @property integer $id
 * @property integer $offerid
 * @property integer $advid
 * @property integer $affid
 * @property string $type
 * @property string $code
 * @property string $createtime
 */
class JoyCOffers extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return JoyOfferPixels the static model class
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
		return 'joy_c_offers';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('offerid, affid', 'numerical', 'integerOnly'=>true),
			array('nation', 'length', 'max'=>200),
			array('createtime', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, offerid, affid, start_date, end_date, nation, max_total, execute_total, hour_total, status, createtime', 'safe', 'on'=>'search'),
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
			'id'		=>	'ID',
			'offerid'	=>	'Offerid',
			'affid'		=>	'Affid',
			'start_date'	=>	'start date',
			'end_date'	=>	'end date',
			'nation'	=>	'nation',
			'max_total'	=>	'max_total',
			'execute_total'	=>	'execute_total',
			'hour_total'	=>	'hour_total',
			'status'	=>	'status',
			'createtime'	=>	'createtime',
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
		$criteria->compare('start_date',$this->start_date);
		$criteria->compare('end_date',$this->end_date);
		$criteria->compare('nation',$this->nation);
		$criteria->compare('max_total',$this->max_total);
		$criteria->compare('execute_total',$this->execute_total);
		$criteria->compare('hour_total',$this->hour_total);
		$criteria->compare('status',$this->status);
		$criteria->compare('createtime',$this->createtime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}