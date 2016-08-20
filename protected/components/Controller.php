<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
header('content-type:text/html;charset=utf-8');
class Controller extends CController{

	public $layouts='//layouts/main.php';
	public $pageDesc;
	public $breadcrumbs=array();
	public $layout = '//layouts/sidebar';
	public $errTemp = '<div class="alert">%s</div>';
//	public $layout = '//layouts/siderbar.php';

	public function filters(){
		return ['authControl'];
	}

	public function filterAuthControl($filterChain){
		//获取当前访问的链接信息
		$moduleId = is_object($filterChain->controller->getModule()) ? $filterChain->controller->getModule()->getId() : "";
		$controllerId = $filterChain->controller->getId();
		$actionId = $filterChain->action->getId();

		//全部转为小写处理，避免有些地方是大写，有些地方是小写而造成的同一方法不同权限结果的问题
		$currentActionPath = empty($moduleId) ? strtolower($controllerId."/".$actionId) : strtolower($moduleId."/".$controllerId."/".$actionId);
		//如果是权限白名单，也直接通过,主要是针对所有用户进行的操作
		if(false){
		}else{
			if(Yii::app()->user->getIsGuest()) {
				Yii::app()->user->setFlash("info", "请先登录");
				Yii::app()->user->loginRequired();  //封装了Yii::app()->user->loginUrl
				Yii::app()->end();
			}
			$user = User::model()->findByPk(Yii::app()->user->getId());
			//如果是系统管理员或者没有设置规则 就要直接通过
			Yii::app()->user->setState('groupid',$user->groupid);
			if($user->groupid == Yii::app()->params['ADMIN_GROUP_ID']) return $filterChain->run();
			//控制器的规则  captain start
			$rules = Yii::app()->redis->get('check.key.rules');
			if(!$rules){
				$rules	=	require Yii::app()->getBasePath() .'/config/powerconf.php';
				$powerJson = json_encode($rules);
				Yii::app()->redis->set('check.key.rules',$powerJson);
			}else{
				$rules = json_decode($rules,true);
			}
			//控制器的规则 captain end
			$support = Yii::app()->redis->get('check.key.support');
			if(!$support){
				$rules_arr = System::getGroupPowerDetail(array('groupid' => $user['groupid'], 'datatype' => 'simple'));//用户登录检查
				$support = $rules_arr['data'];
				$rulesJson = json_encode($support);
				Yii::app()->redis->set('check.key.support'.Yii::app()->user->getId(),$rulesJson);
			}else{
				$support = json_decode($support,true);
			}
			Yii::app()->user->setState('powerinfo',$support);
			$isRun = 0; //是否可执行，默认否
			if(in_array($currentActionPath,$support) && in_array($currentActionPath,$rules)){
				$isRun = 1; //是否可执行，默认否
			}
			if(in_array($currentActionPath,Yii::app()->params['not_check_action'])){
				$isRun = 1;
			}
			if($isRun){
				return $filterChain->run();
			}else{
				throw new CHttpException(401,'抱歉,你无权限操作此内容!');
			}
		}
	}

	public function checkAuth($key){
		$user = User::model()->findByPk(Yii::app()->user->getId());
		//如果是系统管理员直接通过
		if($user->isadmin == 1)
		{
			return true;
		}

		$uids = Yii::app()->db->createCommand("select distinct uid from t_user_actor_assign where actor_id in (select actor_id from t_actor_auth a left join t_auth_detail b on a.auth_id = b.id where b.`key` = '{$key}');")->queryAll();
		$supportUid = [];
		foreach($uids as $item){
			$supportUid[] = $item['uid'];
		}
		return in_array(Yii::app()->user->getId(),$supportUid);
	}

	public function beforeAction($action)
	{
		parent::beforeAction($action);
		$this->logUserOperation();
		return true;
	}

	/**
	 * 记录每个用户访问的行为
	 *
	 * @return [type] [description]
	 */
	private function logUserOperation()
	{
		try {
			$uid = !Yii::app()->user->isGuest?Yii::app()->user->id:'0';
			$busi = 0;
			$time = time();
			$ip = Yii::app()->request->userHostAddress;
			$data = json_encode(array());
			$url = Yii::app()->request->requestUri;

			$sql = "insert into t_access_log (`uid`,`time`,`ip`,`data`,`url`) values ('{$uid}','{$time}','{$ip}','{$data}','{$url}');";

			Yii::app()->db->createCommand($sql)->execute();
		} catch (Exception $e) {
		}

	}
}