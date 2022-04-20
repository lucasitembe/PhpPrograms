<?php
include("./includes/connection.php");
session_start();
$Employee_ID=$_SESSION['userinfo']['Employee_ID'];
$time=date("H:i");

if (isset($_POST['patient_id']) && isset($_POST['admission_id'])) {
  $patient_id = $_POST['patient_id'];
  $admission_id = $_POST['admission_id'];
  $time=date("H:i");

  @$fx = $_POST['fx'];
  @$fy = $_POST['fy'];

  @$sx = $_POST['sx'];
  @$sy = $_POST['sy'];



$datax = array();
$datay = array();
if (!empty($fx) && !empty($fy)) {
  $insert_progress_of_labour = "INSERT INTO tbl_progress_of_labour(patient_id,admission_id,fx,fy,date_time,time_hours,Employee_ID)
  VALUES('$patient_id','$admission_id','$fx','$fy',NOW(),'$time','$Employee_ID')";

  if ($result = mysqli_query($conn,$insert_progress_of_labour)) {
    array_push($datax,$fx,$fy);

    echo json_encode($datax);
  }else {
    echo mysqli_error($conn);
  }
}


if (!empty($sx) && !empty($sy)) {
  $insert_progress_of_labour = "INSERT INTO tbl_progress_of_labour(patient_id,admission_id,sx,sy,date_time,time_hour,Employee_ID)

  VALUES('$patient_id','$admission_id','$sx','$sy',NOW(),'$time','$Employee_ID')";

  if ($result = mysqli_query($conn,$insert_progress_of_labour)) {
    array_push($datay,$sx,$sy);

    echo json_encode($datay);

}else {
  echo mysqli_error($conn);
}


}
}

 ?>
