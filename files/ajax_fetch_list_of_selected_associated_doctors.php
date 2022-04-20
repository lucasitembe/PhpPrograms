<?php
session_start();
include("includes/connection.php");
if (isset($_POST['Registration_ID'])) {
   $Registration_ID=$_POST['Registration_ID'];
   $consultation_ID=$_POST['consultation_ID'];
   $Round_ID=$_POST['Round_ID'];
    //select all associated doctor
   $sql_select_all_aasociated_doctor_result=mysqli_query($conn,"SELECT emp.Employee_ID, Employee_Name,round_associated_doctor_cache_id FROM tbl_employee emp,tbl_round_associated_doctor_cache radc WHERE emp.Employee_ID=radc.Employee_ID AND Registration_ID='$Registration_ID' AND consultation_ID='$consultation_ID' AND Round_ID='$Round_ID'") or die(mysqli_error($conn));

   if(mysqli_num_rows($sql_select_all_aasociated_doctor_result)>0){
       echo"<table class='table'><tr>
                    <td><b>S/No.</b></td>
                    <td><b>Doctors Name</b></td>
                    <td><b>Action</b></td>
            </tr>";
       $count_sn=1;
        while($rows_list=mysqli_fetch_assoc($sql_select_all_aasociated_doctor_result)){
           $Employee_ID=$rows_list['Employee_ID'];
           $Employee_Name=$rows_list['Employee_Name'];
           $round_associated_doctor_cache_id=$rows_list['round_associated_doctor_cache_id'];
           echo "<tr>
                    <td>$count_sn.</td>
                    <td>$Employee_Name</td>
                    <td><input type='button' value='X' onclick='remove_associated_doctor($round_associated_doctor_cache_id)'/td>
                </tr>";
           $count_sn++;
        }
   }
}