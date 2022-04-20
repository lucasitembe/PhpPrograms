<?php
//@gkcchief
include("./includes/connection.php");
    $start_date=$_GET['start_date'];
    $end_date=$_GET['end_date'];
    $diagnosis_type=$_GET['diagnosis_type'];
    $process_status=$_GET['process_status'];
    $filter="";
    if($diagnosis_type!="All"){
       $filter=" AND diagnosis_type='$diagnosis_type'"; 
    }

$filter_matibabu="";
if($process_status!="All"){
if($process_status=="done_procedure"){
     $filter_matibabu .=" AND (ilc.Status='served')";
 }
}
    $filter_matibabu .=" AND ilc.Transaction_Date_And_Time BETWEEN '$start_date' AND '$end_date'";
        
   $mahudhurion_ya_mara_yakwanza_male_undr_5=0;
   $mahudhurion_ya_mara_yakwanza_female_undr_5=0;
   
   $mahudhurion_ya_mara_yakwanza_male_btn_5_14=0;
   $mahudhurion_ya_mara_yakwanza_female_btn_5_14=0;
   
   $mahudhurion_ya_mara_yakwanza_male_abv_15=0;
   $mahudhurion_ya_mara_yakwanza_female_abv_15=0;
   ///////////////////////////////////////////////
   $mahudhurio_ya_marudio_male_undr_5=0;
   $mahudhurio_ya_marudio_female_undr_5=0;
   
   $mahudhurio_ya_marudio_male_btn_5_14=0;
   $mahudhurio_ya_marudio_female_btn_5_14=0;
   
   $mahudhurio_ya_marudio_male_abv_15=0;
   $mahudhurio_ya_marudio_female_abv_15=0;
   
   
   ///
   $mahudhurio_ya_kinywa_na_meno_male_undr_5=0;
   $mahudhurio_ya_kinywa_na_meno_male_btn_5_14=0;
   $mahudhurio_ya_kinywa_na_meno_male_abv_15=0;
   $mahudhurio_ya_kinywa_na_meno_female_undr_5=0;
   $mahudhurio_ya_kinywa_na_meno_female_btn_5_14=0;
   $mahudhurio_ya_kinywa_na_meno_female_abv_15=0;
           
  $dental_patient=[];
    
  $dental_patient=[];
 $get_disease_statistics_result=mysqli_query($conn,"SELECT COUNT(c.Registration_ID) as patient_count,Type_of_patient_case,c.Registration_ID,Gender,TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) AS patient_age FROM  tbl_disease_consultation dc,tbl_consultation c,tbl_mtuha_book_11_report_employee mbrp,tbl_patient_registration pr WHERE dc.consultation_ID=c.consultation_ID AND dc.employee_ID=mbrp.Employee_ID AND c.Registration_ID=pr.Registration_ID AND Disease_Consultation_Date_And_Time BETWEEN '$start_date' AND '$end_date' $filter GROUP BY Type_of_patient_case,c.Registration_ID,Gender") or die(mysqli_error($conn));
 if(mysqli_num_rows($get_disease_statistics_result)>0){
     while($rows_count= mysqli_fetch_assoc($get_disease_statistics_result)){
         $Type_of_patient_case=$rows_count['Type_of_patient_case'];
         $Gender=$rows_count['Gender'];
         $patient_age=$rows_count['patient_age'];
         $Registration_ID=$rows_count['Registration_ID'];
         
         //dental attendance
           ////new case@gkcchief
         if(!in_array($Registration_ID,$dental_patient)){
            if($Gender=="Male"){
                if($patient_age<5){
                    $mahudhurio_ya_kinywa_na_meno_male_undr_5++;
                }else if($patient_age>=5&&$patient_age<=14){
                    $mahudhurio_ya_kinywa_na_meno_male_btn_5_14++;
                }else if($patient_age>=15){
                    $mahudhurio_ya_kinywa_na_meno_male_abv_15++;
                }
            }else{
                if($patient_age<5){
                    $mahudhurio_ya_kinywa_na_meno_female_undr_5++;
                }else if($patient_age>=5&&$patient_age<=14){
                    $mahudhurio_ya_kinywa_na_meno_female_btn_5_14++;
                }else if($patient_age>=15){
                    $mahudhurio_ya_kinywa_na_meno_female_abv_15++;
                }
            }
         }
            array_push($dental_patient, $Registration_ID);
         
         if($Type_of_patient_case=="new_case"){
             
             ////new case@gkcchief
            if($Gender=="Male"){
                if($patient_age<5){
                    $mahudhurion_ya_mara_yakwanza_male_undr_5++;
                }else if($patient_age>=5&&$patient_age<=14){
                    $mahudhurion_ya_mara_yakwanza_male_btn_5_14++;
                }else if($patient_age>=15){
                    $mahudhurion_ya_mara_yakwanza_male_abv_15++;
                }
            }else{
                if($patient_age<5){
                    $mahudhurion_ya_mara_yakwanza_female_undr_5++;
                }else if($patient_age>=5&&$patient_age<=14){
                    $mahudhurion_ya_mara_yakwanza_female_btn_5_14++;
                }else if($patient_age>=15){
                    $mahudhurion_ya_mara_yakwanza_female_abv_15++;
                }
            }
            //end new case
         }else{
            //return case
              if($Gender=="Male"){
                if($patient_age<5){
                    $mahudhurio_ya_marudio_male_undr_5++;
                }else if($patient_age>=5&&$patient_age<=14){
                    $mahudhurio_ya_marudio_male_btn_5_14++;
                }else if($patient_age>=15){
                    $mahudhurio_ya_marudio_male_abv_15++;
                }
            }else{
                if($patient_age<5){
                    $mahudhurio_ya_marudio_female_undr_5++;
                }else if($patient_age>=5&&$patient_age<=14){
                    $mahudhurio_ya_marudio_female_btn_5_14++;
                }else if($patient_age>=15){
                    $mahudhurio_ya_marudio_female_abv_15++;
                }
            }
         }
     }
 }
    
    /** Error reporting */
    error_reporting(E_ALL);
    ini_set('display_errors', TRUE);
    ini_set('display_startup_errors', TRUE);
    date_default_timezone_set('Africa/Dar_es_Salaam');

    if (PHP_SAPI == 'cli')
        die('This page should only be run from a Web Browser');

    /** Include PHPExcel */
    require_once 'PHPExcel-1.8.1/Classes/PHPExcel.php';

    // Create new PHPExcel object
    $objPHPExcel = new PHPExcel();

    // Set document properties
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1','Na')
                ->setCellValue('B1','Maelezo')
                ->setCellValue('C1','Umri chini ya miaka 5')
                ->setCellValue('F1','Umri miaka 5 hadi Miaka 14')
                ->setCellValue('I1','Umri miaka 15 na kuendelea')
                ->setCellValue('L1','Jumla');
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('C2','Me')
                ->setCellValue('D2','Ke')
                ->setCellValue('E2','Jumla')
                ->setCellValue('F2','Me')
                ->setCellValue('G2','Ke')
                ->setCellValue('H2','Jumla')
                ->setCellValue('I2','Me')
                ->setCellValue('J2','Ke')
                ->setCellValue('K2','Jumla');
     cellColor('A1', 'B5A587');
     cellColor('B1', 'B5A587');
     cellColor('C1', 'B5A587');
     cellColor('F1', 'B5A587');
     cellColor('I1', 'B5A587');
     cellColor('L1', 'B5A587');
     cellColor('C2', 'B5A587');
     cellColor('D2', 'B5A587');
     cellColor('E2', 'B5A587');
     cellColor('F2', 'B5A587');
     cellColor('G2', 'B5A587');
     cellColor('H2', 'B5A587');
     cellColor('I2', 'B5A587');
     cellColor('J2', 'B5A587');
     cellColor('K2', 'B5A587');
     cellColor('L2', 'B5A587');
     $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A3',  "1")
                ->setCellValue('B3',  "Mahudhurio ya wagonjwa wa kinywa na Meno")
                ->setCellValue('C3',  "$mahudhurio_ya_kinywa_na_meno_male_undr_5")
                ->setCellValue('D3',  "$mahudhurio_ya_kinywa_na_meno_female_undr_5")
                ->setCellValue('E3',  ($mahudhurio_ya_kinywa_na_meno_male_undr_5+$mahudhurio_ya_kinywa_na_meno_female_undr_5))
                ->setCellValue('F3',  "$mahudhurio_ya_kinywa_na_meno_male_btn_5_14")
                ->setCellValue('G3',  "$mahudhurio_ya_kinywa_na_meno_female_btn_5_14")
                ->setCellValue('H3',  ($mahudhurio_ya_kinywa_na_meno_male_btn_5_14+$mahudhurio_ya_kinywa_na_meno_female_btn_5_14))
                ->setCellValue('I3',  "$mahudhurio_ya_kinywa_na_meno_male_abv_15")
                ->setCellValue('J3',  "$mahudhurio_ya_kinywa_na_meno_female_abv_15")
                ->setCellValue('K3',  ($mahudhurio_ya_kinywa_na_meno_male_abv_15+$mahudhurio_ya_kinywa_na_meno_female_abv_15))
                ->setCellValue('L3',  ($mahudhurio_ya_kinywa_na_meno_male_undr_5+$mahudhurio_ya_kinywa_na_meno_female_undr_5+$mahudhurio_ya_kinywa_na_meno_male_btn_5_14+$mahudhurio_ya_kinywa_na_meno_female_btn_5_14+$mahudhurio_ya_kinywa_na_meno_male_abv_15+$mahudhurio_ya_kinywa_na_meno_female_abv_15));
     $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A4',  "2")
                ->setCellValue('B4',  "Wagonjwa waliohudhuria kwa mara ya kwanza mwaka huu (*)")
                ->setCellValue('C4',  "$mahudhurion_ya_mara_yakwanza_male_undr_5")
                ->setCellValue('D4',  "$mahudhurion_ya_mara_yakwanza_female_undr_5")
                ->setCellValue('E4',  ($mahudhurion_ya_mara_yakwanza_male_undr_5+$mahudhurion_ya_mara_yakwanza_female_undr_5))
                ->setCellValue('F4',  "$mahudhurion_ya_mara_yakwanza_male_btn_5_14")
                ->setCellValue('G4',  "$mahudhurion_ya_mara_yakwanza_female_btn_5_14")
                ->setCellValue('H4',  ($mahudhurion_ya_mara_yakwanza_male_btn_5_14+$mahudhurion_ya_mara_yakwanza_female_btn_5_14))
                ->setCellValue('I4',  "$mahudhurion_ya_mara_yakwanza_male_abv_15")
                ->setCellValue('J4',  "$mahudhurion_ya_mara_yakwanza_female_abv_15")
                ->setCellValue('K4',  ($mahudhurion_ya_mara_yakwanza_male_abv_15+$mahudhurion_ya_mara_yakwanza_female_abv_15))
                ->setCellValue('L4',  ($mahudhurion_ya_mara_yakwanza_male_undr_5+$mahudhurion_ya_mara_yakwanza_female_undr_5+$mahudhurion_ya_mara_yakwanza_male_btn_5_14+$mahudhurion_ya_mara_yakwanza_female_btn_5_14+$mahudhurion_ya_mara_yakwanza_male_abv_15+$mahudhurion_ya_mara_yakwanza_female_abv_15));
     
     $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A5',  "3")
                ->setCellValue('B5',  "Mahudhurio ya Marudio")
                ->setCellValue('C5',  "$mahudhurio_ya_marudio_male_undr_5")
                ->setCellValue('D5',  "$mahudhurio_ya_marudio_female_undr_5")
                ->setCellValue('E5',  ($mahudhurio_ya_marudio_male_undr_5+$mahudhurio_ya_marudio_female_undr_5))
                ->setCellValue('F5',  "$mahudhurio_ya_marudio_male_btn_5_14")
                ->setCellValue('G5',  "$mahudhurio_ya_marudio_female_btn_5_14")
                ->setCellValue('H5',  ($mahudhurio_ya_marudio_male_btn_5_14+$mahudhurio_ya_marudio_female_btn_5_14))
                ->setCellValue('I5',  "$mahudhurio_ya_marudio_male_abv_15")
                ->setCellValue('J5',  "$mahudhurio_ya_marudio_female_abv_15")
                ->setCellValue('K5',  ($mahudhurio_ya_marudio_male_abv_15+$mahudhurio_ya_marudio_female_abv_15))
                ->setCellValue('L5',  ($mahudhurio_ya_marudio_male_undr_5+$mahudhurio_ya_marudio_female_undr_5+$mahudhurio_ya_marudio_male_btn_5_14+$mahudhurio_ya_marudio_female_btn_5_14+$mahudhurio_ya_marudio_male_abv_15+$mahudhurio_ya_marudio_female_abv_15));
                
    
     
     
     //select disease category
            $count_sn=7;
            $count_sn_cat=6;
            $count_sn_rw=4;
            $sql_select_disease_category_result=mysqli_query($conn,"SELECT disease_category_ID,category_discreption FROM tbl_disease_category WHERE icd_10_or_icd_9='icd_10'") or die(mysqli_error($conn));
            if(mysqli_num_rows($sql_select_disease_category_result)>0){
                
                while($categories_rows= mysqli_fetch_assoc($sql_select_disease_category_result)){
                   $disease_category_ID=$categories_rows['disease_category_ID'];
                   $category_discreption=$categories_rows['category_discreption'];
//                   echo "<tr style='background:#B5A587'>
//                            <td colspan='12'><b>$category_discreption</b></td>
//                        </tr>";
                   $count_sn++;
                           $count_sn_cat++;
                     $objPHPExcel->setActiveSheetIndex(0)
                                        ->setCellValue('A'.($count_sn_cat),  "$category_discreption");
                     $objPHPExcel->getActiveSheet()->mergeCells('A'.($count_sn_cat).':L'.($count_sn_cat));
                      cellColor('A'.($count_sn_cat), 'B5A587');
                   //select diseases sub category
                  
                   
                   $sql_select_disease_sub_category_result= mysqli_query($conn,"SELECT subcategory_description,subcategory_ID FROM tbl_disease_subcategory WHERE disease_category_ID='$disease_category_ID'") or die(mysqli_error($conn));
                   if(mysqli_num_rows($sql_select_disease_sub_category_result)>0){
                       while($sub_cat_rows=mysqli_fetch_assoc($sql_select_disease_sub_category_result)){
                           $subcategory_description=$sub_cat_rows['subcategory_description'];
                           $subcategory_ID=$sub_cat_rows['subcategory_ID'];
                           
                            $male_under_5=0;
                            $female_under_5=0;

                            $male_btn_5_and_14=0;
                            $female_btn_5_and_14=0;

                            $male_above_15=0;
                            $female_above_15=0;
                            $get_disease_statistics_per_sub_category_result=mysqli_query($conn,"SELECT COUNT(c.Registration_ID) as patient_count,Type_of_patient_case,c.Registration_ID,Gender,TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) AS patient_age FROM  tbl_disease_consultation dc,tbl_consultation c,tbl_mtuha_book_11_report_employee mbrp,tbl_patient_registration pr,tbl_disease ds WHERE dc.consultation_ID=c.consultation_ID AND dc.employee_ID=mbrp.Employee_ID AND c.Registration_ID=pr.Registration_ID AND ds.disease_ID=dc.disease_ID AND subcategory_ID='$subcategory_ID' AND Disease_Consultation_Date_And_Time BETWEEN '$start_date' AND '$end_date' $filter GROUP BY c.Registration_ID,Gender") or die(mysqli_error($conn));
                            if(mysqli_num_rows($get_disease_statistics_per_sub_category_result)>0){
                                while($rows_count= mysqli_fetch_assoc($get_disease_statistics_per_sub_category_result)){
                                    $Type_of_patient_case=$rows_count['Type_of_patient_case'];
                                    $Gender=$rows_count['Gender'];
                                    $patient_age=$rows_count['patient_age'];
                                    $Registration_ID=$rows_count['Registration_ID'];


                                       if($Gender=="Male"){
                                           if($patient_age<5){
                                               $male_under_5++;
                                           }else if($patient_age>=5&&$patient_age<=14){
                                               $male_btn_5_and_14++;
                                           }else if($patient_age>=15){
                                               $male_above_15++;
                                           }
                                       }else{
                                           if($patient_age<5){
                                               $female_under_5++;
                                           }else if($patient_age>=5&&$patient_age<=14){
                                               $female_btn_5_and_14++;
                                           }else if($patient_age>=15){
                                               $female_above_15++;
                                           }
                                       }


                                }
                            }
                           
                                 $objPHPExcel->setActiveSheetIndex(0)
                                        ->setCellValue('A'.$count_sn,  "$count_sn_rw")
                                        ->setCellValue('B'.$count_sn,  "$subcategory_description")
                                        ->setCellValue('C'.$count_sn,  number_format($male_under_5))
                                        ->setCellValue('D'.$count_sn,  number_format($female_under_5))
                                        ->setCellValue('E'.$count_sn,  number_format($male_under_5+$female_under_5))
                                        ->setCellValue('F'.$count_sn,  number_format($male_btn_5_and_14))
                                        ->setCellValue('G'.$count_sn,  number_format($female_btn_5_and_14))
                                        ->setCellValue('H'.$count_sn,  number_format($male_btn_5_and_14+$female_btn_5_and_14))
                                        ->setCellValue('I'.$count_sn,  number_format($male_above_15))
                                        ->setCellValue('J'.$count_sn,  number_format($female_above_15))
                                        ->setCellValue('K'.$count_sn,  number_format($male_above_15+$female_above_15))
                                        ->setCellValue('L'.$count_sn,  number_format($male_under_5+$female_under_5+$male_btn_5_and_14+$female_btn_5_and_14+$male_above_15+$female_above_15));

                           $count_sn++;
                           $count_sn_cat++;
                           $count_sn_rw++;
                       }
                   }
                }
            }
