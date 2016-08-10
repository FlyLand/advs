<?php

/**
 * This is the model class for table "joy_system_user".
 *
 * The followings are the available columns in table 'joy_system_user':
 * @property integer $id
 * @property string $email
 * @property string $password
 * @property integer $groupid
 * @property string $first_name
 * @property string $last_name
 * @property string $title
 * @property string $company
 * @property string $address
 * @property string $address2
 * @property string $city
 * @property string $region
 * @property string $country
 * @property string $zipcode
 * @property string $phone
 * @property integer $manager_userid
 * @property integer $status
 * @property integer $logincount
 * @property string $lastmodify
 * @property string $lastlogin
 * @property string $loginip
 * @property string $createtime
 * @property string $postback
 * @property string $verify
 * @property int $cutcount
 */
class JoySystemUser extends CActiveRecord
{
	const table_name = 'joy_system_user';
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return JoySystemUser the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public  function tableName()
	{
		return 'joy_system_user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('password', 'required'),
			array('groupid, manager_userid, status, logincount', 'numerical', 'integerOnly'=>true),
			array('email, city, region, country', 'length', 'max'=>100),
			array('password', 'length', 'max'=>40),
			array('first_name, last_name, title, zipcode, phone', 'length', 'max'=>50),
			array('company, address, address2', 'length', 'max'=>255),
			array('loginip', 'length', 'max'=>20),
			array('lastmodify, lastlogin, createtime', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, email, password, groupid, first_name, last_name, title, company, address, address2, city, region, country, zipcode, phone, manager_userid, status, logincount, lastmodify, lastlogin, loginip, createtime', 'safe', 'on'=>'search'),
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
			'groupid' => 'Groupid',
			'first_name' => 'First Name',
			'last_name' => 'Last Name',
			'title' => 'Title',
			'company' => 'Company',
			'address' => 'Address',
			'address2' => 'Address2',
			'city' => 'City',
			'region' => 'Region',
			'country' => 'Country',
			'zipcode' => 'Zipcode',
			'phone' => 'Phone',
			'manager_userid' => 'Manager Userid',
			'status' => 'Status',
			'logincount' => 'Logincount',
			'lastmodify' => 'Lastmodify',
			'lastlogin' => 'Lastlogin',
			'loginip' => 'Loginip',
			'createtime' => 'Createtime',
			'postback' => 'Postback',
			'verify' => 'Verify',
			'cutcount' => 'Cutcount',
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
		$criteria->compare('groupid',$this->groupid);
		$criteria->compare('first_name',$this->first_name,true);
		$criteria->compare('last_name',$this->last_name,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('company',$this->company,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('address2',$this->address2,true);
		$criteria->compare('city',$this->city,true);
		$criteria->compare('region',$this->region,true);
		$criteria->compare('country',$this->country,true);
		$criteria->compare('zipcode',$this->zipcode,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('manager_userid',$this->manager_userid);
		$criteria->compare('status',$this->status);
		$criteria->compare('logincount',$this->logincount);
		$criteria->compare('lastmodify',$this->lastmodify,true);
		$criteria->compare('lastlogin',$this->lastlogin,true);
		$criteria->compare('loginip',$this->loginip,true);
		$criteria->compare('createtime',$this->createtime,true);
		$criteria->compare('postback',$this->postback,true);
		$criteria->compare('cutcount',$this->cutcount,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function create_advertiser($groupid,$params=null){
		$joyAdver = $this->setSystemUser($groupid,$params);
		if($joyAdver->insert()){
			return true;
		}
		return false;
	}

	public function update_advertiser($id){
		$joySystem = JoySystemUser::findByPk($id);
		$joySystem->company = isset($_POST['company']) ? trim($_POST['company']) : '';
		$joySystem->address = isset($_POST['address1']) ? trim($_POST['address1']) : '';
		$joySystem->address2 = isset($_POST['address2']) ? trim($_POST['address2']) : '';
		$joySystem->city = isset($_POST['city']) ? trim($_POST['city']) : '';
		$joySystem->status = isset($_POST['status']) ? trim($_POST['status']) : '1';
		$joySystem->zipcode = isset($_POST['zipcode']) ? trim($_POST['zipcode']) : '';
		$joySystem->phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
		$joySystem->first_name = isset($_POST['first_name']) ? trim($_POST['first_name']) : '';
		$joySystem->last_name = isset($_POST['last_name']) ? trim($_POST['last_name']) : '';
		$joySystem->title = isset($_POST['title']) ? trim($_POST['title']) : '';
		$joySystem->manager_userid = isset($_POST['account_manager_id']) ? trim($_POST['account_manager_id']) : '';
		$joySystem->region = '';
		$joySystem->email = isset($_POST['email']) ? trim($_POST['email']) : '';
		$joySystem->country = isset($_POST['country']) ? trim($_POST['country']) : '';
		$joySystem->postback = isset($_POST['back_code']) ? trim($_POST['back_code']) : '';
		if(isset($_POST['password'])){
			$joySystem->password = md5($_POST['password']);
		}
		if($joySystem->update()){
			return true;
		}
		return false;
	}

	public static function getParams(){
		$params = array();
		$company = Yii::app()->request->getParam('company');
		if(!empty($company)){
			$params['company'] = $company;
		}
		$address = Yii::app()->request->getParam('address1');
		if(!empty($address)){
			$params['address'] = $address;
		}
		$address2 = Yii::app()->request->getParam('address2');
		if(!empty($address2)){
			$params['address'] = $address2;
		}
		$city = Yii::app()->request->getParam('city');
		if(!empty($city)){
			$params['city'] = $city;
		}
		$status = Yii::app()->request->getParam('status');
		if(!empty($status)){
			$params['status'] = $status;
		}
		$zipcode = Yii::app()->request->getParam('zipcode');
		if(!empty($zipcode)){
			$params['zipcode'] = $zipcode;
		}
		$phone = Yii::app()->request->getParam('phone');
		if(!empty($phone)){
			$params['phone'] = $phone;
		}
		$postback = Yii::app()->request->getParam('postback');
		if(!empty($postback)){
			$params['postback'] = $postback;
		}else{
			$params['postback']='';
		}
		$first_name = Yii::app()->request->getParam('first_name');
		if(!empty($first_name)){
			$params['first_name'] = $first_name;
		}
		$last_name = Yii::app()->request->getParam('last_name');
		if(!empty($last_name)){
			$params['last_name'] = $last_name;
		}
		$title = Yii::app()->request->getParam('title');
		if(!empty($title)){
			$params['title'] = $title;
		}
		$manager_userid = Yii::app()->request->getParam('account_manager_id');
		if(!empty($manager_userid)){
			$params['manager_userid'] = $manager_userid;
		}
		$email = Yii::app()->request->getParam('email');
		if(!empty($email)){
			$params['email'] = $email;
		}
		$country = Yii::app()->request->getParam('country');
		if(!empty($country)){
			$params['country'] = $country;
		}
		$back_code = Yii::app()->request->getParam('back_code');
		if(!empty($back_code)){
			$params['back_code'] = $back_code;
		}
		$password = Yii::app()->request->getParam('password');
		if(!empty($password)){
			$params['password'] = md5($password);
		}
		return $params;
	}

	public function setSystemUser($groupid,$params){
		$joySystem = new JoySystemUser();
		$joySystem->id = null;
		$joySystem->company = isset($_POST['company']) ? trim($_POST['company']) : '';
		$joySystem->address = isset($_POST['address1']) ? trim($_POST['address1']) : '';
		$joySystem->address2 = isset($_POST['address2']) ? trim($_POST['address2']) : '';
		$joySystem->city = isset($_POST['city']) ? trim($_POST['city']) : '';
		$joySystem->status = isset($_POST['status']) ? trim($_POST['status']) : '1';
		$joySystem->zipcode = isset($_POST['zipcode']) ? trim($_POST['zipcode']) : '';
		$joySystem->phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
		$joySystem->first_name = isset($_POST['first_name']) ? trim($_POST['first_name']) : '';
		$joySystem->last_name = isset($_POST['last_name']) ? trim($_POST['last_name']) : '';
		$joySystem->title = isset($_POST['title']) ? trim($_POST['title']) : '';
		$joySystem->manager_userid = isset($_POST['account_manager_id']) ? trim($_POST['account_manager_id']) : '';
		$joySystem->createtime = date('Y-m-d H:i:s');
		$joySystem->region = '';
		$joySystem->password=md5(Yii::app()->request->getParam('password'));
		$joySystem->email = isset($_POST['email']) ? trim($_POST['email']) : '';
		$joySystem->country = isset($_POST['country']) ? trim($_POST['country']) : '';
		$joySystem->groupid = $groupid;
		$joySystem->postback = isset($_POST['back_code']) ? trim($_POST['back_code']) : '';
		$joySystem->verify	=	$params == null || !isset($params['verify_token']) ? '': $params['verify_token'];
		$joySystem->cutcount = 20; //Ĭ�Ͽ���20
		return $joySystem;
	}

	public static  function getResults($fileds,$condition,$limit = null){
		$db = Yii::app()->db;
		$table_name = self::table_name;
		$sql = "select $fileds from $table_name where 1=1 and $condition";
		if($limit){
			$sql .= "limit $limit";
		}
		$command = $db->createCommand($sql);
		$result = $command->queryAll();
		return $result;
	}

	public static  function getResult($fileds,$condition,$limit = null){
		$db = Yii::app()->db;
		$table_name = self::table_name;
		$sql = "select $fileds from $table_name where 1=1 and $condition";
		if($limit){
			$sql .= "limit $limit";
		}
		$command = $db->createCommand($sql);
		$result = $command->queryAll();
		return $result;
	}

	//获取关联账户的信息
	public  function getRelevance($groupid,$userid){
		if($groupid == SITE_GROUP_ID){
			$result = JoySites::getResult('affids'," and site_id=$userid");
		}else{
			$result = null;
		}
		return $result;
	}

	public  static function getBusinessRel($userid){
		return JoySystemUser::model()->findByAttributes(array('manager_userid'=>$userid));
	}

	//查询是否已创建相关用户
	public static function userExist($email){
		if(empty(JoySystemUser::model()->findByAttributes(array('email'=>$email))))
			return false;
		else
			return true;
	}

	public static function getSystemId($typeid = 0){
		$filed = 'system_uid';
		if($typeid == 1){
			$filed = 'fetch_uid';
		}
		$db = Yii::app()->db;
		$command = $db->createCommand("select $filed from joy_user_control");
		$result = $command->queryRow();
		return $result[$filed];
	}

	public static function incSystemId($last_id,$typeid = 0){
		$filed = 'system_uid';
		if($typeid == 1){
			$filed = 'fetch_uid';
		}
		$db = Yii::app()->db;
		$sql = "update joy_user_control set $filed = " . ($last_id + 1);
		$command = $db->createCommand($sql);
		return $command->query();
	}

	public static function createUser($groupid,$params,$typeid = 0){
		if(self::userExist($params['email'])){
			return array('msg'=>'The Email Is Exist Already','result'=>0);
		}
		$userModel = new JoySystemUser();
		$params['id'] = self::getSystemId($typeid);
		try {
			foreach ($params as $key => $value) {
				$userModel->$key = $value;
			}
			$userModel->groupid = $groupid;
			$userModel->createtime = date('Y-m-d H:i:s');
			if ($userModel->save()) {
				$ret['result'] = Yii::app()->db->getLastInsertID();
				self::incSystemId($ret['result'],$typeid);
				if($groupid == SITE_GROUP_ID){
					$sites = JoySites::model()->findByAttributes(array('site_id'=>$ret['result']));
					if(!$sites){
						$sites = new JoySites();
						$sites->site_id = $ret['result'];
					}
					$sites->save();
				}
				$ret['msg'] = 'Success';
			}else{
				$ret['msg'] = 'Failed,please try again';
				$ret['result'] = 0;
			}
		}catch (Exception $e){
			$ret['msg'] = $e->getMessage();
			$ret['result'] = 0;
		}
		return $ret;
	}

	public static function updateUserInfo($params,$condition){
		if(empty($condition)){
			return false;
		}
		$db = Yii::app()->db;
		$sql = 'update joy_system_user set ';
		foreach($params as $param=>$value){
			if(empty($value)){
				continue;
			}
			$sql .="$param='$value',";
		}
		$sql = substr($sql,0,strlen($sql)-1);
		$sql .= " where $condition";
		$command =  $db->createCommand($sql);
		return $command->query();
	}

        public static function updateUserInfo2($params,$condition){
		if(empty($condition)){
			return false;
		}
		$db = Yii::app()->db;
		$sql = 'update joy_system_user set ';
		foreach($params as $param=>$value){

			$sql .="$param='$value',";
		}
		$sql = substr($sql,0,strlen($sql)-1);
		$sql .= " where $condition";
		$command =  $db->createCommand($sql);
		return $command->query();
	}

	public function isBelong($groupid,$userid,$id){
		$db = Yii::app()->db;
		$sql = '';
		switch($groupid){
			case BUSINESS_GROUP_ID:
				$sql = "select id from joy_system_user where id=$id and manager_userid=$userid";
				break;
			case SITE_GROUP_ID:
				$sql = "select id from joy_sites where find_in_set($id,affids) and site_id=$userid";
				break;
		}
		$command  = $db->createCommand($sql);
		$result = $command->queryAll();
		if($result){
			return true;
		}else{
			return false;
		}
	}
}
