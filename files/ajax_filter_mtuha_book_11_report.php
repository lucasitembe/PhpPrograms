<?php
//@gkcchief
include("./includes/connection.php");
    $start_date=$_POST['start_date'];
    $end_date=$_POST['end_date'];
    $diagnosis_type=$_POST['diagnosis_type'];
    $process_status=$_POST['process_status'];
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
 $get_disease_statistics_result= mysqli_query($conn,"SELECT COUNT(c.Registration_ID) as patient_count,Type_of_patient_case,c.Registration_ID,Gender,TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) AS patient_age FROM  tbl_disease_consultation dc,tbl_consultation c,tbl_mtuha_book_11_report_employee mbrp,tbl_patient_registration pr WHERE dc.consultation_ID=c.consultation_ID AND dc.employee_ID=mbrp.Employee_ID AND c.Registration_ID=pr.Registration_ID AND Disease_Consultation_Date_And_Time BETWEEN '$start_date' AND '$end_date' $filter GROUP BY Type_of_patient_case,c.Registration_ID,Gender") or die(mysqli_error($conn));
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
    ?>
<table class="table table-condensed" style="font-size: 12px;">
    <tr >
        <td rowspan='2'><b>Na.</b></td>
        <td rowspan='2'><b>Maelezo</b></td>
        <td colspan='3'><b>Umri chini ya miaka 5</b></td>
        <td colspan='3'><b>Umri miaka 5 hadi Miaka 14</b></td>
        <td colspan='3'><b>Umri miaka 15 na kuendelea</b></td>
        <td style='text-align:right;'  rowspan="2"><b>Jumla</b></td>
    </tr>
    <tr>
        <td style='text-align:right;'><b>Me</b></td>
        <td style='text-align:right;'><b>Ke</b></td>
        <td style='text-align:right;'><b>Jumla</b></td>
        <td style='text-align:right;'><b>Me</b></td>
        <td style='text-align:right;'><b>Ke</b></td>
        <td style='text-align:right;'><b>Jumla</b></td>
        <td style='text-align:right;'><b>Me</b></td>
        <td style='text-align:right;'><b>Ke</b></td>
        <td style='text-align:right;'><b>Jumla</b></td>
    </tr>
    <tbody>
        <tr>
            <td>1.</td>
            <td>Mahudhurio ya wagonjwa wa kinywa na Meno</td>
            <td style='text-align:right;'><?= $mahudhurio_ya_kinywa_na_meno_male_undr_5 ?></td>
            <td style='text-align:right;'><?= $mahudhurio_ya_kinywa_na_meno_female_undr_5 ?></td>
            <td style='text-align:right;'><?= ($mahudhurio_ya_kinywa_na_meno_male_undr_5+$mahudhurio_ya_kinywa_na_meno_female_undr_5)?></td>
            <td style='text-align:right;'><?= $mahudhurio_ya_kinywa_na_meno_male_btn_5_14 ?></td>
            <td style='text-align:right;'><?= $mahudhurio_ya_kinywa_na_meno_female_btn_5_14 ?></td>
            <td style='text-align:right;'><?= ($mahudhurio_ya_kinywa_na_meno_male_btn_5_14+$mahudhurio_ya_kinywa_na_meno_female_btn_5_14)?></td>
            <td style='text-align:right;'><?= $mahudhurio_ya_kinywa_na_meno_male_abv_15 ?></td>
            <td style='text-align:right;'><?= $mahudhurio_ya_kinywa_na_meno_female_abv_15 ?></td>
            <td style='text-align:right;'><?= ($mahudhurio_ya_kinywa_na_meno_male_abv_15+$mahudhurio_ya_kinywa_na_meno_female_abv_15)?></td>
            <td style='text-align:right;'><?= ($mahudhurio_ya_kinywa_na_meno_male_undr_5+$mahudhurio_ya_kinywa_na_meno_female_undr_5+$mahudhurio_ya_kinywa_na_meno_male_btn_5_14+$mahudhurio_ya_kinywa_na_meno_female_btn_5_14+$mahudhurio_ya_kinywa_na_meno_male_abv_15+$mahudhurio_ya_kinywa_na_meno_female_abv_15) ?></td>
        </tr>
        <tr>
            <td>2.</td>
            <td>Wagonjwa waliohudhuria kwa mara ya kwanza mwaka huu (*)</td>
            <td style='text-align:right;'><?= $mahudhurion_ya_mara_yakwanza_male_undr_5 ?></td>
            <td style='text-align:right;'><?= $mahudhurion_ya_mara_yakwanza_female_undr_5 ?></td>
            <td style='text-align:right;'><?= ($mahudhurion_ya_mara_yakwanza_male_undr_5+$mahudhurion_ya_mara_yakwanza_female_undr_5) ?></td>
            <td style='text-align:right;'><?= $mahudhurion_ya_mara_yakwanza_male_btn_5_14 ?></td>
            <td style='text-align:right;'><?= $mahudhurion_ya_mara_yakwanza_female_btn_5_14 ?></td>
            <td style='text-align:right;'><?= ($mahudhurion_ya_mara_yakwanza_male_btn_5_14+$mahudhurion_ya_mara_yakwanza_female_btn_5_14) ?></td>
            <td style='text-align:right;'><?= $mahudhurion_ya_mara_yakwanza_male_abv_15 ?></td>
            <td style='text-align:right;'><?= $mahudhurion_ya_mara_yakwanza_female_abv_15 ?></td>
            <td style='text-align:right;'><?= ($mahudhurion_ya_mara_yakwanza_male_abv_15+$mahudhurion_ya_mara_yakwanza_female_abv_15) ?></td>
            <td style='text-align:right;'><?= ($mahudhurion_ya_mara_yakwanza_male_undr_5+$mahudhurion_ya_mara_yakwanza_female_undr_5+$mahudhurion_ya_mara_yakwanza_male_btn_5_14+$mahudhurion_ya_mara_yakwanza_female_btn_5_14+$mahudhurion_ya_mara_yakwanza_male_abv_15+$mahudhurion_ya_mara_yakwanza_female_abv_15)?></td>
        </tr>
        <tr>
            <td>3.</td>
            <td>Mahudhurio ya Marudio</td>
            <td style='text-align:right;'><?= $mahudhurio_ya_marudio_male_undr_5 ?></td>
            <td style='text-align:right;'><?= $mahudhurio_ya_marudio_female_undr_5 ?></td>
            <td style='text-align:right;'><?= ($mahudhurio_ya_marudio_male_undr_5+$mahudhurio_ya_marudio_female_undr_5) ?></td>
            <td style='text-align:right;'><?= $mahudhurio_ya_marudio_male_btn_5_14 ?></td>
            <td style='text-align:right;'><?= $mahudhurio_ya_marudio_female_btn_5_14 ?></td>
            <td style='text-align:right;'><?= ($mahudhurio_ya_marudio_male_btn_5_14+$mahudhurio_ya_marudio_female_btn_5_14)?></td>
            <td style='text-align:right;'><?= $mahudhurio_ya_marudio_male_abv_15?></td>
            <td style='text-align:right;'><?= $mahudhurio_ya_marudio_female_abv_15?></td>
            <td style='text-align:right;'><?= ($mahudhurio_ya_marudio_male_abv_15+$mahudhurio_ya_marudio_female_abv_15)?></td>
            <td style='text-align:right;'><?= ($mahudhurio_ya_marudio_male_undr_5+$mahudhurio_ya_marudio_female_undr_5+$mahudhurio_ya_marudio_male_btn_5_14+$mahudhurio_ya_marudio_female_btn_5_14+$mahudhurio_ya_marudio_male_abv_15+$mahudhurio_ya_marudio_female_abv_15)?></td>
        </tr>
        <?php 
            //select disease category
            $count_sn=4;
            $sql_select_disease_category_result=mysqli_query($conn,"SELECT disease_category_ID,category_discreption FROM tbl_disease_category WHERE icd_10_or_icd_9='icd_10'") or die(mysqli_error($conn));
            if(mysqli_num_rows($sql_select_disease_category_result)>0){
                
                while($categories_rows= mysqli_fetch_assoc($sql_select_disease_category_result)){
                   $disease_category_ID=$categories_rows['disease_category_ID'];
                   $category_discreption=$categories_rows['category_discreption'];
                   echo "<tr style='background:#B5A587'>
                            <td colspan='12'><b>$category_discreption</b></td>
                        </tr>";
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
                           
                           
                           echo "<tr>
                                    <td>$count_sn.</td>
                                    <td class='rows_list' onclick='open_mtuha_book_11_detail($subcategory_ID,\"$subcategory_description\")'>$subcategory_description</td>
                                    <td style='text-align:right;'>".number_format($male_under_5)."</td>
                                    <td style='text-align:right;'>".number_format($female_under_5)."</td>
                                    <td style='text-align:right;'>".number_format($male_under_5+$female_under_5)."</td>
                                    <td style='text-align:right;'>".number_format($male_btn_5_and_14)."</td>
                                    <td style='text-align:right;'>".number_format($female_btn_5_and_14)."</td>
                                    <td style='text-align:right;'>".number_format($male_btn_5_and_14+$female_btn_5_and_14)."</td>
                                    <td style='text-align:right;'>".number_format($male_above_15)."</td>
                                    <td style='text-align:right;'>".number_format($female_above_15)."</td>
                                    <td style='text-align:right;'>".number_format($male_above_15+$female_above_15)."</td>
                                    <td style='text-align:right;'>".number_format($male_under_5+$female_under_5+$male_btn_5_and_14+$female_btn_5_and_14+$male_above_15+$female_above_15)."</td>
                                </tr>";
                           $count_sn++;
                       }
                   }
                }
            }
             echo "<tr style='background:#B5A587'>
                            <td colspan='12'><b>Cause of Trauma</b></td>
                        </tr>";
             $sql_select_cause_of_trauma_result=mysqli_query($conn,"SELECT course_injury,hosp_course_injury_ID FROM tbl_hospital_course_injuries") or die(mysqli_error($conn));
             if(mysqli_num_rows($sql_select_cause_of_trauma_result)>0){
                 while($cause_rows=mysqli_fetch_assoc($sql_select_cause_of_trauma_result)){
                   $course_injury= $cause_rows['course_injury'];
                   $hosp_course_injury_ID= $cause_rows['hosp_course_injury_ID'];
                   echo "<tr>
                            <td>$count_sn.</td>
                            <td class='rows_list' onclick='open_mtuha_book_11_cause_of_trauma($hosp_course_injury_ID,\"$course_injury\")'>$course_injury</td>
                        ";
                   
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
                           
                           
                           echo "
                                    <td style='text-align:right;'>".number_format($male_under_5)."</td>
                                    <td style='text-align:right;'>".number_format($female_under_5)."</td>
                                    <td style='text-align:right;'>".number_format($male_under_5+$female_under_5)."</td>
                                    <td style='text-align:right;'>".number_format($male_btn_5_and_14)."</td>
                                    <td style='text-align:right;'>".number_format($female_btn_5_and_14)."</td>
                                    <td style='text-align:right;'>".number_format($male_btn_5_and_14+$female_btn_5_and_14)."</td>
                                    <td style='text-align:right;'>".number_format($male_above_15)."</td>
                                    <td style='text-align:right;'>".number_format($female_above_15)."</td>
                                    <td style='text-align:right;'>".number_format($male_above_15+$female_above_15)."</td>
                                    <td style='text-align:right;'>".number_format($male_under_5+$female_under_5+$male_btn_5_and_14+$female_btn_5_and_14+$male_above_15+$female_above_15)."</td>
                                </tr>";
                   
                   
                    $count_sn++;
                 }
             }
             echo "<tr style='background:#B5A587'>
                            <td colspan='12'><b>Sababu za Marudio</b></td>
                        </tr>";
             $sql_select_to_come_again_reason_trauma_result=mysqli_query($conn,"SELECT to_come_again_reason,to_come_again_id FROM tbl_to_come_again_reason") or die(mysqli_error($conn));
             if(mysqli_num_rows($sql_select_to_come_again_reason_trauma_result)>0){
                 while($cause_rows=mysqli_fetch_assoc($sql_select_to_come_again_reason_trauma_result)){
                   $to_come_again_reason= $cause_rows['to_come_again_reason'];
                   $to_come_again_id= $cause_rows['to_come_again_id'];
                   echo "<tr>
                            <td>$count_sn.</td>
                            <td class='rows_list' onclick='open_mtuha_book_11_to_come_again($to_come_again_id,\"$to_come_again_reason\")'>$to_come_again_reason</td>
                       ";
                   
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
                           
                           
                           echo "
                                    <td style='text-align:right;'>".number_format($male_under_5)."</td>
                                    <td style='text-align:right;'>".number_format($female_under_5)."</td>
                                    <td style='text-align:right;'>".number_format($male_under_5+$female_under_5)."</td>
                                    <td style='text-align:right;'>".number_format($male_btn_5_and_14)."</td>
                                    <td style='text-align:right;'>".number_format($female_btn_5_and_14)."</td>
                                    <td style='text-align:right;'>".number_format($male_btn_5_and_14+$female_btn_5_and_14)."</td>
                                    <td style='text-align:right;'>".number_format($male_above_15)."</td>
                                    <td style='text-align:right;'>".number_format($female_above_15)."</td>
                                    <td style='text-align:right;'>".number_format($male_above_15+$female_above_15)."</td>
                                    <td style='text-align:right;'>".number_format($male_under_5+$female_under_5+$male_btn_5_and_14+$female_btn_5_and_14+$male_above_15+$female_above_15)."</td>
                                </tr>";
                   
                    $count_sn++;
                 }
             }
              echo "<tr style='background:#B5A587'>
                            <td colspan='12'><b>Matibabu</b></td>
                        </tr>";
                        //die("SELECT  i.Product_Name,i.Item_ID, Check_In_Type FROM  tbl_item_list_cache ilc  INNER JOIN tbl_payment_cache pp ON pp.Payment_Cache_ID =ilc.Payment_Cache_ID JOIN tbl_items i ON ilc.Item_ID=i.Item_ID JOIN tbl_patient_registration pr ON pr.Registration_ID=pp.Registration_ID JOIN tbl_mtuha_book_11_report_employee mbrp ON mbrp.Employee_ID=ilc.Consultant_ID  WHERE i.Item_ID IN (SELECT Item_ID FROM tbl_mtuha_treatment_category tc WHERE Treatment_ID='$Treatment_ID') AND Check_In_Type='Procedure' $filter_matibabu GROUP BY i.Item_ID ");
    $sql_select_treatmment = mysqli_query($conn, "SELECT i.Product_Name,i.Item_ID, name_of_treatment, mtc.Treatment_ID FROM tbl_items i, tbl_mtuha_treatment mt, tbl_mtuha_treatment_category mtc WHERE mtc.Treatment_ID=mt.Treatment_ID AND i.Item_ID=mtc.Item_ID GROUP BY Treatment_ID ORDER BY Treatment_ID ASC") or die(mysqli_error($conn));  
    if((mysqli_num_rows($sql_select_treatmment))>0){
        while($matibabu_row = mysqli_fetch_assoc($sql_select_treatmment)){
            $name_of_treatment =$matibabu_row['name_of_treatment'];
            $Treatment_ID = $matibabu_row['Treatment_ID'];
           // echo $Treatment_ID."<br/>";
            echo  "<tr>
                    <td>$count_sn</td>
                    <td class='rows_list' onclick='open_mtuha_book_11_treatment_details($Treatment_ID,\"$name_of_treatment\")'>$name_of_treatment</td>"; 
            
                // $sql_select_item_result = mysqli_query($conn,"SELECT  i.Product_Name,i.Item_ID FROM  tbl_item_list_cache ilc  INNER JOIN tbl_payment_cache pp ON pp.Payment_Cache_ID =ilc.Payment_Cache_ID JOIN tbl_items i ON ilc.Item_ID=i.Item_ID JOIN tbl_patient_registration pr ON pr.Registration_ID=pp.Registration_ID JOIN tbl_mtuha_book_11_report_employee mbrp ON mbrp.Employee_ID=ilc.Consultant_ID JOIN tbl_mtuha_treatment_category mtc ON ilc.Item_ID=mtc.Item_ID
                // WHERE Treatment_ID='$Treatment_ID' AND Check_In_Type='Procedure' $filter_matibabu GROUP BY pp.Registration_ID ") or die(mysqli_error($conn));
                
                 
                            $male_under_5=0;
                            $female_under_5=0;

                            $male_btn_5_and_14=0;
                            $female_btn_5_and_14=0;

                            $male_above_15=0;
                            $female_above_15=0;
                // if(mysqli_num_rows($sql_select_item_result)>0){
                //     //$count_sn=1;
                    
                //     while($item_rows=mysqli_fetch_assoc($sql_select_item_result)){
                //         $Product_Name=$item_rows['Product_Name'];
                //         $Item_ID=$item_rows['Item_ID'];                        
                    
                            
                           //removed decleration of age range{}
                        
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
                        }//end of count by age loop
                       
                       
                // }
                // }
                ///========== adding here for treatment
                echo "
                <td style='text-align:right;'>".number_format($male_under_5)."</td>
                <td style='text-align:right;'>".number_format($female_under_5)."</td>
                <td style='text-align:right;'>".number_format($male_under_5+$female_under_5)."</td>
                <td style='text-align:right;'>".number_format($male_btn_5_and_14)."</td>
                <td style='text-align:right;'>".number_format($female_btn_5_and_14)."</td>
                <td style='text-align:right;'>".number_format($male_btn_5_and_14+$female_btn_5_and_14)."</td>
                <td style='text-align:right;'>".number_format($male_above_15)."</td>
                <td style='text-align:right;'>".number_format($female_above_15)."</td>
                <td style='text-align:right;'>".number_format($male_above_15+$female_above_15)."</td>
                <td style='text-align:right;'>".number_format($male_under_5+$female_under_5+$male_btn_5_and_14+$female_btn_5_and_14+$male_above_15+$female_above_15)."</td>
                </tr>";

                $count_sn++;
                
            }
        } 
        ?>
    </tbody>
</table>
