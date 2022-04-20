<?php
include("./includes/connection.php");
if (isset($_POST['patient_id']) && isset($_POST['admission_id'])) {
  $patient_id = $_POST['patient_id'];
  $admission_id = $_POST['admission_id'];
  session_start();
  $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
  $times=date("H:i");

  @$resp = $_POST['resp'];
  $time =$_POST['time'];



$data = array();

if (!empty($resp)) {
  $insert_temp_resp = "INSERT INTO tbl_resp(patient_id,admission_id,resp,resp_time,
    actual_resp_time,time_hour,Employee_ID) VALUES('$patient_id','$admission_id','$resp','$time',NOW(),'$times','$Employee_ID')";


    if ($ressult = mysqli_query($conn,$insert_temp_resp)) {
      $data['t'] = $time;
      $data['resp'] = $temp;

      echo json_encode($data);
    }else {
      echo mysqli_error($conn);
    }

}

}
 ?>
