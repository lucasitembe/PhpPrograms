<?php
include("./includes/connection.php");

$filter = '';
$filterIn = '';
$filterDiagnosis= ' ';
$admission_cause = ' ';
if (isset($_GET['fromDate']) && !empty($_GET['fromDate']) && isset($_GET['toDate']) && !empty($_GET['toDate'])) {
    $fromDate = mysqli_real_escape_string($conn,$_GET['fromDate']);
    $toDate = mysqli_real_escape_string($conn,$_GET['toDate']);
    //$admission_cause = mysqli_real_escape_string($conn,$_GET['admission_cause']);
    $start_age = mysqli_real_escape_string($conn,$_GET['start_age']);
    $end_age = mysqli_real_escape_string($conn,$_GET['end_age']);
    $Disease_Cat_Id = mysqli_real_escape_string($conn,$_GET['Disease_Cat_Id']);

    $Disease_Cat_Id=$_GET['Disease_Cat_Id'];
    $diagnosis_type=$_GET['diagnosis_type'];
    $report_from=$_GET['From'];
}

$filterDiseaseCategory=" ";
    if($Disease_Cat_Id!='all'){
        $filterDiseaseCategory=" AND dc.disease_category_ID=$Disease_Cat_Id ";
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
                ->setCellValue('H1','Total');
                //SECOND ROW DATA
$objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('B2','Age < '.$start_age)
                ->setCellValue('E2','Age >= '.$end_age);
                //THIRD ROW DATA
$objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('B3','M')
                ->setCellValue('C3','F')
                ->setCellValue('D3','T')
                ->setCellValue('E3','M')
                ->setCellValue('F3','F')
                ->setCellValue('G3','T');

 // Rename worksheet
                //FIRST ROW MERGE
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B1:G1');
$objPHPExcel->getActiveSheet()->getStyle('B1:G1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

				//SECOND ROW MERGE
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B2:D2');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('E2:G2');
//$objPHPExcel->setActiveSheetIndex(0)->mergeCells('H2:J2');
				//Column merged
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:A3');

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('H1:H3');
$excelAlignment=array(
		'1'=>'A1','2'=>'H1','3'=>'B2','4'=>'E2','5'=>'B3','6'=>'C3','7'=>'D3','8'=>'E3','9'=>'F3','10'=>'G3'
	);
	for($i=1; $i<(count($excelAlignment)+1);$i++){
		$objPHPExcel->getActiveSheet()->getStyle($excelAlignment[$i])->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,'rotation'   => 0,'wrap'     => true));
	}
    //Feeding the data start
    $diagnosisRow=4;
    //Total counts
    $total_less_male_count=0;
    $total_less_female_count=0;
    $total_greater_male_count=0;
    $total_greater_female_count=0;
    $grand_total=0;
    if($report_from == 'Outpatient'){
      $Clinic_ID = mysqli_real_escape_string($conn,$_GET['Clinic_ID']);
      if($Clinic_ID!='all'){
          //$filter=" AND c.Clinic_ID=$Clinic_ID ";
          $filter=" AND  Clinic_ID=$Clinic_ID ";
          $filterIn=" AND cl.Clinic_ID=$Clinic_ID ";
      }
      if($diagnosis_type!='all'){
          if($diagnosis_type=="differential"){
              $diagnosis_type="diferential_diagnosis";
          }else{
          $diagnosis_type=($diagnosis_type=="final")?"diagnosis":"provisional_diagnosis";
          }
          $filterDiagnosis=" AND dc.diagnosis_type IN ('$diagnosis_type')";
      }else{
          $filterDiagnosis=" AND dc.diagnosis_type IN ('diagnosis','provisional_diagnosis','diferential_diagnosis')";
      }

    $select_diagnosis=mysqli_query($conn,"SELECT DISTINCT d.disease_ID, d.disease_name FROM tbl_disease d, tbl_disease_consultation dco, tbl_disease_category dc,tbl_disease_subcategory ds WHERE dc.disease_category_ID=ds.disease_category_ID AND dco.disease_ID=d.disease_ID AND d.subcategory_ID=ds.subcategory_ID AND dco.Disease_Consultation_Date_And_Time  BETWEEN '$fromDate' AND '$toDate' $filterDiseaseCategory ");
    while ($row=mysqli_fetch_assoc($select_diagnosis)){
        $disease_name=$row['disease_name'];
        $disease_ID=$row['disease_ID'];
        $less_male_count=0;
        $less_female_count=0;
        $greater_male_count=0;
        $greater_female_count=0;

        /************************* diagosis attendance starts*************************/
        $query_result=mysqli_query($conn," SELECT (CASE WHEN (TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) >= $end_age AND pr.Gender = 'male' ) THEN 'greator_male' WHEN (TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) < $start_age AND pr.Gender = 'male' ) THEN 'less_male' WHEN (TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) >= $end_age AND pr.Gender = 'female' ) THEN 'greator_female' WHEN (TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) < $start_age AND pr.Gender = 'female' ) THEN 'less_female' END)AS sex FROM tbl_disease_consultation dc, tbl_consultation c, tbl_patient_registration pr WHERE c.consultation_ID = dc.consultation_ID AND c.Registration_ID = pr.Registration_ID AND dc.disease_ID=$disease_ID  $filterDiagnosis $filter AND c.Consultation_Date_And_Time BETWEEN '$fromDate' and '$toDate' ") or die(mysqli_query($conn,));
        while($result_data=mysqli_fetch_assoc($query_result)){
            // male attendance age less than  start_age
            if($result_data['sex'] =='less_male'){$less_male_count++;}
            // female attendance age less than  start_age
            if($result_data['sex'] =='less_female'){$less_female_count++;}
            // male attendance age greater than  end_age
            if($result_data['sex'] =='greator_male'){$greater_male_count++;}
            // female attendance age greater than  end_age
            if($result_data['sex'] =='greator_female'){$greater_female_count++;}
        }

		$subtotal=$less_male_count+$less_female_count+$greater_male_count+$greater_female_count;
		if($subtotal===0){continue;}

        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$diagnosisRow,  "".$disease_name."")
                ->setCellValue('B'.$diagnosisRow,  "".$less_male_count."")
                ->setCellValue('C'.$diagnosisRow,  "".$less_female_count."")
                ->setCellValue('D'.$diagnosisRow,  "".($less_male_count+$less_female_count)."")
                ->setCellValue('E'.$diagnosisRow,  "".$greater_male_count."")
                ->setCellValue('F'.$diagnosisRow,  "".$greater_female_count."")
                ->setCellValue('G'.$diagnosisRow,  "".($greater_male_count+$greater_female_count)."")
                ->setCellValue('H'.$diagnosisRow,  "".($less_male_count+$less_female_count+$greater_male_count+$greater_female_count)."");
                $diagnosisRow++;
                $total_less_male_count+=$less_male_count;
                $total_less_female_count+=$less_female_count;
                $total_greater_male_count+=$greater_male_count;
                $total_greater_female_count+=$greater_female_count;
        }
}
if($report_from == 'Inpatient'){
  $Ward_ID = mysqli_real_escape_string($conn,$_GET['Ward_ID']);
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
// if($departmental_clinic != ''){
//   $filter_departmental_clinic=" AND ad.Hospital_Ward_ID= $departmental_clinic ";
// }

  $num_count=1;
  $select_diagnosis=mysqli_query($conn,"SELECT DISTINCT d.disease_ID, d.disease_name FROM tbl_disease d,tbl_ward_round_disease wrd , tbl_disease_category dc,tbl_disease_subcategory ds WHERE dc.disease_category_ID=ds.disease_category_ID AND wrd.disease_ID=d.disease_ID AND d.subcategory_ID=ds.subcategory_ID AND wrd.Round_Disease_Date_And_Time  BETWEEN '$fromDate' AND '$toDate' $filterDiseaseCategory ");

  //die(mysqli_num_rows($select_diagnosis));
     while ($row=mysqli_fetch_assoc($select_diagnosis)){
      $disease_name=$row['disease_name'];
      $disease_ID=$row['disease_ID'];
      $less_male_count=0;
      $less_female_count=0;
      $greater_male_count=0;
      $greater_female_count=0;

      /************************* diagosis attendance starts*************************/

      $query_result=mysqli_query($conn,"SELECT (CASE WHEN (TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) >= $end_age AND pr.Gender = 'male' ) THEN 'greator_male' WHEN (TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) < $start_age AND pr.Gender = 'male' ) THEN 'less_male' WHEN (TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) >= $end_age AND pr.Gender = 'female' ) THEN 'greator_female' WHEN (TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) < $start_age AND pr.Gender = 'female' ) THEN 'less_female' END)AS sex FROM tbl_ward_round wr, tbl_ward_round_disease wrd,  tbl_patient_registration pr WHERE wr.Registration_ID = pr.Registration_ID AND wr.Round_ID=wrd.Round_ID AND wrd.disease_ID=$disease_ID  $filterDiagnosis  AND wrd.Round_Disease_Date_And_Time  BETWEEN '$fromDate' and '$toDate'") or die(mysqli_error($conn));

      while($result_data=mysqli_fetch_assoc($query_result)){
          // male attendance age less than  start_age
          if($result_data['sex'] =='less_male'){$less_male_count++;}
          // female attendance age less than  start_age
          if($result_data['sex'] =='less_female'){$less_female_count++;}
          // male attendance age greater than  end_age
          if($result_data['sex'] =='greator_male'){$greater_male_count++;}
          // female attendance age greater than  end_age
          if($result_data['sex'] =='greator_female'){$greater_female_count++;}
      }
      $subtotal=$less_male_count+$less_female_count+$greater_male_count+$greater_female_count;
      if($subtotal===0){continue;}
      $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A'.$diagnosisRow,  "".$disease_name."")
              ->setCellValue('B'.$diagnosisRow,  "".$less_male_count."")
              ->setCellValue('C'.$diagnosisRow,  "".$less_female_count."")
              ->setCellValue('D'.$diagnosisRow,  "".($less_male_count+$less_female_count)."")
              ->setCellValue('E'.$diagnosisRow,  "".$greater_male_count."")
              ->setCellValue('F'.$diagnosisRow,  "".$greater_female_count."")
              ->setCellValue('G'.$diagnosisRow,  "".($greater_male_count+$greater_female_count)."")
              ->setCellValue('H'.$diagnosisRow,  "".($less_male_count+$less_female_count+$greater_male_count+$greater_female_count)."");
              $diagnosisRow++;
              $total_less_male_count+=$less_male_count;
              $total_less_female_count+=$less_female_count;
              $total_greater_male_count+=$greater_male_count;
              $total_greater_female_count+=$greater_female_count;
      }
  }

    $grand_total+=$total_less_male_count+$total_less_female_count+$total_greater_male_count+$total_greater_female_count;
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$diagnosisRow,  "Total")
                ->setCellValue('B'.$diagnosisRow,  "".$total_less_male_count."")
                ->setCellValue('C'.$diagnosisRow,  "".$total_less_female_count."")
                ->setCellValue('D'.$diagnosisRow,  "".($total_less_male_count+$total_less_female_count)."")
                ->setCellValue('E'.$diagnosisRow,  "".$total_greater_male_count."")
                ->setCellValue('F'.$diagnosisRow,  "".$total_greater_female_count."")
                ->setCellValue('G'.$diagnosisRow,  "".($total_greater_male_count+$total_greater_female_count)."")
                ->setCellValue('H'.$diagnosisRow,  "".$grand_total."");

    //Feeding the data ends
    //
    // //Formatting the data cells
    $dataExcelAlignment=array(
        '1'=>'B','2'=>'C','3'=>'D','4'=>'E','5'=>'F','6'=>'G','7'=>'H'
    );

    //formating total
    // $objPHPExcel->setActiveSheetIndex(0)->mergeCells('H'.$diagnosisRow.':'.'J'.$diagnosisRow);
    $objPHPExcel->getActiveSheet()->getStyle('H'.$diagnosisRow)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,'rotation'   => 0,'wrap'     => true));


    for($i=1;$i<=6;$i++){
    $objPHPExcel->getActiveSheet()->getStyle($dataExcelAlignment[$i].$diagnosisRow)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,'rotation'   => 0,'wrap'     => true));
    }

    for($i=4;$i<$diagnosisRow;$i++){
        for($j=1;$j<(count($dataExcelAlignment)+1);$j++){
          $data=$dataExcelAlignment[$j].$i;
          $objPHPExcel->getActiveSheet()->getStyle($data)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,'rotation'   => 0,'wrap'     => true));
        }
    }
    if($report_from=='Outpatient')
        $worksheetTitle='OPD';
    else if($report_from=='Inpatient')
        $worksheetTitle='IPD';
    else
        $worksheetTitle='OPD';
 $objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(20);

 $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(70);
 $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);

 $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
 $objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
 $objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
 $objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
 $objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
 $objPHPExcel->getActiveSheet()->getStyle('H1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
 $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(12);
 $objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setSize(12);
 $objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setSize(12);
 $objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setSize(12);
 $objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->setSize(12);
$objPHPExcel->getActiveSheet()->setTitle($worksheetTitle);

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

// // Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$worksheetTitle.' Diagnosis from '.date_format(date_create($fromDate),'d-m-Y g:iA').' to '.date_format(date_create($toDate),'d-m-Y g:iA').'.xls"');
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
