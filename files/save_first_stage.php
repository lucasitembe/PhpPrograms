<?php
include("./includes/connection.php");
if (isset($_POST['patient_id']) && $_POST['admission_id']) {
  $patient_id = mysqli_real_escape_string($conn,trim($_POST['patient_id']));
  $admission_id = mysqli_real_escape_string($conn,trim($_POST['admission_id']));
  $set_labour_time_and_date = mysqli_real_escape_string($conn,trim($_POST['set_labour_time_and_date']));
  $admitted_at = mysqli_real_escape_string($conn,trim($_POST['admitted_at']));
  $if_ruptured_date_and_time = mysqli_real_escape_string($conn,trim($_POST['if_rupture_date_and_time']));
  $total_time_elapsed_since_rupture = mysqli_real_escape_string($conn,trim($_POST['total_time_elapsed_since_rupture']));
  $yes_reasons = mysqli_real_escape_string($conn,trim($_POST['yes_reason']));
  $state_of_membrane = mysqli_real_escape_string($conn,trim($_POST['state_of_membrane']));
  $duration_of_first_labour = mysqli_real_escape_string($conn,trim($_POST['duration_of_first_stage_labour']));
  $abdomalities = mysqli_real_escape_string($conn,trim($_POST['abdomalities_first_stage']));
  $drug_given = mysqli_real_escape_string($conn,trim($_POST['drug_given']));
  $remark = mysqli_real_escape_string($conn,trim($_POST['remarks']));
  $arm = mysqli_real_escape_string($conn,trim($_POST['arm']));
  $no_of_vaginal_examination = mysqli_real_escape_string($conn,trim($_POST['no_of_vaginal_examination']));
  $induction_of_labour = mysqli_real_escape_string($conn,trim($_POST['induction_of_labour']));


  $today = Date("Y-m-d");

  $query_insert_first_stage = "INSERT INTO tbl_first_stage_of_labour(patient_id,admission_id,set_of_labour_time_and_date,admitted_at,state_of_membrane,time_and_date_of_rupture,
    time_elapsed_since_rupture,arm,no_of_vaginal_examination,
    abdomalities_of_first_stage,induction_of_labour,induction_reason,
    total_duration_of_first_stage_labour,drugs_given,remarks,date_time) 
    VALUES('$patient_id','$admission_id','$set_labour_time_and_date','$admitted_at','$state_of_membrane','$if_ruptured_date_and_time','$total_time_elapsed_since_rupture','$arm','$no_of_vaginal_examination','$abdomalities',
    '$induction_of_labour','$yes_reasons','$duration_of_first_labour',
    '$drug_given','$remark','$today')";

  if ($result_first_stage = mysqli_query($conn,$query_insert_first_stage)) {
    echo "Successfull Saved there";
  } else {
    echo mysqli_error($conn);
  }
}
?>
