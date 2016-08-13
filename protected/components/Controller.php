<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */ 
abstract class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/main';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();
	
	public $session = array();

	public $manager_group = array(ADMIN_GROUP_ID,MANAGER_GROUP_ID,BUSINESS_GROUP_ID,AM_GROUP_ID,FINANCE_GROUP_ID);

	public $client_group = array(ADVERTISER_GROUP_ID,AFF_GROUP_ID,SITE_GROUP_ID);
	
	public $user= array();

	function checkAction(){
        $this->user = $userSession	=	Yii::app()->user->getState('userSession');
	}
	
	
	/**
	 * 动态加载公共部件
	 */
	public function helper(){
		$help = new Helper();
		return $help;
	}
	
	
	public function getSessionId(){
		if (isset($_REQUEST['sessionId'])) {
			return $_REQUEST['sessionId'];
		}
		return Yii::app()->session->sessionID;
	}
	
	/**
	 * 查看用户是否登陆
	 * @param user=>用户信息 ， url->跳转的页面
	 */
	public function checkUserStatus( $params=array() ){
		$type		=	isset($params['type']) ? $params['type'] : '';
		$url		=	isset($params['url']) ? $params['url'] : '' ;
			
		$ret_array	=	array('ret'=>-1, 'msg'=>'', 'data'=>'');
		do{
			if( !isset($this->user['email']) || empty($this->user['email']) ){
				if('json' == $type){
					$ret_array['msg']	=	'Please login first';
				}else{
					if( !empty($url) ){
						$_SESSION['activity_login_url']	=	$this->createUrl($url);
					}
					$this->redirect(array("system/login"));
					exit;
				}
			}
			$ret_array['ret']	=	0;
		}while(0);
		if(0 != $ret_array['ret']){
			echo CfgAR::enJson($ret_array);
			exit;
		}
	}
}
