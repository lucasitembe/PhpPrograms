<?php
include("./includes/connection.php");
if (isset($_POST['patient_id']) && isset($_POST['admission_id'])) {
  $patient_id = $_POST['patient_id'];
  $admission_id = $_POST['admission_id'];
  session_start();
  $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
  $times=date("H:i");

  @$thin_remark = $_POST['thin_remark'];
  $time =$_POST['time'];
    // echo $thin_remark;

$data = array();

if (!empty($thin_remark)) {
  $insert_oxytocine = "INSERT INTO tbl_thin(patient_id,admission_id,thin_remark,thin_remark_time,Employee_ID) VALUES('$patient_id','$admission_id','$thin_remark','$time','$Employee_ID')";


    if ($ressult = mysqli_query($conn,$insert_oxytocine)) {
      $data['t'] = $time;
      $data['thin_remark'] = $thin_remark;

      echo json_encode($data);
    }else {
      echo mysqli_error($conn);
    }

}

}
 ?>
