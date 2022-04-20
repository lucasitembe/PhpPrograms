<?php
include("./includes/connection.php");
session_start();
$Employee_ID=$_SESSION['userinfo']['Employee_ID'];
$today = Date("Y-m-d H:i:s");
$today_date = Date("Y-m-d");
// if (isset($_POST['Registration_ID']) && $_POST['admission_id']) {
  $Registration_ID = mysqli_real_escape_string($conn,trim($_POST['Registration_ID']));
  $admission_id = mysqli_real_escape_string($conn,trim($_POST['admission_id']));
  $from = mysqli_real_escape_string($conn,trim($_POST['from']));
  $summary_Antenatal = mysqli_real_escape_string($conn,trim($_POST['summary_Antenatal']));
  $abnormalities = mysqli_real_escape_string($conn,trim($_POST['abnormalities']));
  $lmp = mysqli_real_escape_string($conn,trim($_POST['lmp']));
  $edd = mysqli_real_escape_string($conn,trim($_POST['edd']));
  $ga = mysqli_real_escape_string($conn,trim($_POST['ga']));
  $general_condition = mysqli_real_escape_string($conn,trim($_POST['general_condition']));
  $fundamental_height = mysqli_real_escape_string($conn,trim($_POST['fundamental_height']));
  $blood_pressure = mysqli_real_escape_string($conn,trim($_POST['blood_pressure']));
  $size_fetus = mysqli_real_escape_string($conn,trim($_POST['size_fetus']));
  $lie = mysqli_real_escape_string($conn,trim($_POST['lie']));
  $oedema = mysqli_real_escape_string($conn,trim($_POST['oedema']));
  $presentation = mysqli_real_escape_string($conn,trim($_POST['presentation']));
  $acetone = mysqli_real_escape_string($conn,trim($_POST['acetone']));
  $protein = mysqli_real_escape_string($conn,trim($_POST['protein']));
  $liquor = mysqli_real_escape_string($conn,trim($_POST['liquor']));
  $height = mysqli_real_escape_string($conn,trim($_POST['height']));
  $meconium = mysqli_real_escape_string($conn,trim($_POST['meconium']));
  $estimation_presentation = mysqli_real_escape_string($conn,trim($_POST['estimation_presentation']));
  $membrane = mysqli_real_escape_string($conn,trim($_POST['membrane']));
  $last_recorded = mysqli_real_escape_string($conn,trim($_POST['last_recorded']));
  $blood_group = mysqli_real_escape_string($conn,trim($_POST['blood_group']));
  $date_time = mysqli_real_escape_string($conn,trim($_POST['date_time']));
  $cervic_state = mysqli_real_escape_string($conn,trim($_POST['cervic_state']));
  $presenting_part = mysqli_real_escape_string($conn,trim($_POST['presenting_part']));
  $levels = mysqli_real_escape_string($conn,trim($_POST['levels']));
  $position = mysqli_real_escape_string($conn,trim($_POST['position']));
  $moulding = mysqli_real_escape_string($conn,trim($_POST['moulding']));
  $caput = mysqli_real_escape_string($conn,trim($_POST['caput']));
  $bony = mysqli_real_escape_string($conn,trim($_POST['bony']));
  $membranes_liquor = mysqli_real_escape_string($conn,trim($_POST['membranes_liquor']));
  $sacral_promontory = mysqli_real_escape_string($conn,trim($_POST['sacral_promontory']));
  $sacral_curve = mysqli_real_escape_string($conn,trim($_POST['sacral_curve']));
  $Lachial_spines = mysqli_real_escape_string($conn,trim($_POST['Lachial_spines']));
  $subpubic_angle = mysqli_real_escape_string($conn,trim($_POST['subpubic_angle']));
  $sacral_tuberosites = mysqli_real_escape_string($conn,trim($_POST['sacral_tuberosites']));
  $summary = mysqli_real_escape_string($conn,trim($_POST['summary']));
  $remarks = mysqli_real_escape_string($conn,trim($_POST['remarks']));
  $dilation = mysqli_real_escape_string($conn,trim($_POST['dilation']));
  $temperature = mysqli_real_escape_string($conn,trim($_POST['temperature']));
  $admission_reason = mysqli_real_escape_string($conn,trim($_POST['admission_reason']));
  $lv_children = mysqli_real_escape_string($conn,trim($_POST['lv_children']));
  $gravida = mysqli_real_escape_string($conn,trim($_POST['gravida']));
  $para = mysqli_real_escape_string($conn,trim($_POST['para']));

  if (isset($_POST['history_year'])) {
    $history_year= $_POST['history_year'];
  } else {
    $history_year = '';
  }
  if (isset($_POST['history_complication'])) {
    $history_complication= $_POST['history_complication'];
  } else {
    $history_complication = '';
  }
  if (isset($_POST['gravida_method'])) {
    $gravida_method= $_POST['gravida_method'];
  } else {
    $gravida_method = '';
  }
  if (isset($_POST['gravida_alive'])) {
    $gravida_alive= $_POST['gravida_alive'];
  } else {
    $gravida_alive = '';
  }
  if (isset($_POST['gravida_wt'])) {
    $gravida_wt= $_POST['gravida_wt'];
  } else {
    $gravida_wt = '';
  }
  if (isset($_POST['para_year'])) {
    $para_year= $_POST['para_year'];
  } else {
    $para_year = '';
  }
  if (isset($_POST['para_complication'])) {
    $para_complication= $_POST['para_complication'];
  } else {
    $para_complication = '';
  }
  if (isset($_POST['living_method'])) {
    $living_method= $_POST['living_method'];
  } else {
    $living_method = '';
  }
  if (isset($_POST['living_wt'])) {
    $living_wt= $_POST['living_wt'];
  } else {
    $living_wt = '';
  }
  if (isset($_POST['living_alive'])) {
    $living_alive= $_POST['living_alive'];
  } else {
    $living_alive = '';
  }


  $query_insert_labour_record = "INSERT INTO tbl_labour_record2(Registration_ID, admission_id, Employee_ID, today_date, summary_Antenatal, abnormalities, lmp, edd, ga, general_condition, fundamental_height, blood_pressure, size_fetus, lie, oedema, presentation, acetone, protein, liquor, height, meconium, estimation_presentation, membrane, last_recorded, blood_group, cervic_state, presenting_part, levels, position, moulding, caput, bony, membranes_liquor, sacral_promontory, sacral_curve, Lachial_spines, subpubic_angle, sacral_tuberosites, summary, remarks,dilation,temperature,admission_reason,admission_from,lv_children,para,gravida) VALUES ('$Registration_ID','$admission_id','$Employee_ID','$today_date','$summary_Antenatal','$abnormalities','$lmp','$edd','$ga','$general_condition','$fundamental_height','$blood_pressure','$size_fetus','$lie','$oedema','$presentation','$acetone','$protein','$liquor','$height','$meconium','$estimation_presentation','$membrane','$last_recorded','$blood_group','$cervic_state','$presenting_part','$levels','$position','$moulding','$caput','$bony','$membranes_liquor','$sacral_promontory','$sacral_curve','$Lachial_spines','$subpubic_angle','$sacral_tuberosites','$summary','$remarks','$dilation','$temperature','$admission_reason','$from','$lv_children','$para','$gravida')";

  if ($result_insert_labour = mysqli_query($conn,$query_insert_labour_record) or die( mysqli_error($conn))) {
    $select_last_inserted_labour_record=mysqli_query($conn,"SELECT labour_record_ID FROM tbl_labour_record2 ORDER BY labour_record_ID DESC limit 1") or die( mysqli_error($conn));
    
    while($data=mysqli_fetch_array($select_last_inserted_labour_record)){
      $labour_record_ID=$data['labour_record_ID'];
    }
      for($i=0; $i< 3;$i++){
      $insert_history=mysqli_query($conn,"INSERT INTO tbl_labour_history( history_year, history_complication, gravida_method, gravida_wt, labour_record_ID,Employee_ID) VALUES ('$history_year[$i]','$history_complication[$i]','$gravida_method[$i]','$gravida_wt[$i]','$labour_record_ID','$Employee_ID')");
      if(!$insert_history){
        // echo "failed";
      }else{
        // echo "good";
      }
      
    }
    echo "Successfull Saved.";
    
  } else {
    echo "Data Not Saved";
  }
// }
?>
