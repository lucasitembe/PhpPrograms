<?php
include("./includes/connection.php");
if (isset($_POST['patient_id']) && isset($_POST['admission_id'])) {
  $patient_id = $_POST['patient_id'];
  $admision_id = $_POST['admission_id'];
  // die("SELECT * FROM tbl_labour_position WHERE patient_id = '$patient_id' and admission_id='$admision_id' ORDER BY labour_position_remark_time");
  $select_mould_liqour = "SELECT * FROM tbl_labour_position WHERE patient_id = '$patient_id' and admission_id='$admision_id' ORDER BY labour_position_remark_time";
  $response = array();
  $data = array();

  if ($result = mysqli_query($conn,$select_mould_liqour)) {
    while ($row = mysqli_fetch_assoc($result)) {
    $data['labour_position_remark'] =$row['labour_position_remark'];
    $data['labour_position_remark_time'] = $row['labour_position_remark_time'];
    $data['date_time'] = $row['date_time'];
    array_push($response,$data);
    }
    echo json_encode($response);
  }else {
    echo mysqli_error($conn);
  }

}
 ?>
