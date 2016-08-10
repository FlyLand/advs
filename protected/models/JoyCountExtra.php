<?php

/**
 * This is the model class for table "joy_count_extra".
 *
 * The followings are the available columns in table 'joy_count_extra':
 * @property integer $id
 * @property double $extra
 * @property string $remark
 * @property integer $invoice_id
 * @property integer $site_id
 * @property string $count_date
 */
class JoyCountExtra extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return JoyCountExtra the static model class
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
		return 'joy_count_extra';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('invoice_id, site_id', 'numerical', 'integerOnly'=>true),
			array('extra', 'numerical'),
			array('remark', 'length', 'max'=>255),
			array('count_date', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, extra, remark, invoice_id, site_id, count_date', 'safe', 'on'=>'search'),
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
			'extra' => 'Extra',
			'remark' => 'Remark',
			'invoice_id' => 'Invoice',
			'site_id' => 'Site',
			'count_date' => 'Count Date',
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
		$criteria->compare('extra',$this->extra);
		$criteria->compare('remark',$this->remark,true);
		$criteria->compare('invoice_id',$this->invoice_id);
		$criteria->compare('site_id',$this->site_id);
		$criteria->compare('count_date',$this->count_date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public static function getExtraSum($siteid,$sdate,$edate){
		$db = Yii::app()->db;
		$extra_sql = "select sum(extra) as extra from joy_count_extra WHERE count_date<= '$edate' AND count_date >= '$sdate' AND site_id = $siteid";
		$extra_model = $db->createCommand($extra_sql);
		$result = $extra_model->queryRow();
		return $result['extra'];
	}
}
