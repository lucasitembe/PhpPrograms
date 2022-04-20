<?php
include('../includes/connection.php');

if (isset($_POST['patient_id'])) {
  $patient_id = $_POST['patient_id'];


$data = array();
  $select_status = "SELECT date_time,data_status FROM tbl_icu_form_five WHERE patient_id='$patient_id'";
  if ($status_selected = mysqli_query($conn,$select_status)) {
      while ($row = mysqli_fetch_assoc($status_selected)) {
        $data['data_status'] = $row['data_status'];
        $data['date_time'] = $row['date_time'];
      }
      echo json_encode($data);
  }else {
    echo mysqli_error($conn);
  }

}
 ?>
