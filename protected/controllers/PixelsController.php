<?php


class PixelsController extends Controller{
    
    public function __construct(){
        parent::checkAction();
    }
    /**
     * 显示pixels的数据列表
     */
    public function actionList() {
        $page			=	Yii::app()->request->getParam('page');
        $count = JoyOfferPixels::model()->count();
        $jpurl  = $this->createUrl('pixels/list');
        $size			=	30;
        $jparams		=	array();
        $page_obj			=	new Page();
        $page_control		=	$page_obj->pageCut($count,$page,$size);
        $data['page']		=	$page_control['page'];
        /*
         if( isset($_GET['affid']) && intval($_GET['affid']) > 0 ){
        $affid	=	intval($_GET['affid']);
        $jparams[]	=	'affid='.$affid;
        }
        */
        if( 0 < count($jparams) ){
            $tmp_str		=	strpos($jpurl, '?') ? '&' : '?';
            $jpurl			.=	$tmp_str.join('&', $jparams);
        }

        $page_obj			=	new Page();
        $fenyecode	=	$page_obj->createPage( array('url'=>$jpurl, 'size'=>$count, 'page'=>$data['page'], 'pageSize'=>$size) );
        $pixels = JoyOfferPixels::model()->with('advertiser')->findAll(array(
            'offset' => $page_control['query_count'],
            'limit' => $size,
        ));
        $this->render('pixels/pixels-list',array(
            'pixels'=>$pixels,
            'fenyecode'=>$fenyecode,
            'count'=>$count,
        ));
    }
    /**
     * url测试功能   
     */
    public function actionUrlTest(){
        $pixelsId = Yii::app()->request->getParam('pixelsId');
        $pixels = JoyOfferPixels::model()->findByPk($pixelsId);
        $offer = joy_offers::model()->findByAttributes(array('id'=>$pixels['offerid']));
        $affiliate = JoySystemUser::model()->findByPk($pixels['affid']); 
        $testUrl = Yii::app()->request->getHostInfo().$this->createUrl('api/urltest').'&offer_id='.$offer['id'].'&aff_id='.$affiliate['id'].'&test=1';
        $this->render('pixels/url-test',array(
            'pixels'=>$pixels,
            'offer'=>$offer,
            'affiliate'=>$affiliate,
            'testUrl'=>$testUrl
        ));
    }

    /*
    * 修改
    */
    public function actionPixelsEdit(){
        $pix_id = Yii::app()->request->getParam('id');
        $type = Yii::app()->request->getParam('type');
        if(!$pix_id){
            throw new ErrorException('error data');
        }
        $data['pix'] = $pix = JoyOfferPixels::model()->with('affiliate','offer')->findByPk($pix_id);
        if(!$pix){
            throw new ErrorException('the pix is already be deleted!');
        }
        if($type == 'update'){
            $pix->code = Yii::app()->request->getParam('pix_code');
            if(!$pix->update()){
                Common::jsalerturl('failed',$this->createUrl('pixels/pixels_edit',array('data'=>$data)));
            }
        }
        $this->render('pixels/pixels_edit',$data);
    }

    /**
    *删除
     */
    public function actionPixelsDelete(){
        $pix_id = Yii::app()->request->getParam('id');
        $pix = JoyOfferPixels::model()->findByPk($pix_id);
        if(!$pix){
            throw new ErrorException('the pix is already be deleted');
        }
        if(!$pix->delete()){
            Common::jsalerturl('failed delete');
        }
        Common::jsalerturl('delete success',$this->createUrl('pixels/list'));
    }
}