<?php
@session_start();
include("./includes/connection.php");

$filter = '';
$filterIn = '';
$patient_type = '';
if (isset($_GET['fromDate']) && !empty($_GET['fromDate'])) {
    $fromDate = $_GET['fromDate'];
    $toDate = $_GET['toDate'];
    $bill_type = $_GET['bill_type'];
    $start_age= $_GET['start_age'];
    $end_age = $_GET['end_age'];
    
    $Disease_Name = ' ';
    $filter = "  and dc.Disease_Consultation_Date_And_Time between '$fromDate' and '$toDate' and  TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) BETWEEN '$start_age' AND '$end_age'";
    $filterIn = "  and wd.Round_Disease_Date_And_Time between '$fromDate' and '$toDate' and  TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) BETWEEN '$start_age' AND '$end_age'";
   /* if (!empty($Disease_Name)) {
        $filter .=" and d.disease_name like '%$Disease_Name%'";
        $filterIn .=" and d.disease_name like '%$Disease_Name%'";
    }
    
    $patient_type = '<b>Patient Type:</b>' . $bill_type;

    if ($bill_type != 'All') {
        $patient_type = '<b>Patient Type:</b>' . $bill_type;
    }*/

}
/** Error reporting */
    error_reporting(E_ALL);
    ini_set('display_errors', TRUE);
    ini_set('display_startup_errors', TRUE);
    date_default_timezone_set('Africa/Dar_es_Salaam');

    if (PHP_SAPI == 'cli')
        die('This example should only be run from a Web Browser');

    /** Include PHPExcel */
    require_once 'PHPExcel-1.8.1/Classes/PHPExcel.php';

    // Create new PHPExcel object
    $objPHPExcel = new PHPExcel();

    // Set document properties
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1','SN')
                ->setCellValue('B1','Disease Name')
                ->setCellValue('C1','Disease Code')
                ->setCellValue('D1','Provisional Quantity')
                ->setCellValue('E1','Final Quantity');

        $categoryRow=1;
        $i=2;

        $temp = 1;

        $worksheetTitle='';

