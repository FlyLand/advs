<?php

/**
 * This is the model class for table "joy_jump_task".
 *
 * The followings are the available columns in table 'joy_jump_task':
 * @property integer $id
 * @property integer $auditor
 * @property string $content
 * @property string $createtime
 * @property integer $affid
 * @property string $now_url
 * @property string $back_url
 * @property integer $jump_status
 * @property string $audit_date
 * @property integer $applicant_id
 * @property integer $task_type
 */
class JoyJumpTask extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return JoyJumpTask the static model class
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
		return 'joy_jump_task';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('auditor, affid, jump_status, applicant_id, task_type', 'numerical', 'integerOnly'=>true),
			array('now_url, back_url', 'length', 'max'=>255),
			array('content, createtime, audit_date', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, auditor, content, createtime, affid, now_url, back_url, jump_status, audit_date, applicant_id, task_type', 'safe', 'on'=>'search'),
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
			'audit'=>array(self::BELONGS_TO,'JoySystemUser','auditor'),
			'applicant'=>array(self::BELONGS_TO,'JoySystemUser','applicant_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'auditor' => 'Auditor',
			'content' => 'Content',
			'createtime' => 'Createtime',
			'affid' => 'Affid',
			'now_url' => 'Now Url',
			'back_url' => 'Back Url',
			'jump_status' => 'Jump Status',
			'audit_date' => 'Audit Date',
			'applicant_id' => 'Applicant',
			'task_type' => 'Task Type',
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
		$criteria->compare('auditor',$this->auditor);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('createtime',$this->createtime,true);
		$criteria->compare('affid',$this->affid);
		$criteria->compare('now_url',$this->now_url,true);
		$criteria->compare('back_url',$this->back_url,true);
		$criteria->compare('jump_status',$this->jump_status);
		$criteria->compare('audit_date',$this->audit_date,true);
		$criteria->compare('applicant_id',$this->applicant_id);
		$criteria->compare('task_type',$this->task_type);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}