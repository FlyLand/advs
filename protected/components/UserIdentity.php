<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity{
	private $_id = NULL;
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate(){
		if($this->username == NULL || $this->username == ''){
			$this->errorCode = self::ERROR_USERNAME_INVALID;
		}

		if($this->password == NULL || $this->password == ''){
			$this->errorCode = self::ERROR_PASSWORD_INVALID;
		}
		
		$user = JoySystemUser::model()->findByAttributes(array('email' => $this->username));

        if($user == NULL){
            $this->errorCode = self::ERROR_USERNAME_INVALID;
		}
        else if($user->status == "'DISABLED'"){
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        }else{
			$md5Passwd = md5($this->password);
			if($md5Passwd === $user->password){
				//登录验证成功，保存session信息
                $data = array(
                    'lastlogin' => date('Y-m-d H:i:s'),
                    'loginip' => Yii::app()->request->userHostAddress,
                );
                JoySystemUser::model()->updateByPk($user->id,$data);
				$this->setState('truename',$user->email);
				$this->_id = $user->id;
				$this->errorCode = self::ERROR_NONE;
			}else{
				$this->errorCode = self::ERROR_PASSWORD_INVALID;
			}
		}
		return !$this->errorCode;
	}
	
	public function getId(){
		return $this->_id;
	}
}