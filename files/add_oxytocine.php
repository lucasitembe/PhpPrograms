<?php
include("./includes/connection.php");
if (isset($_POST['patient_id']) && isset($_POST['admission_id'])) {
  $patient_id = $_POST['patient_id'];
  $admission_id = $_POST['admission_id'];
  session_start();
  $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
  $times=date("H:i");

  @$oxytocine = $_POST['oxytocine'];
  $time =$_POST['time'];



$data = array();

if (!empty($oxytocine)) {
  $insert_oxytocine = "INSERT INTO tbl_oxytocine(patient_id,admission_id,oxytocine,oxytocine_time,
    actual_oxytocine_time,time_hour,Employee_ID) VALUES('$patient_id','$admission_id','$oxytocine','$time',NOW(),'$times','$Employee_ID')";


    if ($ressult = mysqli_query($conn,$insert_oxytocine)) {
      $data['t'] = $time;
      $data['oxytocine'] = $oxytocine;

      echo json_encode($data);
    }else {
      echo mysqli_error($conn);
    }

}

}
 ?>
