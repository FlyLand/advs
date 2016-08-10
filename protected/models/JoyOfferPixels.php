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
class JoyOfferPixels extends CActiveRecord
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
		return 'joy_offer_pixels';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('offerid, advid, affid', 'numerical', 'integerOnly'=>true),
			array('type', 'length', 'max'=>20),
			array('code', 'length', 'max'=>200),
			array('createtime', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, offerid, advid, affid, revenue, payout, daily_con, month_con, daily_pay, month_pay, daily_rev, month_rev, type, code, createtime', 'safe', 'on'=>'search'),
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
			'affiliate'=>array(self::BELONGS_TO,'JoySystemUser','','on' => 't.affid=affiliate.id'),
			'offer' => array(self::BELONGS_TO,'joy_offers','','on' => 't.offerid=offer.id'),
			'advertiser'=>array(self::BELONGS_TO,'JoySystemUser','', 'on' => 't.advid=advertiser.id'),
			'cups'=>array(self::BELONGS_TO,'JoyOffersCaps','','on'=>'t.offerid=cups.offer_id')
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
			'advid'		=>	'Advid',
			'affid'		=>	'Affid',
			'revenue'	=>	'revenue',
			'payout'	=>	'payout',
			'daily_con'	=>	'daily_con',
			'month_con'	=>	'month_con',
			'daily_pay'	=>	'daily_pay',
			'month_pay'	=>	'month_pay',
			'daily_rev'	=>	'daily_rev',
			'month_rev'	=>	'month_rev',
			'type'		=>	'Type',
			'code'		=>	'Code',
			'createtime'=>	'Createtime',
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
		$criteria->compare('advid',$this->advid);
		$criteria->compare('affid',$this->affid);
		$criteria->compare('revenue',$this->revenue);
		$criteria->compare('payout',$this->payout);
		$criteria->compare('daily_con',$this->daily_con);
		$criteria->compare('month_con',$this->month_con);
		$criteria->compare('daily_pay',$this->daily_pay);
		$criteria->compare('month_pay',$this->month_pay);
		$criteria->compare('daily_rev',$this->daily_rev);
		$criteria->compare('month_rev',$this->month_rev);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('createtime',$this->createtime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}