<?php
include('../includes/connection.php');
if(isset($_POST['field_name']) && isset($_POST['registration_id'])){
  $fiels_name  = mysqli_real_escape_string($conn,trim($_POST['field_name']));
  $field_value = mysqli_real_escape_string($conn,trim($_POST['field_data']));
  $registration_id = mysqli_real_escape_string($conn,trim($_POST['registration_id']));
  $medic_id = mysqli_real_escape_string($conn,trim($_POST['medic_id']));

$update_medic_hour = "UPDATE tbl_icu_form_three_hours SET
$fiels_name = '$field_value'WHERE medication_id='$medic_id' AND patient_id='$registration_id'";


if($update_successfully = mysqli_query($conn,$update_medic_hour)) {
  echo "successfully updated";
}else{
  echo mysqli_error($conn);
}

}

 ?>
