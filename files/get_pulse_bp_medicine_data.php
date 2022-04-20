<?php
include("./includes/connection.php");
if (isset($_POST['patient_id']) && isset($_POST['admission_id'])) {
  $patient_id = $_POST['patient_id'];
  $admision_id = $_POST['admission_id'];


  $select_pulse = "SELECT DISTINCT pulse,b.bp_start,p.pulse_time,p.actual_pulse_time,b.actual_bp_time,b.bp_end FROM
  tbl_pulse p JOIN tbl_bp b ON
  p.patient_id=b.patient_id WHERE p.patient_id='$patient_id' AND b.patient_id='$patient_id' and admission_id='$admision_id' GROUP BY p.pulse_time ORDER By p.pulse_time ";

  $response = array();
  $data = array();


  if ($result = mysqli_query($conn,$select_pulse)) {
    while ($row = mysqli_fetch_assoc($result)) {
      $data['pulse'] = $row['pulse'];
      $data['bp_start'] = $row['bp_start'];
      $data['bp_end'] = $row['bp_end'];
      $data['pulse_time'] = $row['pulse_time'];

      array_push($response,$data);
    }

    echo json_encode($response);

  }else {
    echo mysqli_error($conn);
  }
}
 ?>
