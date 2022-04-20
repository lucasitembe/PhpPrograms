<?php
include("./includes/connection.php");
if (isset($_POST['patient_id']) && isset($_POST['admission_id'])) {
  $patient_id = $_POST['patient_id'];
  $admision_id = $_POST['admission_id'];


  $select_pulse = "SELECT bp_start,bp_end,bp_time FROM tbl_bp WHERE patient_id='$patient_id' and admission_id='$admision_id' ORDER By bp_time ";

  $response = array();
  $data = array();


  if ($result = mysqli_query($conn,$select_pulse)) {
    while ($row = mysqli_fetch_assoc($result)) {
      $data['bp_start'] = $row['bp_start'];
      $data['bp_end'] = $row['bp_end'];
      $data['bp_time'] = $row['bp_time'];

      array_push($response,$data);
    }

    echo json_encode($response);

  }else {
    echo mysqli_error($conn);
  }
}
 ?>
