<?php
include('../includes/connection.php');
// include("../MPDF/mpdf.php");

//header("Access-Control-Allow-Origin: *");
// if(strpos($_SERVER['HTTP_ORIGIN'], 'javascript') == false)
// 																			{
// 		header('Access-Control-Allow-Origin:'.$_SERVER['HTTP_ORIGIN']);
// }
//
//
// $data = json_decode(file_get_contents('php://input'));
if ($_POST['action'] == 'save_neonatal') {
 echo "reached";

}

if ($_POST['action1'] == 'save_neonatal') {
 echo "reached";
  // DATA tbl_neonatal_care_data
  $name_of_baby = mysqli_real_escape_string($conn,trim($data->name_of_baby));
  $referral_from = mysqli_real_escape_string($conn,trim($data->referral_from));
  $Employee_ID = mysqli_real_escape_string($conn,trim($data->Employee_ID));
  $Registration_ID = mysqli_real_escape_string($conn,trim($data->Registration_ID));
  $transer_from_maternity = mysqli_real_escape_string($conn,trim($data->transer_from_maternity));
  $date_birth = mysqli_real_escape_string($conn,trim($data->date_birth));
  $admission_date = mysqli_real_escape_string($conn,trim($data->admission_date));
  $length_cm = mysqli_real_escape_string($conn,trim($data->length_cm));
  $head_circumference_cm = mysqli_real_escape_string($conn,trim($data->head_circumference_cm));
  $pmtct = mysqli_real_escape_string($conn,trim($data->pmtct));
  $gender = mysqli_real_escape_string($conn,trim($data->gender));
  $apgar_score = mysqli_real_escape_string($conn,trim($data->apgar_score));
  $ga = mysqli_real_escape_string($conn,trim($data->ga));
  $birth_weight = mysqli_real_escape_string($conn,trim($data->birth_weight));
  $Admision_ID = mysqli_real_escape_string($conn,trim($data->Admision_ID));
  $consultation_id = mysqli_real_escape_string($conn,trim($data->consultation_id));

  $data_ID = 0;
  $error = false;
  $success = false;

  $sql_data = "INSERT INTO tbl_neonatal_care_data(
               name_of_baby,referral_from,Employee_ID,Registration_ID,Admision_ID,consultation_id,transer_from_maternity,date_birth,admission_date,length_cm,
               head_circumference_cm,pmtct,gender,apgar_score,ga,birth_weight)
               VALUES('$name_of_baby','$referral_from','$Employee_ID','$Registration_ID','$Admision_ID','$consultation_id','$transer_from_maternity','$date_birth',
               '$admission_date','$length_cm','$head_circumference_cm','$pmtct','$gender','$apgar_score','$ga','$birth_weight')";

               $execute = mysqli_query($conn,$sql_data);

               if($execute)
               {
                $data_ID = mysqli_insert_id($conn);
                $success = true;
                $error = false;
              }else {
                $success = false;
                $error = true;

              }


  //HISTORY-PREVIOUS tbl_neonatal_care_history_previous
  $chronical_maternal_illiness = mysqli_real_escape_string($conn,trim($data->chronical_maternal_illiness));
  $family_illnesses = mysqli_real_escape_string($conn,trim($data->family_illnesses));
  $gravida = mysqli_real_escape_string($conn,trim($data->gravida));
  $para = mysqli_real_escape_string($conn,trim($data->para));
  $number_of_living_children = mysqli_real_escape_string($conn,trim($data->number_of_living_children));
  $known_problem_of_living_children = mysqli_real_escape_string($conn,trim($data->known_problem_of_living_children));
  $complication_during_previous_pregnancies = mysqli_real_escape_string($conn,trim($data->complication_during_previous_pregnancies));
  $marital_status = mysqli_real_escape_string($conn,trim($data->marital_status));

  $sql_previous = "INSERT INTO tbl_neonatal_care_history_previous(
                   chronical_maternal_illiness,family_illnesses,gravida,para,number_of_living_children,known_problem_of_living_children,
                   complication_during_previous_pregnancies,marital_status,Employee_ID,Registration_ID,data_ID)
                   VALUES('$chronical_maternal_illiness','$family_illnesses','$gravida','$para','$number_of_living_children',
                   '$known_problem_of_living_children','$complication_during_previous_pregnancies','$marital_status','$Employee_ID',
                   '$Registration_ID','$data_ID')";

                   $xecute1 = mysqli_query($conn,$sql_previous);

                   if ($xecute1) {
                     $success = true;
                     $error = false;
                   }else {
                     $success = false;
                     $error = true;
                   }


  //Antenatal history tbl_neonatal_care_antenatal_history
  $lnmp = mysqli_real_escape_string($conn,trim($data->lnmp));
  $edd = mysqli_real_escape_string($conn,trim($data->edd));
  $vdrl = mysqli_real_escape_string($conn,trim($data->vdrl));
  $malaria = mysqli_real_escape_string($conn,trim($data->malaria));
  $hep_b = mysqli_real_escape_string($conn,trim($data->hep_b));
  $hb_level = mysqli_real_escape_string($conn,trim($data->hb_level));
  $hypertension = mysqli_real_escape_string($conn,trim($data->hypertension));
  $blood_pressure = mysqli_real_escape_string($conn,trim($data->blood_pressure));
  $drug_abuse = mysqli_real_escape_string($conn,trim($data->drug_abuse));
  $blood_group_rh = mysqli_real_escape_string($conn,trim($data->blood_group_rh));
  $anc_attended = mysqli_real_escape_string($conn,trim($data->anc_attended));
  $where_anc_done = mysqli_real_escape_string($conn,trim($data->where_anc_done));
  $number_of_visits = mysqli_real_escape_string($conn,trim($data->number_of_visits));
  $ga_at_1st_visit = mysqli_real_escape_string($conn,trim($data->ga_at_1st_visit));




  $sql_antenatal = "INSERT INTO tbl_neonatal_care_antenatal_history(
                    lnmp,edd,vdrl,malaria,hep_b,hb_level,hypertension,blood_pressure,drug_abuse,blood_group_rh,
                    anc_attended,where_anc_done,number_of_visits,ga_at_1st_visit,Employee_ID,Registration_ID,data_ID)
                    VALUES('$lnmp','$edd','$vdrl','$malaria','$hep_b','$hb_level','$hypertension','$blood_pressure',
                    '$drug_abuse','$blood_group_rh','$anc_attended','$where_anc_done','$number_of_visits','$ga_at_1st_visit',
                    '$Employee_ID','$Registration_ID','$data_ID')";

                    $xecute2 = mysqli_query($conn,$sql_antenatal);

                    if ($xecute2) {
                      $success = true;
                      $error = false;
                    }else {
                      $success = false;
                      $error = true;
                    }



  //Delivery history tbl_neonatal_care_delivery_history
  $maternal_fever = mysqli_real_escape_string($conn,trim($data->maternal_fever));
  $ab_treatment = mysqli_real_escape_string($conn,trim($data->ab_treatment));
  $ab_treatment_yes_drug = mysqli_real_escape_string($conn,trim($data->ab_treatment_yes_drug));
  $prom = mysqli_real_escape_string($conn,trim($data->prom));
  $prom_yes_hrs = mysqli_real_escape_string($conn,trim($data->prom_yes_hrs));
  $amniotic_fluid = mysqli_real_escape_string($conn,trim($data->amniotic_fluid));
  $abnormalities_of_placenta = mysqli_real_escape_string($conn,trim($data->abnormalities_of_placenta));
  $abnormalities_of_placenta_yes = mysqli_real_escape_string($conn,trim($data->abnormalities_of_placenta_yes));
  $abnormal_presentation = mysqli_real_escape_string($conn,trim($data->abnormal_presentation));
  $abnormal_presentation_yes = mysqli_real_escape_string($conn,trim($data->abnormal_presentation_yes));
  $mode_of_delivery = mysqli_real_escape_string($conn,trim($data->mode_of_delivery));
  $cs = mysqli_real_escape_string($conn,trim($data->cs));
  $indication = mysqli_real_escape_string($conn,trim($data->indication));
  $duration_of_cs = mysqli_real_escape_string($conn,trim($data->duration_of_cs));
  $duration_of_labour_stage1 = mysqli_real_escape_string($conn,trim($data->duration_of_labour_stage1));
  $duration_of_labour_stage2 = mysqli_real_escape_string($conn,trim($data->duration_of_labour_stage2));
  $duration_of_labour_stage3 = mysqli_real_escape_string($conn,trim($data->duration_of_labour_stage3));
  $obstructed_labour = mysqli_real_escape_string($conn,trim($data->obstructed_labour));
  $place_of_delivery = mysqli_real_escape_string($conn,trim($data->place_of_delivery));
  $delivery_attendant = mysqli_real_escape_string($conn,trim($data->delivery_attendant));
  $if_assisted_delivery_why = mysqli_real_escape_string($conn,trim($data->if_assisted_delivery_why));


  $sql_delivery = "INSERT INTO tbl_neonatal_care_delivery_history(
                   maternal_fever,ab_treatment,ab_treatment_yes_drug,prom,prom_yes_hrs,amniotic_fluid,abnormalities_of_placenta,
                   abnormalities_of_placenta_yes,abnormal_presentation,abnormal_presentation_yes,mode_of_delivery,cs,indication,
                   duration_of_cs,duration_of_labour_stage1,duration_of_labour_stage2,duration_of_labour_stage3,obstructed_labour,
                   place_of_delivery,delivery_attendant,if_assisted_delivery_why,Employee_ID,Registration_ID,data_ID)
                   VALUES('$maternal_fever','$ab_treatment','$ab_treatment_yes_drug','$prom','$prom_yes_hrs','$amniotic_fluid',
                   '$abnormalities_of_placenta','$abnormalities_of_placenta_yes','$abnormal_presentation','$abnormal_presentation_yes',
                   '$mode_of_delivery','$cs','$indication','$duration_of_cs','$duration_of_labour_stage1','$duration_of_labour_stage2',
                   '$duration_of_labour_stage3','$obstructed_labour','$place_of_delivery','$delivery_attendant','$if_assisted_delivery_why'
                    ,'$Employee_ID','$Registration_ID','$data_ID')";

                    $xecute3 = mysqli_query($conn,$sql_delivery);

                    if ($xecute3) {
                      $success = true;
                      $error = false;
                    }else {
                      $success = false;
                      $error = true;
                    }


  //Postnatal history tbl_neonatal_care_postnatal_history
  $problems_of_baby_after_birth = mysqli_real_escape_string($conn,trim($data->problems_of_baby_after_birth));
  $resuscitation = mysqli_real_escape_string($conn,trim($data->$resuscitation));
  $resuscitation_yes = mysqli_real_escape_string($conn,trim($data->resuscitation_yes));
  $eye_prophylaxis = mysqli_real_escape_string($conn,trim($data->eye_prophylaxis));
  $vitamin_K_given = mysqli_real_escape_string($conn,trim($data->vitamin_K_given));
  $drugs_given = mysqli_real_escape_string($conn,trim($data->drugs_given));
  $drugs_given_yes_which = mysqli_real_escape_string($conn,trim($data->drugs_given_yes_which));
  $feeding_started_within_1_hour = mysqli_real_escape_string($conn,trim($data->feeding_started_within_1_hour));
  $chief_complaints = mysqli_real_escape_string($conn,trim($data->chief_complaints));


  $sql_postnatal = "INSERT INTO tbl_neonatal_care_postnatal_history(
                    problems_of_baby_after_birth,resuscitation,resuscitation_yes,eye_prophylaxis,vitamin_K_given,
                    drugs_given,drugs_given_yes_which,feeding_started_within_1_hour,chief_complaints,Employee_ID,
                    Registration_ID,data_ID)
                    VALUES('$problems_of_baby_after_birth','$resuscitation','$resuscitation_yes','$eye_prophylaxis',
                  '$vitamin_K_given','$drugs_given','$drugs_given_yes_which','$feeding_started_within_1_hour',
                   '$chief_complaints','$Employee_ID','$Registration_ID','$data_ID')";

                   $xecute4 = mysqli_query($conn,$sql_postnatal);

                   if ($xecute4) {
                     $success = true;
                     $error = false;
                   }else {
                     $success = false;
                     $error = true;
                   }





  //History of the baby tbl_neonatal_care_baby_history
  $fever = mysqli_real_escape_string($conn,trim($data->fever));
  $vomiting = mysqli_real_escape_string($conn,trim($data->vomiting));
  $feeding = mysqli_real_escape_string($conn,trim($data->feeding));
  $enough_breast_milk = mysqli_real_escape_string($conn,trim($data->enough_breast_milk));
  $feeding_interval = mysqli_real_escape_string($conn,trim($data->feeding_interval));
  $passage_of_urine = mysqli_real_escape_string($conn,trim($data->passage_of_urine));
  $passage_of_stool = mysqli_real_escape_string($conn,trim($data->passage_of_stool));
  $quality = mysqli_real_escape_string($conn,trim($data->quality));
  $other_complaints = mysqli_real_escape_string($conn,trim($data->other_complaints));
  $baby_recieve_any_vaccines = mysqli_real_escape_string($conn,trim($data->baby_recieve_any_vaccines));




  $sql_baby = "INSERT INTO tbl_neonatal_care_baby_history(
               fever,vomiting,feeding,enough_breast_milk,feeding_interval,passage_of_urine,passage_of_stool,
               quality,other_complaints,baby_recieve_any_vaccines,Employee_ID,Registration_ID,data_ID
               )VALUES('$fever','$vomiting','$feeding','$enough_breast_milk','$feeding_interval','$passage_of_urine',
               '$passage_of_stool','$quality','$other_complaints','$baby_recieve_any_vaccines','$Employee_ID',
               '$Registration_ID','$data_ID')";

               $xecute5 = mysqli_query($conn,$sql_baby);

               if ($xecute5) {
                 $success = true;
                 $error = false;
               }else {
                 $success = false;
                 $error = true;
               }



  //PHYSICAL EXAMINATION  tbl_neonatal_care_physical_examination
  $weight = mysqli_real_escape_string($conn,trim($data->weight));
  $temp = mysqli_real_escape_string($conn,trim($data->temp));
  $pulse = mysqli_real_escape_string($conn,trim($data->pulse));
  $resp_rate = mysqli_real_escape_string($conn,trim($data->resp_rate));
  $SpO2 = mysqli_real_escape_string($conn,trim($data->SpO2));
  $rbg = mysqli_real_escape_string($conn,trim($data->rbg));
  $appearance_condition = mysqli_real_escape_string($conn,trim($data->appearance_condition));
  $appearance_activeness = mysqli_real_escape_string($conn,trim($data->appearance_activeness));
  $appearance_nourished = mysqli_real_escape_string($conn,trim($data->appearance_nourished));
  $appearance_Pathol = mysqli_real_escape_string($conn,trim($data->appearance_Pathol));
  $appearance_comment = mysqli_real_escape_string($conn,trim($data->appearance_comment));
  $skin_temperature = mysqli_real_escape_string($conn,trim($data->skin_temperature));
  $skin_color = mysqli_real_escape_string($conn,trim($data->skin_color));
  $skin_turgor = mysqli_real_escape_string($conn,trim($data->skin_turgor));
  $skin_cyanosed = mysqli_real_escape_string($conn,trim($data->skin_cyanosed));
  $skin_cyanosed_yes = mysqli_real_escape_string($conn,trim($data->skin_cyanosed_yes));
  $skin_rashes = mysqli_real_escape_string($conn,trim($data->skin_rashes));
  $skin_ctr = mysqli_real_escape_string($conn,trim($data->skin_ctr));
  $head1 = mysqli_real_escape_string($conn,trim($data->head1));
  $head1_shape = mysqli_real_escape_string($conn,trim($data->head1_shape));
  $head1_fontanelle = mysqli_real_escape_string($conn,trim($data->head1_fontanelle));
  $head1_sutures = mysqli_real_escape_string($conn,trim($data->head1_sutures));
  $head1_swelling_trauma = mysqli_real_escape_string($conn,trim($data->head1_swelling_trauma));
  $head1_size = mysqli_real_escape_string($conn,trim($data->head1_size));
  $head2_other_malformation = mysqli_real_escape_string($conn,trim($data->head2_other_malformation));
  $head2_eye_discharge = mysqli_real_escape_string($conn,trim($data->head2_eye_discharge));
  $neck_lymphadenopathy = mysqli_real_escape_string($conn,trim($data->neck_lymphadenopathy));
  $neck_lymphadenopathy_yes = mysqli_real_escape_string($conn,trim($data->neck_lymphadenopathy_yes));




  $sql_physical1 = "INSERT INTO tbl_neonatal_care_physical_examination(
                    weight,temp,pulse,resp_rate,SpO2,rbg,appearance_condition,appearance_activeness,appearance_nourished,
                    appearance_Pathol,appearance_comment,skin_temperature,skin_color,skin_turgor,
                    skin_cyanosed,skin_cyanosed_yes,skin_rashes,skin_ctr,head1,head1_shape,head1_fontanelle,head1_sutures,
                    head1_swelling_trauma,head1_size,head2_other_malformation,head2_eye_discharge,neck_lymphadenopathy,
                    neck_lymphadenopathy_yes,Registration_ID,data_ID
                    )VALUES('$weight','$temp','$pulse','$resp_rate','$SpO2','$rbg','$appearance_condition','$appearance_activeness',
                     '$appearance_nourished','$appearance_Pathol','$appearance_comment','$skin_temperature',
                     '$skin_color','$skin_turgor','$skin_cyanosed','$skin_cyanosed_yes','$skin_rashes','$skin_ctr','$head1','$head1_shape',
                     '$head1_fontanelle','$head1_sutures','$head1_swelling_trauma','$head1_size','$head2_other_malformation','$head2_eye_discharge',
                     '$neck_lymphadenopathy','$neck_lymphadenopathy_yes','$Registration_ID','$data_ID')";


                     $xecute6 = mysqli_query($conn,$sql_physical1);

                     if ($xecute6) {
                       $success = true;
                       $error = false;
                     }else {
                       $success = false;
                       $error = true;
                     }





  //tbl_neonatal_care_physical_examination2
  $neck_clavicle_fractured = mysqli_real_escape_string($conn,trim($data->neck_clavicle_fractured));
  $breathing_chest_movement = mysqli_real_escape_string($conn,trim($data->breathing_chest_movement));
  $breathing_indrawing = mysqli_real_escape_string($conn,trim($data->breathing_indrawing));
  $breathing_sounds = mysqli_real_escape_string($conn,trim($data->breathing_sounds));
  $breathing_preterm = mysqli_real_escape_string($conn,trim($data->breathing_preterm));
  $heart_rhythm = mysqli_real_escape_string($conn,trim($data->heart_rhythm));
  $heart_murmurs = mysqli_real_escape_string($conn,trim($data->heart_murmurs));
  $heart_describe = mysqli_real_escape_string($conn,trim($data->heart_describe));
  $abdomen = mysqli_real_escape_string($conn,trim($data->abdomen));
  $umbillical_cord = mysqli_real_escape_string($conn,trim($data->umbillical_cord));
  $genitalia_male = mysqli_real_escape_string($conn,trim($data->genitalia_male));
  $genitalia_testis = mysqli_real_escape_string($conn,trim($data->genitalia_testis));
  $genitalia_ambiguous = mysqli_real_escape_string($conn,trim($data->genitalia_ambiguous));
  $genitalia_female = mysqli_real_escape_string($conn,trim($data->genitalia_female));
  $genitalia_female_describe = mysqli_real_escape_string($conn,trim($data->genitalia_female_describe));
  $anus_patent = mysqli_real_escape_string($conn,trim($data->anus_patent));
  $anus_patent_no_describe = mysqli_real_escape_string($conn,trim($data->anus_patent_no_describe));
  $anus_abdnormality = mysqli_real_escape_string($conn,trim($data->anus_abdnormality));
  $back_posture = mysqli_real_escape_string($conn,trim($data->back_posture));
  $back_malformation = mysqli_real_escape_string($conn,trim($data->back_malformation));
  $back_malformation_hints = mysqli_real_escape_string($conn,trim($data->back_malformation_hints));
  $neurology_spotaneous_movement = mysqli_real_escape_string($conn,trim($data->neurology_spotaneous_movement));
  $neurology_musde_tone = mysqli_real_escape_string($conn,trim($data->neurology_musde_tone));
  $neurology_flexes_glasping = mysqli_real_escape_string($conn,trim($data->neurology_flexes_glasping));
  $neurology_flexes_sucking = mysqli_real_escape_string($conn,trim($data->neurology_flexes_sucking));
  $neurology_flexes_traction = mysqli_real_escape_string($conn,trim($data->neurology_flexes_traction));
  $neurology_flexes_moro = mysqli_real_escape_string($conn,trim($data->neurology_flexes_moro));
  $finnstroem_score = mysqli_real_escape_string($conn,trim($data->finnstroem_score));
  $additional_findings = mysqli_real_escape_string($conn,trim($data->additional_findings));
  $key_findings = mysqli_real_escape_string($conn,trim($data->key_findings));



  $sql_physical2 = "INSERT INTO tbl_neonatal_care_physical_examination2(
                    neck_clavicle_fractured,breathing_chest_movement,breathing_indrawing,breathing_sounds,breathing_preterm,heart_rhythm,
                    heart_murmurs,heart_describe,abdomen,umbillical_cord,genitalia_male,genitalia_testis,genitalia_ambiguous,genitalia_female,
                    genitalia_female_describe, anus_patent,anus_patent_no_describe,anus_abdnormality, back_posture,back_malformation,back_malformation_hints,
                    neurology_spotaneous_movement,neurology_musde_tone,
                    neurology_flexes_glasping,neurology_flexes_sucking,neurology_flexes_traction,neurology_flexes_moro,finnstroem_score,
                    additional_findings,key_findings,Registration_ID,data_ID)
                    VALUES('$neck_clavicle_fractured','$breathing_chest_movement','$breathing_indrawing','$breathing_sounds',
                    '$breathing_preterm','$heart_rhythm','$heart_murmurs','$heart_describe','$abdomen','$umbillical_cord',
                    '$genitalia_male','$genitalia_testis','$genitalia_ambiguous','$genitalia_female','$genitalia_female_describe','$anus_patent','$anus_patent_no_describe',
                     '$anus_abdnormality','$back_posture','$back_malformation','$back_malformation_hints','$neurology_spotaneous_movement',
                    '$neurology_musde_tone','$neurology_flexes_glasping','$neurology_flexes_sucking','$neurology_flexes_traction',
                    '$neurology_flexes_moro','$finnstroem_score','$additional_findings','$key_findings','$Registration_ID','$data_ID')";



                    $xecute7 = mysqli_query($conn,$sql_physical2);

                    if ($xecute7) {
                      $success = true;
                      $error = false;
                    }else {
                      $success = false;
                      $error = true;
                    }


  //MANAGEMENT tbl_neonatal_care_management
  $provisional_diagnoses = mysqli_real_escape_string($conn,trim($data->provisional_diagnoses));
  $differential_diagnoses = mysqli_real_escape_string($conn,trim($data->differential_diagnoses));
  $investigation = mysqli_real_escape_string($conn,trim($data->investigation));
  $treatment = mysqli_real_escape_string($conn,trim($data->treatment));
  $supportive_care = mysqli_real_escape_string($conn,trim($data->supportive_care));
  $preventions = mysqli_real_escape_string($conn,trim($data->preventions));


  $sql_management = "INSERT INTO tbl_neonatal_care_management(
                     provisional_diagnoses,differential_diagnoses,investigation,treatment,supportive_care,preventions,
                     Registration_ID,data_ID)
                     VALUES('$provisional_diagnoses','$differential_diagnoses','$investigation','$treatment','$supportive_care',
                     '$preventions','$Registration_ID','$data_ID')";


                     $xecute8 = mysqli_query($conn,$sql_management);

                     if ($xecute8) {
                       $success = true;
                       $error = false;
                     }else {
                       $success = false;
                       $error = true;
                     }


                     if ($success) {
                       echo "Record Added Successfully!";
                     }elseif ($error) {
                      die("Fail to add record".mysqli_error($conn));
                     }




}



