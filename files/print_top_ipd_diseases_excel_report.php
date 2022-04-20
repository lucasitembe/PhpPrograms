<?php
@session_start();
include("./includes/connection.php");
	//search for the top n diseases
@$fromDate =$_GET['fromDate'];
@$toDate=$_GET['toDate'];
@$bill_type = $_GET['bill_type'];
@$search_top_n_diseases= $_GET['search_top_n_diseases'];
@$Ward_ID=$_GET['Ward_ID'];
@$diagnosis_type=$_GET['diagnosis_type'];
$filterClinic = " ";
$filter =" ";
if($Ward_ID!='all'){
    //$filter=" AND c.Clinic_ID=$Clinic_ID ";
    //$filter=" WHERE Clinic_ID=$Ward_ID ";
    $filter=" AND Ward_ID=$Ward_ID ";
    $filterIn=" AND cl.Clinic_ID=$Clinic_ID ";
}
if($diagnosis_type!='all'){
    if($diagnosis_type=="differential"){
        $diagnosis_type="diferential_diagnosis";
    }else{
    $diagnosis_type=($diagnosis_type=="final")?"diagnosis":"provisional_diagnosis";
    }
    $filterDiagnosis=" AND wrd.diagnosis_type IN ('$diagnosis_type')";
}else{
    $filterDiagnosis=" AND wrd.diagnosis_type IN ('diagnosis','provisional_diagnosis','diferential_diagnosis')";
}
if(trim($search_top_n_diseases)){

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
                ->setCellValue('C1','ICD')
                ->setCellValue('D1','Quantity');

      $categoryRow=1;
        $i=2;

        //$filter = "  and dc.Disease_Consultation_Date_And_Time between '$fromDate' and '$toDate' ";
        $filterIn = "  and wd.Round_Disease_Date_And_Time between '$fromDate' and '$toDate' '";

        $sqlinpatient = "SELECT DISTINCT d.disease_name, d.disease_ID, d.disease_code FROM tbl_ward_round wr, tbl_disease d, tbl_ward_round_disease wrd WHERE d.disease_ID = wrd.disease_ID AND wr.Round_ID = wrd.Round_ID AND wrd.Round_Disease_Date_And_Time BETWEEN '$fromDate' AND '$toDate'  ORDER BY d.disease_ID ";

        //echo $sqloutpatient;exit;
        $result = mysqli_query($conn,$sqlinpatient) or die(mysqli_error($conn));
        $diseasesData=array();
        $sn=1;

    while ($row = mysqli_fetch_array($result)) {
      $no_diagnosis = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(wrd.disease_ID) AS amount FROM tbl_ward_round wr, tbl_ward_round_disease wrd, tbl_patient_registration pr WHERE wr.Round_ID=wrd.Round_ID AND pr.Registration_ID = wr.Registration_ID AND pr.Date_Of_Birth !='0000-00-00' $filterDiagnosis  AND wrd.disease_ID='" . $row['disease_ID'] . "'   AND wrd.Round_Disease_Date_And_Time BETWEEN '$fromDate' AND '$toDate'"))['amount'];

        if($no_diagnosis === 0)continue;
        array_push($diseasesData, array(
                        'final_quantity'=>number_format($no_diagnosis),
                        'disease_code'=>trim($row['disease_code']),
                        'disease_name'=>trim($row['disease_name'])
                    ));
    }
    array_multisort($diseasesData,SORT_DESC);
    //print_r($diseasesData);
    if(mysqli_num_rows(($result))){
		$top_diseases=(mysqli_num_rows($result)<$search_top_n_diseases)? mysqli_num_rows($result):$search_top_n_diseases;
        for ($index=0; $index<$top_diseases; $index++) {
			if($diseasesData[$i]['final_quantity'] == 0)continue;
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$i,  "".($sn)."")
                ->setCellValue('B'.$i, $diseasesData[$index]['disease_name'])
                ->setCellValue('C'.$i, $diseasesData[$index]['disease_code'])
                ->setCellValue('D'.$i, $diseasesData[$index]['final_quantity']);
                //  $objPHPExcel->getActiveSheet()->getRowDimension(''.$i.'')->setRowHeight(20);
                ++$i;
                ++$categoryRow;
				$sn++;
        }
    }

    // Rename worksheet
 $objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(20);

 $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(85);
 $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
 $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
//  $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(20);
 $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
 $objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
 $objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
 $objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
 $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15);
 $objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setSize(15);
 $objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setSize(15);
 $objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setSize(15);
 $objPHPExcel->getActiveSheet()->setTitle('Top '.$search_top_n_diseases.' IDP Diseases');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Top Diseases from '.date_format(date_create($fromDate),'d-m-Y g:iA').' to '.date_format(date_create($toDate),'d-m-Y g:iA').'.xls"');
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
}
?>