//couse of trauma__________________
$count_sn++;
$count_sn_cat++;
$count_sn++;
$count_sn_cat++;
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$count_sn_cat,  "Cause of Trauma");
cellColor('A'.$count_sn_cat, 'B5A587');
cellColor('B'.$count_sn_cat, 'B5A587');
cellColor('C'.$count_sn_cat, 'B5A587');
cellColor('F'.$count_sn_cat, 'B5A587');
cellColor('I'.$count_sn_cat, 'B5A587');
cellColor('L'.$count_sn_cat, 'B5A587');
cellColor('D'.$count_sn_cat, 'B5A587');
cellColor('E'.$count_sn_cat, 'B5A587');
cellColor('F'.$count_sn_cat, 'B5A587');
cellColor('G'.$count_sn_cat, 'B5A587');
cellColor('H'.$count_sn_cat, 'B5A587');
cellColor('J'.$count_sn_cat, 'B5A587');
cellColor('K'.$count_sn_cat, 'B5A587');

$sql_select_cause_of_trauma_result=mysqli_query($conn,"SELECT course_injury,hosp_course_injury_ID FROM tbl_hospital_course_injuries") or die(mysqli_error($conn));
if(mysqli_num_rows($sql_select_cause_of_trauma_result)>0){
while($cause_rows=mysqli_fetch_assoc($sql_select_cause_of_trauma_result)){
$course_injury= $cause_rows['course_injury'];
$hosp_course_injury_ID= $cause_rows['hosp_course_injury_ID'];

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$count_sn,  "$count_sn_rw")
            ->setCellValue('B'.$count_sn,  "$course_injury");

 $male_under_5=0;
$female_under_5=0;

$male_btn_5_and_14=0;
$female_btn_5_and_14=0;

$male_above_15=0;
$female_above_15=0;
$get_disease_statistics_per_sub_category_result=mysqli_query($conn,"SELECT COUNT(c.Registration_ID) as patient_count,Type_of_patient_case,c.Registration_ID,Gender,TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) AS patient_age FROM  tbl_consultation c,tbl_mtuha_book_11_report_employee mbrp,tbl_patient_registration pr WHERE  c.employee_ID=mbrp.Employee_ID AND c.Registration_ID=pr.Registration_ID AND course_of_injuries='$hosp_course_injury_ID' AND Consultation_Date_And_Time BETWEEN '$start_date' AND '$end_date' GROUP BY c.Registration_ID,Gender") or die(mysqli_error($conn));
if(mysqli_num_rows($get_disease_statistics_per_sub_category_result)>0){
    while($rows_count= mysqli_fetch_assoc($get_disease_statistics_per_sub_category_result)){
        $Type_of_patient_case=$rows_count['Type_of_patient_case'];
        $Gender=$rows_count['Gender'];
        $patient_age=$rows_count['patient_age'];
        $Registration_ID=$rows_count['Registration_ID'];


           if($Gender=="Male"){
               if($patient_age<5){
                   $male_under_5++;
               }else if($patient_age>=5&&$patient_age<=14){
                   $male_btn_5_and_14++;
               }else if($patient_age>=15){
                   $male_above_15++;
               }
           }else{
               if($patient_age<5){
                   $female_under_5++;
               }else if($patient_age>=5&&$patient_age<=14){
                   $female_btn_5_and_14++;
               }else if($patient_age>=15){
                   $female_above_15++;
               }
           }


    }
}


    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('C'.$count_sn,  number_format($male_under_5))
        ->setCellValue('D'.$count_sn,  number_format($female_under_5))
        ->setCellValue('E'.$count_sn,  number_format($male_under_5+$female_under_5))
        ->setCellValue('F'.$count_sn,  number_format($male_btn_5_and_14))
        ->setCellValue('G'.$count_sn,  number_format($female_btn_5_and_14))
        ->setCellValue('H'.$count_sn,  number_format($male_btn_5_and_14+$female_btn_5_and_14))
        ->setCellValue('I'.$count_sn,  number_format($male_above_15))
        ->setCellValue('J'.$count_sn,  number_format($female_above_15))
        ->setCellValue('K'.$count_sn,  number_format($male_above_15+$female_above_15))
        ->setCellValue('L'.$count_sn,  number_format($male_under_5+$female_under_5+$male_btn_5_and_14+$female_btn_5_and_14+$male_above_15+$female_above_15));



        $count_sn++;
        $count_sn_cat++;
        $count_sn_rw++;
}
}

