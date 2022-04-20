<?php
session_start();
include("includes/connection.php");
if (isset($_POST['Employee_ID'])) {
   $Employee_ID=$_POST['Employee_ID'];
   $Registration_ID=$_POST['Registration_ID'];
   $consultation_ID=$_POST['consultation_ID'];
   $Round_ID=$_POST['Round_ID'];
    //add associated doctors to catch
    $sql_select_all_aasociated_doctor_result=mysqli_query($conn,"SELECT round_associated_doctor_cache_id FROM tbl_round_associated_doctor_cache  WHERE Employee_ID='$Employee_ID' AND Registration_ID='$Registration_ID' AND consultation_ID='$consultation_ID'") or die(mysqli_error($conn));
    if(mysqli_num_rows($sql_select_all_aasociated_doctor_result)<=0){
             $sql_addl_associated_doctors_to_catch_result=mysqli_query($conn,"INSERT INTO tbl_round_associated_doctor_cache(Employee_ID,consultation_ID,Registration_ID,Round_ID) VALUES('$Employee_ID','$consultation_ID','$Registration_ID','$Round_ID')") or die(mysqli_error($conn));
             if($sql_addl_associated_doctors_to_catch_result){
                 echo "success";
             }else{
                 echo "fail";
            }
   
    }else{
        echo "success";
    }
}