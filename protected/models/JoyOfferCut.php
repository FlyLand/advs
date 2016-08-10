<?php

/**
 * This is the model class for table "joy_offer_cut".
 *
 * The followings are the available columns in table 'joy_offer_cut':
 * @property integer $id
 * @property string $offer_id
 * @property integer $aff_id
 * @property integer $advid
 * @property double $cut_num
 * @property double $payout
 * @property integer $isshow
 */
class JoyOfferCut extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return JoyOfferCut the static model class
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
		return 'joy_offer_cut';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('aff_id', 'numerical', 'integerOnly'=>true),
			array('cut_num', 'numerical'),
			array('offer_id', 'length', 'max'=>11),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, offer_id, aff_id, cut_num', 'safe', 'on'=>'search'),
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
			'aff'=>array(self::BELONGS_TO,'JoySystemUser','aff_id')
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
			'aff_id' => 'Aff',
			'cut_num' => 'Cut Num',
			'payout' => 'Payout',
			'isshow'=>'Isshow',
				'advid'=>'Advid'
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
		$criteria->compare('offer_id',$this->offer_id,true);
		$criteria->compare('aff_id',$this->aff_id);
		$criteria->compare('cut_num',$this->cut_num);
		$criteria->compare('payout',$this->payout);
		$criteria->compare('isshow',$this->isshow);
		$criteria->compare('advid',$this->advid);


		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public static function getOfferShow($userid){
		$offers = JoyOfferCut::model()->findAllByAttributes(array('isshow'=>1,'aff_id'=>$userid));
		$offer_ids_str = '';
		if(!empty($offers)){
			$offer_ids = array_column($offers, 'offer_id');
			$offer_ids_str = implode(',',$offer_ids);
		}
		return $offer_ids_str;
	}
}
