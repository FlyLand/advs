<?php
/**
 * @author jack 2012-12-14 
 * 
 * 面包屑挂件
 */
class BreadCrumb extends CWidget {
 
    public $crumbs = array();
    public $delimiter = ' > ';
    public $links = array();
    public $homeLinks = array();
 
    public function run() {
        $this->render('breadCrumb');
    }
 
}
