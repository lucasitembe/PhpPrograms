<?php
include("./includes/connection.php");
if (isset($_POST['patient_id']) && isset($_POST['admission_id'])) {
  $patient_id = $_POST['patient_id'];
  $admission_id = $_POST['admission_id'];
  session_start();
  $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
  $times=date("H:i");

  @$temp = $_POST['temp'];
  @$resp = $_POST['resp'];
  $time =$_POST['time'];



$data = array();

if (!empty($temp)) {

  $insert_temp_resp = "INSERT INTO tbl_temp_resp(patient_id,admission_id,temp,tr_time,
    actual_temp_resp_time,time_hour,Employee_ID) VALUES('$patient_id','$admission_id','$temp','$time',NOW(),'$times','$Employee_ID')";


    if ($ressult = mysqli_query($conn,$insert_temp_resp)) {
      $data['t'] = $time;
      $data['temp'] = $temp;
        echo json_encode($data);
    }else {
      echo mysqli_error($conn);
    }


}elseif (!empty($resp)) {
  $insert_temp_resp = "INSERT INTO tbl_temp_resp(patient_id,admission_id,resp,tr_time,
    actual_temp_resp_time) VALUES('$patient_id','$admission_id','$resp','$time',NOW())";


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
