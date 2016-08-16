<?php

/**
 * This is the model class for table "adv_user".
 *
 * The followings are the available columns in table 'adv_user':
 * @property integer $id
 * @property string $email
 * @property string $password
 * @property string $username
 * @property string $true_name
 * @property integer $groupid
 * @property integer $cutcount
 * @property string $postback
 * @property integer $manager_id
 * @property integer $status
 * @property string $createtime
 * @property integer $site_id
 */
class User extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return User the static model class
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
		return 'adv_user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, email, password, username', 'required'),
			array('id, groupid, cutcount, manager_id, status, site_id', 'numerical', 'integerOnly'=>true),
			array('email, username', 'length', 'max'=>200),
			array('password, postback', 'length', 'max'=>255),
			array('true_name', 'length', 'max'=>120),
			array('createtime', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, email, password, username, true_name, groupid, cutcount, postback, manager_id, status, createtime, site_id', 'safe', 'on'=>'search'),
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
			'email' => 'Email',
			'password' => 'Password',
			'username' => 'Username',
			'true_name' => 'True Name',
			'groupid' => 'Groupid',
			'cutcount' => 'Cutcount',
			'postback' => 'Postback',
			'manager_id' => 'Manager',
			'status' => 'Status',
			'createtime' => 'Createtime',
			'site_id' => 'Site',
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
		$criteria->compare('email',$this->email,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('true_name',$this->true_name,true);
		$criteria->compare('groupid',$this->groupid);
		$criteria->compare('cutcount',$this->cutcount);
		$criteria->compare('postback',$this->postback,true);
		$criteria->compare('manager_id',$this->manager_id);
		$criteria->compare('status',$this->status);
		$criteria->compare('createtime',$this->createtime,true);
		$criteria->compare('site_id',$this->site_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}