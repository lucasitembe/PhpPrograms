<?php
include("./includes/connection.php");
if (isset($_POST['patient_id']) && isset($_POST['admission_id'])) {
  $patient_id = $_POST['patient_id'];
  $admision_id = $_POST['admission_id'];


  $select_pulse = "SELECT DISTINCT * FROM tbl_resp WHERE patient_id='$patient_id' ORDER By resp_time ";

  $response = array();
  $data = array();

  if ($result = mysqli_query($conn,$select_pulse)) {
    while ($row = mysqli_fetch_assoc($result)) {

      $data['resp'] = $row['resp'];
      $data['resp_time'] = $row['resp_time'];

      array_push($response,$data);

    }
  echo json_encode($response);
  }else {
    echo mysqli_error($conn);
  }
}

?>
