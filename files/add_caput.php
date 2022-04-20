<?php
include("./includes/connection.php");
if (isset($_POST['patient_id']) && isset($_POST['admission_id'])) {
  $patient_id = $_POST['patient_id'];
  $admission_id = $_POST['admission_id'];
  session_start();
  $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
  $times=date("H:i");

  @$caput_remark = $_POST['caput_remark'];
  $time =$_POST['time'];


$data = array();

if (!empty($caput_remark)) {
  $insert_oxytocine = "INSERT INTO tbl_caput(patient_id,admission_id,caput_remark,caput_remark_time,Employee_ID) VALUES('$patient_id','$admission_id','$caput_remark','$time','$Employee_ID')";


    if ($ressult = mysqli_query($conn,$insert_oxytocine)) {
      $data['t'] = $time;
      $data['caput_remark'] = $caput_remark;

      echo json_encode($data);
    }else {
      echo mysqli_error($conn);
    }

}

}
 ?>
