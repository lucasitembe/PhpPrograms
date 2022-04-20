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
  
  $get_all_clinics = mysqli_query($conn, "select Hospital_Ward_Name, Hospital_Ward_ID from tbl_hospital_ward order by Hospital_Ward_Name") or die(mysqli_error($conn));
  
  while ($clinic = mysqli_fetch_assoc($get_all_clinics)){
      $Hospital_Ward_Name = $clinic['Hospital_Ward_Name'];
      $Hospital_Ward_ID = $clinic['Hospital_Ward_ID'];
      $btn_5_and_59_female_new = 0;
      $plus_60_male_new = 0;
      $plus_60_female_new = 0;
      
      //get bed
      $beds = mysqli_query($conn, "select count(Bed_ID) as bed from tbl_beds where Ward_ID = '$Hospital_Ward_ID'") or die(mysqli_error($conn));
      $les_than_5_female_new = mysqli_fetch_assoc($beds)['bed'];
      if($les_than_5_female_new < 1)          continue;
      
      $admission = mysqli_query($conn, "select count(*) as admission from tbl_admission where Admission_Date_Time between '$start_date' and '$end_date' and Admission_Status in ('Admitted','dischareged','Discharged','pending') and Hospital_Ward_ID = '$Hospital_Ward_ID'") or die(mysqli_error($conn));
      $btn_5_and_59_male_new = mysqli_fetch_assoc($admission)['admission'];
      
      $discharge = mysqli_query($conn, "select dc.Discharge_Reason from tbl_admission ad, tbl_discharge_reason dc where ad.Discharge_Reason_ID = dc.Discharge_Reason_ID and ad.Discharge_Date_Time between '$start_date' and '$end_date' and ad.Hospital_Ward_ID = '$Hospital_Ward_ID'
") or die(mysqli_error($conn));
      if(mysqli_num_rows($discharge) > 0){
          while($rows = mysqli_fetch_assoc($discharge)){
              $Discharge_Reason = $rows['Discharge_Reason'];
              
              if(strtolower($Discharge_Reason) == "death"){
                  $plus_60_male_new++;
              }else if(strtolower($Discharge_Reason) == "normal" || strtolower($Discharge_Reason) == "refferal"){
                  $btn_5_and_59_female_new++;
              }
          }
      }
      
      $obd = mysqli_query($conn, "select datediff(date(Discharge_Date_Time),date(Admission_Date_Time)) as obd from tbl_admission where Discharge_Date_Time between '$start_date' and '$end_date' and Hospital_Ward_ID = '$Hospital_Ward_ID'") or die(mysqli_error($conn));
      if(mysqli_num_rows($obd) > 0){
          while($rows = mysqli_fetch_assoc($obd)){
              $plus_60_female_new += $rows['obd'];
              
          }
      }
      $query = "insert into tbl_dhis2_submission_query(ward_id,les_than_5_female_new,btn_5_and_59_male_new,btn_5_and_59_female_new,plus_60_male_new,plus_60_female_new,employee_id) values ('$Hospital_Ward_ID','$les_than_5_female_new','$btn_5_and_59_male_new','$btn_5_and_59_female_new','$plus_60_male_new','$plus_60_female_new','$Employee_ID')";
      mysqli_query($conn, $query) or die(mysqli_error($conn));
     
               echo "
                <tr class='rows_list' $change_color_style>
                        <td>$count_sn.</td>
                        <td>$Hospital_Ward_Name</td>
                        <td style='text-align:center;'>$les_than_5_female_new</td>
                        <td style='text-align:center;'>$btn_5_and_59_male_new</td>
                        <td style='text-align:center;'>$btn_5_and_59_female_new</td>
                        <td style='text-align:center;'>$plus_60_male_new</td>
                        <td style='text-align:center;'>$plus_60_female_new</td>
                    </a>
                </tr>
                ";
        $count_sn++;
    }


}
