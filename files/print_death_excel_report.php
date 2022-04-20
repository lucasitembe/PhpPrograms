<?php
@session_start();
include("./includes/connection.php");

$filter = '';
$filterIn = '';
$patient_type = '';
if (isset($_GET['fromDate']) && !empty($_GET['fromDate'])) {
    $fromDate = $_GET['fromDate'];
    $toDate = $_GET['toDate'];
    $start_age_death= $_GET['start_age_death'];
    $end_age_death = $_GET['end_age_death'];
	$death_ward=$_GET['death_ward'];
    $filterDeathWard=' ';
    if($death_ward!=='all'){
        $filterDeathWard =" AND Hospital_Ward_ID='$death_ward'";
    }
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
   			//FIRST ROW DATA
$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A1','Diagnosis')
            ->setCellValue('B1','ICD')
            ->setCellValue('C1','Number Of Death')
            ->setCellValue('I1','Total');
            //SECOND ROW DATA
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('C2','Age < '.$start_age_death)
            ->setCellValue('F2','Age >= '.$end_age_death);
            //THIRD ROW DATA
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('C3','M')
            ->setCellValue('D3','F')
            ->setCellValue('E3','T')
            ->setCellValue('F3','M')
            ->setCellValue('G3','F')
            ->setCellValue('H3','T');

 // Rename worksheet
            //FIRST ROW MERGE
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('C1:H1');
$objPHPExcel->getActiveSheet()->getStyle('C1:H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

			//SECOND ROW MERGE
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('C2:E2');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('F2:H2');
//$objPHPExcel->setActiveSheetIndex(0)->mergeCells('H2:J2');
			//Column merged
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:A3');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B1:B3');

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('I1:I3');
//POPULATE THE EXCEL START
$deathData=array();
$sn=1;
$grandTotal=0;
$i=4;
$diagnosisRow=4;
$total_less_age_male_death=0;
$total_less_age_female_death=0;
$total_greater_age_male_death=0;
$total_greater_age_female_death=0;
$disease_caused_death_select=mysqli_query($conn,"SELECT DISTINCT dcd.disease_name from tbl_admission ad, tbl_patient_registration pr, tbl_disease_caused_death dcd where pr.`Registration_ID`=ad.`Registration_ID` and ad.Admision_ID=dcd.Admision_ID and ad.death_date_time BETWEEN '$fromDate' AND '$toDate' group by dcd.disease_name
");
while($row=mysqli_fetch_assoc($disease_caused_death_select)){
	extract($row);
	$icd=($disease_name=='others')?"NIL":mysqli_fetch_assoc(mysqli_query($conn,"SELECT disease_code FROM tbl_disease WHERE disease_name='$disease_name'"))['disease_code'];
	//select less male
	$less_age_male_death=mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(dcd.disease_name) AS total_death FROM tbl_disease_caused_death dcd, tbl_patient_registration pr WHERE dcd.registration_ID=pr.registration_ID AND pr.Gender='Male' AND TIMESTAMPDIFF(YEAR,Date_Of_Birth,CURDATE()) < $start_age_death AND dcd.disease_name='$disease_name'"))['total_death'];
	//select less female
	$less_age_female_death=mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(dcd.disease_name) AS total_death FROM tbl_disease_caused_death dcd, tbl_patient_registration pr WHERE dcd.registration_ID=pr.registration_ID AND pr.Gender='Female' AND TIMESTAMPDIFF(YEAR,Date_Of_Birth,CURDATE()) < $start_age_death AND dcd.disease_name='$disease_name'"))['total_death'];
	//select greater male
	$greater_age_male_death=mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(dcd.disease_name) AS total_death FROM tbl_disease_caused_death dcd, tbl_patient_registration pr WHERE dcd.registration_ID=pr.registration_ID AND pr.Gender='Male' AND TIMESTAMPDIFF(YEAR,Date_Of_Birth,CURDATE()) >= $end_age_death AND dcd.disease_name='$disease_name'"))['total_death'];
	//select greater female
	$greater_age_female_death=mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(dcd.disease_name) AS total_death FROM tbl_disease_caused_death dcd, tbl_patient_registration pr WHERE dcd.registration_ID=pr.registration_ID AND pr.Gender='Female' AND TIMESTAMPDIFF(YEAR,Date_Of_Birth,CURDATE()) >= $end_age_death AND dcd.disease_name='$disease_name'"))['total_death'];
	$subTotal=$less_age_male_death+$less_age_female_death+$greater_age_male_death+$greater_age_female_death;
	if($subTotal==0)continue;
	array_push($deathData,array(
	'disease_death_total'=>$subTotal,
	'disease_caused_death'=>$disease_name,
	'icd'=>$icd,
	'less_age_male_death'=>$less_age_male_death,
	'less_age_female_death'=>$less_age_female_death,
	'greater_age_male_death'=>$greater_age_male_death,
	'greater_age_female_death'=>$greater_age_female_death
	));
	$total_less_age_male_death+=$less_age_male_death;
	$total_less_age_female_death+=$less_age_female_death;
	$total_greater_age_male_death+=$greater_age_male_death;
	$total_greater_age_female_death+=$greater_age_female_death;
	$grandTotal+=$subTotal;
}
array_multisort($deathData,SORT_DESC);
	foreach ($deathData as $key => $value){
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A'.$i, $value['disease_caused_death'])
					->setCellValue('B'.$i, $value['icd'])
					->setCellValue('C'.$i, $value['less_age_male_death'])
					->setCellValue('D'.$i, $value['less_age_female_death'])
					->setCellValue('E'.$i, ($value['less_age_male_death']+$value['less_age_female_death']))
					->setCellValue('F'.$i, $value['greater_age_male_death'])
					->setCellValue('G'.$i, $value['greater_age_female_death'])
					->setCellValue('H'.$i, ($value['greater_age_male_death']+$value['greater_age_female_death']))
					->setCellValue('I'.$i, $value['disease_death_total']);
    
                //  $objPHPExcel->getActiveSheet()->getRowDimension(''.$i.'')->setRowHeight(20);
        ++$i;
        ++$diagnosisRow;
		++$sn;
	}
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$diagnosisRow,  "Total Diagnosis")
            ->setCellValue('C'.$diagnosisRow,  "".$total_less_age_male_death."")
            ->setCellValue('D'.$diagnosisRow,  "".$total_less_age_female_death."")
            ->setCellValue('E'.$diagnosisRow,  "".($total_less_age_male_death+$total_less_age_female_death)."")
            ->setCellValue('F'.$diagnosisRow,  "".$total_greater_age_male_death."")
            ->setCellValue('G'.$diagnosisRow,  "".$total_greater_age_female_death."")
            ->setCellValue('H'.$diagnosisRow,  "".($total_greater_age_male_death+$total_greater_age_female_death)."")
            ->setCellValue('I'.$diagnosisRow,  "".$grandTotal."");
		//POPULATE THE EXCEL ENDS
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$diagnosisRow.':'.'B'.$diagnosisRow);
		//Formatting the title cells
