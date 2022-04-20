<?php
include("./includes/connection.php");
if (isset($_POST['patient_id']) && isset($_POST['admission_id'])) {
  $patient_id = $_POST['patient_id'];
  $admission_id = $_POST['admission_id'];

  $time = $_POST['time'];
  if (isset($_POST['oxytocine'])){
    $oxytocine = $_POST['oxytocine'];


    $insert_volume = "INSERT INTO tbl_oxytocine_drops(patient_id,admission_id,oxytocine,oxytocine_time,
      actual_time) VALUES('$patient_id','$admission_id','$oxytocine','$time',NOW())";
    if ($result = mysqli_query($conn,$insert_volume)) {
      $data['t'] = $time;

      $data['protein'] = $oxytocine;
      echo json_encode($data);
    }else{
      echo mysqli_error($conn);
    }

  }

if (isset($_POST['drops'])) {
  $drops = $_POST['drops'];

  $insert_volume = "INSERT INTO tbl_oxytocine_drops(patient_id,admission_id,drops,oxytocine_time,actual_time) VALUES('$patient_id','$admission_id','$drops','$time',NOW())";
  if ($result = mysqli_query($conn,$insert_volume)) {
    $data['t'] = $time;
    $data['protein'] = $drops;

    echo json_encode($data);
  }else{
    echo mysqli_error($conn);
  }

}


}
 ?>
