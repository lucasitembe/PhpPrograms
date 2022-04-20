<?php
@session_start();
include("./includes/connection.php");
	$age_group = $_GET['age_group'];
	$disease_data = json_decode(base64_decode($_GET['disease_data']),true);
	$disease_ID = $disease_data['disease_ID'];
	$fromDate = $disease_data['fromDate'];
	$toDate = $disease_data['toDate'];
	$start_age = $disease_data['start_age'];
	$end_age = $disease_data['end_age'];
	$Clinic_ID = $disease_data['Clinic_ID'];
	$disease_name = $disease_data['disease_name'];
	$patient_type = $disease_data['patient_type'];
	$filter= ' ';
	$filterIn= ' ';
    $diagnosis_type = $disease_data['diagnosis_type'];
if($Clinic_ID!='all'){
    $filter=" AND c.Clinic_ID=$Clinic_ID ";
    $filterIn=" AND cl.Clinic_ID=$Clinic_ID ";
}
if($diagnosis_type!='all'){
    if($diagnosis_type=="differential")$diagnosis_type="diferential_diagnosis";
    if($diagnosis_type=="diagnosis")$diagnosis_type="diagnosis";
    if($diagnosis_type=="provisional_diagnosis")$diagnosis_type="provisional_diagnosis";
    
    $filterDiagnosis=" AND dc.diagnosis_type IN ('$diagnosis_type')";
}else{
    $filterDiagnosis=" AND dc.diagnosis_type IN ('diagnosis','provisional_diagnosis','diferential_diagnosis')";
}
	$age_group_selector=' ';
	if($age_group=="less"){
		$age_group_selector=' < '.$start_age;
		//if($patient_type=="Outpatient"){
		$select_patients=mysqli_query($conn,"select distinct pr.Registration_ID, pr.patient_name,pr.Phone_Number,pr.Region,pr.District,TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) as age, pr.Gender from tbl_disease_consultation dc, tbl_disease d,tbl_consultation c,tbl_patient_registration pr WHERE
                                    d.disease_ID = dc.disease_ID and
                                    c.consultation_ID = dc.consultation_ID and
                                    c.Registration_ID = pr.Registration_ID and
                                    d.disease_ID=$disease_ID  $filterDiagnosis $filter and c.Consultation_Date_And_Time BETWEEN '$fromDate' and '$toDate' and TIMESTAMPDIFF(YEAR,Date_Of_Birth,CURDATE()) < $start_age  ORDER BY TIMESTAMPDIFF(YEAR,Date_Of_Birth,CURDATE()), pr.patient_name ASC");
	//}
	// if($patient_type=="Inpatient"){
	// 	$select_patients=mysqli_query($conn,"select distinct pr.Registration_ID,pr.patient_name, TIMESTAMPDIFF(YEAR,Date_Of_Birth,CURDATE()) as age,pr.Gender from tbl_ward_round_disease wd, tbl_disease d,tbl_ward_round wr,tbl_patient_registration pr,tbl_consultation c,tbl_clinic cl where wr.consultation_ID=c.consultation_ID AND d.disease_ID = wd.disease_ID and wr.Round_ID = wd.Round_ID and wr.Registration_ID = pr.Registration_ID and diagnosis_type = 'diagnosis' and d.disease_ID=$disease_ID  and
 //  cl.Clinic_ID=c.Clinic_ID $filterIn and wd.Round_Disease_Date_And_Time between '$fromDate' and '$toDate' and TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE())    
 //            < $start_age ORDER BY TIMESTAMPDIFF(YEAR,Date_Of_Birth,CURDATE()) ASC");
	// }
}
		
	if($age_group=="greater"){
		$age_group_selector=' >= '.$end_age;
	//	if($patient_type=="Outpatient"){
		$select_patients=mysqli_query($conn,"select distinct pr.Registration_ID,pr.patient_name,pr.Phone_Number,pr.Region,pr.District,TIMESTAMPDIFF(YEAR,Date_Of_Birth,CURDATE()) as age, pr.Gender from tbl_disease_consultation dc, tbl_disease d,tbl_consultation c,tbl_patient_registration pr WHERE
                                    d.disease_ID = dc.disease_ID and
                                    c.consultation_ID = dc.consultation_ID and
                                    c.Registration_ID = pr.Registration_ID and
                                    d.disease_ID=$disease_ID $filterDiagnosis $filter and c.Consultation_Date_And_Time BETWEEN '$fromDate' and '$toDate' and TIMESTAMPDIFF(YEAR,Date_Of_Birth,CURDATE()) >= $end_age ORDER BY TIMESTAMPDIFF(YEAR,Date_Of_Birth,CURDATE()), pr.patient_name ASC");
		// }
		// if($patient_type=="Inpatient"){
		// 	$select_patients=mysqli_query($conn,"select distinct pr.Registration_ID,pr.patient_name, TIMESTAMPDIFF(YEAR,Date_Of_Birth,CURDATE()) as age,pr.Gender from tbl_ward_round_disease wd, tbl_disease d,tbl_ward_round wr,tbl_patient_registration pr,tbl_consultation c,tbl_clinic cl where wr.consultation_ID=c.consultation_ID AND d.disease_ID = wd.disease_ID and wr.Round_ID = wd.Round_ID and wr.Registration_ID = pr.Registration_ID and diagnosis_type = 'diagnosis' and d.disease_ID=$disease_ID  and
  // cl.Clinic_ID=c.Clinic_ID $filterIn and wd.Round_Disease_Date_And_Time between '$fromDate' and '$toDate' and TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE())	
		// 	>= $end_age ORDER BY TIMESTAMPDIFF(YEAR,Date_Of_Birth,CURDATE()) ASC");
		// }
	}
    /** Include PHPExcel */
    require_once 'PHPExcel-1.8.1/Classes/PHPExcel.php';

    // Create new PHPExcel object
    $objPHPExcel = new PHPExcel();

    // Set document properties
                //FIRST ROW DATA
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1',' Patients List From '.date_format(date_create($fromDate),'d-m-Y g:iA').' To '.date_format(date_create($toDate),'d-m-Y g:iA').' With Age '.$age_group_selector);
                //SECOND ROW DATA
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A2','Disease Name: '.$disease_name);
                //THIRD ROW DATA
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A3','SN')
                ->setCellValue('B3','Patient Name')
                ->setCellValue('C3','File No.')
                ->setCellValue('D3','Age')
                ->setCellValue('E3','Sex')
                ->setCellValue('F3','Location')
                ->setCellValue('G3','Contact');

    $rowCounter=3;
    $count=0;
    while ($row=mysqli_fetch_assoc($select_patients)) {
		$rowCounter++;
        $count++;
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$rowCounter,  "".$count."")
                ->setCellValue('B'.$rowCounter,  "".ucwords(strtolower($row['patient_name']))."")
                ->setCellValue('C'.$rowCounter,  "".$row['Registration_ID']."")
                ->setCellValue('D'.$rowCounter,  "".$row['age']."")
                ->setCellValue('E'.$rowCounter,  "".ucfirst($row['Gender'])."")
                ->setCellValue('F'.$rowCounter,  "".$row['District'].",".$row['Region']."")
                ->setCellValue('G'.$rowCounter,  "".$row['Phone_Number']."");
               
	}
 $worksheetTitle='Patient List';
 $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,'rotation'   => 0,'wrap'     => true));
 $objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,'rotation'   => 0,'wrap'     => true));
    
 $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:G1');
 $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A2:G2');
 $objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(20);

 $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
 $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(35);
 $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
 $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
 $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
 $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(35);
 $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
 
 $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(13);
 $objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setSize(13);
 $objPHPExcel->getActiveSheet()->getStyle('A3')->getFont()->setSize(12);
 $objPHPExcel->getActiveSheet()->getStyle('B3')->getFont()->setSize(12);
 $objPHPExcel->getActiveSheet()->getStyle('C3')->getFont()->setSize(12);
 $objPHPExcel->getActiveSheet()->getStyle('D3')->getFont()->setSize(12);
 $objPHPExcel->getActiveSheet()->getStyle('E3')->getFont()->setSize(12);
 $objPHPExcel->getActiveSheet()->getStyle('F3')->getFont()->setSize(12);
 $objPHPExcel->getActiveSheet()->getStyle('G3')->getFont()->setSize(12);
$objPHPExcel->getActiveSheet()->setTitle($worksheetTitle);

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$worksheetTitle.' report from '.date_format(date_create($fromDate),'d-m-Y g:iA').' to '.date_format(date_create($toDate),'d-m-Y g:iA').'.xls"');
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