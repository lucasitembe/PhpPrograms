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

  if (isset($_POST['acetone'])) {
    $acetone = $_POST['acetone'];

    $insert_acetone = "INSERT INTO tbl_acetone(patient_id,admission_id,acetone,acetone_time,actual_acetone_time,time_hour,Employee_ID) VALUES('$patient_id','$admission_id','$acetone','$time',NOW(),'$times','$Employee_ID')";
    if ($result = mysqli_query($conn,$insert_acetone)) {
      $data['t'] = $time;
      $data['protein'] = $acetone;

      echo json_encode($data);
    }else{
      echo mysqli_error($conn);
    }

  }
  

  // echo $patient_id;
}
 ?>
