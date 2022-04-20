<?php
include("./includes/connection.php");
if (isset($_POST['patient_id']) && isset($_POST['admission_id'])) {
  $patient_id = $_POST['patient_id'];
  $admission_id = $_POST['admission_id'];
  session_start();
  $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
  $times=date("H:i");

  @$position_remark = $_POST['position_remark'];
  $time =$_POST['time'];

  echo $position_remark;
  echo $time;


$data = array();

if (!empty($position_remark)) {
  $insert_oxytocine = "INSERT INTO tbl_labour_position(patient_id,admission_id,labour_position_remark,labour_position_remark_time,Employee_ID) VALUES('$patient_id','$admission_id','$position_remark','$time','$Employee_ID')";


    if ($ressult = mysqli_query($conn,$insert_oxytocine)) {
      $data['t'] = $time;
      $data['position_remark'] = $position_remark;
      echo json_encode($data);
    }else {
      echo mysqli_error($conn);
    }
}
}
 ?>
