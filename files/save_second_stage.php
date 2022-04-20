<?php
include("./includes/connection.php");

$today = Date("Y-m-d");
if (isset($_POST['patient_id']) && $_POST['admission_id']) {
  $patient_id = $_POST['patient_id'];
  $admission_id = $_POST['admission_id'];
  $time_began = $_POST['time_began'];
  $date_of_birth = $_POST['date_of_birth'];
  $duration = $_POST['duration'];
  $mode_of_delivery = $_POST['mode_of_delivery'];
  $drug = $_POST['drug'];
  $remarks = $_POST['remarks'];

  $query_insert_second_stage = "INSERT INTO tbl_second_stage_of_labour(patient_id,admission_id,time_began,date_of_birth,
    duration,mode_of_delivery,drugs,remarks,date_time) 
    VALUES('$patient_id','$admission_id','$time_began','$date_of_birth','$duration','$mode_of_delivery','$drug','$remarks','$today')";

  if ($result_second_stage = mysqli_query($conn,$query_insert_second_stage)) {
    echo "Successsfully Saved";
  } else {
    echo mysqli_error($conn);
  }
}
?>
