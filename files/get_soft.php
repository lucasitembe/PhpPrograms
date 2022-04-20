<?php
include("./includes/connection.php");
if (isset($_POST['patient_id']) && isset($_POST['admission_id'])) {
  $patient_id = $_POST['patient_id'];
  $admision_id = $_POST['admission_id'];


  $select_pulse = "SELECT  * FROM tbl_soft where patient_id='$patient_id' and admission_id='$admision_id' ORDER By soft_remark_time ";

  $response = array();
  $data = array();

  if ($result = mysqli_query($conn,$select_pulse)) {
    while ($row = mysqli_fetch_assoc($result)) {
      $data['soft_remark'] = $row['soft_remark'];
      $data['soft_remark_time'] = $row['soft_remark_time'];
      array_push($response,$data);
    }
  echo json_encode($response);
  }else {
    echo mysqli_error($conn);
  }
}
 ?>
