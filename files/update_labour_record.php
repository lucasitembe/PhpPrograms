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
  $admission_reason = mysqli_real_escape_string($conn,trim($_POST['admission_reason']));
  $admission_from = mysqli_real_escape_string($conn,trim($_POST['from']));
  $dilation = mysqli_real_escape_string($conn,trim($_POST['dilation']));
  $temperature = mysqli_real_escape_string($conn,trim($_POST['temperature']));
  $lv_children = mysqli_real_escape_string($conn,trim($_POST['lv_children']));
  $para = mysqli_real_escape_string($conn,trim($_POST['para']));
  $gravida = mysqli_real_escape_string($conn,trim($_POST['gravida']));

$query_insert_labour_record ="UPDATE tbl_labour_record2 SET today_date='$today_date',summary_Antenatal='$summary_Antenatal',abnormalities='$abnormalities',lmp='$lmp',edd='$edd',ga='$ga',general_condition='$fundamental_height',fundamental_height='$fundamental_height',blood_pressure='$blood_pressure',size_fetus='$size_fetus',lie='$lie',oedema='$oedema',presentation='$presentation',acetone='$acetone',protein='$protein',liquor='$liquor',height='$height',meconium='$meconium',estimation_presentation='$estimation_presentation',membrane='$membrane',last_recorded='$last_recorded',blood_group='$blood_group',cervic_state='$cervic_state',presenting_part='$presenting_part',levels='$levels',position='$position',moulding='$moulding',caput='$caput',bony='$bony',membranes_liquor='$membranes_liquor',sacral_promontory='$sacral_promontory',sacral_curve='$sacral_curve',Lachial_spines='$Lachial_spines',subpubic_angle='$subpubic_angle',sacral_tuberosites='$sacral_tuberosites',summary='$summary',remarks='$remarks',Employee_ID='$Employee_ID',dilation='$dilation',temperature='$temperature',admission_reason='$admission_reason',admission_from='$admission_from',lv_children='$lv_children', para='$para', gravida='$gravida' WHERE Registration_ID='$Registration_ID' AND admission_id='$admission_id'";

  if ($result_insert_labour = mysqli_query($conn,$query_insert_labour_record) or die( mysqli_error($conn))) {

    $select_last_inserted_labour_record=mysqli_query($conn,"SELECT labour_record_ID FROM tbl_labour_record2 ORDER BY labour_record_ID DESC limit 1") or die( mysqli_error($conn));
    while($data=mysqli_fetch_array($select_last_inserted_labour_record)){
      $labour_record_ID=$data['labour_record_ID'];
    }
    echo "Labour Record Saved";
  } else {
    echo "Labour Record Not Saved";
  }
// }
?>
