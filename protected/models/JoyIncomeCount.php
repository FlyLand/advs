<?php

/**
 * This is the model class for table "joy_income_count".
 *
 * The followings are the available columns in table 'joy_income_count':
 * @property integer $id
 * @property integer $offerid
 * @property double $revenue
 * @property integer $count
 * @property integer $affid
 * @property string $country
 */
class JoyIncomeCount extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return JoyIncomeCount the static model class
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
		return 'joy_income_count';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('offerid, count, affid', 'numerical', 'integerOnly'=>true),
			array('revenue', 'numerical'),
			array('country', 'length', 'max'=>200),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, offerid, revenue, count, affid, country', 'safe', 'on'=>'search'),
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
			'revenue' => 'Revenue',
			'count' => 'Count',
			'affid' => 'Affid',
			'country' => 'Country',
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
		$criteria->compare('revenue',$this->revenue);
		$criteria->compare('count',$this->count);
		$criteria->compare('affid',$this->affid);
		$criteria->compare('country',$this->country,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}