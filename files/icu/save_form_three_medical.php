<?php
include('../includes/connection.php');
if(isset($_POST['field_name']) && isset($_POST['registration_id'])){
  $fiels_name  = mysqli_real_escape_string($conn,trim($_POST['field_name']));
  $field_value = mysqli_real_escape_string($conn,trim($_POST['field_data']));
  $registration_id = mysqli_real_escape_string($conn,trim($_POST['registration_id']));
  $medic_id = mysqli_real_escape_string($conn,trim($_POST['medic_id']));
  // echo $medic_id;

  $select_patient_form_three_medical="SELECT * FROM     tbl_icu_formthree_medication fm JOIN tbl_icu_form_three_hours fh
  ON fm.medic_id = fh.medication_id WHERE fm.patient_id='$registration_id' AND DATE(fm.date_time)=CURDATE() AND fm.medic_id='$medic_id'";

  $select_result = mysqli_query($conn,$select_patient_form_three_medical);
    // while ($row = mysqli_fetch_assoc($select_result)) {
      // echo $row['medic_id'] . " now" ;
    // }
  // }else {
    // echo mysqli_error($conn);
  // }
    $num = mysqli_num_rows($select_result);
     echo "this numbers".  $num;
    if($num > 0){
        echo $num .'in';
      $update_medication = "UPDATE tbl_icu_formthree_medication SET medication = '$field_value' WHERE medic_id='$medic_id' AND DATE(date_time)=CURDATE()";
      if($update_medication  = mysqli_query($conn,$update_medication)) {
        echo "successfully Updated";
      }else {
        echo mysqli_error($conn);
      }
    }else{
      echo "no data now";
      if (!empty($field_value)){
      $insert_medication_hours = "INSERT INTO tbl_icu_formthree_medication(patient_id,medic_id,medication,date_time) VALUES('$registration_id','$medic_id','$field_value',NOW())";

      if($result_insert_medication = mysqli_query($conn,$insert_medication_hours)) {

        $last_id = mysql_insert_id();

        // echo $last_id;
        $insert_into_hour = "INSERT INTO tbl_icu_form_three_hours(medication_id,patient_id,date_time) VALUES('$medic_id','$registration_id',NOW())";

        if($insert_into_hour = mysqli_query($conn,$insert_into_hour)) {
            echo "successfully inserted";
        }else{
          echo mysqli_error($conn);
        }
      }
    }
    }
}

?>
