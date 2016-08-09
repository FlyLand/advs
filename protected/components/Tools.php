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
            $objPHPExcel->setActiveSheetIndex(0) ->setCellValue($colum.'1', $v);
            $key += 1;
        }
        $exc_colum = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
        $detail_arr = array();
        foreach($list as $detail){
            if(!isset($detail_arr[$detail['site_id']])){
                $detail_arr[$detail['site_id']] = array();
            }
            array_push($detail_arr[$detail['site_id']],$detail);
        }
        $arr = JoySites::getCompanyData($detail_arr);
        $t= 0;
        foreach ($arr as $key => $value) {
            $sum = $value['amount'];
            $am = 0;
            if(isset($value['am'])){
                $am = $value['am'];
            }
            $col_a = count($value['data']);
            $col_e = $col_a;
            $col_f = $col_a;
            $col_g = $col_a;
            foreach ($value['data'] as $arr_value) {
                foreach($arr_value as $params) {
                    $i = 0;
                    foreach ($params as $s=>$param) {
                        if($col_a > 1){
                            if ($exc_colum[$i] == 'A') {
                                $objPHPExcel->getActiveSheet()->mergeCells($exc_colum[$i] . ($t + 2) . ':' . $exc_colum[$i] . ($t + 2 + $col_a -1));
                                $col_a --;
                            }
                        }
                        if($exc_colum[$i] == 'B'){
                            $param = date('M',strtotime($param));
                        }
                        if($col_e > 1){
                            if ($exc_colum[$i] == 'E') {
                                $objPHPExcel->getActiveSheet()->mergeCells($exc_colum[$i] . ($t + 2) . ':' . $exc_colum[$i] . ($t + 2 + $col_e - 1));
                                $param = $sum;
                                $col_e --;
                            }
                        }
                        if($col_f > 1){
                            if ($exc_colum[$i] == 'F') {
                                $objPHPExcel->getActiveSheet()->mergeCells($exc_colum[$i] . ($t + 2) . ':' . $exc_colum[$i] . ($t + 2 + $col_f - 1));
                                $param = $sum;
                                $col_f --;
                            }
                        }
                        if($col_g > 1){
                            if ($exc_colum[$i] == 'G') {
                                $objPHPExcel->getActiveSheet()->mergeCells($exc_colum[$i] . ($t + 2) . ':' . $exc_colum[$i] . ($t + 2 + $col_g - 1));
                                $param = $am;
                                $col_g --;
                            }
                        }
                        $objPHPExcel->getActiveSheet()->setCellValue($exc_colum[$i] . ($t + 2), $param);
                        $i++;
                    }
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
//        $fileName = date('His');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$fileName.'.xlsx"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }

    public static function excel_payment_with_site($head,$item,$row_count,$objPHPExcel,$t)
    {
        $exc_colum = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
        $col_a = $row_count;
        $col_b = $row_count;
        $col_d = $row_count;
        $col_g = $row_count;
        $col_h = $row_count;
        $col_i = $row_count;
        $col_j = $row_count;
        $col_k = $row_count;
        $amount_paid = 0;
        if(!empty($item['invoice'])){
            foreach($item['invoice'] as $invoice){
                $amount_paid += $invoice['amount'];
            }
            foreach ($item['invoice'] as $param) {
                $row_control = count($head);
                $i = 0;
                for (; $row_control > 0; $row_control--) {
                    $filed = null;
                    switch ($exc_colum[$i]) {
                        case 'A':
                            if ($col_a > 1 || $row_count == 1) {
                                $objPHPExcel->getActiveSheet()->mergeCells($exc_colum[$i] . ($t + 2) . ':' . $exc_colum[$i] . ($t + 2 + $col_a - 1));
                                $col_a--;
                                $filed = $item['contract_code'];
                            }
                            break;
                        case 'B':
                            if ($col_b > 1 || $row_count == 1) {
                                $objPHPExcel->getActiveSheet()->mergeCells($exc_colum[$i] . ($t + 2) . ':' . $exc_colum[$i] . ($t + 2 + $col_b - 1));
                                $col_b--;
                                $filed = $item['company'];
                            }
                            break;
                        case 'C':
                            $filed = date('M', strtotime($param['count_date']));
                            break;
                        case 'D':
                            if ($col_d > 1 || $row_count == 1) {
                                $objPHPExcel->getActiveSheet()->mergeCells($exc_colum[$i] . ($t + 2) . ':' . $exc_colum[$i] . ($t + 2 + $col_d - 1));
                                $col_d--;
                                $filed = $item['site_id'];
                            }
                            break;
                        case 'E':
                            $filed = $param['affid'];
                            break;
                        case 'F':
                            $filed = $param['amount'];
                            break;
                        case 'G':
                            if ($col_g > 1 || $row_count == 1) {
                                $objPHPExcel->getActiveSheet()->mergeCells($exc_colum[$i] . ($t + 2) . ':' . $exc_colum[$i] . ($t + 2 + $col_g - 1));
                                $filed = $amount_paid;
                                $col_g--;
                            }
                            break;
                        case 'H':
                            if ($col_h > 1 || $row_count == 1) {
                                $objPHPExcel->getActiveSheet()->mergeCells($exc_colum[$i] . ($t + 2) . ':' . $exc_colum[$i] . ($t + 2 + $col_h - 1));
                                $filed = $item['bank_name'];
                                $col_h--;
                            }
                            break;
                        case 'I':
                            if ($col_i > 1 || $row_count == 1) {
                                $objPHPExcel->getActiveSheet()->mergeCells($exc_colum[$i] . ($t + 2) . ':' . $exc_colum[$i] . ($t + 2 + $col_i - 1));
                                $filed = $item['bank_address'];
                                $col_i--;
                            }
                            break;
                        case 'J':
                            if ($col_j > 1 || $row_count == 1) {
                                $objPHPExcel->getActiveSheet()->mergeCells($exc_colum[$i] . ($t + 2) . ':' . $exc_colum[$i] . ($t + 2 + $col_j - 1));
                                $filed = $item['bank_account'];
                                $col_j--;
                            }
                            break;
                        case 'K':
                            if ($col_k > 1 || $row_count == 1) {
                                $objPHPExcel->getActiveSheet()->mergeCells($exc_colum[$i] . ($t + 2) . ':' . $exc_colum[$i] . ($t + 2 + $col_k - 1));
                                $filed = $item['swift_code'];
                                $col_k--;
                            }
                            break;
                        default:
                            $filed = 0;
                            break;
                    }
                    $objPHPExcel->getActiveSheet()->setCellValue($exc_colum[$i] . ($t + 2), $filed);
                    $i++;
                }
                $t++;
            }
        }else{
            $i = 0;
            $row_control = count($head);
            for (; $row_control > 0; $row_control--) {
                switch ($exc_colum[$i]) {
                    case 'A':
                        $filed = $item['contract_code'];
                        break;
                    case 'B':
                        $filed = $item['company'];
                        break;
                    case 'C':
                        $filed = date('M', strtotime($item['count_date']));
                        break;
                    case 'D':
                        $filed = $item['site_id'];
                        break;
                    case 'E':
                        $filed = $item['site_id'];
                        break;
                    case 'F':
                        $filed = $item['amount'];
                        break;
                    case 'G':
                        $filed = $item['amount'];
                        break;
                    case 'H':
                        $filed = $item['bank_name'];
                        break;
                    case 'I':
                        $filed = $item['bank_address'];
                        break;
                    case 'J':
                        $filed = $item['bank_account'];
                        break;
                    case 'K':
                        $filed = $item['swift_code'];
                        break;
                    default:
                        $filed = 0;
                        break;
                }
                $objPHPExcel->getActiveSheet()->setCellValue($exc_colum[$i] . ($t + 2), $filed);
            }
            $t++;
        }


        return $t;
    }


    public static function excel_download_payment($head,$arr,$item,$row_count,$objPHPExcel,$t){
        $exc_colum = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
        $col_a = $row_count;
        $col_b = $row_count;
        $col_f = $row_count;
        $col_g = $row_count;
        $col_h = $row_count;
        $col_i = $row_count;
        $col_j = $row_count;
        if(!empty($arr)) {
            foreach ($arr as $project) {
                foreach ($project as $affid => $params) {
                    foreach ($params as $param) {
                        $row_control = count($head);
                        $i = 0;
                        for (; $row_control > 0; $row_control--) {
                            $filed = 1;
                            switch ($exc_colum[$i]) {
                                case 'A':
                                    if ($col_a > 1) {
                                        $objPHPExcel->getActiveSheet()->mergeCells($exc_colum[$i] . ($t + 2) . ':' . $exc_colum[$i] . ($t + 2 + $col_a - 1));
                                        $col_a--;
                                        $filed = $item['contract_code'];
                                    }
                                    break;
                                case 'B':
                                    if ($col_b > 1) {
                                        $objPHPExcel->getActiveSheet()->mergeCells($exc_colum[$i] . ($t + 2) . ':' . $exc_colum[$i] . ($t + 2 + $col_b - 1));
                                        $col_b--;
                                        $filed = $item['company'];
                                    }
                                    break;
                                case 'C':
                                    $filed = date('M', strtotime($param['count_date']));
                                    break;
                                case 'D':
                                    $filed = $affid;
                                    break;
                                case 'E':
                                    $filed = $param['amount'];
                                    break;
                                case 'F':
                                    if ($col_f > 1) {
                                        $objPHPExcel->getActiveSheet()->mergeCells($exc_colum[$i] . ($t + 2) . ':' . $exc_colum[$i] . ($t + 2 + $col_f - 1));
                                        $filed = $item['amount'];
                                        $col_f--;
                                    }
                                    break;
                                case 'G':
                                    if ($col_g > 1) {
                                        $objPHPExcel->getActiveSheet()->mergeCells($exc_colum[$i] . ($t + 2) . ':' . $exc_colum[$i] . ($t + 2 + $col_g - 1));
                                        $filed = $item['bank_name'];
                                        $col_g--;
                                    }
                                    break;
                                case 'H':
                                    if ($col_h > 1) {
                                        $objPHPExcel->getActiveSheet()->mergeCells($exc_colum[$i] . ($t + 2) . ':' . $exc_colum[$i] . ($t + 2 + $col_h - 1));
                                        $filed = $item['bank_address'];
                                        $col_h--;
                                    }
                                    break;
                                case 'I':
                                    if ($col_i > 1) {
                                        $objPHPExcel->getActiveSheet()->mergeCells($exc_colum[$i] . ($t + 2) . ':' . $exc_colum[$i] . ($t + 2 + $col_i - 1));
                                        $filed = $item['bank_account'];
                                        $col_i--;
                                    }
                                    break;
                                case 'J':
                                    if ($col_j > 1) {
                                        $objPHPExcel->getActiveSheet()->mergeCells($exc_colum[$i] . ($t + 2) . ':' . $exc_colum[$i] . ($t + 2 + $col_j - 1));
                                        $filed = $item['swift_code'];
                                        $col_j--;
                                    }
                                    break;
                                default:
                                    $filed = 0;
                                    break;
                            }
                            $objPHPExcel->getActiveSheet()->setCellValue($exc_colum[$i] . ($t + 2), $filed);
                            $i++;
                        }
                        $t++;
                    }
                }
            }
        }else {
            $i = 0;
            $row_control = count($head);
            for (; $row_control > 0; $row_control--) {
                switch ($exc_colum[$i]) {
                    case 'A':
                        $filed = $item['contract_code'];
                        break;
                    case 'B':
                        $filed = $item['company'];
                        break;
                    case 'C':
                        $filed = date('M', strtotime($item['count_date']));
                        break;
                    case 'D':
                        $filed = $item['site_id'];
                        break;
                    case 'E':
                        $filed = $item['amount'];
                        break;
                    case 'F':
                        $filed = $item['amount'];
                        break;
                    case 'G':
                        $filed = $item['bank_name'];
                        break;
                    case 'H':
                        $filed = $item['bank_address'];
                        break;
                    case 'I':
                        $filed = $item['bank_account'];
                        break;
                    case 'J':
                        $filed = $item['swift_code'];
                        break;
                    default:
                        $filed = 0;
                        break;
                }
                $objPHPExcel->getActiveSheet()->setCellValue($exc_colum[$i] . ($t + 2), $filed);
                $i++;
            }
            $t++;
        }


       /* foreach ($item as $key => $value) {
            $sum = $value['amount'];
            $am = 0;
            if(isset($value['am'])){
                $am = $value['am'];
            }
            $col_a = count($value['data']);
            $col_e = $col_a;
            $col_f = $col_a;
            $col_g = $col_a;
            foreach ($value['data'] as $arr_value) {
                foreach($arr_value as $params) {
                    $i = 0;
                    foreach ($params as $s=>$param) {
                        if($col_a > 1){
                            if ($exc_colum[$i] == 'A') {
                                $objPHPExcel->getActiveSheet()->mergeCells($exc_colum[$i] . ($t + 2) . ':' . $exc_colum[$i] . ($t + 2 + $col_a -1));
                                $col_a --;
                            }
                        }
                        if($exc_colum[$i] == 'B'){
                            $param = date('M',strtotime($param));
                        }
                        if($col_e > 1){
                            if ($exc_colum[$i] == 'E') {
                                $objPHPExcel->getActiveSheet()->mergeCells($exc_colum[$i] . ($t + 2) . ':' . $exc_colum[$i] . ($t + 2 + $col_e - 1));
                                $param = $sum;
                                $col_e --;
                            }
                        }
                        if($col_f > 1){
                            if ($exc_colum[$i] == 'F') {
                                $objPHPExcel->getActiveSheet()->mergeCells($exc_colum[$i] . ($t + 2) . ':' . $exc_colum[$i] . ($t + 2 + $col_f - 1));
                                $param = $sum;
                                $col_f --;
                            }
                        }
                        if($col_g > 1){
                            if ($exc_colum[$i] == 'G') {
                                $objPHPExcel->getActiveSheet()->mergeCells($exc_colum[$i] . ($t + 2) . ':' . $exc_colum[$i] . ($t + 2 + $col_g - 1));
                                $param = $am;
                                $col_g --;
                            }
                        }
                        $objPHPExcel->getActiveSheet()->setCellValue($exc_colum[$i] . ($t + 2), $param);
                        $i++;
                    }
                }
                $t++;
            }
        }*/
        return $t;
    }

    public static function download_cr_excel($head,$report,$display_type){
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
            $objPHPExcel->setActiveSheetIndex(0) ->setCellValue($colum.'1', $v);
            $key += 1;
        }
        $exc_colum = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
        $t=0;
        foreach ($report as  $item) {
            $i = 0;
            $count = count($head);
            for(;$count > 0;$count--,$i ++){
                switch($exc_colum[$i]){
                    case 'A':
                        if($display_type == 0){
                            $filed = $item['time'];
                        }elseif($display_type == 1){
                            $filed = $item['project_name'];
                        }elseif($display_type == 2){
                            $filed = $item['affid'];
                        }elseif($display_type == 3){
                            $filed = $item['siteid'];
                        }
                        break;
                    case 'B':
                        $filed = $item['click_count'];
                        break;
                    case 'C':
                        $filed = $item['revenue'];
                        break;
                    case 'D':
                        $filed = $item['payout'];
                        break;
                    case 'E':
                    $filed = $item['payout'] - $item['revenue'];
                    break;
                    default:
                        $filed = 0;
                }
                $objPHPExcel->getActiveSheet()->setCellValue($exc_colum[$i] . ($t + 2), $filed);
            }
            $t ++;
        }
        $fileName   =   date('YmdHis') . 'crreport';
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
//        $fileName = date('His');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$fileName.'.xlsx"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }
    public static function downloadAffiliatesPayment($head,$report){
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
            $objPHPExcel->setActiveSheetIndex(0) ->setCellValue($colum.'1', $v);
            $key += 1;
        }
        $exc_colum = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
        $t=0;

        foreach ($report as  $item) {
            $i = 0;
            $count = count($head);
            for(;$count > 0;$count--,$i ++){
                switch($exc_colum[$i]){
                    case 'A':
                        $filed = $item['company']['company'];
                        break;
                    case 'B':
                        $filed = $item['affid'];
                        break;
                    case 'C':
                        $filed = $item['beneficiary'];
                        break;
                    case 'D':
                        $filed = $item['bank_name'];
                        break;
                    case 'E':
                        $filed = $item['bank_address'];
                        break;
                    case 'F':
                        $filed = $item['bank_account'];
                        break;
                    case 'G':
                        $filed = $item['swift_code'];
                        break;
                    default:
                        $filed = 0;
                }
                $objPHPExcel->getActiveSheet()->setCellValue($exc_colum[$i] . ($t + 2), $filed);
            }
            $t ++;
        }
        $fileName   =   date('YmdHis') . 'crreport';
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
//        $fileName = date('His');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$fileName.'.xlsx"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }

    public static function getLastLine(){
        $rs = './config/sqlCache.txt';
        $fp = fopen($rs, 'r');
        fseek($fp,-1,SEEK_END);
        $s = '';
        while(($c = fgetc($fp)) !== false)
        {
            if($c == "\n" && $s) break;
            $s = $c . $s;
            fseek($fp, -2, SEEK_CUR);
        }
        fclose($fp);
        return $s;
    }

    public static function write($message){
        return file_put_contents('./config/sqlCache.txt', $message, FILE_APPEND);
    }

    public static function downloadMonthExcel($data,$headArr){
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
        foreach($headArr as $v){
            $colum = chr($key);
            $objPHPExcel->setActiveSheetIndex(0) ->setCellValue($colum.'1', $v);
            $key += 1;
        }
        $exc_colum = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
        $t = 0;
        foreach($data as $item) {
            $i = 0;
            $count = count($headArr);
            for(;$count > 0;$count--,$i ++){
                switch($exc_colum[$i]){
                    case 'A':
                        $value = $item['id'];
                        break;
                    case 'B':
                        $value = $item['beneficiary'];
                        break;
                    default:
                        $value = '';
                        break;
                }
                $objPHPExcel->getActiveSheet()->setCellValue($exc_colum[$i] . ($t + 2), $value);
            }
            $t++;
        }
        $fileName   =   date('Ymd') . 'Report';
        $objPHPExcel->getActiveSheet()->setTitle('Simple');
        $objPHPExcel->setActiveSheetIndex(0);
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
            ->setLastModifiedBy("Maarten Balliauw")
            ->setTitle("Office 2003 XLS Test Document")
            ->setSubject("Office 2003 XLS Test Document")
            ->setDescription("Test document for Office 2007 XLS, generated using PHP classes.")
            ->setKeywords("office 2003 openxml php")
            ->setCategory("Test result file");
        // Redirect output to a client��s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$fileName.'.xls"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }

    public  static function read($filename,$encode='utf-8'){
        Yii::$enableIncludePath = false;
        Yii::import('application.extensions.PHPExcel.PHPExcel', 1);
        $objReader = PHPExcel_IOFactory::createReader('Excel5');
        $objReader->setReadDataOnly(true);
        $objPHPExcel = $objReader->load($filename);
        $objWorksheet = $objPHPExcel->getActiveSheet();
        $highestRow = $objWorksheet->getHighestRow();
        $highestColumn = $objWorksheet->getHighestColumn();
        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
        $excelData = array();
        for ($row = 1; $row <= $highestRow; $row++) {
            for ($col = 0; $col < $highestColumnIndex; $col++) {
                $excelData[$row][] =(string)$objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
            }
        }
        return $excelData;
    }
}
