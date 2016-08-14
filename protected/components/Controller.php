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
        $url_this = $_SERVER['SERVER_NAME'];
        if($url_this != MANAGER_SERVER_NAME){
            $oid	=	Yii::app()->request->getParam('oid');
            $channel		=	Yii::app()->request->getParam('channel');
            $clickid	=	Yii::app()->request->getParam('clickid');
            $subid		=	Yii::app()->request->getParam('subid');
            $query	=  Yii::app()->request->getParam('search');
            $this->redirect(LINK."?channel=$channel&oid=$oid&clickid=$clickid&subid=$subid");
            Yii::app()->end();
            /*if($url_this == OUT_LAND_SERVER_NAME){
                $offer_id = isset($_GET['offer_id']) ? $_GET['offer_id'] : '';
                $aff = isset($_GET['aff_id']) ? $_GET['aff_id'] : '';
                $aff_sub = isset($_GET['aff_sub']) ? $_GET['aff_sub'] : '';
                $subid = isset($_GET['subid']) ? $_GET['subid'] : '';
                $this->redirect(LINK."&ffid=$aff&oid=$offer_id&clickid=$aff_sub&f_sub=$subid");
            }elseif($url_this == SEARCH_SERVER_NAME){
                $aff = isset($_GET['c']) ? $_GET['c'] : '';
                $offer_id = isset($_GET['o']) ? $_GET['o'] : '';
                if(!empty($search2)){
                    $this->redirect(LINK."&search=$search2&aff_id=$aff&offer_id=$offer_id");
                }else{
                    $this->redirect(LINK."&search=$search&aff_id=$aff&offer_id=$offer_id");
                }
            }*/
        }
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
