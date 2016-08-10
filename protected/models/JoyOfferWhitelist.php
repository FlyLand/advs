<?php

/**
 * This is the model class for table "joy_offer_whitelist".
 *
 * The followings are the available columns in table 'joy_offer_whitelist':
 * @property integer $id
 * @property integer $offerid
 * @property string $ip
 * @property integer $status
 * @property datetime $createtime
 */
class JoyOfferWhitelist extends CActiveRecord
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
		return 'joy_offer_whitelist';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('offerid', 'required'),
			array('offerid, content_type, status', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, offerid, content_type, content, status, createtime', 'safe', 'on'=>'search'),
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
			'id' => 'id',
			'offerid' => 'offerid',
			'content_type' => 'content_type',
			'content' => 'content',
			'status' => 'status',
			'createtime' => 'createtime'
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
		$criteria->compare('content_type',$this->content_type);
		$criteria->compare('content',$this->content);
		$criteria->compare('status',$this->status);
		$criteria->compare('createtime',$this->createtime);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}