//end couse of trauma_____________________  

//to come again___________________________

$count_sn++;
$count_sn_cat++;
$count_sn++;
$count_sn_cat++;
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$count_sn_cat,  "Sababu za Marudio");
cellColor('A'.$count_sn_cat, 'B5A587');
cellColor('B'.$count_sn_cat, 'B5A587');
cellColor('C'.$count_sn_cat, 'B5A587');
cellColor('F'.$count_sn_cat, 'B5A587');
cellColor('I'.$count_sn_cat, 'B5A587');
cellColor('L'.$count_sn_cat, 'B5A587');
cellColor('D'.$count_sn_cat, 'B5A587');
cellColor('E'.$count_sn_cat, 'B5A587');
cellColor('F'.$count_sn_cat, 'B5A587');
cellColor('G'.$count_sn_cat, 'B5A587');
cellColor('H'.$count_sn_cat, 'B5A587');
cellColor('J'.$count_sn_cat, 'B5A587');
cellColor('K'.$count_sn_cat, 'B5A587');

$sql_select_to_come_again_reason_trauma_result=mysqli_query($conn,"SELECT to_come_again_reason,to_come_again_id FROM tbl_to_come_again_reason") or die(mysqli_error($conn));
if(mysqli_num_rows($sql_select_to_come_again_reason_trauma_result)>0){
while($cause_rows=mysqli_fetch_assoc($sql_select_to_come_again_reason_trauma_result)){
$to_come_again_reason= $cause_rows['to_come_again_reason'];
$to_come_again_id= $cause_rows['to_come_again_id'];

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$count_sn,  "$count_sn_rw")
            ->setCellValue('B'.$count_sn,  "$to_come_again_reason");

 $male_under_5=0;
$female_under_5=0;

$male_btn_5_and_14=0;
$female_btn_5_and_14=0;

$male_above_15=0;
$female_above_15=0;
$get_disease_statistics_per_sub_category_result=mysqli_query($conn,"SELECT COUNT(c.Registration_ID) as patient_count,Type_of_patient_case,c.Registration_ID,Gender,TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) AS patient_age FROM  tbl_consultation c,tbl_mtuha_book_11_report_employee mbrp,tbl_patient_registration pr WHERE  c.employee_ID=mbrp.Employee_ID AND c.Registration_ID=pr.Registration_ID AND to_come_again_reason='$to_come_again_reason' AND Consultation_Date_And_Time BETWEEN '$start_date' AND '$end_date' GROUP BY c.Registration_ID,Gender") or die(mysqli_error($conn));
if(mysqli_num_rows($get_disease_statistics_per_sub_category_result)>0){
    while($rows_count= mysqli_fetch_assoc($get_disease_statistics_per_sub_category_result)){
        $Type_of_patient_case=$rows_count['Type_of_patient_case'];
        $Gender=$rows_count['Gender'];
        $patient_age=$rows_count['patient_age'];
        $Registration_ID=$rows_count['Registration_ID'];


           if($Gender=="Male"){
               if($patient_age<5){
                   $male_under_5++;
               }else if($patient_age>=5&&$patient_age<=14){
                   $male_btn_5_and_14++;
               }else if($patient_age>=15){
                   $male_above_15++;
               }
           }else{
               if($patient_age<5){
                   $female_under_5++;
               }else if($patient_age>=5&&$patient_age<=14){
                   $female_btn_5_and_14++;
               }else if($patient_age>=15){
                   $female_above_15++;
               }
           }


    }
}

$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('C'.$count_sn,  number_format($male_under_5))
->setCellValue('D'.$count_sn,  number_format($female_under_5))
->setCellValue('E'.$count_sn,  number_format($male_under_5+$female_under_5))
->setCellValue('F'.$count_sn,  number_format($male_btn_5_and_14))
->setCellValue('G'.$count_sn,  number_format($female_btn_5_and_14))
->setCellValue('H'.$count_sn,  number_format($male_btn_5_and_14+$female_btn_5_and_14))
->setCellValue('I'.$count_sn,  number_format($male_above_15))
->setCellValue('J'.$count_sn,  number_format($female_above_15))
->setCellValue('K'.$count_sn,  number_format($male_above_15+$female_above_15))
->setCellValue('L'.$count_sn,  number_format($male_under_5+$female_under_5+$male_btn_5_and_14+$female_btn_5_and_14+$male_above_15+$female_above_15));

    $count_sn++;
    $count_sn_cat++;
    $count_sn_rw++;
}
}