$excelAlignment=array(
	'1'=>'A1','2'=>'I1','3'=>'C2','4'=>'F2','5'=>'C3','6'=>'D3','7'=>'E3','8'=>'F3','9'=>'G3','10'=>'H3','11'=>'B1'
);
for($i=1; $i<(count($excelAlignment)+1);$i++){
	$objPHPExcel->getActiveSheet()->getStyle($excelAlignment[$i])->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,'rotation'   => 0,'wrap'     => true));
}
	//Formatting the data cells
$dataExcelAlignment=array(
    '1'=>'C','2'=>'D','3'=>'E','4'=>'F','5'=>'G','6'=>'H','7'=>'I','8'=>'B'
);
for($i=1;$i<=7;$i++){
	$objPHPExcel->getActiveSheet()->getStyle($dataExcelAlignment[$i].$diagnosisRow)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,'rotation'   => 0,'wrap'     => true));
}
for($i=4;$i<$diagnosisRow;$i++){
    for($j=1;$j<(count($dataExcelAlignment)+1);$j++){
        $data=$dataExcelAlignment[$j].$i;
		$objPHPExcel->getActiveSheet()->getStyle($data)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,'rotation'   => 0,'wrap'     => true));
    }
}


$worksheetTitle='Death';
$objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(20);

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(70);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
 
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
$objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
$objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
$objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
$objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
$objPHPExcel->getActiveSheet()->getStyle('I1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(12);
$objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setSize(12);
$objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setSize(12);
$objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setSize(12);
$objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->setSize(12);
$objPHPExcel->getActiveSheet()->setTitle("COMMON CAUSES OF DEATH ");

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$worksheetTitle.' Report from '.date_format(date_create($fromDate),'d-m-Y g:iA').'_to_'.date_format(date_create($toDate),'d-m-Y g:iA').'.xls"');
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