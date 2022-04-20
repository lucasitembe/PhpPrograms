<?php
include("./includes/connection.php");
if (isset($_POST['patient_id']) && isset($_POST['admission_id'])) {
  $patient_id = $_POST['patient_id'];
  $admision_id = $_POST['admission_id'];


  $select_pulse = "SELECT  * FROM tbl_caput where patient_id='$patient_id' and admission_id='$admision_id' ORDER By caput_remark_time ";

  $response = array();
  $data = array();

  if ($result = mysqli_query($conn,$select_pulse)) {
    while ($row = mysqli_fetch_assoc($result)) {
      $data['caput_remark'] = $row['caput_remark'];
      $data['caput_remark_time'] = $row['caput_remark_time'];
      array_push($response,$data);
    }
  echo json_encode($response);
  }else {
    echo mysqli_error($conn);
  }
}
 ?>
