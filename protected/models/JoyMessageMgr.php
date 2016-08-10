<?php

/**
 * This is the model class for table "joy_message_mgr".
 *
 * The followings are the available columns in table 'joy_message_mgr':
 * @property integer $id
 * @property integer $sendid
 * @property integer $fromid
 * @property integer $msgid
 * @property integer $status
 */
class JoyMessageMgr extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return JoyMessageMgr the static model class
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
		return 'joy_message_mgr';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('fromid, msgid', 'required'),
			array('sendid, fromid, msgid, status', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, sendid, fromid, msgid, status', 'safe', 'on'=>'search'),
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
				'msg'=>array(self::BELONGS_TO,'JoySystemMessage','msgid'),
				'senduser'=>array(self::BELONGS_TO,'JoySystemUser','sendid'),
				'fromuser'=>array(self::BELONGS_TO,'JoySystemUser','fromid'),
				'msgtype'=>array(self::BELONGS_TO,'JoyMessageType','type'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'sendid' => 'Sendid',
			'fromid' => 'Fromid',
			'msgid' => 'Msgid',
			'status' => 'Status',
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
		$criteria->compare('sendid',$this->sendid);
		$criteria->compare('fromid',$this->fromid);
		$criteria->compare('msgid',$this->msgid);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	* @property $select_type: the message type,0 means system message and 1 means apply for affiliates
	* @property $task_type : the apply type, 0 means Change Url , 1 means Apply Offer Link and 2 means Create Account
	* @property $sendids : the array of affiliate ids
	* @property $params : params of send
	*/
	public static function sendMessage($select_type,$userid,$sendids = array(),$params = array()){
		$data['msg'] = '';
		$content = isset($params['content']) ? $params['content'] : '';
		$title = isset($params['title']) ? $params['title'] : '';
		$affid = isset($params['affid']) ? $params['affid'] : '';
		$back_url = isset($params['back_url']) ? $params['back_url']  : '';
		$task_type = isset($params['task_type']) ? $params['task_type'] : '';
		$content_input = isset($params['content_input']) ? $params['content_input'] : '';
		try {
			do {
				if (0 == $select_type) {
					if (!empty($sendids)) {
						$message = new JoySystemMessage();
						$message->title = $title;
						$message->content = $content;
						$message->time = date('Y-m-d H:i:s');
						$message->type = $select_type;
						if (!$message->save()) {
							$data['msg'] = 'failed!';
							break;
						}
						$message_id = $message->attributes['id'];
						foreach ($sendids as $sendid) {
							$msg_mgr = new JoyMessageMgr();
							$msg_mgr->fromid = $userid;
							$msg_mgr->msgid = $message_id;
							$msg_mgr->sendid = $sendid;
							if (!$msg_mgr->save()) {
								$data['msg'] = 'failed!';
								break;
							}
						}
						$data['msg'] = 'success!';
					}
				} elseif (1 == $select_type) {
					if (2 == $task_type) {
						$content = $content_input;
					}
					$task = new JoyJumpTask();
					$task->content = $content;
					$task->createtime = date('Y-m-d H:i:s');
					$task->affid = $affid;
					$task->back_url = $back_url;
					$task->applicant_id = $userid;
					$task->task_type = $task_type;
					if (!$task->save()) {
						$data['msg'] = 'Failed!';
						break;
					}
					$message = new JoySystemMessage();
					if (0 == $task_type) {
						$message->title = 'Change Url';
					} elseif (1 == $task_type) {
						$message->title = 'Apply Offer Link';
					} elseif (2 == $task_type) {
						$message->title = 'Create Account';
					}
					$message->content = $content;
					$message->time = date('Y-m-d H:i:s');
					$message->type = $select_type;
					if (!$message->save()) {
						$data['msg'] = 'failed!';
						break;
					}
					$message_id = $message->attributes['id'];
					$sendids = JoySystemUser::model()->findAllByAttributes(array('groupid' => AM_GROUP_ID));
					foreach ($sendids as $sendid) {
						$msg_mgr = new JoyMessageMgr();
						$msg_mgr->fromid = $userid;
						$msg_mgr->msgid = $message_id;
						$msg_mgr->sendid = $sendid['id'];
						if (!$msg_mgr->save()) {
							$data['msg'] = 'failed!';
							break;
						}
					}
					$data['msg'] = 'Success!';
				}else{
					$data['msg'] =  'Error!';
				}
			} while (0);
		}catch (Exception $e){
			$data['msg'] =  'System Error!';
 		}
		return $data['msg'];
	}

	public static function getSendName($id){
		$db = Yii::app()->db;
		$sql = "select u.company from joy_system_message mes left JOIN joy_message_mgr mgr on mes.id=mgr.msgid
left JOIN joy_system_user u on mgr.sendid=u.id
where mes.id=$id";
		$command = $db->createCommand($sql);
		$result = $command->queryRow();
		if(!empty($result)){
			return $result['company'];
		}else{
			return null;
		}
	}
}