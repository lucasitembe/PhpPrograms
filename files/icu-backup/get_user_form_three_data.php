<?php
include('../includes/connection.php');
if (isset($_POST['registration_id'])) {
  $registration_id = $_POST['registration_id'];

    $select_patient_data = "SELECT * FROM tbl_icu_formthree_medication fm JOIN tbl_icu_form_three_hours ON fm.medic_id = fh.medication_id WHERE fm.patient_id ='$registration_id' ";

    $server_response = array();

    if ($select_successfully = mysqli_query($conn,$select_patient_data)) {
      $num = mysql_num_row($select_successfully);
      if ($num > 0) {
        while ($row = mysqli_fetch_array($select_successfully)) {
          array_push($server_response,$row);
        }

        echo json_encode($server_response);
      }echo "no data";
    }else {
      mysqli_error($conn);
    }
}

 ?>
