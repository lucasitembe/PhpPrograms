<?php
include("./includes/connection.php");
session_start();
$Employee_ID=$_SESSION['userinfo']['Employee_ID'];
$time=date("H:i");
if (isset($_GET['x']) && isset($_GET['y']) && isset($_GET['patient_id'])
&& isset($_GET['patient_id'])) {
  $patient_id = mysqli_real_escape_string($conn,trim($_GET['patient_id']));
  $admission_id = mysqli_real_escape_string($conn,trim($_GET['admission_id']));
  $x = mysqli_real_escape_string($conn,trim($_GET['x']));
  $y = mysqli_real_escape_string($conn,trim($_GET['y']));

// insert data to cache
$insert_fetal_heart_rate = "INSERT INTO tbl_fetal_heart_rate_cache(patient_id,admission_id,x,y,date_time,time_hours,Employee_ID) VALUES('$patient_id','$admission_id','$x','$y',NOW(),'$time','$Employee_ID')";
$data=array();
if ($excute_fetal_rate = mysqli_query($conn,$insert_fetal_heart_rate)) {
  array_push($data,$y);
  array_push($data,$x);

  echo json_encode($data);
}else {
  echo mysqli_error($conn);
}
}

 ?>
