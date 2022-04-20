<?php
include("./includes/connection.php");
if (isset($_POST['save_to_table'])) {
  $bp = mysqli_real_escape_string($conn,trim($_POST['bp']));
  $bp2 = mysqli_real_escape_string($conn,trim($_POST['bp2']));
  $hb = mysqli_real_escape_string($conn,trim($_POST['hb']));
  $hb2 = mysqli_real_escape_string($conn,trim($_POST['hb2']));
  $pmtct = mysqli_real_escape_string($conn,trim($_POST['pmtct']));
  $pmtct2 = mysqli_real_escape_string($conn,trim($_POST['pmtct2']));
  $vdrl = mysqli_real_escape_string($conn,trim($_POST['vdrl']));
  $vdrl2 = mysqli_real_escape_string($conn,trim($_POST['vdrl2']));
  $mrdt = mysqli_real_escape_string($conn,trim($_POST['mrdt']));
  $mrdt2 = mysqli_real_escape_string($conn,trim($_POST['mrdt2']));
  $urinalysis = mysqli_real_escape_string($conn,trim($_POST['urinalysis']));
  $urinalysis2 = mysqli_real_escape_string($conn,trim($_POST['urinalysis2']));
  $fefo = mysqli_real_escape_string($conn,trim($_POST['fefo']));
  $sp = mysqli_real_escape_string($conn,trim($_POST['sp']));
  $tt = mysqli_real_escape_string($conn,trim($_POST['tt']));
  $mebendazole = mysqli_real_escape_string($conn,trim($_POST['mebendazole']));
  $number_ofanc_visit = mysqli_real_escape_string($conn,trim($_POST['number_ofanc_visit']));
  $ga_at_1stvisit = mysqli_real_escape_string($conn,trim($_POST['ga_at_1stvisit']));
  $date_of_1stvsit = mysqli_real_escape_string($conn,trim($_POST['date_of_1stvsit']));
  $history_of_pregnancy = mysqli_real_escape_string($conn,trim($_POST['history_of_pregnancy']));
  $present_history = mysqli_real_escape_string($conn,trim($_POST['present_history']));
  $labour_history = mysqli_real_escape_string($conn,trim($_POST['labour_history']));
  $social_history = mysqli_real_escape_string($conn,trim($_POST['social_history']));
  $living = mysqli_real_escape_string($conn,trim($_POST['living']));
  $patient_id = mysqli_real_escape_string($conn,trim($_POST['patient_id']));
  $admission_id = mysqli_real_escape_string($conn,trim($_POST['admission_id']));
  $obstretic = mysqli_real_escape_string($conn,trim($_POST['obstretic']));
  $history = mysqli_real_escape_string($conn,trim($_POST['history']));
  $gravida = mysqli_real_escape_string($conn,trim($_POST['gravida']));
  $para = mysqli_real_escape_string($conn,trim($_POST['para']));
  $lmp = mysqli_real_escape_string($conn,trim($_POST['lmp']));
  $edd = mysqli_real_escape_string($conn,trim($_POST['edd']));
  $ga = mysqli_real_escape_string($conn,trim($_POST['ga']));
  $bloodgroup = mysqli_real_escape_string($conn,trim($_POST['bloodgroup']));
  $weight = mysqli_real_escape_string($conn,trim($_POST['weight']));
  $height = mysqli_real_escape_string($conn,trim($_POST['height']));
  $date_of_admission = mysqli_real_escape_string($conn,trim($_POST['date_of_admission']));
  $date_of_first_attendance = mysqli_real_escape_string($conn,trim($_POST['date_of_first_attendance']));
  $risk_factor = mysqli_real_escape_string($conn,trim($_POST['risk_factor']));
  $previous_therapy = mysqli_real_escape_string($conn,trim($_POST['previous_therapy']));
  $date_of_discharge = mysqli_real_escape_string($conn,trim($_POST['date_of_discharge']));
  $medical_surgical_history = mysqli_real_escape_string($conn,trim($_POST['medical_surgical_history']));
  $family_history = mysqli_real_escape_string($conn,trim($_POST['family_history']));
  $drug_allegies = mysqli_real_escape_string($conn,trim($_POST['drug_allegies']));
  $diagnosis_reason_for_admission = mysqli_real_escape_string($conn,trim($_POST['diagnosis_reason_for_admission']));


  // add these data to the table
  $year_of_birth = mysqli_real_escape_string($conn,trim($_POST['year_of_birth']));
  $date_and_time = mysqli_real_escape_string($conn,trim($_POST['date_and_time']));
  $matunity = mysqli_real_escape_string($conn,trim($_POST['matunity']));
  $gender = mysqli_real_escape_string($conn,trim($_POST['gender']));
  $mode_of_delivery = mysqli_real_escape_string($conn,trim($_POST['mode_of_delivery']));
  $birth_weight = mysqli_real_escape_string($conn,trim($_POST['birth_weight']));
  $place_of_birth = mysqli_real_escape_string($conn,trim($_POST['place_of_birth']));
  $breast_fed_duration = mysqli_real_escape_string($conn,trim($_POST['breast_fed_duration']));
  $pueperium = mysqli_real_escape_string($conn,trim($_POST['peuperium']));
  $present_child_condition = mysqli_real_escape_string($conn,trim($_POST['present_child_condition']));

if (empty($obstretic) && empty($history) && empty($bloodgroup) && empty($para) && empty($para)) {
  echo "Please Fill in Data";
}else{

  $query_insert_demographic_data = "INSERT INTO tbl_demographic(patient_id,admission_id,obstretic,history,gravida,para,lmp,edd,ga,bloodgroup,weight,
    height,date_of_admission,date_of_first_attendance,risk_factor,
    previous_therapy,date_of_discharge,medical_sergical_history,
    diagnosis_reason_for_admission,family_history,social_history,drug_allegies,living,present_history,labour_history,
    history_of_pregnancy,date_of_1stvsit,ga_at_1stvisit,number_ofanc_visit,bp,bp2,hb,hb2,pmtct,pmtct2,
    vdrl,vdrl2,mrdt,mrdt2,urinalysis,urinalysis2,fefo,sp,tt,mebendazole)
   VALUES('$patient_id','$admission_id','$obstretic','$history','$gravida','$para','$lmp','$edd','$ga',
     '$bloodgroup','$weight','$height','$date_of_admission',
     '$date_of_first_attendance','$risk_factor','$previous_therapy',
     '$date_of_discharge','$medical_surgical_history',
     '$diagnosis_reason_for_admission','$family_history','$social_history','$drug_allegies','$living','$present_history','$labour_history',
     '$history_of_pregnancy','$date_of_1stvsit','$ga_at_1stvisit','$number_ofanc_visit','$bp','$bp2','$hb','$hb2','$pmtct','$pmtct2',
     '$vdrl','$vdrl2','$mrdt','$mrdt2','$urinalysis','$urinalysis2','$fefo','$sp','$tt','$mebendazole')";

     if (mysqli_query($conn,$query_insert_demographic_data)){
       echo "data succcessfully saved";
     }else{
       echo "Failed to save " . mysqli_error($conn);
     }

     }
}


 ?>
