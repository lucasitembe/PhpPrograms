<?php
include("./includes/connection.php");
if (isset($_POST['patient_id']) && isset($_POST['admission_id'])) {
  $patient_id = $_POST['patient_id'];
  $admission_id = $_POST['admission_id'];
  session_start();
  $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
  $times=date("H:i");

  @$presenting_remark = $_POST['presenting_remark'];
  $time =$_POST['time'];


$data = array();

if (!empty($presenting_remark)) {
  $insert_oxytocine = "INSERT INTO tbl_presenting(patient_id,admission_id,presenting_remark,presenting_remark_time,Employee_ID) VALUES('$patient_id','$admission_id','$presenting_remark','$time','$Employee_ID')";


    if ($ressult = mysqli_query($conn,$insert_oxytocine)) {
      $data['t'] = $time;
      $data['presenting_remark'] = $presenting_remark;

      echo json_encode($data);
    }else {
      echo mysqli_error($conn);
    }

}

}
 ?>