$id = 0;
$year = date("Y");

// DATA tbl_neonatal_care_data
if ($_GET['action'] == 'get_data') {

  $Registration_ID = $_GET['Registration_ID'];
  $get_data = "SELECT data_ID,name_of_baby,referral_from,d.Employee_ID,d.Registration_ID,transer_from_maternity,date_birth,admission_date,length_cm,
               head_circumference_cm,pmtct,gender,apgar_score,ga,birth_weight,saved_time,e.Employee_ID,e.Employee_Name
               FROM tbl_neonatal_care_data d INNER JOIN tbl_employee e
               ON d.Employee_ID = e.Employee_ID
               WHERE d.Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' ORDER BY saved_time ASC LIMIT 1";
  $query1 = mysqli_query($conn,$get_data);
  $data_output = array();

  while($r1 = mysqli_fetch_assoc($query1)){
    $id = $r1['data_ID'];
    $data_output[] = $r1;
  }

  echo json_encode($data_output);
}


// DATA tbl_neonatal_care_data by year
if ($_GET['action'] == 'get_data1' && $_GET['year']) {
  $y = $_GET['year'];
  $Registration_ID = $_GET['Registration_ID'];
  $get_data1 = "SELECT data_ID,name_of_baby,referral_from,d.Employee_ID,d.Registration_ID,transer_from_maternity,date_birth,admission_date,length_cm,
               head_circumference_cm,pmtct,gender,apgar_score,ga,birth_weight,saved_time,e.Employee_ID,e.Employee_Name
               FROM tbl_neonatal_care_data d INNER JOIN tbl_employee e
               ON d.Employee_ID = e.Employee_ID
               WHERE d.Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' ORDER BY saved_time ASC LIMIT 1";
  $query11 = mysqli_query($conn,$get_data1);
  $data_output1 = array();

  while($r11 = mysqli_fetch_assoc($query11)){
    $id = $r11['data_ID'];
    $data_output1[] = $r11;
  }

  echo json_encode($data_output1);
}


