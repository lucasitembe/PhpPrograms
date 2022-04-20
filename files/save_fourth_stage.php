<?php
include("./includes/connection.php");
if (isset($_POST['patient_id']) && isset($_POST['admission_id'])) {
  $admision_id = mysqli_real_escape_string($conn,trim($_POST['admission_id']));
  $patient_id = mysqli_real_escape_string($conn,trim($_POST['patient_id']));
  $consultation_id = mysqli_real_escape_string($conn,trim($_POST['consultaion_id']));
  $bp = mysqli_real_escape_string($conn,trim($_POST['bp']));
  $temp = mysqli_real_escape_string($conn,trim($_POST['temp']));
  $pr = mysqli_real_escape_string($conn,trim($_POST['pr']));
  $state_of_uterus = mysqli_real_escape_string($conn,trim($_POST['state_of_uterus']));
  $fundal_height = mysqli_real_escape_string($conn,trim($_POST['fundal_height']));
  $state_of_cervix = mysqli_real_escape_string($conn,trim($_POST['state_of_cervix']));
  $state_of_perinium = mysqli_real_escape_string($conn,trim($_POST['state_of_perinium']));
  $suture_type = mysqli_real_escape_string($conn,trim($_POST['suture_type']));
  $how_many_stiches = mysqli_real_escape_string($conn,trim($_POST['how_many_stiches']));
  $blood_loss = mysqli_real_escape_string($conn,trim($_POST['blood_loss']));
  $doctor_recommendation = mysqli_real_escape_string($conn,trim($_POST['doctor_recommandation']));


  $query_insert_fourth_stage = "INSERT INTO tbl_fourth_stage_of_labour(patient_id,admission_id,temp,pr,bp,state_of_uterus,fundal_height,state_of_cervix,state_of_perinium,type_of_sature,
    number_of_stiches,blood_loss,doctor_midwife_recommendation) VALUES('$patient_id','$admision_id','$temp','$pr','$bp','$state_of_uterus',
      '$fundal_height','$state_of_cervix','$state_of_perinium','$suture_type',
      '$how_many_stiches','$blood_loss','$doctor_recommendation')";

      if ($fourth_stage_result = mysqli_query($conn,$query_insert_fourth_stage)){
        echo "Successfull Saved";
      }else {
        echo mysqli_error($conn);
      }
}
 ?>
