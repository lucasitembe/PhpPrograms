<?php
@session_start();
include("./includes/connection.php");

$filter = '';
$filterIn = '';
$filterDiagnosis=' ';
$admission_cause = '';
if (isset($_GET['fromDate']) && !empty($_GET['fromDate']) && isset($_GET['toDate']) && !empty($_GET['toDate'])) {
    $fromDate = mysqli_real_escape_string($conn,$_GET['fromDate']);
    $toDate = mysqli_real_escape_string($conn,$_GET['toDate']);
    //$admission_cause = mysqli_real_escape_string($conn,$_GET['admission_cause']);
    $start_age = mysqli_real_escape_string($conn,$_GET['start_age']);
    $end_age = mysqli_real_escape_string($conn,$_GET['end_age']);
    $Disease_Cat_Id = mysqli_real_escape_string($conn,$_GET['Disease_Cat_Id']);
    $report_from = mysqli_real_escape_string($conn,$_GET['From']);
  	$Disease_Cat_Id=$_GET['Disease_Cat_Id'];
    $diagnosis_type=$_GET['diagnosis_type'];

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
                ->setCellValue('N1','Total');
                //SECOND ROW DATA
$objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('B2','Age < '.$start_age.'')
                ->setCellValue('H2','Age >= '.$end_age.'');
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
                ->setCellValue('M4','T');
                //Feeding the data start here
$diagnosisRow=5;
    //Total counts
$sub_total_less_male_count_new=0;
$sub_total_less_male_count_return=0;
$sub_total_less_female_count_new=0;
$sub_total_less_female_count_return=0;
$sub_total_greater_male_count_new=0;
$sub_total_greater_male_count_return=0;
$sub_total_greater_female_count_new=0;
$sub_total_greater_female_count_return=0;

$grand_total_new=0;
$grand_total_return=0;
$grand_total=0;
//outpatient
if($report_from == 'Outpatient'){
  $Clinic_ID = mysqli_real_escape_string($conn,$_GET['Clinic_ID']);
  if($Clinic_ID!='all'){
      $filter=" AND c.Clinic_ID=$Clinic_ID ";
      //$filterIn=" AND cl.Clinic_ID=$Ward_ID ";
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
    while($row=mysqli_fetch_assoc($select_diagnosis)){
        $disease_ID=$row['disease_ID'];
        $disease_name=$row['disease_name'];
        $total_less_male_count_new=0;
        $total_less_male_count_return=0;
        $total_less_female_count_new=0;
        $total_less_female_count_return=0;
        $total_greater_male_count_new=0;
        $total_greater_male_count_return=0;
        $total_greater_female_count_new=0;
        $total_greater_female_count_return=0;

            $select_new=mysqli_query($conn,"SELECT c.Registration_ID,(CASE WHEN (TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) >= $end_age AND pr.Gender = 'male' ) THEN 'greator_male' WHEN (TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) < $start_age AND pr.Gender = 'male' ) THEN 'less_male' WHEN (TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) >= $end_age AND pr.Gender = 'female' ) THEN 'greator_female' WHEN (TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) < $start_age AND pr.Gender = 'female' ) THEN 'less_female' END) AS sex FROM tbl_consultation c, tbl_disease_consultation dc, tbl_patient_registration pr WHERE c.consultation_ID=dc.consultation_ID AND c.Registration_ID=pr.Registration_ID AND DATE(c.Consultation_Date_And_Time) = pr.Registration_Date AND dc.disease_ID=$disease_ID  $filterDiagnosis $filter  AND c.Consultation_Date_And_Time BETWEEN '$fromDate' and '$toDate' ");

            while($result_data=mysqli_fetch_assoc($select_new)){
                //male new attendance age less than start_age
                if($result_data['sex'] =='less_male'){$total_less_male_count_new++;}
                //female new attendance age less than start_age
                if($result_data['sex'] =='less_female'){$total_less_female_count_new++;}
                //male new attendance age greater than end_age
                if($result_data['sex'] =='greator_male'){$total_greater_male_count_new++;}
                //female new attendance age greator than end_age
                if($result_data['sex'] =='greator_female'){$total_greater_female_count_new++;}
            }
            /****************************** new diagnosis attendance ends ****************************/

            /**************************** return diagnosis attendance ********************************/
            //$select_return=mysqli_query($conn," SELECT COUNT(pr.Registration_ID) AS Registration_ID, (CASE WHEN (TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) >= $end_age AND pr.Gender = 'male' ) THEN 'greator_male' WHEN (TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) < $start_age AND pr.Gender = 'male' ) THEN 'less_male' WHEN (TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) >= $end_age AND pr.Gender = 'female' ) THEN 'greator_female' WHEN (TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) < $start_age AND pr.Gender = 'female' ) THEN 'less_female' END)AS sex FROM tbl_disease_consultation dc, tbl_consultation c, tbl_patient_registration pr WHERE c.consultation_ID = dc.consultation_ID AND c.Registration_ID = pr.Registration_ID AND dc.disease_ID=$disease_ID  $filterDiagnosis $filter AND c.Consultation_Date_And_Time BETWEEN '$fromDate' and '$toDate' GROUP BY c.Registration_ID HAVING ( COUNT(c.Registration_ID) > 1 )");

            $select_return=mysqli_query($conn,"SELECT c.Registration_ID,(CASE WHEN (TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) >= $end_age AND pr.Gender = 'male' ) THEN 'greator_male' WHEN (TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) < $start_age AND pr.Gender = 'male' ) THEN 'less_male' WHEN (TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) >= $end_age AND pr.Gender = 'female' ) THEN 'greator_female' WHEN (TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) < $start_age AND pr.Gender = 'female' ) THEN 'less_female' END) AS sex FROM tbl_consultation c, tbl_disease_consultation dc, tbl_patient_registration pr WHERE c.consultation_ID=dc.consultation_ID AND c.Registration_ID=pr.Registration_ID AND DATE(c.Consultation_Date_And_Time) > pr.Registration_Date AND dc.disease_ID=$disease_ID  $filterDiagnosis $filter  AND c.Consultation_Date_And_Time BETWEEN '$fromDate' and '$toDate' ");

            while($result_data=mysqli_fetch_assoc($select_return)){
                //male return attendance age less than start_age
                if($result_data['sex'] =='less_male'){$total_less_male_count_return++;}
                //female return attendance age less than start_age
                if($result_data['sex'] =='less_female'){$total_less_female_count_return++;}
                //male return attendance age greater than end_age
                if($result_data['sex'] =='greator_male'){$total_greater_male_count_return++;}
                //female return attendance age greator than end_age
                if($result_data['sex'] =='greator_female'){$total_greater_female_count_return++;}
            }
            /*********************** diagnosis attendance end *******************************/

        $subtotal=$total_less_male_count_new+$total_less_female_count_new+$total_less_male_count_return+$total_less_female_count_return+$total_greater_male_count_new+$total_greater_female_count_new+$total_greater_male_count_return+$total_greater_female_count_return;
            if($subtotal===0){continue;}

        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$diagnosisRow,  "".$disease_name."")
                ->setCellValue('B'.$diagnosisRow,  "".$total_less_male_count_new."")
                ->setCellValue('C'.$diagnosisRow,  "".$total_less_female_count_new."")
                ->setCellValue('D'.$diagnosisRow,  "".($total_less_male_count_new+$total_less_female_count_new)."")
                ->setCellValue('E'.$diagnosisRow,  "".$total_less_male_count_return."")
                ->setCellValue('F'.$diagnosisRow,  "".$total_less_female_count_return."")
                ->setCellValue('G'.$diagnosisRow,  "".($total_less_male_count_return+$total_less_female_count_return)."")
                ->setCellValue('H'.$diagnosisRow,  "".$total_greater_male_count_new."")
                ->setCellValue('I'.$diagnosisRow,  "".$total_greater_female_count_new."")
                ->setCellValue('J'.$diagnosisRow,  "".($total_greater_male_count_new+$total_greater_female_count_new)."")
                ->setCellValue('K'.$diagnosisRow,  "".$total_greater_male_count_return."")
                ->setCellValue('L'.$diagnosisRow,  "".$total_greater_female_count_return."")
                ->setCellValue('M'.$diagnosisRow,  "".($total_greater_male_count_return+$total_greater_female_count_return)."")
                ->setCellValue('N'.$diagnosisRow,  "".($total_less_male_count_new+$total_less_female_count_new+$total_less_male_count_return+$total_less_female_count_return+$total_greater_male_count_new+$total_greater_female_count_new+$total_greater_male_count_return+$total_greater_female_count_return)."");
                $diagnosisRow++;

                $sub_total_less_male_count_new+=$total_less_male_count_new;
                $sub_total_less_female_count_new+=$total_less_female_count_new;
                $sub_total_less_male_count_return+=$total_less_male_count_return;
                $sub_total_less_female_count_return+=$total_less_female_count_return;
                $sub_total_greater_male_count_new+=$total_greater_male_count_new;
                $sub_total_greater_female_count_new+=$total_greater_female_count_new;
                $sub_total_greater_male_count_return+=$total_greater_male_count_return;
                $sub_total_greater_female_count_return+=$total_greater_female_count_return;
    }
}

//inpatient
if($report_from == 'Inpatient'){
  $Ward_ID = mysqli_real_escape_string($conn,$_GET['Ward_ID']);

  if($Ward_ID!='all'){
      //$filter=" AND c.Clinic_ID=$Clinic_ID ";
      $filter=" WHERE Ward_ID=$Ward_ID ";
      //$filterIn=" AND cl.Clinic_ID=$Ward_ID ";
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


  $sub_total_less_male_count_new=0;
  $sub_total_less_male_count_return=0;
  $sub_total_less_female_count_new=0;
  $sub_total_less_female_count_return=0;
  $sub_total_greater_male_count_new=0;
  $sub_total_greater_male_count_return=0;
  $sub_total_greater_female_count_new=0;
  $sub_total_greater_female_count_return=0;

  $grand_total_new=0;
  $grand_total_return=0;
  $grand_total=0;
  $num_count=1;
  $select_diagnosis=mysqli_query($conn,"SELECT DISTINCT d.disease_ID, d.disease_name FROM tbl_disease d,tbl_ward_round_disease wrd , tbl_disease_category dc,tbl_disease_subcategory ds WHERE dc.disease_category_ID=ds.disease_category_ID AND wrd.disease_ID=d.disease_ID AND d.subcategory_ID=ds.subcategory_ID AND wrd.Round_Disease_Date_And_Time  BETWEEN '$fromDate' AND '$toDate' $filterDiseaseCategory ");
      while($row=mysqli_fetch_assoc($select_diagnosis)){
          $disease_ID=$row['disease_ID'];
          $disease_name=$row['disease_name'];
          $total_less_male_count_new=0;
          $total_less_male_count_return=0;
          $total_less_female_count_new=0;
          $total_less_female_count_return=0;
          $total_greater_male_count_new=0;
          $total_greater_male_count_return=0;
          $total_greater_female_count_new=0;
          $total_greater_female_count_return=0;

          /******************** new diagonsis attendance  starts *****************************/
          /******************** new diagonsis attendance  starts *****************************/
              $select_new=mysqli_query($conn,"SELECT (CASE WHEN (TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) >= $end_age AND pr.Gender = 'male' ) THEN 'greator_male' WHEN (TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) < $start_age AND pr.Gender = 'male' ) THEN 'less_male' WHEN (TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) >= $end_age AND pr.Gender = 'female' ) THEN 'greator_female' WHEN (TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) < $start_age AND pr.Gender = 'female' ) THEN 'less_female' END)AS sex FROM tbl_ward_round wr, tbl_ward_round_disease wrd,  tbl_patient_registration pr WHERE wr.Registration_ID IN(SELECT ci.Registration_ID FROM tbl_check_in ci, tbl_patient_registration pr WHERE pr.Registration_ID=ci.Registration_ID AND pr.Registration_Date = ci.Visit_Date AND   ci.Check_In_Date_And_Time BETWEEN '$fromDate' and '$toDate') AND wr.Registration_ID = pr.Registration_ID AND wr.Round_ID=wrd.Round_ID AND wrd.disease_ID=$disease_ID  $filterDiagnosis  AND wrd.Round_Disease_Date_And_Time  BETWEEN '$fromDate' and '$toDate'  GROUP BY wr.Registration_ID") or die(mysqli_error($conn));

              while($result_data=mysqli_fetch_assoc($select_new)){
                  //male new attendance age less than start_age
                  if($result_data['sex'] =='less_male'){$total_less_male_count_new++;}
                  //female new attendance age less than start_age
                  if($result_data['sex'] =='less_female'){$total_less_female_count_new++;}
                  //male new attendance age greater than end_age
                  if($result_data['sex'] =='greator_male'){$total_greater_male_count_new++;}
                  //female new attendance age greator than end_age
                  if($result_data['sex'] =='greator_female'){$total_greater_female_count_new++;}
              }
              /****************************** new diagnosis attendance ends ****************************/

              /**************************** return diagnosis attendance ********************************/

              $select_return=mysqli_query($conn,"SELECT (CASE WHEN (TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) >= $end_age AND pr.Gender = 'male' ) THEN 'greator_male' WHEN (TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) < $start_age AND pr.Gender = 'male' ) THEN 'less_male' WHEN (TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) >= $end_age AND pr.Gender = 'female' ) THEN 'greator_female' WHEN (TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) < $start_age AND pr.Gender = 'female' ) THEN 'less_female' END)AS sex FROM tbl_ward_round wr, tbl_ward_round_disease wrd,  tbl_patient_registration pr WHERE wr.Registration_ID = pr.Registration_ID AND wr.Round_ID=wrd.Round_ID AND wrd.disease_ID=$disease_ID  $filterDiagnosis AND wrd.Round_Disease_Date_And_Time  BETWEEN '$fromDate' and '$toDate'") or die(mysqli_error($conn));

              while($result_data=mysqli_fetch_assoc($select_return)){
                  //male return attendance age less than start_age
                  if($result_data['sex'] =='less_male'){$total_less_male_count_return++;}
                  //female return attendance age less than start_age
                  if($result_data['sex'] =='less_female'){$total_less_female_count_return++;}
                  //male return attendance age greater than end_age
                  if($result_data['sex'] =='greator_male'){$total_greater_male_count_return++;}
                  //female return attendance age greator than end_age
                  if($result_data['sex'] =='greator_female'){$total_greater_female_count_return++;}
              }
              $total_less_male_count_return=$total_less_male_count_return-$total_less_male_count_new;
              $total_less_female_count_return=$total_less_female_count_return-$total_less_female_count_new;
              $total_greater_male_count_return=$total_greater_male_count_return-$total_greater_male_count_new;
              $total_greater_female_count_return=$total_greater_female_count_return-$total_greater_female_count_new;
              /*********************** diagnosis attendance end *******************************/

              $subtotal=$total_less_male_count_new+$total_less_female_count_new+$total_less_male_count_return+$total_less_female_count_return+$total_greater_male_count_new+$total_greater_female_count_new+$total_greater_male_count_return+$total_greater_female_count_return;
              if($subtotal===0){continue;}

              //display each row diagonsis attendance
              $objPHPExcel->setActiveSheetIndex(0)
                      ->setCellValue('A'.$diagnosisRow,  "".$disease_name."")
                      ->setCellValue('B'.$diagnosisRow,  "".$total_less_male_count_new."")
                      ->setCellValue('C'.$diagnosisRow,  "".$total_less_female_count_new."")
                      ->setCellValue('D'.$diagnosisRow,  "".($total_less_male_count_new+$total_less_female_count_new)."")
                      ->setCellValue('E'.$diagnosisRow,  "".$total_less_male_count_return."")
                      ->setCellValue('F'.$diagnosisRow,  "".$total_less_female_count_return."")
                      ->setCellValue('G'.$diagnosisRow,  "".($total_less_male_count_return+$total_less_female_count_return)."")
                      ->setCellValue('H'.$diagnosisRow,  "".$total_greater_male_count_new."")
                      ->setCellValue('I'.$diagnosisRow,  "".$total_greater_female_count_new."")
                      ->setCellValue('J'.$diagnosisRow,  "".($total_greater_male_count_new+$total_greater_female_count_new)."")
                      ->setCellValue('K'.$diagnosisRow,  "".$total_greater_male_count_return."")
                      ->setCellValue('L'.$diagnosisRow,  "".$total_greater_female_count_return."")
                      ->setCellValue('M'.$diagnosisRow,  "".($total_greater_male_count_return+$total_greater_female_count_return)."")
                      ->setCellValue('N'.$diagnosisRow,  "".($total_less_male_count_new+$total_less_female_count_new+$total_less_male_count_return+$total_less_female_count_return+$total_greater_male_count_new+$total_greater_female_count_new+$total_greater_male_count_return+$total_greater_female_count_return)."");
                      $diagnosisRow++;
              //  calculate subtotal for diagnosis
                  $sub_total_less_male_count_new+=$total_less_male_count_new;
                  $sub_total_less_female_count_new+=$total_less_female_count_new;
                  $sub_total_less_male_count_return+=$total_less_male_count_return;
                  $sub_total_less_female_count_return+=$total_less_female_count_return;
                  $sub_total_greater_male_count_new+=$total_greater_male_count_new;
                  $sub_total_greater_female_count_new+=$total_greater_female_count_new;
                  $sub_total_greater_male_count_return+=$total_greater_male_count_return;
                  $sub_total_greater_female_count_return+=$total_greater_female_count_return;
                  $num_count++;
      }
}
$grand_total=$sub_total_less_male_count_new+$sub_total_less_male_count_return+$sub_total_less_female_count_new+$sub_total_less_female_count_return+$sub_total_greater_male_count_new+$sub_total_greater_male_count_return+$sub_total_greater_female_count_new+$sub_total_greater_female_count_return;

$objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$diagnosisRow,  "Total")
                ->setCellValue('B'.$diagnosisRow,  "".$sub_total_less_male_count_new."")
                ->setCellValue('C'.$diagnosisRow,  "".$sub_total_less_female_count_new."")
                ->setCellValue('D'.$diagnosisRow,  "".($sub_total_less_male_count_new+$sub_total_less_female_count_new)."")
                ->setCellValue('E'.$diagnosisRow,  "".$sub_total_less_male_count_return."")
                ->setCellValue('F'.$diagnosisRow,  "".$sub_total_less_female_count_return."")
                ->setCellValue('G'.$diagnosisRow,  "".($sub_total_less_male_count_return+$sub_total_less_female_count_return)."")
                ->setCellValue('H'.$diagnosisRow,  "".$sub_total_greater_male_count_new."")
                ->setCellValue('I'.$diagnosisRow,  "".$sub_total_greater_female_count_new."")
                ->setCellValue('J'.$diagnosisRow,  "".($sub_total_greater_male_count_new+$sub_total_greater_female_count_new)."")
                ->setCellValue('K'.$diagnosisRow,  "".$sub_total_greater_male_count_return."")
                ->setCellValue('L'.$diagnosisRow,  "".$sub_total_greater_female_count_return."")
                ->setCellValue('M'.$diagnosisRow,  "".($sub_total_greater_male_count_return+$sub_total_greater_female_count_return)."")
                ->setCellValue('N'.$diagnosisRow,  "".$grand_total."");
                //Feeding the data ends here
 // Rename worksheet
                //FIRST ROW MERGE
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B1:M1');
$objPHPExcel->getActiveSheet()->getStyle('B1:M1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

                //SECOND ROW MERGE
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B2:G2');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('H2:M2');
                //THIRD ROW MERGE
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B3:D3');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('E3:G3');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('H3:J3');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('K3:M3');
                //Column merged
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:A4');

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('N1:N4');
$excelAlignment=array(
        '1'=>'A1','2'=>'N1','3'=>'B2','4'=>'H2','5'=>'N2','6'=>'B3','7'=>'E3','8'=>'H3','9'=>'K3','10'=>'N3','11'=>'Q3','12'=>'B4','13'=>'C4','14'=>'D4','15'=>'E4','16'=>'F4','17'=>'G4','18'=>'H4','19'=>'I4','20'=>'J4','21'=>'K4','22'=>'L4','23'=>'M4','24'=>'N4','25'=>'O4','26'=>'O4','27'=>'Q4','28'=>'R4','29'=>'S4',
    );
    for($i=1; $i<(count($excelAlignment)+1);$i++){
        $objPHPExcel->getActiveSheet()->getStyle($excelAlignment[$i])->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,'rotation'   => 0,'wrap'     => true));
    }

//Formatting the data cells
    $dataExcelAlignment=array(
        '1'=>'B','2'=>'C','3'=>'D','4'=>'E','5'=>'F','6'=>'G','7'=>'H','8'=>'I',
        '9'=>'J','10'=>'K','11'=>'L','12'=>'M','13'=>'N'
    );
for($i=1;$i<=13;$i++){
    $objPHPExcel->getActiveSheet()->getStyle($dataExcelAlignment[$i].$diagnosisRow)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,'rotation'   => 0,'wrap'     => true));
    }

for($i=4;$i<$diagnosisRow;$i++){
    for($j=1;$j<(count($dataExcelAlignment)+1);$j++){
        $objPHPExcel->getActiveSheet()->getStyle($dataExcelAlignment[$j].$i)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,'rotation'   => 0,'wrap'     => true));
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
header('Content-Disposition: attachment;filename="'.$worksheetTitle.' Diagnosis report from '.date_format(date_create($fromDate),'d-m-Y g:iA').' to '.date_format(date_create($toDate),'d-m-Y g:iA').'.xls"');
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