//HISTORY-PREVIOUS tbl_neonatal_care_history_previous
if ($_GET['action'] == 'get_previous') {

  $Registration_ID = $_GET['Registration_ID'];
  $get_previous = "SELECT * FROM tbl_neonatal_care_history_previous WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' ORDER BY saved_time ASC LIMIT 1";
  $query2 = mysqli_query($conn,$get_previous);
  $previous_output = array();

  while($r2 = mysqli_fetch_assoc($query2)){
    $previous_output[] = $r2;
  }

  echo json_encode($previous_output);

}

//HISTORY-PREVIOUS tbl_neonatal_care_history_previous by year
if ($_GET['action'] == 'get_previous1' && $_GET['year']) {
  $y = $_GET['year'];
  $Registration_ID = $_GET['Registration_ID'];
  $get_previous1 = "SELECT * FROM tbl_neonatal_care_history_previous WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' ORDER BY saved_time ASC LIMIT 1";
  $query21 = mysqli_query($conn,$get_previous1);
  $previous_output1 = array();

  while($r21 = mysqli_fetch_assoc($query21)){
    $previous_output1[] = $r21;
  }

  echo json_encode($previous_output1);

}



//Antenatal history tbl_neonatal_care_antenatal_history
if ($_GET['action'] == 'get_antenatal') {

  $Registration_ID = $_GET['Registration_ID'];
  $get_antenatal = "SELECT * FROM tbl_neonatal_care_antenatal_history WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' ORDER BY saved_time ASC LIMIT 1";
  $query3 = mysqli_query($conn,$get_antenatal);
  $antenatal_output = array();

  while($r3 = mysqli_fetch_assoc($query3)){
    $antenatal_output[] = $r3;
  }

  echo json_encode($antenatal_output);


}


