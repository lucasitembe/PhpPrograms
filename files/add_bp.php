<?php
include("./includes/connection.php");
if (isset($_POST['patient_id']) && isset($_POST['admission_id'])) {
  $patient_id = $_POST['patient_id'];
  $admission_id = $_POST['admission_id'];
  $bp_start = $_POST['bp_start'];
  $bp_end = $_POST['bp_end'];
  $time = $_POST['time'];

  $data =array();
  $data['x']=$bp_start;
  $data['y']=$bp_end;
  $data['t']=$time;


$insert_bp = "INSERT INTO tbl_bp(patient_id,admission_id,bp_start,bp_end,bp_time,actual_bp_time) VALUES('$patient_id','$admission_id','$bp_start','$bp_end','$time',NOW())";
if ($ressult = mysqli_query($conn,$insert_bp)) {
  echo json_encode($data);
}else {
  echo mysqli_error($conn);
}


}

 ?>
