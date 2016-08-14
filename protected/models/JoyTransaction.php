<?php

/**
 * This is the model class for table "joy_transaction".
 *
 * The followings are the available columns in table 'joy_transaction':
 * @property integer $id
 * @property integer $offerid
 * @property string $original_offerid
 * @property integer $advid
 * @property string $original_affid
 * @property integer $affid
 * @property string $transactionid
 * @property string $aff_subid
 * @property string $campaign_id
 * @property integer $type
 * @property integer $ref_offerid
 * @property string $ip
 * @property string $offer_url
 * @property string $country
 * @property string $createtime
 * @property string $createtime2
 * @property string $checkAction
 */
class JoyTransaction extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return JoyTransaction the static model class
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
		return 'joy_transaction';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('transactionid', 'required'),
			array('offerid, advid, affid, type, ref_offerid', 'numerical', 'integerOnly'=>true),
			array('original_offerid, original_affid, aff_subid', 'length', 'max'=>100),
			array('transactionid, campaign_id, country', 'length', 'max'=>255),
			array('ip', 'length', 'max'=>100),
			array('createtime, createtime2', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, offerid, original_offerid, advid, original_affid, affid, transactionid, aff_subid, campaign_id, type, ref_offerid, ip, country, createtime, createtime2', 'safe', 'on'=>'search'),
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
			'original_offerid' => 'Original Offerid',
			'advid' => 'Advid',
			'original_affid' => 'Original Affid',
			'affid' => 'Affid',
			'transactionid' => 'Transactionid',
			'aff_subid' => 'Aff Subid',
			'campaign_id' => 'Campaign',
			'type' => 'Type',
			'ref_offerid' => 'Ref Offerid',
			'ip' => 'Ip',
			'country' => 'Country',
			'createtime' => 'Createtime',
			'createtime2' => 'Createtime2',
			'offer_url' => 'Offer Url',
            'checkAction' => 'Check Action'
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
		$criteria->compare('original_offerid',$this->original_offerid,true);
		$criteria->compare('advid',$this->advid);
		$criteria->compare('original_affid',$this->original_affid,true);
		$criteria->compare('affid',$this->affid);
		$criteria->compare('transactionid',$this->transactionid,true);
		$criteria->compare('aff_subid',$this->aff_subid,true);
		$criteria->compare('campaign_id',$this->campaign_id,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('ref_offerid',$this->ref_offerid);
		$criteria->compare('ip',$this->ip,true);
		$criteria->compare('country',$this->country,true);
		$criteria->compare('createtime',$this->createtime,true);
		$criteria->compare('createtime2',$this->createtime2,true);
		$criteria->compare('offer_url',$this->offer_url,true);
        $criteria->compare('checkAction',$this->checkAction,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}