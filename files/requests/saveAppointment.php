<?php
session_start();
include("../includes/connection.php");
if(isset($_POST['action'])){
    if($_POST['action']=='save'){
        $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
        $appdate=  mysqli_real_escape_string($conn,$_POST['appdate']);
        $reason=  mysqli_real_escape_string($conn,$_POST['reason']);
        $doctor=  mysqli_real_escape_string($conn,$_POST['doctor']);
        $clinic=  mysqli_real_escape_string($conn,$_POST['clinic']);
        $from_procedure=  mysqli_real_escape_string($conn,$_POST['from_procedure']);
        $patientID=mysqli_real_escape_string($conn,$_POST['patientID']);
        $insert=mysqli_query($conn,"INSERT INTO tbl_appointment (Set_BY,patient_No,date_time,appointment_reason,doctor,Clinic,from_procedure) VALUES ('$Employee_ID','$patientID','$appdate','$reason','$doctor','$clinic','$from_procedure')");
        if($insert){
            echo 'Appointment saved successfully';
        }  else {
            echo 'Appointment saving error';  
        }
    } elseif($_POST['action']=='Edit'){
      $appdate=  mysqli_real_escape_string($conn,$_POST['appdate']);
      $reason=  mysqli_real_escape_string($conn,$_POST['reason']);
      $doctor=  mysqli_real_escape_string($conn,$_POST['doctor']);
      $clinic=  mysqli_real_escape_string($conn,$_POST['clinic']);
      $from_procedure=  mysqli_real_escape_string($conn,$_POST['from_procedure']);
      $id=$_POST['id']; 
//      echo "UPDATE tbl_appointment SET date_time='$appdate',appointment_reason='$reason',doctor='$doctor' WHERE appointment_id='$id'";
      $insert=mysqli_query($conn,"UPDATE tbl_appointment SET date_time='$appdate',appointment_reason='$reason',doctor='$doctor',Clinic='$clinic' WHERE appointment_id='$id'");
        if($insert){
            echo 'Appointment saved successfully';
        }  else {
            echo 'Appointment saving error';  
        }  
    } elseif($_POST['action']=='delete'){
     $id=$_POST['id'];
     $insert=mysqli_query($conn,"UPDATE tbl_appointment SET Status='0' WHERE appointment_id='$id'");
        if($insert){
            echo 'Appointment removed successfully';
        }  else {
            echo 'Appointment removing error';  
        }    
    }
}