//Antenatal history tbl_neonatal_care_antenatal_history by year
if ($_GET['action'] == 'get_antenatal1' && $_GET['year']) {
  $y = $_GET['year'];
  $Registration_ID = $_GET['Registration_ID'];
  $get_antenatal1 = "SELECT * FROM tbl_neonatal_care_antenatal_history WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' ORDER BY saved_time ASC LIMIT 1";
  $query31 = mysqli_query($conn,$get_antenatal1);
  $antenatal_output1 = array();

  while($r31 = mysqli_fetch_assoc($query31)){
    $antenatal_output1[] = $r31;
  }

  echo json_encode($antenatal_output1);


}



//Delivery history tbl_neonatal_care_delivery_history
if ($_GET['action'] == 'get_delivery') {

  $Registration_ID = $_GET['Registration_ID'];
  $get_delivery = "SELECT * FROM tbl_neonatal_care_delivery_history WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' ORDER BY saved_time ASC LIMIT 1";
  $query4 = mysqli_query($conn,$get_delivery);
  $delivery_output = array();

  while($r4 = mysqli_fetch_assoc($query4)){
    $delivery_output[] = $r4;
  }

  echo json_encode($delivery_output);

}


//Delivery history tbl_neonatal_care_delivery_history by year
if ($_GET['action'] == 'get_delivery1' && $_GET['year']) {

  $y = $_GET['year'];
  $Registration_ID = $_GET['Registration_ID'];
  $get_delivery1 = "SELECT * FROM tbl_neonatal_care_delivery_history WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' ORDER BY saved_time ASC LIMIT 1";
  $query41 = mysqli_query($conn,$get_delivery1);
  $delivery_output1 = array();

  while($r41 = mysqli_fetch_assoc($query41)){
    $delivery_output1[] = $r41;
  }

  echo json_encode($delivery_output1);

}




