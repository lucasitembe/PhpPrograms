<?php
session_start();
include("../includes/connection.php");
if(isset($_POST['action'])){
    if($_POST['action']=='save'){
        $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
        $appdate=  mysql_real_escape_string($_POST['appdate']);
        $reason=  mysql_real_escape_string($_POST['reason']);
        $doctor=  mysql_real_escape_string($_POST['doctor']);
        $clinic=  mysql_real_escape_string($_POST['clinic']);
        $patientID=mysql_real_escape_string($_POST['patientID']);
        $insert=mysql_query("INSERT INTO tbl_appointment (Set_BY,patient_No,date_time,appointment_reason,doctor,Clinic) VALUES ('$Employee_ID','$patientID','$appdate','$reason','$doctor','$clinic')");
        if($insert){
            echo 'Appointment saved successfully';
        }  else {
            echo 'Appointment saving error';  
        }
    } elseif($_POST['action']=='Edit'){
      $appdate=  mysql_real_escape_string($_POST['appdate']);
      $reason=  mysql_real_escape_string($_POST['reason']);
      $doctor=  mysql_real_escape_string($_POST['doctor']);
      $clinic=  mysql_real_escape_string($_POST['clinic']);
      $id=$_POST['id']; 
//      echo "UPDATE tbl_appointment SET date_time='$appdate',appointment_reason='$reason',doctor='$doctor' WHERE appointment_id='$id'";
      $insert=mysql_query("UPDATE tbl_appointment SET date_time='$appdate',appointment_reason='$reason',doctor='$doctor',Clinic='$clinic' WHERE appointment_id='$id'");
        if($insert){
            echo 'Appointment saved successfully';
        }  else {
            echo 'Appointment saving error';  
        }  
    } elseif($_POST['action']=='delete'){
     $id=$_POST['id'];
     $insert=mysql_query("UPDATE tbl_appointment SET Status='0' WHERE appointment_id='$id'");
        if($insert){
            echo 'Appointment removed successfully';
        }  else {
            echo 'Appointment removing error';  
        }    
    }
}