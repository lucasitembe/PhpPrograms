<?php
include("./includes/connection.php");
@session_start();
$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
$les_than_5_male_new = 0;
if(isset($_POST['start_date'])&&isset($_POST['end_date'])){
  $start_date=$_POST['start_date'];
  $end_date=$_POST['end_date'];
  $count_sn = 1;
  mysqli_query($conn, "delete from tbl_dhis2_submission_query where employee_id='$Employee_ID' and status='not submited'") or die(mysqli_error($conn));
  $filter=" AND ci.Check_In_Date_And_Time BETWEEN '$start_date' AND '$end_date'";
  
  $get_all_clinics = mysqli_query($conn, "select Clinic_Name, Clinic_ID from tbl_clinic where Clinic_Status = 'Available'") or die(mysqli_error($conn));
  
  while ($clinic = mysqli_fetch_assoc($get_all_clinics)){
      $clinicName = $clinic['Clinic_Name'];
      $clinicId = $clinic['Clinic_ID'];
      $les_than_5_male_new = 0;
      $les_than_5_female_new = 0;
      $btn_5_and_59_male_new = 0;
      $btn_5_and_59_female_new = 0;
      $plus_60_male_new = 0;
      $plus_60_female_new = 0;
      
      $les_than_5_male_return = 0;
      $les_than_5_female_return = 0;
      $btn_5_and_59_male_return = 0;
      $btn_5_and_59_female_return = 0;
      $plus_60_male_return = 0;
      $plus_60_female_return = 0;
      
      $result = mysqli_query($conn, "select (DATEDIFF(CURRENT_TIMESTAMP(),DATE(pr.Date_Of_Birth))/365.2425 ) AS age,pr.Gender,ci.Type_Of_Check_In from tbl_patient_payment_item_list plt, tbl_patient_payments pp, tbl_consultation c, tbl_check_in ci, tbl_patient_registration pr where c.Patient_Payment_Item_List_ID = plt.Patient_Payment_Item_List_ID and
plt.Patient_Payment_ID = pp.Patient_Payment_ID and pp.Check_In_ID = ci.Check_In_ID and c.Registration_ID = pr.Registration_ID and c.Clinic_ID = '$clinicId' $filter group by ci.Check_In_ID") or die(mysqli_error($conn));
      if(mysqli_num_rows($result) > 0){
          while ($rows = mysqli_fetch_assoc($result)){
              $age = $rows['age'];
              $sex = $rows['Gender'];
              $visit_type = $rows['Type_Of_Check_In'];
              
              if($sex == 'Male' && $age < 5 && $visit_type == 'Afresh'){
                  $les_than_5_male_new ++;
              }
              if($sex == 'Female' && $age < 5 && $visit_type == 'Afresh'){
                  $les_than_5_female_new ++;
              }
              if($sex == 'Male' && $age >= 5 && $age <= 59  && $visit_type == 'Afresh'){
                  $btn_5_and_59_male_new ++;
              }
              if($sex == 'Female' && $age >= 5 && $age <= 59  && $visit_type == 'Afresh'){
                  $btn_5_and_59_female_new ++;
              }
              if($sex == 'Male' && $age >=60  && $visit_type == 'Afresh'){
                  $plus_60_male_new ++;
              }
              if($sex == 'Female' && $age >=60  && $visit_type == 'Afresh'){
                  $plus_60_female_new ++;
              }
              
              
              if($sex == 'Male' && $age < 5 && $visit_type == 'Continuous'){
                  $les_than_5_male_return ++;
              }
              if($sex == 'Female' && $age < 5 && $visit_type == 'Continuous'){
                  $les_than_5_female_return ++;
              }
              if($sex == 'Male' && $age >= 5 && $age <= 59  && $visit_type == 'Continuous'){
                  $btn_5_and_59_male_return ++;
              }
              if($sex == 'Female' && $age >= 5 && $age <= 59  && $visit_type == 'Continuous'){
                  $btn_5_and_59_female_return ++;
              }
              if($sex == 'Male' && $age >=60  && $visit_type == 'Continuous'){
                  $plus_60_male_return ++;
              }
              if($sex == 'Female' && $age >=60  && $visit_type == 'Continuous'){
                  $plus_60_female_return ++;
              }
              
          }
      }
      
      $query = "insert into tbl_dhis2_submission_query(clinic_id,les_than_5_male_new,les_than_5_female_new,btn_5_and_59_male_new,btn_5_and_59_female_new,plus_60_male_new,plus_60_female_new,les_than_5_male_return,les_than_5_female_return,btn_5_and_59_male_return,btn_5_and_59_female_return,plus_60_male_return,plus_60_female_return,employee_id) values ('$clinicId','$les_than_5_male_new','$les_than_5_female_new','$btn_5_and_59_male_new','$btn_5_and_59_female_new','$plus_60_male_new','$plus_60_female_new','$les_than_5_male_return',"
              . "'$les_than_5_female_return','$btn_5_and_59_male_return','$btn_5_and_59_female_return','$plus_60_male_return','$plus_60_female_return',"
              . "'$Employee_ID')";
      mysqli_query($conn, $query) or die(mysqli_error($conn));
      
      
               echo "
                <tr class='rows_list' $change_color_style>
                        <td>$count_sn.</td>
                        <td>$clinicName</td>
                        <td style='text-align:center;'>$les_than_5_male_new</td>
                        <td style='text-align:center;'>$les_than_5_female_new</td>
                        <td style='text-align:center;'>$btn_5_and_59_male_new</td>
                        <td style='text-align:center;'>$btn_5_and_59_female_new</td>
                        <td style='text-align:center;'>$plus_60_male_new</td>
                        <td style='text-align:center;'>$plus_60_female_new</td>
                        <td style='text-align:center;'>$les_than_5_male_return</td>
                        <td style='text-align:center;'>$les_than_5_female_return</td>
                        <td style='text-align:center;'>$btn_5_and_59_male_return</td>
                        <td style='text-align:center;'>$btn_5_and_59_female_return</td>
                        <td style='text-align:center;'>$plus_60_male_return</td>
                        <td style='text-align:center;'>$plus_60_female_return</td>
                    </a>
                </tr>
                ";
        $count_sn++;
    }


}