//Postnatal history tbl_neonatal_care_postnatal_history
if ($_GET['action'] == 'get_postnatal') {

  $Registration_ID = $_GET['Registration_ID'];
  $get_postnatal = "SELECT * FROM tbl_neonatal_care_postnatal_history WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' ORDER BY saved_time ASC LIMIT 1";
  $query5 = mysqli_query($conn,$get_postnatal);
  $postnatal_output = array();

  while($r5 = mysqli_fetch_assoc($query5)){
    $postnatal_output[] = $r5;
  }

  echo json_encode($postnatal_output);

}


//Postnatal history tbl_neonatal_care_postnatal_history by year
if ($_GET['action'] == 'get_postnatal1' && $_GET['year']) {
  $y =  $_GET['year'];
  $Registration_ID = $_GET['Registration_ID'];
  $get_postnatal1 = "SELECT * FROM tbl_neonatal_care_postnatal_history WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' ORDER BY saved_time ASC LIMIT 1";
  $query51 = mysqli_query($conn,$get_postnatal1);
  $postnatal_output1 = array();

  while($r51 = mysqli_fetch_assoc($query51)){
    $postnatal_output1[] = $r51;
  }

  echo json_encode($postnatal_output1);

}



//History of the baby tbl_neonatal_care_baby_history
if ($_GET['action'] == 'get_baby') {

  $Registration_ID = $_GET['Registration_ID'];
  $get_baby = "SELECT * FROM tbl_neonatal_care_baby_history WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' ORDER BY saved_time ASC LIMIT 1";
  $query6 = mysqli_query($conn,$get_baby);
  $baby_output = array();

  while($r6 = mysqli_fetch_assoc($query6)){
    $baby_output[] = $r6;
  }

  echo json_encode($baby_output);

}


