<?php
include("./includes/connection.php");
session_start();
$Employee_ID=$_SESSION['userinfo']['Employee_ID'];
$time=date("H:i");
if(isset($_POST['admission_id']) && isset($_POST['patient_id'])) {
  
  $patient_id = mysqli_real_escape_string($conn,trim($_POST['patient_id']));
  $admission_id = mysqli_real_escape_string($conn,trim($_POST['admission_id']));
 
  @$moulding = mysqli_real_escape_string($conn,trim($_POST['moulding']));
  @$moulding_time = mysqli_real_escape_string($conn,trim($_POST['moulding_time']));
  
// insert moulding

  if (!empty($moulding) || $moulding == 0) {
    $insert_fetal_heart_rate = "INSERT INTO tbl_moulding(patient_id,admission_id,moulding,moulding_time,date_time,time_hour,Employee_ID) VALUES('$patient_id','$admission_id','$moulding','$moulding_time',NOW(),'$time','$Employee_ID')";
    $data=array();
    if ($excute_fetal_rate = mysqli_query($conn,$insert_fetal_heart_rate)) {
      echo "Successfully saved";
    }else {
      echo mysqli_error($conn);
    }
    }

}


 ?>
