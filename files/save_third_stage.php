<?php
include("./includes/connection.php");

$today = Date("Y-m-d");
if (isset($_POST['patient_id']) && isset($_POST['admission_id'])) {

  $patient_id = mysqli_real_escape_string($conn,trim($_POST['patient_id']));
  $admission_id = mysqli_real_escape_string($conn,trim($_POST['admission_id']));
  $consultation_id = mysqli_real_escape_string($conn,trim($_POST['consultaion_id']));
  $method_of_delivery_of_placenter = mysqli_real_escape_string($conn,trim($_POST['method_of_delivery_of_placenter']));
  $date_and_time = mysqli_real_escape_string($conn,trim($_POST['date_and_time']));
  $duration = mysqli_real_escape_string($conn,trim($_POST['duration']));
  $placenta_weight = mysqli_real_escape_string($conn,trim($_POST['placenta_weight']));
  $colour = mysqli_real_escape_string($conn,trim($_POST['colour']));
  $cord = mysqli_real_escape_string($conn,trim($_POST['cord']));
  $state_of_cervix = mysqli_real_escape_string($conn,trim($_POST['state_of_cervix']));
  $episiotomy_tear = mysqli_real_escape_string($conn,trim($_POST['episiotomy_tear']));
  $repaired_with_suture = mysqli_real_escape_string($conn,trim($_POST['repaired_with_suture']));
  $total_blood_loss = mysqli_real_escape_string($conn,trim($_POST['total_blood_loss']));
  $lochia = mysqli_real_escape_string($conn,trim($_POST['lochia']));
  $state_of_uterus = mysqli_real_escape_string($conn,trim($_POST['state_of_uterus']));
  $t = mysqli_real_escape_string($conn,trim($_POST['t']));
  $p = mysqli_real_escape_string($conn,trim($_POST['p']));
  $bp = mysqli_real_escape_string($conn,trim($_POST['bp']));
  $r = mysqli_real_escape_string($conn,trim($_POST['r']));
  $disposal = mysqli_real_escape_string($conn,trim($_POST['disposal']));
  $membrane = mysqli_real_escape_string($conn,trim($_POST['membrane']));
  $stage_of_placent = mysqli_real_escape_string($conn,trim($_POST['stage_of_placent']));
  $remarks = mysqli_real_escape_string($conn,trim($_POST['remarks']));

  $query_insert_third_stage = "INSERT INTO tbl_third_stage_of_labour(
    patient_id,admission_id,methodology_delivery_placenter,date_and_time,
    duration,placenter_weight,stage_of_placent,colour,cord,membranes,disposal,
    state_of_cervix,episiotomy_tear,repaired_with_sutures,total_blood_loss,t,p,
    r,bp,lochia,state_of_uterus,remarks,date_time) 
    VALUES('$patient_id','$admission_id','$method_of_delivery_of_placenter',
      '$date_and_time','$duration','$placenta_weight','$stage_of_placent',
      '$colour','$cord','$membrane','$disposal','$state_of_cervix',
      '$episiotomy_tear','$repaired_with_suture','$total_blood_loss','$t','$p',
      '$r','$bp','$lochia','$state_of_uterus','$remarks','$today')";

  if ($result_third_stage = mysqli_query($conn,$query_insert_third_stage)) {
    echo "Successfully saved";
  } else {
    echo mysqli_error($conn);
  }
}


?>
