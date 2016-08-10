<?php

/**
 * This is the model class for table "offer_count_self".
 *
 * The followings are the available columns in table 'offer_count_self':
 * @property integer $id
 * @property integer $offerid
 * @property string $affid
 * @property integer $conversion
 * @property double $revenue
 * @property double $payout
 * @property double $CPM
 * @property integer $click_count
 * @property string $time
 * @property double $CTR
 * @property string $project_name
 */
class OfferCountSelf extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return OfferCountSelf the static model class
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
		return 'offer_count_self';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('offerid, conversion, click_count', 'numerical', 'integerOnly'=>true),
			array('revenue, payout, CPM, CTR', 'numerical'),
			array('affid', 'length', 'max'=>200),
			array('project_name', 'length', 'max'=>100),
			array('time', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, offerid, affid, conversion, revenue, payout, CPM, click_count, time, CTR, project_name', 'safe', 'on'=>'search'),
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
			'CPM' => 'Cpm',
			'click_count' => 'Click Count',
			'time' => 'Time',
			'CTR' => 'Ctr',
			'project_name' => 'Project Name',
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
		$criteria->compare('affid',$this->affid,true);
		$criteria->compare('conversion',$this->conversion);
		$criteria->compare('revenue',$this->revenue);
		$criteria->compare('payout',$this->payout);
		$criteria->compare('CPM',$this->CPM);
		$criteria->compare('click_count',$this->click_count);
		$criteria->compare('time',$this->time,true);
		$criteria->compare('CTR',$this->CTR);
		$criteria->compare('project_name',$this->project_name,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

		public static function getAllAffiliate(){
			$select_sql = "select affid from offer_count_self group by affid";
			$db = Yii::app()->db;
			$conn = $db->createCommand($select_sql);
			return $conn->queryAll();
	}
}