<?php
include("./includes/connection.php");
if(isset($_POST['start_date'])){
   $start_date=$_POST['start_date'];
   $end_date=$_POST['end_date'];
   //first select all referral hospital
   $sql_select_all_referral_hospital_result=mysqli_query($conn,"SELECT hospital_name,referred_from_hospital_id FROM tbl_referred_from_hospital WHERE referred_from_hospital_id IN (SELECT Referral_Hospital_ID FROM tbl_referred_patients rp, tbl_check_in ch WHERE rp.Check_In_ID=ch.Check_In_ID AND Check_In_Date_And_Time BETWEEN '$start_date' AND '$end_date' )") or die(mysqli_error($conn));
   if(mysqli_num_rows($sql_select_all_referral_hospital_result)>0){
       $count=1;
       $grand_total_male=0;
       $grand_total_female=0;
       while($refferal_letters_rows=mysqli_fetch_assoc($sql_select_all_referral_hospital_result)){
          $referred_from_hospital_id=$refferal_letters_rows['referred_from_hospital_id'];
          $hospital_name=$refferal_letters_rows['hospital_name'];
          
          $male_count=0;
          $female_count=0;
          
          //count number of refferal from a specific date range
          $sql_count_number_of_rferral_from_aspecific_date_range_result=mysqli_query($conn,"SELECT COUNT(rp.Registration_ID) AS total_refferal,Gender FROM tbl_patient_registration pr,tbl_referred_patients rp,tbl_check_in ch WHERE pr.Registration_ID=rp.Registration_ID AND pr.Registration_ID=ch.Registration_ID AND rp.Check_In_ID=ch.Check_In_ID AND Referral_Hospital_ID='$referred_from_hospital_id' AND Check_In_Date_And_Time BETWEEN '$start_date' AND '$end_date'  GROUP BY Gender") or die(mysqli_error($conn));
          $returned_rows=mysqli_num_rows($sql_count_number_of_rferral_from_aspecific_date_range_result);
          
          if($returned_rows>0){
              while($rfr_no_rows=mysqli_fetch_assoc($sql_count_number_of_rferral_from_aspecific_date_range_result)){
                  $total_refferal=$rfr_no_rows['total_refferal'];
                  $Gender=$rfr_no_rows['Gender']; 
                if($Gender=="Male"){
                    $male_count=$total_refferal;
                }else{
                    $female_count=$total_refferal; 
                }
              }
          }
          
        $grand_total_male +=$male_count;
        $grand_total_female +=$female_count;
          echo "<tr>
                    <td>$count</td>
                    <td>$hospital_name</td>
                    <td style='text-align: center' class='rows_list' onclick='open_refferal_male_form($referred_from_hospital_id,\"$hospital_name\")'>".number_format($male_count)."</td>
                    <td style='text-align: center' class='rows_list' onclick='open_refferal_female_form($referred_from_hospital_id,\"$hospital_name\")'>".number_format($female_count)."</td>
                    <td style='text-align: center'>".number_format($male_count+$female_count)."</td>
                </tr>";
          $count++;
       }
       echo "<tr>
                <td colspan='2'><b>GRAND TOTAL</b></td>
                <td style='text-align: center'><b>".number_format($grand_total_male)."</b></td>
                <td style='text-align: center'><b>".number_format($grand_total_female)."</b></td>
                <td style='text-align: center'><b>".number_format($grand_total_male+$grand_total_female)."</b></td>
            </tr>";
   }
}