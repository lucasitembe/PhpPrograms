<?php
include('../includes/connection.php');
if (isset($_POST['field_name']) && isset($_POST['registration_id'])){
  $fiels_name  = mysqli_real_escape_string($conn,trim($_POST['field_name']));
  $field_value = mysqli_real_escape_string($conn,trim($_POST['field_data']));
  $registration_id = mysqli_real_escape_string($conn,trim($_POST['registration_id']));

  // echo $field_value;
  // check user alredy exist
  $select_patient_from_icu_form_three = "SELECT * FROM tbl_form_three_intro WHERE patient_id = '$registration_id'
   AND date_time != NOW()";

  if ($select_result = mysqli_query($conn,$select_patient_from_icu_form_three)) {
    $num = mysqli_num_rows($select_result);
  }else {
    echo mysqli_error($conn);
  }


  if ($num > 0) {
      $inset_icu_form_five = "UPDATE tbl_form_three_intro SET $fiels_name = '$field_value' WHERE patient_id='$registration_id'";

      if ($successfully_update = mysqli_query($conn,$inset_icu_form_five)) {
        echo "successfully saved";
      }else{
        echo mysqli_error($conn);
      }

  }else{
    $insert_patient_detailes_in_icu_form_five = "INSERT INTO tbl_form_three_intro(patient_id,date_time) VALUES('$registration_id',NOW())";
    if ($insert_successfully = mysqli_query($conn,$insert_patient_detailes_in_icu_form_five)) {
      $inset_icu_form_five = "UPDATE tbl_form_three_intro SET $fiels_name = '$field_value' WHERE patient_id='$registration_id'";

      if ($successfully_update = mysqli_query($conn,$inset_icu_form_five)) {
        echo "successfully saved";
      }
    }
  }

}

?>
