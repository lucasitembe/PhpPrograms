<?php
include("./includes/connection.php");
session_start();
  $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
  $times=date("H:i");
if (isset($_POST['patient_id']) && isset($_POST['admission_id'])) {
  $patient_id = $_POST['patient_id'];
  $admission_id = $_POST['admission_id'];
  $data = array();
  $time = $_POST['time'];

  
  if (isset($_POST['bp'])) {
    $bp = $_POST['bp'];

    $insert_pressure = "INSERT INTO tbl_pressure(patient_id,admission_id,pressure,pressure_time,actual_pressure_time,time_hour,Employee_ID) VALUES('$patient_id','$admission_id','$bp','$time',NOW(),'$times','$Employee_ID')";
    if ($result = mysqli_query($conn,$insert_pressure)) {
      $data['t'] = $time;
      $data['pressure'] = $pressure;

      echo json_encode($data);
    }else{
      echo mysqli_error($conn);
    }


  }

  echo $patient_id;
}
 ?>