//History of the baby tbl_neonatal_care_baby_history by year
if ($_GET['action'] == 'get_baby1' && $_GET['year']) {
  $y = $_GET['year'];
  $Registration_ID = $_GET['Registration_ID'];
  $get_baby1 = "SELECT * FROM tbl_neonatal_care_baby_history WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' ORDER BY saved_time ASC LIMIT 1";
  $query61 = mysqli_query($conn,$get_baby1);
  $baby_output1 = array();

  while($r61 = mysqli_fetch_assoc($query61)){
    $baby_output1[] = $r61;
  }

  echo json_encode($baby_output1);

}




//PHYSICAL EXAMINATION  tbl_neonatal_care_physical_examination
if ($_GET['action'] == 'get_physical1') {

  $Registration_ID = $_GET['Registration_ID'];
  $get_physical1 = "SELECT * FROM tbl_neonatal_care_physical_examination WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' ORDER BY saved_time ASC LIMIT 1";
  $query7 = mysqli_query($conn,$get_physical1);
  $physical1_output = array();

  while($r7 = mysqli_fetch_assoc($query7)){
    $physical1_output[] = $r7;
  }

  echo json_encode($physical1_output);

}


//PHYSICAL EXAMINATION  tbl_neonatal_care_physical_examination by year
if ($_GET['action'] == 'get_physical11' && $_GET['year']) {
  $y =  $_GET['year'];
  $Registration_ID = $_GET['Registration_ID'];
  $get_physical11 = "SELECT * FROM tbl_neonatal_care_physical_examination WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' ORDER BY saved_time ASC LIMIT 1";
  $query71 = mysqli_query($conn,$get_physical11);
  $physical1_output1 = array();

  while($r71 = mysqli_fetch_assoc($query71)){
    $physical1_output1[] = $r71;
  }

  echo json_encode($physical1_output1);

}



