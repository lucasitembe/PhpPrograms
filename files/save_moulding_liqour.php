<?php
include("./includes/connection.php");
session_start();
$Employee_ID=$_SESSION['userinfo']['Employee_ID'];
$time=date("H:i");
if (isset($_POST['admission_id']) && isset($_POST['patient_id'])) {
  $patient_id = mysqli_real_escape_string($conn,trim($_POST['patient_id']));
  $admission_id = mysqli_real_escape_string($conn,trim($_POST['admission_id']));
  @$liqour_remark = mysqli_real_escape_string($conn,trim($_POST['liqour_remark']));
  // @$moulding = mysqli_real_escape_string($conn,trim($_POST['moulding']));
  // @$moulding_time = mysqli_real_escape_string($conn,trim($_POST['liqour_remark_time']));
  @$liqour_remark_time = mysqli_real_escape_string($conn,trim($_POST['liqour_remark_time']));
// insert liqour remark

if (!empty($liqour_remark)) {
  $insert_fetal_heart_rate = "INSERT INTO tbl_mould_liqour(patient_id,admission_id,liqour_remark,liqour_remark_time,
    date_time,time_hour,Employee_ID) VALUES('$patient_id','$admission_id','$liqour_remark','$liqour_remark_time',NOW(),'$time','$Employee_ID')";
  $data=array();
  if ($excute_fetal_rate = mysqli_query($conn,$insert_fetal_heart_rate)) {
    echo "Successfully saved";
  }else {
    echo mysqli_error($conn);
  }
  }

// insert moulding
  // if (!empty($moulding)) {
  //   $insert_fetal_heart_rate = "INSERT INTO tbl_mould_liqour(patient_id,admission_id,moulding,moulding_time,date_time) VALUES('$patient_id','$admission_id','$moulding','$moulding_time',NOW())";
  //   $data=array();
  //   if ($excute_fetal_rate = mysqli_query($conn,$insert_fetal_heart_rate)) {
  //     echo "Successfully saved";
  //   }else {
  //     echo mysqli_error($conn);
  //   }
  //   }

}



 ?>
