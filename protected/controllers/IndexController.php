<?php
/**
 * 推荐首页
 * @author jiangpw
 *
 */
class IndexController extends Controller
{

	function __construct(){
		parent::checkAction();
	}

	/**
	 * 首页
	 */
	public function actionIndex(){
		if(!$this->user){
			$this->renderPartial('/comm/login');
		}else{
			$this->redirect(array("system/index"));
		}
	}
	
	/**
	 * 退出
	 */
	public function actionLogout()
	{
		CfgAR::delMc(array('link'=>CACHE, 'key'=>Admin_MEM_PIX . $this->getSessionId()));
		$url = $this->createUrl("index/index");
		setcookie("email", '',time()-1,'/');
		setcookie("password",'',time()-1,'/');
		$this->redirect($url);
	}
}
