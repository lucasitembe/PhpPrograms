<?php
include("./includes/connection.php");
if (isset($_POST['patient_id']) && isset($_POST['admission_id'])) {
  $patient_id = $_POST['patient_id'];
  $admision_id = $_POST['admission_id'];


  $select_pulse = "SELECT DISTINCT * FROM tbl_volume WHERE patient_id='$patient_id' ORDER By volume_time ";


  $data = array();
  $response = array();

  if ($result = mysqli_query($conn,$select_pulse)) {
    while ($row = mysqli_fetch_assoc($result)) {

      // $data['protein'] = $row['protein'];
      $data['volume'] = $row['volume'];
      // $data['volume'] = $row['volume'];
      $data['volume_time'] = $row['volume_time'];
      array_push($response,$data);

    }
    echo json_encode($response);
  }else {
    echo mysqli_error($conn);
  }
}
 ?>
