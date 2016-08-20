<?php
/**
 * 推荐首页
 * @author jiangpw
 *
 */
class IndexController extends Controller
{
	public function filters()
	{
		return ['authControl'];
	}

	public $layout = '//layouts/sidebar';
	/**
	 * 首页
	 */
	public function actionIndex(){
		$this->layout = '//layouts/sidebar';
		$this->render("index");
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
