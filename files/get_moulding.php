<?php
include("./includes/connection.php");
if (isset($_POST['patient_id']) && isset($_POST['admission_id'])) {
  $patient_id = $_POST['patient_id'];
  $admision_id = $_POST['admission_id'];


  $select_Moulding = "SELECT * FROM tbl_moulding WHERE patient_id = '$patient_id' and admission_id='$admision_id' ORDER BY moulding_time";
  $response = array();
  $data = array();


  if ($result = mysqli_query($conn,$select_Moulding)) {
    while ($row = mysqli_fetch_assoc($result)) {
    $data['moulding'] =$row['moulding'];
    $data['drops'] = $row['drops'];
    $data['moulding_time'] = $row['moulding_time'];
    $data['date_time'] = $row['date_time'];
    array_push($response,$data);
    }
    echo json_encode($response);
  }else {
    echo mysqli_error($conn);
  }

}
 ?>
