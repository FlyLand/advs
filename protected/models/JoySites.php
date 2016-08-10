<?php

/**
 * This is the model class for table "joy_sites".
 *
 * The followings are the available columns in table 'joy_sites':
 * @property integer $id
 * @property integer $site_id
 * @property string $affids
 */
class JoySites extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return JoySites the static model class
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
		return 'joy_sites';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('site_id', 'required'),
			array('site_id', 'numerical', 'integerOnly'=>true),
			array('affids', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, site_id, affids', 'safe', 'on'=>'search'),
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
			'site_id' => 'Site',
			'affids' => 'Affids',
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
		$criteria->compare('site_id',$this->site_id);
		$criteria->compare('affids',$this->affids,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}


	/**
	 * @param $id integer the system id in joy_site
	 * @param $site_id integer the site id in system
	 * @return boolean
	 */
	public static function deleteAffiliateId($id,$site_id){
		$record = self::getResult('id,affids'," and site_id=$site_id");
		if(!empty($record)){
			$affids_arr = explode(',',$record['affids']);
		}else{
			$affids_arr = '';
		}
		$key = array_search($id,$affids_arr);
		$affids = '';
		unset($affids_arr[$key]);
		if(!empty($affids_arr)) {
			$affids = implode(',', $affids_arr);
		}
		if(JoySites::model()->updateByPk($record['id'],array('affids'=>$affids))){
			return true;
		}else{
			return false;
		}
	}

	public static function getResults($fileds,$condition,$limit = null){
		$db = Yii::app()->db;
		$sql = "select $fileds from joy_sites where 1=1 and $condition";
		if($limit){
			$sql .= "limit $limit";
		}
		$command = $db->createCommand($sql);
		return $command->queryAll($command);
	}
	public static function getResult($fileds,$condition,$limit = null){
		if(empty($condition)){
			$condition = '';
		}
		$db = Yii::app()->db;
		$tableName = 'joy_sites';
		$sql = "select $fileds from $tableName where 1=1  $condition";
		if($limit){
			$sql .= "limit $limit";
		}
		$command = $db->createCommand($sql);
		return $command->queryRow($command);
	}
	public static function getCompanySite($siteid = null){
		if(empty($siteid)){
			$sites = JoySites::model()->findAll();
		}else{
			$sites = JoySites::model()->findAllByAttributes(array('site_id'=>$siteid));
		}
		$arr = array();
		foreach($sites as $site){
			$arr[$site['site_id']] =  explode(',',$site['affids']);
		}
		return $arr;
	}

	/**
	 * return the array with site and aff
	 * if here is no aff in site ,we just do the same thing with the aff as a site
	 * @param array $detail_arr the invoice detail
	 * @return array
	 */
	public static function getCompanyData($detail_arr){
		$sites = self::getCompanySite();
		foreach($sites as $key=>$site){
			$am = $detail_arr[$key];
			$arr[$key]['am'] = array_column($am,'am_name')[0];
			$arr[$key]['amount'] = 0;
			$arr[$key]['fee'] = 0;
			foreach($site as $affid){
				if(!key_exists($affid,$detail_arr)){
					continue;
				}
				$amount_arr = array_column($detail_arr[$affid],'amount');
				foreach($amount_arr as $amount){
					$arr[$key]['amount'] += $amount;
				}
				$fee_arr = array_column($detail_arr[$affid],'fee');
				foreach($fee_arr as $fee){
					$arr[$key]['fee'] += $fee;
				}
				$arr[$key]['data'][$affid] = $detail_arr[$affid];
				unset($detail_arr[$affid]);
			}
		}
		foreach($detail_arr as $key=>$detail){
			$arr[$key]['am'] = $detail_arr[$key];
			$amount_arr = array_column($detail,'amount');
			$fee_arr = array_column($detail,'fee');
			$arr[$key]['am'] = array_column($detail,'am_name')[0];
			$arr[$key]['amount'] = 0;
			$arr[$key]['fee'] = 0;
			foreach($amount_arr as $amount){
				$arr[$key]['amount'] += $amount;
			}
			foreach($fee_arr as $fee){
				$arr[$key]['fee'] += $fee;
			}
			$arr[$key]['data'][$key] = $detail;
		}
		return $arr;
	}

	public static function getSiteIdWithAff($aff,$type = 0){
		$result['site_id'] = -1;
		if($type == 1){
			$site_arr = JoySites::getCompanySite();
			foreach($site_arr as $site_id=>$aff_arr){
				if(in_array($aff,$aff_arr)){
					$result['site_id'] = $site_id;
					break;
				}
			}
		}else{
			$db = Yii::app()->db;
			$sql = "select site_id from joy_sites where find_in_set(affids,'$aff')";
			$command = $db->createCommand($sql);
			$result = $command->queryRow();
			if(empty($result)){
				$result['site_id'] = null;
			}
		}
		return $result['site_id'];
	}


	public static function getSitesInformationWithBusiness($userid){
		$db = Yii::app()->db;
		$ids = JoySystemUser::getResults('id',"manager_userid=$userid");
		if(empty($ids)){
			return null;
		}
		$ids_str = implode(',',array_column($ids,'id'));
		if(empty($ids_str)){
			$ids_str = -1;
		}
		$site_sql = "select * from joy_sites where site_id in ($ids_str)";
		$arr = array();
		$sites_command = $db->createCommand($site_sql);
		$sites = $sites_command->queryAll();
		if(empty($sites)){
			return null;
		}
		foreach($sites as $site){
			$arr[$site['site_id']] =  explode(',',$site['affids']);
		}
		$result = null;
		$db = Yii::app()->db;
		foreach($arr as $site=>$item){
			$sql = "select u.*,am.company as am_name from joy_system_user u LEFT JOIN joy_system_user am ON  u.manager_userid=am.id where u.id=$site";
			$command = $db->createCommand($sql);
			$list = $command->queryRow();
			if(!empty($list)){
				$result[$site] = $list;
				$result[$site]['aff'] = $item;
			}
		}
		return $result;
	}

	/**
	 * @param  integer $site_id
	 * @return  array The site information
	 */
	public static function getSitesInformation($site_id = null){
		if(!empty($site_id)){
			$sites_affiliates_arr = self::getCompanySite($site_id);
		}else{
			$sites_affiliates_arr = self::getCompanySite();
		}
		$result = null;
		$db = Yii::app()->db;
		foreach($sites_affiliates_arr as $site=>$item){
			$sql = "select u.*,am.company as am_name from joy_system_user u LEFT JOIN joy_system_user am ON  u.manager_userid=am.id where u.id=$site";
			$command = $db->createCommand($sql);
			$list = $command->queryRow();
			if(!empty($list)){
				$result[$site] = $list;
				$result[$site]['aff'] = $item;
			}
		}
		return $result;
	}

	/**
	 * get all affiliates and the information by site
	 * @param $site_id
	 * @return  array
	*/
	public static function getAllRel($site_id){
		$result = self::getResult('affids'," and site_id=$site_id");
		if(empty($result)){
			return null;
		}
		return JoySystemUser::getResult('*'," id in({$result['affids']})");
	}

	public static function getSiteList($date){
		$db = Yii::app()->db;
		$sql = "select u.company as aff_name,t.count_date as mon ,t.affid,t.amount,t.invoice_date,m.company as am_name
from joy_invoice t LEFT JOIN joy_system_user u on u.id=t.affid
LEFT JOIN joy_system_user m on t.am_id=m.id
where count_date = '$date'
ORDER BY aff_name";
		$command = $db->createCommand($sql);
		$result = $command->queryAll();
		if(empty($result)){
			return null;
		}
		$detail_arr = array();
		$arr = array();
		foreach($result as $detail){
			$detail_arr[$detail['affid']] = $detail;
		}
		$site_arr = JoySites::getCompanySite();
		foreach($site_arr as $siteid=>$item){
			foreach($item as $key=>$affid){
				if(!isset($detail_arr[$affid])) {
					continue;
				}
				$arr[$siteid][$affid] = $detail_arr[$affid];
				unset($detail_arr[$affid]);
			}
		}
		foreach($detail_arr as $key=>$detail){
			$arr[$key][$detail['affid']] = $detail;
		}

		$amount = 0;
		foreach($arr as $key=>$value){
			foreach($value as $k=>$v){
				$amount+=$v['amount'];
			}
		}
		foreach($arr as $site=>$item){
			$amount = 0;
			$fee = 0;
			foreach($item as $invoice){
				$amount += isset($invoice['amount']) ? $invoice['amount'] : 0;
				$fee += isset($invoice['fee']) ? $invoice['fee'] : 0;
			}
			$arr[$site]['amount']=$amount;
			$arr[$site]['fee']=$amount;
		}
		return $arr;
	}


	/**
	 * get the report by invoice
	 *@param $date string
	 *@return array
	 */
	public static function getList($date){
		$db = Yii::app()->db;
		$sql = "select u.company as aff_name,t.count_date as mon ,t.affid,t.amount,t.invoice_date,t.fee,m.company as am_name
from joy_invoice t LEFT JOIN joy_system_user u on u.id=t.affid
 LEFT JOIN joy_system_user m on t.am_id=m.id
where count_date = '$date'
ORDER BY aff_name";
		$command = $db->createCommand($sql);
		$result = $command->queryAll();
		if(empty($result)){
			return null;
		}
		$detail_arr = array();
		//group with affid
		foreach($result as $detail){
			if(!isset($detail_arr[$detail['affid']])){
				$detail_arr[$detail['affid']] = array();
			}
			array_push($detail_arr[$detail['affid']],$detail);
		}
		$amount = 0;
		foreach($detail_arr as $item){
			$amount_arr = array_column($item,'amount');
			foreach($amount_arr as $amount_s){
				$amount += $amount_s;
			}
		}
		//group with site
		var_dump($detail_arr);die();
		$arr = JoySites::getCompanyData($detail_arr);
		var_dump($detail_arr);die();

		$amount = 0;
		foreach($arr as $item){
			$amount += $item['amount'];
		}
		return $arr;
	}
}