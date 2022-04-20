<?php

//  time_from:time_from,time_to:time_to,Appointment_Idadi:Appointment_Idadi

   require_once('includes/connection.php');
	
   if (isset($_POST['time_from'])) {
      $time_from= $_POST['time_from'];
      } else {
       $time_from = "";
     }
   if (isset($_POST['time_to'])) {
      $time_to= $_POST['time_to'];
      } else {
       $time_to = "";
     }
   if (isset($_POST['edit_total_number'])) {
      $Appointment_Idadi= $_POST['edit_total_number'];
      } else {
       $Appointment_Idadi = "";
     }
   if (isset($_POST['Time_ID'])) {
      $Time_ID= $_POST['Time_ID'];
      } else {
       $Time_ID = "";
     }
   if (isset($_POST['App_ID'])) {
        $App_ID= $_POST['App_ID'];
      } else {
        $App_ID = "";
     }
   if (isset($_POST['edit_date'])) {
        $edit_date= $_POST['edit_date'];
      } else {
        $edit_date = "";
     }
     $save_c=0;
     $sql_edit_appointment_config_result=mysqli_query($conn,"UPDATE tbl_time_appointment SET time_to='$time_to',time_from='$time_from',Appointment_Idadi='$Appointment_Idadi' WHERE Time_ID='$Time_ID'") or die(mysqli_error($conn));
     if($sql_edit_appointment_config_result){
         $save_c++;
     }
     
     $sql_update_app_date_resul=mysqli_query($conn,"UPDATE tbl_date_appointment SET Date='$edit_date' WHERE App_ID='$App_ID'") or die(mysqli_error($conn));
     if($sql_update_app_date_resul){
        $save_c++; 
     }
if($save_c>1){
    echo "Updated Successfully";
}else{
    echo "Process fail...Try Again";
}