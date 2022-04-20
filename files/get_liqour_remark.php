<?php
include("./includes/connection.php");
if (isset($_POST['patient_id']) && isset($_POST['admission_id'])) {
  $patient_id = $_POST['patient_id'];
  $admision_id = $_POST['admission_id'];


  $select_mould_liqour = "SELECT * FROM tbl_mould_liqour WHERE patient_id = '$patient_id' and admission_id='$admision_id' ORDER BY liqour_remark_time";
  $response = array();
  $data = array();


  if ($result = mysqli_query($conn,$select_mould_liqour)) {
    while ($row = mysqli_fetch_assoc($result)) {
    $data['liqour_remark'] =$row['liqour_remark'];
    $data['liqour_remark_time'] = $row['liqour_remark_time'];
    $data['date_time'] = $row['date_time'];
    array_push($response,$data);
    }
    echo json_encode($response);
  }else {
    echo mysqli_error($conn);
  }

}
 ?>
