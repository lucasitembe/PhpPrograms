<?php
include('../includes/connection.php');
if (isset($_POST['status']) && isset($_POST['patient_id'])) {
  $status = $_POST['status'];
  $patient_id = $_POST['patient_id'];

  $insert_status = "UPDATE tbl_form_three_intro SET data_status = '$status' WHERE patient_id='$patient_id'";


  if ($pdate = mysqli_query($conn,$insert_status)) {
    echo "successfully Saved";
  }else {
    echo mysqli_error($conn);
  }
}else{
  echo "please pass user data";
}
 ?>