if ($bill_type == 'All') {
    $worksheetTitle='Inpatient & Outpatient';
    //OUTPATIENT

    $sqloutpatient = "select d.disease_name, d.disease_ID, d.disease_code
                                    from tbl_disease_consultation dc, tbl_disease d,tbl_consultation c,tbl_patient_registration pr where
                                    d.disease_ID = dc.disease_ID and
                                    c.consultation_ID = dc.consultation_ID and
                                    c.Registration_ID = pr.Registration_ID  and
                                    diagnosis_type IN ('diagnosis','provisional_diagnosis') $filter 
                                    group by d.disease_ID order by d.disease_name";

        //echo $sqloutpatient;exit;
        $result = mysqli_query($conn,$sqloutpatient) or die(mysqli_error($conn));
        $diseasesData=array();

        while ($row = mysqli_fetch_array($result)) {
            //echo $sqloutpatient;exit;
            $no_diagnosis = mysqli_fetch_assoc(mysqli_query($conn,"select count(dc.disease_ID) as amount
                                    from tbl_disease_consultation dc, tbl_disease d,tbl_consultation c,tbl_patient_registration pr where
                                    d.disease_ID = dc.disease_ID and
                                    c.consultation_ID = dc.consultation_ID and
                                    c.Registration_ID = pr.Registration_ID and 
                                    diagnosis_type = 'diagnosis' and
                                    d.disease_ID='" . $row['disease_ID'] . "' $filter
                                    group by d.disease_ID order by d.disease_name"))['amount'] +
                    mysqli_fetch_assoc(mysqli_query($conn,"select count(wd.disease_ID) as amount
                                    from tbl_ward_round_disease wd, tbl_disease d,tbl_ward_round wr,tbl_patient_registration pr where
                                    d.disease_ID = wd.disease_ID and
                                    wr.Round_ID = wd.Round_ID and
                                    wr.Registration_ID = pr.Registration_ID and
                                    diagnosis_type = 'diagnosis' and
                                    d.disease_ID='" . $row['disease_ID'] . "' $filterIn
                                    group by d.disease_ID order by d.disease_name"))['amount'];
            
            
            $no_provisional_diagnosis = mysqli_fetch_assoc(mysqli_query($conn,"select count(dc.disease_ID) as amount
                                    from tbl_disease_consultation dc, tbl_disease d,tbl_consultation c,tbl_patient_registration pr where
                                    d.disease_ID = dc.disease_ID and
                                    c.consultation_ID = dc.consultation_ID and
                                    c.Registration_ID = pr.Registration_ID and 
                                    diagnosis_type = 'provisional_diagnosis' and
                                    d.disease_ID='" . $row['disease_ID'] . "' $filter
                                    group by d.disease_ID order by d.disease_name"))['amount'] +
                    mysqli_fetch_assoc(mysqli_query($conn,"select count(wd.disease_ID) as amount
                                    from tbl_ward_round_disease wd, tbl_disease d,tbl_ward_round wr,tbl_patient_registration pr where
                                    d.disease_ID = wd.disease_ID and
                                    wr.Round_ID = wd.Round_ID and
                                    wr.Registration_ID = pr.Registration_ID and
                                    diagnosis_type = 'provisional_diagnosis' and
                                    d.disease_ID='" . $row['disease_ID'] . "' $filterIn
                                    group by d.disease_ID order by d.disease_name"))['amount'];

            if (empty($no_diagnosis)) {
                $no_diagnosis = 0;
            } if (empty($no_provisional_diagnosis)) {
                $no_provisional_diagnosis = 0;
            }

           //echo $i.' : '.$row['disease_name'].' --:-- '.$row['disease_code'].' --:-- '.$no_provisional_diagnosis.' --:-- '.$no_diagnosis.'<br>';

         /*
          $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$i,  "".($temp)."")
                ->setCellValue('B'.$i, trim($row['disease_name']))
                ->setCellValue('C'.$i, trim($row['disease_code']))
                ->setCellValue('D'.$i, "".number_format($no_provisional_diagnosis)."")
                ->setCellValue('E'.$i, "".number_format($no_diagnosis)."");
    
                //  $objPHPExcel->getActiveSheet()->getRowDimension(''.$i.'')->setRowHeight(20);
                ++$i;
                
                
        $temp++;

        */
        array_push($diseasesData, array(
                        'final_quantity'=>number_format($no_diagnosis),
                        'provisional_quantity'=>number_format($no_provisional_diagnosis),
                        'disease_code'=>trim($row['disease_code']),
                        'disease_name'=>trim($row['disease_name'])
                    ));
    }


//END OUTPATIENT
    //INPATIENT

     $sqlinpatient = "select d.disease_name, d.disease_ID, d.disease_code
                                    from tbl_ward_round_disease wd, tbl_disease d,tbl_ward_round wr,tbl_patient_registration pr where
                                    d.disease_ID = wd.disease_ID and
                                    wr.Round_ID = wd.Round_ID and
                                    wr.Registration_ID = pr.Registration_ID and
                                    d.disease_ID NOT IN (select  d.disease_ID from tbl_disease_consultation dc, tbl_disease d where
                                    d.disease_ID = dc.disease_ID and
                                    diagnosis_type IN ('diagnosis','provisional_diagnosis') $filter 
                                    group by d.disease_ID order by d.disease_name) and
                                    diagnosis_type IN ('diagnosis','provisional_diagnosis') $filterIn 
                                    group by d.disease_ID order by d.disease_name";

    //echo $sqloutpatient;exit;
    $result = mysqli_query($conn,$sqlinpatient) or die(mysqli_error($conn));

    while ($row = mysqli_fetch_array($result)) {

        $no_diagnosis = mysqli_fetch_assoc(mysqli_query($conn,"select count(dc.disease_ID) as amount
                                    from tbl_disease_consultation dc, tbl_disease d,tbl_consultation c,tbl_patient_registration pr where
                                    d.disease_ID = dc.disease_ID and
                                    c.consultation_ID = dc.consultation_ID and
                                    c.Registration_ID = pr.Registration_ID and 
                                    diagnosis_type = 'diagnosis' and
                                    d.disease_ID='" . $row['disease_ID'] . "' $filter
                                    group by d.disease_ID order by d.disease_name"))['amount'] +
                mysqli_fetch_assoc(mysqli_query($conn,"select count(wd.disease_ID) as amount
                                    from tbl_ward_round_disease wd, tbl_disease d ,tbl_ward_round wr,tbl_patient_registration pr where
                                    d.disease_ID = wd.disease_ID and
                                    wr.Round_ID = wd.Round_ID and
                                    wr.Registration_ID = pr.Registration_ID and
                                    diagnosis_type = 'diagnosis' and
                                    d.disease_ID='" . $row['disease_ID'] . "' $filterIn
                                    group by d.disease_ID order by d.disease_name"))['amount'];
        
        
        $no_provisional_diagnosis = mysqli_fetch_assoc(mysqli_query($conn,"select count(dc.disease_ID) as amount
                                    from tbl_disease_consultation dc, tbl_disease d,tbl_consultation c,tbl_patient_registration pr where
                                    d.disease_ID = dc.disease_ID and
                                    c.consultation_ID = dc.consultation_ID and
                                    c.Registration_ID = pr.Registration_ID and 
                                    diagnosis_type = 'provisional_diagnosis' and
                                    d.disease_ID='" . $row['disease_ID'] . "' $filter
                                    group by d.disease_ID order by d.disease_name"))['amount'] +
                mysqli_fetch_assoc(mysqli_query($conn,"select count(wd.disease_ID) as amount
                                    from tbl_ward_round_disease wd, tbl_disease d,tbl_ward_round wr,tbl_patient_registration pr where
                                    d.disease_ID = wd.disease_ID and
                                    wr.Round_ID = wd.Round_ID and
                                    wr.Registration_ID = pr.Registration_ID and
                                    diagnosis_type = 'provisional_diagnosis' and
                                    d.disease_ID='" . $row['disease_ID'] . "' $filterIn
                                    group by d.disease_ID order by d.disease_name"))['amount'];
        

        if (empty($no_diagnosis)) {
            $no_diagnosis = 0;
        } if (empty($no_provisional_diagnosis)) {
            $no_provisional_diagnosis = 0;
        }

        /*
          $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$i,  "".($temp)."")
                ->setCellValue('B'.$i, trim($row['disease_name']))
                ->setCellValue('C'.$i, trim($row['disease_code']))
                ->setCellValue('D'.$i, "".number_format($no_provisional_diagnosis)."")
                ->setCellValue('E'.$i, "".number_format($no_diagnosis)."");
    
                //  $objPHPExcel->getActiveSheet()->getRowDimension(''.$i.'')->setRowHeight(20);
                ++$i;
                
        $temp++;
        */

        array_push($diseasesData, array(
                        'final_quantity'=>number_format($no_diagnosis),
                        'provisional_quantity'=>number_format($no_provisional_diagnosis),
                        'disease_code'=>trim($row['disease_code']),
                        'disease_name'=>trim($row['disease_name'])
                    ));

    }

//END INPATIENT

array_multisort($diseasesData,SORT_DESC);

foreach ($diseasesData as $key => $value) {
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$i,  "".($temp)."")
                ->setCellValue('B'.$i, $value['disease_name'])
                ->setCellValue('C'.$i, $value['disease_code'])
                ->setCellValue('D'.$i, $value['provisional_quantity'])
                ->setCellValue('E'.$i, $value['final_quantity']);
    
                //  $objPHPExcel->getActiveSheet()->getRowDimension(''.$i.'')->setRowHeight(20);
                ++$i;

        $temp++;
    }


} else if ($bill_type == 'Outpatient') {
    //OUTPATIENT
    $worksheetTitle='Outpatient';
    $sqloutpatient = "select d.disease_name, d.disease_ID, d.disease_code
                                    from tbl_disease_consultation dc, tbl_disease d, tbl_consultation c,tbl_patient_registration pr where
                                    d.disease_ID = dc.disease_ID and
                                    c.consultation_ID = dc.consultation_ID and
                                    c.Registration_ID = pr.Registration_ID and 
                                    diagnosis_type IN ('diagnosis','provisional_diagnosis') $filter 
                                    group by d.disease_ID order by d.disease_name";

    //echo $sqloutpatient;exit;
    $result = mysqli_query($conn,$sqloutpatient) or die(mysqli_error($conn));
    $diseasesData=array();

    while ($row = mysqli_fetch_array($result)) {

        $no_diagnosis = mysqli_fetch_assoc(mysqli_query($conn,"select count(dc.disease_ID) as amount
                                    from tbl_disease_consultation dc, tbl_disease d,tbl_consultation c,tbl_patient_registration pr where
                                    d.disease_ID = dc.disease_ID and
                                    c.consultation_ID = dc.consultation_ID and
                                    c.Registration_ID = pr.Registration_ID and 
                                    diagnosis_type = 'diagnosis' and
                                    d.disease_ID='" . $row['disease_ID'] . "' $filter
                                    group by d.disease_ID order by d.disease_name"))['amount'];
        $no_provisional_diagnosis = mysqli_fetch_assoc(mysqli_query($conn,"select count(dc.disease_ID) as amount
                                    from tbl_disease_consultation dc, tbl_disease d,tbl_consultation c,tbl_patient_registration pr where
                                    d.disease_ID = dc.disease_ID and
                                    c.consultation_ID = dc.consultation_ID and
                                    c.Registration_ID = pr.Registration_ID and 
                                    diagnosis_type = 'provisional_diagnosis' and
                                    d.disease_ID='" . $row['disease_ID'] . "' $filter
                                    group by d.disease_ID order by d.disease_name"))['amount'];

        if (empty($no_diagnosis)) {
            $no_diagnosis = 0;
        } if (empty($no_provisional_diagnosis)) {
            $no_provisional_diagnosis = 0;
        }

                array_push($diseasesData, array(
                        'final_quantity'=>number_format($no_diagnosis),
                        'provisional_quantity'=>number_format($no_provisional_diagnosis),
                        'disease_code'=>trim($row['disease_code']),
                        'disease_name'=>trim($row['disease_name'])
                    ));
    }

    array_multisort($diseasesData,SORT_DESC);
    foreach ($diseasesData as $key => $value) {
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$i,  "".($temp)."")
                ->setCellValue('B'.$i, $value['disease_name'])
                ->setCellValue('C'.$i, $value['disease_code'])
                ->setCellValue('D'.$i, $value['provisional_quantity'])
                ->setCellValue('E'.$i, $value['final_quantity']);
    
                //  $objPHPExcel->getActiveSheet()->getRowDimension(''.$i.'')->setRowHeight(20);
        ++$i;
        ++$temp;
    }

//END OUTPATIENT
} else if ($bill_type == 'Inpatient') {
    //INPATIENT
$worksheetTitle="Inpatient";
   $sqlinpatient = "select d.disease_name, d.disease_ID, d.disease_code
                                    from tbl_ward_round_disease wd, tbl_disease d ,tbl_ward_round wr,tbl_patient_registration pr where
                                    d.disease_ID = wd.disease_ID and
                                    wr.Round_ID = wd.Round_ID and
                                    wr.Registration_ID = pr.Registration_ID and
                                    diagnosis_type IN ('diagnosis','provisional_diagnosis') $filterIn 
                                    group by d.disease_ID order by d.disease_name";

    //echo $sqloutpatient;exit;
    $result = mysqli_query($conn,$sqlinpatient) or die(mysqli_error($conn));
    $diseasesData=array();

    while ($row = mysqli_fetch_array($result)) {

        $no_diagnosis = mysqli_fetch_assoc(mysqli_query($conn,"select count(wd.disease_ID) as amount
                                    from tbl_ward_round_disease wd, tbl_disease d,tbl_ward_round wr,tbl_patient_registration pr where
                                    d.disease_ID = wd.disease_ID and
                                    wr.Round_ID = wd.Round_ID and
                                    wr.Registration_ID = pr.Registration_ID and
                                    diagnosis_type = 'diagnosis' and
                                    d.disease_ID='" . $row['disease_ID'] . "' $filterIn
                                    group by d.disease_ID order by d.disease_name"))['amount'];
        $no_provisional_diagnosis = mysqli_fetch_assoc(mysqli_query($conn,"select count(wd.disease_ID) as amount
                                    from tbl_ward_round_disease wd, tbl_disease d,tbl_ward_round wr,tbl_patient_registration pr where
                                    d.disease_ID = wd.disease_ID and
                                    wr.Round_ID = wd.Round_ID and
                                    wr.Registration_ID = pr.Registration_ID and
                                    diagnosis_type = 'provisional_diagnosis' and
                                    d.disease_ID='" . $row['disease_ID'] . "' $filterIn
                                    group by d.disease_ID order by d.disease_name"))['amount'];

        if (empty($no_diagnosis)) {
            $no_diagnosis = 0;
        } if (empty($no_provisional_diagnosis)) {
            $no_provisional_diagnosis = 0;
        }
            /*
                 $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$i,  "".($temp)."")
                ->setCellValue('B'.$i, trim($row['disease_name']))
                ->setCellValue('C'.$i, trim($row['disease_code']))
                ->setCellValue('D'.$i, "".number_format($no_provisional_diagnosis)."")
                ->setCellValue('E'.$i, "".number_format($no_diagnosis)."");
                
                //  $objPHPExcel->getActiveSheet()->getRowDimension(''.$i.'')->setRowHeight(20);
                ++$i;
                $temp++;
            */

        array_push($diseasesData, array(
                        'final_quantity'=>number_format($no_diagnosis),
                        'provisional_quantity'=>number_format($no_provisional_diagnosis),
                        'disease_code'=>trim($row['disease_code']),
                        'disease_name'=>trim($row['disease_name'])
                    ));
    }
    array_multisort($diseasesData,SORT_DESC);
    foreach ($diseasesData as $key => $value) {
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$i,  "".($temp)."")
                ->setCellValue('B'.$i, $value['disease_name'])
                ->setCellValue('C'.$i, $value['disease_code'])
                ->setCellValue('D'.$i, $value['provisional_quantity'])
                ->setCellValue('E'.$i, $value['final_quantity']);
    
                //  $objPHPExcel->getActiveSheet()->getRowDimension(''.$i.'')->setRowHeight(20);
        ++$i;
        ++$temp;
    }

//END INPATIENT
}
 // Rename worksheet
 $objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(20);

 $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(70);
 $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
 $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
 $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
//  $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(20);
 $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
 $objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
 $objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
 $objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
 $objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
 $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(12);
 $objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setSize(12);
 $objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setSize(12);
 $objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setSize(12);
 $objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->setSize(12);
$objPHPExcel->getActiveSheet()->setTitle($worksheetTitle);

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="diseases_report_from_'.$fromDate.'_to_'.$toDate.'.xls"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>
