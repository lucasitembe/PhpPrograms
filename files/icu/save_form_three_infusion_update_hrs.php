<?php
include('../includes/connection.php');
if(isset($_POST['field_name']) && isset($_POST['registration_id'])){
  $fiels_name  = mysqli_real_escape_string($conn,trim($_POST['field_name']));
  $field_value = mysqli_real_escape_string($conn,trim($_POST['field_data']));
  $registration_id = mysqli_real_escape_string($conn,trim($_POST['registration_id']));
  @$medic_id = mysqli_real_escape_string($conn,trim($_POST['medic_id']));
  // echo $medic_id;

  $select_patient_form_three_medical="SELECT * FROM     tbl_icu_formthree_infusion fm JOIN tbl_icu_form_three_infusion_hours fh
  ON fm.medic_id = fh.medication_id WHERE fm.patient_id='$registration_id' AND DATE(fm.date_time)=CURDATE() AND fm.medic_id='$medic_id'";

  $select_result = mysqli_query($conn,$select_patient_form_three_medical);

    $num = mysqli_num_rows($select_result);

    if($num > 0){
      $update_infusion_hours = "UPDATE tbl_icu_form_three_infusion_hours SET $fiels_name = '$field_value' WHERE medication_id='$medic_id' AND patient_id = '$registration_id'";

     if ($updateinfusion_hrs = mysqli_query($conn,$update_infusion_hours)) {
        echo "successfully updated";
      }
      else {
        echo mysqli_error($conn);
      }

    }else{
      echo "please fill in medication first";

    }
}
?>
