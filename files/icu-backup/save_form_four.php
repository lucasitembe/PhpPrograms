<?php
include('../includes/connection.php');
if (isset($_POST['field_name']) && isset($_POST['registration_id'])){
  $fiels_name  = mysqli_real_escape_string($conn,trim($_POST['field_name']));
  $field_value = mysqli_real_escape_string($conn,trim($_POST['field_data']));
  $registration_id = mysqli_real_escape_string($conn,trim($_POST['registration_id']));

  // echo $field_value;
  // check user alredy exist
  $select_patient_from_icu_from_four = "SELECT * FROM
  tbl_icu_form_four f JOIN tbl_icu_form_foursecond fs ON f.patient_id = fs.patient_id WHERE f.patient_id = '$registration_id'
  AND f.date_time != NOW()";

  $select_result = mysqli_query($conn,$select_patient_from_icu_from_four);
  $num = mysqli_num_rows($select_result);

  if ($num > 0) {
      $inset_icu_form_four = "UPDATE tbl_icu_form_four SET $fiels_name = '$field_value' WHERE patient_id='$registration_id'";

      $inset_icu_form_foursecond = "UPDATE tbl_icu_form_foursecond SET $fiels_name = '$field_value' WHERE patient_id='$registration_id'";


      if ($successfully_update = mysqli_query($conn,$inset_icu_form_four)) {
        echo "successfully saved";
      }elseif ($successfully_update = mysqli_query($conn,$inset_icu_form_foursecond)) {
        echo "successfully saved";
      }
      else{
        echo mysqli_error($conn);
      }

  }else{
    $insert_patient_detailes_in_icu_form_four = "INSERT INTO tbl_icu_form_four(patient_id,date_time) VALUES('$registration_id',NOW())";


    $insert_patient_detailes_in_icu_form_foursecond = "INSERT INTO tbl_icu_form_foursecond(patient_id,date_time) VALUES('$registration_id',NOW())";

    if ($insert_successfully = mysqli_query($conn,$insert_patient_detailes_in_icu_form_four)) {
      $inset_icu_form_four = "UPDATE tbl_icu_form_four SET $fiels_name = '$field_value' WHERE patient_id='$registration_id'";

      if ($successfully_update = mysqli_query($conn,$inset_icu_form_four)) {
        echo "successfully saved";
      }

    }
    if($insert_successfullysecond = mysqli_query($conn,$insert_patient_detailes_in_icu_form_foursecond)) {
      $inset_icu_form_foursecond = "UPDATE tbl_icu_form_foursecond
      SET $fiels_name = '$field_value' WHERE patient_id='$registration_id'";

      if ($successfully_updatesecond = mysqli_query($conn,$inset_icu_form_foursecond)) {
        echo "successfully saved";
      }else{
        echo mysqli_error($conn);
      }
    }
  }

}

?>
