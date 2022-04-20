<?php
include("./includes/connection.php");
session_start();
$Employee_ID=$_SESSION['userinfo']['Employee_ID'];
$times=date("H:i");
if (isset($_POST['patient_id']) && isset($_POST['admission_id'])) {
  $patient_id = $_POST['patient_id'];
  $admission_id = $_POST['admission_id'];

$time = $_POST['time'];
$data = array();

if (isset($_POST['contraction'])) {
  $contraction = $_POST['contraction'];

  $insert_contraction = "INSERT INTO tbl_contraction(patient_id,admission_id,contraction,c_time,actual_time,time_hour,Employee_ID)
  VALUES('$patient_id','$admission_id','$contraction','$time',NOW(),'$times','$Employee_ID')";
if ($result = mysqli_query($conn,$insert_contraction)) {
  $data['t'] = $time;
  $data['c'] = $contraction;

echo json_encode($data);

}
}
}
 ?>
