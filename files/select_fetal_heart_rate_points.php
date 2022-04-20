<?php
include("./includes/connection.php");
if (isset($_POST['patient_id']) && isset($_POST['admission_id']) ) {
  $admision_id = $_POST['admission_id'];
  $patient_id = $_POST['patient_id'];

// echo $patient_id;
$response = array();
// get patient_id fetal graph if exidt:
$select_fetal_rate_point = "SELECT x,y FROM tbl_fetal_heart_rate_cache WHERE patient_id='$patient_id' AND admission_id='$admision_id'";
if ($result_points  = mysqli_query($conn,$select_fetal_rate_point)) {
  $data = array();
  if (($num_fetal = mysqli_num_rows($result_points)) > 0) {
    while ($row_fetal_rate = mysqli_fetch_assoc($result_points)) {
      $data['x'] = $row_fetal_rate['x'];
      $data['y'] = $row_fetal_rate['y'];
      array_push($response,$data);
      // array_push($response,$row_fetal_rate['y']);

    }
    echo json_encode($response);
  }else {

  }
}else {
  echo mysqli_error($conn);
}
// end
}

 ?>
