<?php
include('../includes/connection.php');
if(isset($_POST['field_name']) && isset($_POST['registration_id'])){
  $fields_name  = mysqli_real_escape_string($conn,trim($_POST['field_name']));
  $field_value = mysqli_real_escape_string($conn,trim($_POST['field_data']));
  $registration_id = mysqli_real_escape_string($conn,trim($_POST['registration_id']));
  @$item_id = mysqli_real_escape_string($conn,trim($_POST['item_id']));

  $select_patient_form_two_evee_opening="SELECT * FROM tbl_icu_artery_gases WHERE patient_id='$registration_id' AND DATE(date_time)=CURDATE() AND item_id='$item_id'";

  $select_result = mysqli_query($conn,$select_patient_form_two_evee_opening);

    if(($num = mysqli_num_rows($select_result)) > 0){

      $update_eve_opening = "UPDATE tbl_icu_artery_gases SET $fields_name = '$field_value' WHERE item_id='$item_id' AND patient_id='$registration_id' ";


      if($eve_opening_updated  = mysqli_query($conn,$update_eve_opening)) {
        echo "successfully Updated";
      }
      else{
        echo mysqli_error($conn);
      }

    }else
    {
      if (!empty($field_value)){
      $insert_eve_opening = "INSERT INTO tbl_icu_artery_gases(patient_id,item_id,$fields_name,date_time) VALUES('$registration_id','$item_id','$field_value',NOW())";

      if($result_insert_eve = mysqli_query($conn,$insert_eve_opening))
      {
            echo "successfully inserted";
      }else
        {
          echo mysqli_error($conn);
        }
      }
    }
  }else {
    echo "no registration id";
  }

?>
