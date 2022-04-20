<?php
include("./includes/connection.php");
if (isset($_POST['patient_id']) && isset($_POST['admission_id'])) {
  $patient_id = $_POST['patient_id'];
  $admission_id = $_POST['admission_id'];

  $pulse = $_POST['pulse'];
  $pulse_time = $_POST['pulse_time'];

  $data = array();

  // $data.push($pulse);
  // $data.push($pulse_time)
  $data['x'] = $pulse_time;
  $data['y'] = $pulse;
  // array_push($data,$pulse,$pulse_time);

  $insert_pulse = "INSERT INTO tbl_pulse(patient_id,admission_id,pulse,pulse_time,actual_pulse_time) VALUES('$patient_id','$admission_id','$pulse','$pulse_time',NOW())";

if ($ressult = mysqli_query($conn,$insert_pulse)) {
  echo  json_encode($data);
}else {
  echo mysqli_error($conn);
}

}
 ?>