//tbl_neonatal_care_physical_examination2
if ($_GET['action'] == 'get_physical2') {

  $Registration_ID = $_GET['Registration_ID'];
  $get_physical2 = "SELECT * FROM tbl_neonatal_care_physical_examination2 WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' ORDER BY saved_time ASC LIMIT 1";
  $query8 = mysqli_query($conn,$get_physical2);
  $physical2_output = array();

  while($r8 = mysqli_fetch_assoc($query8)){
    $physical2_output[] = $r8;
  }

  echo json_encode($physical2_output);

}

//tbl_neonatal_care_physical_examination2 by year
if ($_GET['action'] == 'get_physical21' && $_GET['year']) {
  $y = $_GET['year'];
  $Registration_ID = $_GET['Registration_ID'];
  $get_physical21 = "SELECT * FROM tbl_neonatal_care_physical_examination2 WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' ORDER BY saved_time ASC LIMIT 1";
  $query81 = mysqli_query($conn,$get_physical21);
  $physical2_output1 = array();

  while($r81 = mysqli_fetch_assoc($query81)){
    $physical2_output1[] = $r81;
  }

  echo json_encode($physical2_output1);

}

//MANAGEMENT tbl_neonatal_care_management
if ($_GET['action'] == 'get_management') {

  $Registration_ID = $_GET['Registration_ID'];
  $get_management = "SELECT * FROM tbl_neonatal_care_management WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' ORDER BY saved_time ASC LIMIT 1";
  $query9 = mysqli_query($conn,$get_management);
  $management_output = array();

  while($r9 = mysqli_fetch_assoc($query9)){
    $management_output[] = $r9;
  }

  echo json_encode($management_output);

}


//MANAGEMENT tbl_neonatal_care_management by year
if ($_GET['action'] == 'get_management1' && $_GET['year']) {
  $y = $_GET['year'];
  $Registration_ID = $_GET['Registration_ID'];
  $get_management1 = "SELECT * FROM tbl_neonatal_care_management WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' ORDER BY saved_time ASC LIMIT 1";
  $query91 = mysqli_query($conn,$get_management1);
  $management_output1 = array();

  while($r91 = mysqli_fetch_assoc($query91)){
    $management_output1[] = $r91;
  }

  echo json_encode($management_output1);

}




 ?>