//end of to come again_____________________

///sart of treatment 
$count_sn++;
$count_sn_cat++;
$count_sn++;
$count_sn_cat++;
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$count_sn_cat,  "Matibabu");
cellColor('A'.$count_sn_cat, 'B5A587');
cellColor('B'.$count_sn_cat, 'B5A587');
cellColor('C'.$count_sn_cat, 'B5A587');
cellColor('F'.$count_sn_cat, 'B5A587');
cellColor('I'.$count_sn_cat, 'B5A587');
cellColor('L'.$count_sn_cat, 'B5A587');
cellColor('D'.$count_sn_cat, 'B5A587');
cellColor('E'.$count_sn_cat, 'B5A587'); 
cellColor('F'.$count_sn_cat, 'B5A587');
cellColor('G'.$count_sn_cat, 'B5A587');
cellColor('H'.$count_sn_cat, 'B5A587');
cellColor('J'.$count_sn_cat, 'B5A587');
cellColor('K'.$count_sn_cat, 'B5A587');
$sql_select_treatmment = mysqli_query($conn, "SELECT i.Product_Name,i.Item_ID, name_of_treatment, mtc.Treatment_ID FROM tbl_items i, tbl_mtuha_treatment mt, tbl_mtuha_treatment_category mtc WHERE mtc.Treatment_ID=mt.Treatment_ID AND i.Item_ID=mtc.Item_ID GROUP BY name_of_treatment") or die(mysqli_error($conn));  
if((mysqli_num_rows($sql_select_treatmment))>0){
    while($matibabu_row = mysqli_fetch_assoc($sql_select_treatmment)){
        $name_of_treatment =$matibabu_row['name_of_treatment'];
        $Treatment_ID = $matibabu_row['Treatment_ID'];
       // echo $Treatment_ID."<br/>";
        // echo  "<tr>
        //         <td>$count_sn</td>
        //         <td>$name_of_treatment</td>"; 
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$count_sn,  "$count_sn_rw")
            ->setCellValue('B'.$count_sn,  "$name_of_treatment");
          
        
// $sql_select_item_result = mysqli_query($conn,"SELECT  i.Product_Name,i.Item_ID FROM  tbl_item_list_cache ilc  INNER JOIN tbl_payment_cache pp ON pp.Payment_Cache_ID =ilc.Payment_Cache_ID JOIN tbl_items i ON ilc.Item_ID=i.Item_ID JOIN tbl_patient_registration pr ON pr.Registration_ID=pp.Registration_ID JOIN tbl_mtuha_book_11_report_employee mbrp ON mbrp.Employee_ID=ilc.Consultant_ID  WHERE i.Item_ID IN (SELECT Item_ID FROM tbl_mtuha_treatment_category tc WHERE Treatment_ID='$Treatment_ID') AND Check_In_Type='Procedure' $filter_matibabu ") or die(mysqli_error($conn));

//$sql_select_item_result = mysqli_query($conn,"SELECT i.Product_Name,i.Item_ID FROM tbl_item_list_cache ilc  INNER JOIN tbl_payment_cache pp ON pp.Payment_Cache_ID =ilc.Payment_Cache_ID JOIN tbl_items i ON ilc.Item_ID=i.Item_ID JOIN tbl_patient_registration pr ON pr.Registration_ID=pp.Registration_ID JOIN tbl_mtuha_book_11_report_employee mbrp ON mbrp.Employee_ID=ilc.Consultant_ID  WHERE  Check_In_Type='Procedure' $filter_matibabu GROUP BY Product_Name") or die(mysqli_error($conn));
//   if(mysqli_num_rows($sql_select_item_result)>0){
//       //$count_sn=1;
//       while($item_rows=mysqli_fetch_assoc($sql_select_item_result)){
//           $Product_Name=$item_rows['Product_Name'];
//           $Item_ID=$item_rows['Item_ID'];
          
//          echo  "<tr>
//                    <td>$count_sn</td>
//                    <td>$Product_Name</td>";
        //   $objPHPExcel->setActiveSheetIndex(0)
        //     ->setCellValue('A'.$count_sn,  "$count_sn_rw")
        //     ->setCellValue('B'.$count_sn,  "$Product_Name");
          
       
             $male_under_5=0;
            $female_under_5=0;

            $male_btn_5_and_14=0;
            $female_btn_5_and_14=0;

            $male_above_15=0;
            $female_above_15=0;
           
          //// COUNT PROCEDURE
          $sql_count_procedure_result= mysqli_query($conn, "SELECT Gender,TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) AS patient_age FROM tbl_item_list_cache ilc  INNER JOIN tbl_payment_cache pp ON pp.Payment_Cache_ID =ilc.Payment_Cache_ID JOIN tbl_patient_registration pr ON pr.Registration_ID=pp.Registration_ID JOIN tbl_mtuha_book_11_report_employee mbrp ON mbrp.Employee_ID=ilc.Consultant_ID
                        JOIN tbl_mtuha_treatment_category mtc ON ilc.Item_ID=mtc.Item_ID
                         WHERE  Check_In_Type='Procedure' AND Treatment_ID='$Treatment_ID' $filter_matibabu GROUP BY pr.Registration_ID,Gender") or die(mysqli_error($conn));
          if(mysqli_num_rows($sql_count_procedure_result)>0){
              while($count_rows=mysqli_fetch_assoc($sql_count_procedure_result)){
                    $Gender=$count_rows['Gender'];
                    $patient_age=$count_rows['patient_age'];


                       if($Gender=="Male"){
                           if($patient_age<5){
                               $male_under_5++;
                           }else if($patient_age>=5&&$patient_age<=14){
                               $male_btn_5_and_14++;
                           }else if($patient_age>=15){
                               $male_above_15++;
                           }
                       }else{
                           if($patient_age<5){
                               $female_under_5++;
                           }else if($patient_age>=5&&$patient_age<=14){
                               $female_btn_5_and_14++;
                           }else if($patient_age>=15){
                               $female_above_15++;
                           }
                       }

              }
              
          }
         
//   }
// }//ending of inner loop of treatment
$objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('C'.$count_sn,  number_format($male_under_5))
                    ->setCellValue('D'.$count_sn,  number_format($female_under_5))
                    ->setCellValue('E'.$count_sn,  number_format($male_under_5+$female_under_5))
                    ->setCellValue('F'.$count_sn,  number_format($male_btn_5_and_14))
                    ->setCellValue('G'.$count_sn,  number_format($female_btn_5_and_14))
                    ->setCellValue('H'.$count_sn,  number_format($male_btn_5_and_14+$female_btn_5_and_14))
                    ->setCellValue('I'.$count_sn,  number_format($male_above_15))
                    ->setCellValue('J'.$count_sn,  number_format($female_above_15))
                    ->setCellValue('K'.$count_sn,  number_format($male_above_15+$female_above_15))
                    ->setCellValue('L'.$count_sn,  number_format($male_under_5+$female_under_5+$male_btn_5_and_14+$female_btn_5_and_14+$male_above_15+$female_above_15));

    $count_sn++;
    $count_sn_cat++;
    $count_sn_rw++;
}
}
//end of treatment
    
function cellColor($cells,$color){
    global $objPHPExcel;

    $objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array(
             'rgb' => $color
        )
    ));
}

 $objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(20);
 $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
 $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
 $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
 $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
 $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
 $objPHPExcel->getActiveSheet()->mergeCells('A1:A2');
 $objPHPExcel->getActiveSheet()->mergeCells('B1:B2');
 $objPHPExcel->getActiveSheet()->mergeCells('C1:E1');
// $objPHPExcel->getActiveSheet()->mergeCells('D1:E1');
 
 $objPHPExcel->getActiveSheet()->mergeCells('F1:H1');
// $objPHPExcel->getActiveSheet()->mergeCells('G1:H1');
 
 $objPHPExcel->getActiveSheet()->mergeCells('I1:K1');
// $objPHPExcel->getActiveSheet()->mergeCells('J1:K1');
// $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(12);
// $objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setSize(12);
// $objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setSize(12);
 $objPHPExcel->getActiveSheet()->setTitle("mtuha_book_11_report");

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
header('Content-Disposition: attachment;filename="mtuha_book_11_report.xls"');
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