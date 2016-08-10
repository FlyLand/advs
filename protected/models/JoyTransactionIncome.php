<?php

/**
 * This is the model class for table "joy_transaction_income".
 *
 * The followings are the available columns in table 'joy_transaction_income':
 * @property integer $id
 * @property integer $offerid
 * @property integer $advid
 * @property integer $affid
 * @property string $transactionid
 * @property double $revenue
 * @property double $payout
 * @property string $serverip
 * @property string $clientip
 * @property string $transactiontime
 * @property string $transactiontime2
 * @property integer $cut_num
 * @property integer $ispostbacked
 * @property string $postback
 * @property string $createtime
 * @property string $createtime2
 * @property string $country
 * @property string $carrier
 * @property double $am
 * @property string $platform
 * @property integer $kimia_id
 * @property string $error
 * @property  int $belong
 */
class JoyTransactionIncome extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return JoyTransactionIncome the static model class
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
		return 'joy_transaction_income';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('offerid, advid, affid, cut_num, ispostbacked, kimia_id', 'numerical', 'integerOnly'=>true),
			array('revenue, payout', 'numerical'),
			array('transactionid', 'length', 'max'=>255),
			array('serverip, clientip', 'length', 'max'=>50),
			array('postback', 'length', 'max'=>300),
			array('country, carrier, platform', 'length', 'max'=>255),
			array('transactiontime, transactiontime2, createtime, createtime2', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, offerid, advid, affid, transactionid, revenue, payout, serverip, clientip, transactiontime, transactiontime2, cut_num, ispostbacked, postback, createtime, createtime2, country, carrier,  platform, kimia_id', 'safe', 'on'=>'search'),
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
			'advertiser'=>array(self::BELONGS_TO,'JoySystemUser','', 'on' => 't.advid=advertiser.id'),
			'affiliate'=>array(self::BELONGS_TO,'JoySystemUser','', 'on' => 't.affid=affiliate.id'),
			'offer'=>array(self::BELONGS_TO, 'joy_offers', '', 'on' => 't.offerid=offer.id'),
			'cups'=>array(self::BELONGS_TO,'JoyOffersCaps','','on'=>'t.offerid=cups.offer_id')
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
			'advid' => 'Advid',
			'affid' => 'Affid',
			'transactionid' => 'Transactionid',
			'revenue' => 'Revenue',
			'payout' => 'Payout',
			'serverip' => 'Serverip',
			'clientip' => 'Clientip',
			'transactiontime' => 'Transactiontime',
			'transactiontime2' => 'Transactiontime2',
			'cut_num' => 'Cut Num',
			'ispostbacked' => 'Ispostbacked',
			'postback' => 'Postback',
			'createtime' => 'Createtime',
			'createtime2' => 'Createtime2',
			'country' => 'Country',
			'carrier' => 'Carrier',
			'platform' => 'Platform',
			'kimia_id' => 'Kimia',
				'belong' => 'Belong',
				'error' => 'Error'
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
		$criteria->compare('transactionid',$this->transactionid,true);
		$criteria->compare('revenue',$this->revenue);
		$criteria->compare('payout',$this->payout);
		$criteria->compare('serverip',$this->serverip,true);
		$criteria->compare('clientip',$this->clientip,true);
		$criteria->compare('transactiontime',$this->transactiontime,true);
		$criteria->compare('transactiontime2',$this->transactiontime2,true);
		$criteria->compare('cut_num',$this->cut_num);
		$criteria->compare('ispostbacked',$this->ispostbacked);
		$criteria->compare('postback',$this->postback,true);
		$criteria->compare('createtime',$this->createtime,true);
		$criteria->compare('createtime2',$this->createtime2,true);
		$criteria->compare('country',$this->country,true);
		$criteria->compare('carrier',$this->carrier,true);
		$criteria->compare('platform',$this->platform,true);
		$criteria->compare('kimia_id',$this->kimia_id,true);
		$criteria->compare('error',$this->error,true);
		$criteria->compare('belong',$this->belong,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
