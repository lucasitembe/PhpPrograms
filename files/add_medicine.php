<?php
include("./includes/connection.php");
if (isset($_POST['patient_id']) && isset($_POST['admission_id'])) {
  $patient_id = $_POST['patient_id'];
  $admission_id = $_POST['admission_id'];
  $data_name = array();
  $data_id = array();
if (isset($_POST['med_name'])) {
  $med =  $_POST['med_name'];
  $data_name = explode(",",$med);
}
if (isset($_POST['medicine'])) {
  $data_id = $_POST['medicine'];
}
if (isset($_POST['time'])) {
  $time = $_POST['time'];
}
$data = array();
  $size = sizeof($data_id);
for ($i=0; $i <sizeof($data_id); $i++) {
  $insert_medicine = "INSERT INTO tbl_medicine(patient_id,admission_id,medicine_time,med_short_name,actual_time,
    med_id) VALUES('$patient_id','$admission_id','$time','".$data_name[$i] ."',
    NOW(),'".$data_id[$i]."')";

$count = $i;
$ressult = mysqli_query($conn,$insert_medicine);
if ($count == $size) {
  $data = "Done";
}
}
  echo json_encode($data);

}
 ?>
