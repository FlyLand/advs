<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/3/24
 * Time: 10:43
 */
class Tools
{
    public static function download_excel($head,$list,$params = null){
        /** Error reporting */
        error_reporting(E_ALL);
        ini_set('display_errors', TRUE);
        ini_set('display_startup_errors', TRUE);
        date_default_timezone_set('Europe/London');
        if (PHP_SAPI == 'cli')
            die('This example should only be run from a Web Browser');
        /** Include PHPExcel */
        Yii::$enableIncludePath = false;
        Yii::import('application.extensions.PHPExcel.PHPExcel', 1);
        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();
        $key = ord("A");
        foreach($head as $v){
            $colum = chr($key);
//            if($colum == 'B'){
//                $objPHPExcel->getActiveSheet()->mergeCells('B1:C1');
//                $key += 1;
//            }
            $objPHPExcel->setActiveSheetIndex(0) ->setCellValue($colum.'1', $v);
            $key += 1;
        }
        $exc_colum = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
        $detail_arr = array();
        foreach($list as $detail){
            $detail_arr[$detail['affid']] = array();
            array_push($detail_arr[$detail['affid']],$detail);
        }
        $sites = self::getCompanySite();
        foreach($sites as $key=>$site){
            foreach($site as $affid){
                if(!in_array($affid,$detail_arr)){
                    continue;
                }
                $arr[$key] = $detail_arr[$affid];
            }
        }
        $type =1;
        if(empty($arr)){
            $arr = $detail_arr;
            $type = 0;
        }
        $t= 0;
        foreach($arr as $key=>$value) {
            $i = 0;
            foreach($value as $arr_value){
                foreach($arr_value as $item) {
                    var_dump($item);die();
                    $objPHPExcel->getActiveSheet(0)->setCellValue($exc_colum[$i] . ($t + 2), $item);
                    $i++;
                }
                $t++;
            }
        }
        $fileName   =   date('YmdHis') . 'Report';
        $objPHPExcel->getActiveSheet()->setTitle('Simple');
        $objPHPExcel->setActiveSheetIndex(0);
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
            ->setLastModifiedBy("Maarten Balliauw")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");
        // Redirect output to a client��s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$fileName.'.xlsx"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }

    public static function getCompanySite(){
        $sites = JoySites::model()->findAll();
        $arr = array();
        foreach($sites as $site){
            $arr[$site['site_id']] =  explode(',',$site['affids']);
        }
        return $arr;
    }
}