<?php
@session_start();
include("./includes/connection.php");

$filter = '';
$filterIn = '';
$admission_cause = '';
if (isset($_GET['fromDate']) && !empty($_GET['fromDate']) && isset($_GET['toDate']) && !empty($_GET['toDate'])) {
    $fromDate = mysqli_real_escape_string($conn,$_GET['fromDate']);
    $toDate = mysqli_real_escape_string($conn,$_GET['toDate']);
    $admission_cause = mysqli_real_escape_string($conn,$_GET['admission_cause']);
    $start_age= mysqli_real_escape_string($conn,$_GET['start_age']);
    $end_age = mysqli_real_escape_string($conn,$_GET['end_age']);
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
                ->setCellValue('B1','Number Of Patients')
                ->setCellValue('N1','Total');
                //SECOND ROW DATA
$objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('B2','Age < '.$start_age)
                ->setCellValue('H2','Age >= '.$end_age)
                ->setCellValue('N2','Miaka 60+');
                //THIRD ROW DATA
$objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('B3','New')
                ->setCellValue('E3','Return')
                ->setCellValue('H3','New')
                ->setCellValue('K3','Return');
                //FORTH ROW DATA
$objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('B4','M')
                ->setCellValue('C4','F')
                ->setCellValue('D4','T')
                ->setCellValue('E4','M')
                ->setCellValue('F4','F')
                ->setCellValue('G4','T')
                ->setCellValue('H4','M')
                ->setCellValue('I4','F')
                ->setCellValue('J4','T')
                ->setCellValue('K4','M')
                ->setCellValue('L4','F')
                ->setCellValue('M4','T')
                ->setCellValue('N4','M');

 // Rename worksheet
                //FIRST ROW MERGE
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B1:M1');
$objPHPExcel->getActiveSheet()->getStyle('B1:M1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

				//SECOND ROW MERGE
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B2:G2');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('H2:M2');
//$objPHPExcel->setActiveSheetIndex(0)->mergeCells('N2:S2');
				//THIRD ROW MERGE
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B3:D3');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('E3:G3');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('H3:J3');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('K3:M3');
//$objPHPExcel->setActiveSheetIndex(0)->mergeCells('N3:P3');
//$objPHPExcel->setActiveSheetIndex(0)->mergeCells('Q3:S3');
				//Column merged
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:A4');

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('N1:N4');
$excelAlignment=array(
		'1'=>'A1','2'=>'N1','3'=>'B2','4'=>'H2','5'=>'N2','6'=>'B3','7'=>'E3','8'=>'H3','9'=>'K3','10'=>'N3','11'=>'Q3','12'=>'B4','13'=>'C4','14'=>'D4','15'=>'E4','16'=>'F4','17'=>'G4','18'=>'H4','19'=>'I4','20'=>'J4','21'=>'K4','22'=>'L4','23'=>'M4','24'=>'N4','25'=>'O4','26'=>'O4','27'=>'Q4','28'=>'R4','29'=>'S4',
	);
	for($i=1; $i<(count($excelAlignment)+1);$i++){
		$objPHPExcel->getActiveSheet()->getStyle($excelAlignment[$i])->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,'rotation'   => 0,'wrap'     => true));
	}


 $worksheetTitle='Admission';
 $objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(20);

 $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
 $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(15);
 
 $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
 $objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
 $objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
 $objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
 $objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
 $objPHPExcel->getActiveSheet()->getStyle('N1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
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
header('Content-Disposition: attachment;filename="admission_report_from_'.$fromDate.'_to_'.$toDate.'.xls"');
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