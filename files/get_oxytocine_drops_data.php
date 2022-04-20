<?php
include("./includes/connection.php");
if (isset($_POST['patient_id']) && isset($_POST['admission_id'])) {
  $patient_id = $_POST['patient_id'];
  $admision_id = $_POST['admission_id'];


  $select_contraction = "SELECT * FROM tbl_oxytocine_drops WHERE patient_id = '$patient_id' and admission_id='$admision_id' ORDER BY oxytocine_time";
  $response = array();
  $data = array();


  if ($result = mysqli_query($conn,$select_contraction)) {
    while ($row = mysqli_fetch_assoc($result)) {
    $data['oxytocine'] =$row['oxytocine'];
    $data['drops'] = $row['drops'];
    $data['oxytocine_time'] = $row['oxytocine_time'];
    $data['actual_time'] = $row['actual_time'];
    array_push($response,$data);
    }
    echo json_encode($response);
  }else {
    echo mysqli_error($conn);
  }

}
 ?>
