<?php
include("./includes/connection.php");
if (isset($_POST['patient_id']) && isset($_POST['admission_id']) ) {
  $date_of_first_visit = mysqli_real_escape_string($conn,trim($_POST['a_date']));
  $patient_id = mysqli_real_escape_string($conn,trim($_POST['patient_id']));
  $admission_id = mysqli_real_escape_string($conn,trim($_POST['admission_id']));
  $consultation_id = mysqli_real_escape_string($conn,trim($_POST['consultation_id']));
  $height = mysqli_real_escape_string($conn,trim($_POST['height']));
  $weight = mysqli_real_escape_string($conn,trim($_POST['weight']));
  $bp = mysqli_real_escape_string($conn,trim($_POST['bp']));
  $fulse = mysqli_real_escape_string($conn,trim($_POST['fulse']));
  $temp = mysqli_real_escape_string($conn,trim($_POST['temp']));
  $investigations = mysqli_real_escape_string($conn,trim($_POST['investigations']));
  $hb = mysqli_real_escape_string($conn,trim($_POST['hb']));
  $bloodgroup = mysqli_real_escape_string($conn,trim($_POST['bloodgroup']));
  $vdrl = mysqli_real_escape_string($conn,trim($_POST['vdrl']));
  $blisa = mysqli_real_escape_string($conn,trim($_POST['blisa']));
  $colour = mysqli_real_escape_string($conn,trim($_POST['colour']));
  $blood = mysqli_real_escape_string($conn,trim($_POST['blood']));
  $specific_gravity = mysqli_real_escape_string($conn,trim($_POST['specific_gravity']));
  $keytones = mysqli_real_escape_string($conn,trim($_POST['keytones']));
  $ph = mysqli_real_escape_string($conn,trim($_POST['ph']));
  $lie = mysqli_real_escape_string($conn,trim($_POST['lie']));
  $urobilinogen = mysqli_real_escape_string($conn,trim($_POST['urobilinogen']));
  $alumin = mysqli_real_escape_string($conn,trim($_POST['alumin']));
  $leucocetes = mysqli_real_escape_string($conn,trim($_POST['leucocetes']));
  $sugar = mysqli_real_escape_string($conn,trim($_POST['sugar']));
  $head = mysqli_real_escape_string($conn,trim($_POST['head']));
  $neck = mysqli_real_escape_string($conn,trim($_POST['neck']));
  $eyes = mysqli_real_escape_string($conn,trim($_POST['eyes']));
  $ears = mysqli_real_escape_string($conn,trim($_POST['ears']));
  $teeth = mysqli_real_escape_string($conn,trim($_POST['teeth']));
  $breast = mysqli_real_escape_string($conn,trim($_POST['breast']));
  $axilla = mysqli_real_escape_string($conn,trim($_POST['axilla']));
  $size = mysqli_real_escape_string($conn,trim($_POST['size']));
  $varicobe_veins= mysqli_real_escape_string($conn,trim($_POST['varicobe_veins']));
  $shape = mysqli_real_escape_string($conn,trim($_POST['shape']));
  $scar = mysqli_real_escape_string($conn,trim($_POST['scar']));
  $skin = mysqli_real_escape_string($conn,trim($_POST['skin']));
  $oval_pendulus = mysqli_real_escape_string($conn,trim($_POST['oval_pendulus']));
  $fundal_height = mysqli_real_escape_string($conn,trim($_POST['fundal_height']));
  $presenting_part = mysqli_real_escape_string($conn,trim($_POST['presenting_part']));
  $position = mysqli_real_escape_string($conn,trim($_POST['position']));
  $deep_pelvic_palpation = mysqli_real_escape_string($conn,trim($_POST['deep_pelvic_palpation']));
  $engagement_in_relationship_to_brim = mysqli_real_escape_string($conn,trim($_POST['engagement_in_relationship_to_brim']));
  $fetal_heart_rate = mysqli_real_escape_string($conn,trim($_POST['fetal_heart_rate']));
  $sonicard = mysqli_real_escape_string($conn,trim($_POST['sonicard']));
  $fetoscope = mysqli_real_escape_string($conn,trim($_POST['fetoscope']));
  $external = mysqli_real_escape_string($conn,trim($_POST['external']));
  $herpes = mysqli_real_escape_string($conn,trim($_POST['herpes']));
  $warts = mysqli_real_escape_string($conn,trim($_POST['warts']));
  $haemorrhoids = mysqli_real_escape_string($conn,trim($_POST['haemorrhoids']));
  $any_other = mysqli_real_escape_string($conn,trim($_POST['any_other']));
  $warcobe_veins = mysqli_real_escape_string($conn,trim($_POST['warcobe_veins']));
  $odema = mysqli_real_escape_string($conn,trim($_POST['oedema']));
  // $varicobe_vein = mysqli_real_escape_string($conn,trim($_POST['varicobe_vein']));
  $other_abdomalities = mysqli_real_escape_string($conn,trim($_POST['other_abdomalities']));

  // this for another table
  // $date = mysqli_real_escape_string($conn,trim($_POST['date']));
  // $sugar = mysqli_real_escape_string($conn,trim($_POST['sugar']));
  // $weight = mysqli_real_escape_string($conn,trim($_POST['weight']));
  // $up = mysqli_real_escape_string($conn,trim($_POST['up']));
  // $remarks = mysqli_real_escape_string($conn,trim($_POST['remarks']));
  // $pregnancy_weeks_by_date = mysqli_real_escape_string($conn,trim($_POST['pregnancy_weeks_by_date']));
  // $pregenancy_weeks_by_size = mysqli_real_escape_string($conn,trim($_POST['pregenancy_weeks_by_size']));
  // $presentation_in_relation_to_the_brim = mysqli_real_escape_string($conn,trim($_POST['presentation_in_relation_to_the_brim']));
  // $poetal_heart_rate = mysqli_real_escape_string($conn,trim($_POST['poetal_heart_rate']));


$query_insert_into_antenatal_record = "INSERT INTO tbl_atenal_record(patient_id,admission_id,atenal_date,height,weight,bp,fulse,
  temp,investigations,hb,bloodgroup,vdrl,blisa,colour,specific_gravity,ph,
  alumin,sugar,blood,keytones,urobilinogen,leucocetes,head,neck,eye,ears,teeth,
  breast,axilla,size,shape,scar,skin,oval_pendulus,fundal_height,lie,
  presenting_part,position,deep_pelvic_palpation,
  engagement_in_relationship_to_brim,fetal_heart_rate,sonicard,
  fetoscope,external,herpes,warts,haemorrhoids,any_other,
  warcobe_veins,odema,varicobe_veins,other_abdomalities) VALUES('$patient_id','$admission_id','$date_of_first_visit','$height','$weight','$bp','$fulse','$temp','$investigations','$hb','$bloodgroup','$vdrl',
    '$blisa',
  '$colour','$specific_gravity','$ph','$alumin','$sugar','$blood','$keytones',
  '$urobilinogen','$leucocetes','$head','$neck','$eyes','$ears','$teeth',
  '$breast','$axilla','$size','$shape','$scar','$skin','$oval_pendulus',
  '$fundal_height','$lie','$presenting_part','$position',
  '$deep_pelvic_palpation',
  '$engagement_in_relationship_to_brim','$fetal_heart_rate','$sonicard',
  '$fetoscope',
  '$external','$herpes','$warts','$haemorrhoids','$any_other',
  '$warcobe_veins','$odema','$varicobe_veins','$other_abdomalities')";


  if ($result_insert_antenatal = mysqli_query($conn,$query_insert_into_antenatal_record)) {
    echo "Successfull y Inserted";
  }else {
    echo mysqli_error($conn);
  }
}


 ?>
