<?php
include('../includes/connection.php');
if (isset($_POST['field_name']) && isset($_POST['registration_id'])){
  $fiels_name  = $_POST['field_name'];
  $field_value = $_POST['field_data'];
  $registration_id = $_POST['registration_id'];

  // echo $field_value;
  // check user alredy exist
  $select_patient_from_icu_from_seven = "SELECT * FROM tbl_icu_form_seven WHERE patient_id = '$registration_id' AND date_time != NOW()";

  $insert_result = mysqli_query($conn,$select_patient_from_icu_from_seven);
  $num = mysqli_num_rows($insert_result);

  if ($num > 0) {
      $inset_icu_form_seven = "UPDATE tbl_icu_form_seven SET $fiels_name = '$field_value' WHERE patient_id='$registration_id'";

      if ($successfully_update = mysqli_query($conn,$inset_icu_form_seven)) {
        echo "successfully saved";
      }else{
        echo mysqli_error($conn);
      }

  }else{
    $insert_patient_detailes_in_icu_form_seven = "INSERT INTO tbl_icu_form_seven(patient_id,date_time) VALUES('$registration_id',NOW())";
    if ($insert_successfully = mysqli_query($conn,$insert_patient_detailes_in_icu_form_seven)) {
      $inset_icu_form_seven = "UPDATE tbl_icu_form_seven SET $fiels_name = '$field_value' WHERE patient_id='$registration_id'";

      if ($successfully_update = mysqli_query($conn,$inset_icu_form_seven)) {
        echo "successfully saved";
      }
    }
  }

}

?>
