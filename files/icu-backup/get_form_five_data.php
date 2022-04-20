<?php
function returnFormFiveData($registration_id)
{
  $select_form_five_data = "SELECT * FROM tbl_icu_form_five WHERE patient_id='$registration_id'";


  $data = array();
  if ($result = mysqli_query($conn,$select_form_five_data)) {
    while ($row = mysqli_fetch_assoc($result)) {
      $data['local_mood_time'] = $row['local_mood_time'];
      $data['local_mood_evants'] = $row['local_mood_evants'];
      $data['comment'] = $row['comment'];
      $data['sensationtime'] = $row['sensationtime'];
      $data['sensationevants'] = $row['sensationevants'];
      $data['ecg_rate_rthmtime'] = $row['ecg_rate_rthmtime'];
      $data['ecg_rate_rthmevants'] = $row['ecg_rate_rthmevants'];
      $data['bptime'] = $row['bptime'];
      $data['bpevants'] = $row['bpevants'];
      $data['urine_outputtime'] = $row['urine_outputtime'];
      $data['urine_outputevants'] = $row['urine_outputevants'];
      $data['temperaturetime'] = $row['temperaturetime'];
      $data['temperatureevants'] = $row['temperatureevants'];
      $data['breathingtime'] = $row['breathingtime'];
      $data['breathingevants'] = $row['breathingevants'];
      $data['activitytime'] = $row['activitytime'];
      $data['activityevants'] = $row['activityevants'];
      $data['diet_And_eliminationtime'] = $row['diet_And_eliminationtime'];
      $data['diet_And_eliminationevants'] = $row['diet_And_eliminationevants'];
      $data['Skintime'] = $row['Skintime'];
      $data['Skinevants'] = $row['Skinevants'];
      $data['infectiontime'] = $row['infectiontime'];
      $data['comfortevants'] = $row['comfortevants'];
      $data['comforttime'] = $row['comforttime'];

      $data['bleedingtime'] = $row['bleedingtime'];
      $data['bleedingevants'] = $row['bleedingevants'];
      $data['patient_complainttime'] = $row['patient_complainttime'];
      $data['patient_complaintevants'] = $row['patient_complaintevants'];
      $data['patient_complainttime'] = $row['patient_complainttime'];
      $data['patient_complaintevants'] = $row['patient_complaintevants'];
      $data['patient_complainttime'] = $row['patient_complainttime'];
      $data['patient_complaintevants'] = $row['patient_complaintevants'];
      $data['family_Concerntime'] = $row['family_Concerntime'];
      $data['family_Concernevants'] = $row['family_Concernevants'];
      $data['cocio_culture_issuestime'] = $row['cocio_culture_issuestime'];
      $data['cocio_culture_issuesevants'] = $row['cocio_culture_issuesevants'];
      $data['fluid_and_electrolytetime'] = $row['fluid_and_electrolytetime'];
      $data['fluid_and_electrolyteevants'] = $row['fluid_and_electrolyteevants'];
      $data['labs_ivestigationtime'] = $row['labs_ivestigationtime'];
      $data['labs_ivestigationevants'] = $row['labs_ivestigationevants'];
      $data['leaming_needstime'] = $row['leaming_needstime'];
      $data['leaming_needsevants'] = $row['leaming_needsevants'];
    }
    return $data;
  }else{
    echo mysqli_error($conn);
  }
}


function returnFormSixData($registration_id)
{
  $select_form_five_data = "SELECT * FROM tbl_icu_form_six WHERE patient_id='$registration_id'";


  $data = array();
  if ($result = mysqli_query($conn,$select_form_five_data)) {
    while ($row = mysqli_fetch_assoc($result)) {
      $data['respiratoryam'] = $row['respiratoryam'];
      $data['respiratorypm'] = $row['respiratorypm'];
      // $data['comment'] = $row['comment'];
      $data['air_entryam'] = $row['air_entryam'];
      $data['air_entrypm'] = $row['air_entrypm'];
      $data['air_entrynight'] = $row['air_entrynight'];
      $data['breath_soundam'] = $row['breath_soundam'];
      $data['breath_soundpm'] = $row['breath_soundpm'];
      $data['breath_soundnight'] = $row['breath_soundnight'];
      $data['chest_expansionam'] = $row['chest_expansionam'];
      $data['chest_expansionpm'] = $row['chest_expansionpm'];
      $data['chest_expansionnight'] = $row['chest_expansionnight'];
      $data['use_of_accessory_muscleam'] = $row['use_of_accessory_muscleam'];
      $data['use_of_accessory_musclepm'] = $row['use_of_accessory_musclepm'];
      $data['use_of_accessory_muscleam'] = $row['use_of_accessory_muscleam'];
      $data['use_of_accessory_musclenight'] = $row['use_of_accessory_musclenight'];
      $data['ability_to_cougham'] = $row['ability_to_cougham'];
      $data['ability_to_coughpm'] = $row['ability_to_coughpm'];
      $data['ability_to_coughnight'] = $row['ability_to_coughnight'];
      $data['cvam'] = $row['cvam'];
      $data['cvpm'] = $row['cvpm'];
      $data['cvnight'] = $row['cvnight'];
      $data['rythmam'] = $row['rythmam'];
      $data['rythmpm'] = $row['rythmpm'];

      $data['rythmnight'] = $row['rythmnight'];
      $data['daily_weightam'] = $row['daily_weightam'];
      $data['daily_weightpm'] = $row['daily_weightpm'];
      $data['daily_weightnight'] = $row['daily_weightnight'];
      $data['capillary_refilam'] = $row['capillary_refilam'];
      $data['capillary_refilpm'] = $row['capillary_refilpm'];
      $data['capillary_refilnight'] = $row['capillary_refilnight'];
      $data['skin_conditionam'] = $row['skin_conditionam'];
      $data['skin_conditionpm'] = $row['skin_conditionpm'];
      $data['skin_conditionnight'] = $row['skin_conditionnight'];
      $data['colour_pink_pale_cynotic_juandiceam'] = $row['colour_pink_pale_cynotic_juandiceam'];
      $data['colour_pink_pale_cynotic_juandicepm'] = $row['colour_pink_pale_cynotic_juandicepm'];
      $data['colour_pink_pale_cynotic_juandicenight'] = $row['colour_pink_pale_cynotic_juandicenight'];
      $data['turgor_normal_loose_ight_shinyam'] = $row['turgor_normal_loose_ight_shinyam'];
      $data['turgor_normal_loose_ight_shinypm'] = $row['turgor_normal_loose_ight_shinypm'];
      $data['turgor_normal_loose_ight_shinynight'] = $row['turgor_normal_loose_ight_shinynight'];
      $data['texture_dry_moistam'] = $row['texture_dry_moistam'];
      $data['texture_dry_moistpm'] = $row['texture_dry_moistpm'];

      $data['odema_sitesam'] = $row['odema_sitesam'];
      $data['odema_sitespm'] = $row['odema_sitespm'];

      $data['odema_sitesnight'] = $row['odema_sitesnight'];
      $data['giam'] = $row['giam'];

      $data['gipm'] = $row['gipm'];
      $data['ginight'] = $row['ginight'];

      $data['abdomen_soft_hard_distended_tenderam'] = $row['abdomen_soft_hard_distended_tenderam'];
      $data['abdomen_soft_hard_distended_tenderpm'] = $row['abdomen_soft_hard_distended_tenderpm'];
      $data['abdomen_soft_hard_distended_tendernight'] = $row['abdomen_soft_hard_distended_tendernight'];
      $data['bowel_sound_normal_hyperactiveam'] = $row['bowel_sound_normal_hyperactiveam'];
      $data['bowel_sound_normal_hyperactivepm'] = $row['bowel_sound_normal_hyperactivepm'];
      $data['bowel_sound_normal_hyperactivenight'] = $row['bowel_sound_normal_hyperactivenight'];
      $data['hypoactive_absentam'] = $row['hypoactive_absentam'];
      $data['hypoactive_absentpm'] = $row['hypoactive_absentpm'];
      $data['hypoactive_absentnight'] = $row['hypoactive_absentnight'];
      $data['ng_tube_insertion_date_na_clamped_cont_suction_intam'] = $row['ng_tube_insertion_date_na_clamped_cont_suction_intam'];
      $data['ng_tube_insertion_date_na_clamped_cont_suction_intpm'] = $row['ng_tube_insertion_date_na_clamped_cont_suction_intpm'];
      $data['ng_tube_insertion_date_na_clamped_cont_suction_intnight'] = $row['ng_tube_insertion_date_na_clamped_cont_suction_intnight'];
      $data['diet_restrictedam'] = $row['diet_restrictedam'];
      $data['diet_restrictedpm'] = $row['diet_restrictedpm'];
      $data['diet_restrictednight'] = $row['diet_restrictednight'];
      $data['activityam'] = $row['activityam'];
      $data['level_of_mobilityam'] = $row['level_of_mobilityam'];
      $data['level_of_mobilitypm'] = $row['level_of_mobilitypm'];
      $data['level_of_mobilitynight'] = $row['level_of_mobilitynight'];
      $data['cbr_up_to_washroomam'] = $row['cbr_up_to_washroomam'];
      $data['cbr_up_to_washroompm'] = $row['cbr_up_to_washroompm'];
      $data['cbr_up_to_washroomnight'] = $row['cbr_up_to_washroomnight'];
      $data['activity_assisted_selfam'] = $row['activity_assisted_selfam'];
      $data['activity_assisted_selfpm'] = $row['activity_assisted_selfpm'];
      $data['activity_assisted_selfnight'] = $row['activity_assisted_selfnight'];
      $data['drains_na_type_locationam'] = $row['drains_na_type_locationam'];
      $data['drains_na_type_locationpm'] = $row['drains_na_type_locationpm'];
      $data['drains_na_type_locationpm'] = $row['drains_na_type_locationpm'];
      $data['drains_na_type_locationnight'] = $row['drains_na_type_locationnight'];
      $data['characteram'] = $row['characteram'];
      $data['characterpm'] = $row['characterpm'];
      $data['characternight'] = $row['characternight'];
      $data['vomitus_amount_colouram'] = $row['vomitus_amount_colouram'];
      $data['vomitus_amount_colourpm'] = $row['vomitus_amount_colourpm'];
      $data['vomitus_amount_colournight'] = $row['vomitus_amount_colournight'];
      $data['stool_consistencyam'] = $row['stool_consistencyam'];
      $data['stool_consistencypm'] = $row['stool_consistencypm'];
      $data['stool_consistencynight'] = $row['stool_consistencynight'];
      $data['amount_small_m_l_nilam'] = $row['amount_small_m_l_nilam'];
      $data['amount_small_m_l_nilpm'] = $row['amount_small_m_l_nilpm'];
      $data['amount_small_m_l_nilnight'] = $row['amount_small_m_l_nilnight'];
      $data['guam'] = $row['guam'];
      $data['gupm'] = $row['gupm'];
      $data['gunight'] = $row['gunight'];

      $data['urine_colouram'] = $row['urine_colouram'];
      $data['urine_colourpm'] = $row['urine_colourpm'];
      $data['urine_colournight'] = $row['urine_colournight'];
      $data['foleys_isertion_dateam'] = $row['foleys_isertion_dateam'];
      $data['foleys_isertion_datepm'] = $row['foleys_isertion_datepm'];
      $data['foleys_isertion_datenight'] = $row['foleys_isertion_datenight'];
      $data['dialysisam'] = $row['dialysisam'];
      $data['dialysispm'] = $row['dialysispm'];
      $data['dialysisnight'] = $row['dialysisnight'];

      $data['pulse_codeam'] = $row['pulse_codeam'];
      $data['pulse_codepm'] = $row['pulse_codepm'];
      $data['pulse_codenight'] = $row['pulse_codenight'];
      $data['absent_radialam'] = $row['absent_radialam'];
      $data['absent_radialpm'] = $row['absent_radialpm'];
      $data['absent_radialnight'] = $row['absent_radialnight'];
      $data['weak_femoralam'] = $row['weak_femoralam'];
      $data['weak_femoralpm'] = $row['weak_femoralpm'];
      $data['weak_femoralnight'] = $row['weak_femoralnight'];

      $data['normal_dor_pedam'] = $row['normal_dor_pedam'];
      $data['normal_dor_pedpm'] = $row['normal_dor_pedpm'];
      $data['normal_dor_pednight'] = $row['normal_dor_pednight'];
      $data['strong_post_tipam'] = $row['strong_post_tipam'];
      $data['strong_post_tippm'] = $row['strong_post_tippm'];
      $data['strong_post_tipnight'] = $row['strong_post_tipnight'];
      $data['boundingam'] = $row['boundingam'];
      $data['boundingpm'] = $row['boundingpm'];
      $data['boundingnight'] = $row['boundingnight'];
      $data['nurse_family_interactionam'] = $row['nurse_family_interactionam'];
      $data['nurse_family_interactionpm'] = $row['nurse_family_interactionpm'];
      $data['nurse_family_interactionnight'] = $row['nurse_family_interactionnight'];


    }
    return $data;
  }else{
    echo mysqli_error($conn);
  }
}



function returnFormSevenData($registration_id)
{
  $select_form_five_data = "SELECT * FROM tbl_icu_form_seven WHERE patient_id='$registration_id'";


  $data = array();
  if ($result = mysqli_query($conn,$select_form_five_data)) {
    while ($row = mysqli_fetch_assoc($result)) {

      $data['batham']=$row['batham'];
      $data['bathpm']=$row['bathpm'];
      $data['bathnight']=$row['bathnight'];
      $data['back_caream']=$row['back_caream'];
      $data['back_carepm']=$row['back_carepm'];
      $data['back_carenight']=$row['back_carenight'];
      $data['mouth_caream']=$row['mouth_caream'];
      $data['mouth_carepm']=$row['mouth_carepm'];
      $data['mouth_carenight']=$row['mouth_carenight'];
      $data['eye_caream']=$row['eye_caream'];
      $data['eye_carepm']=$row['eye_carepm'];
      $data['eye_carenight']=$row['eye_carenight'];
      $data['cathete_caream']=$row['cathete_caream'];
      $data['cathete_carepm']=$row['cathete_carepm'];
      $data['cathete_carenight']=$row['cathete_carenight'];
      $data['perinial_caream']=$row['perinial_caream'];
      $data['perinial_carepm']=$row['perinial_carepm'];
      $data['perinial_carenight']=$row['perinial_carenight'];
      $data['ng_caream']=$row['ng_caream'];
      $data['ng_carepm']=$row['ng_carepm'];
      $data['ng_carenight']=$row['ng_carenight'];
      $data['nose_ear_eaream']=$row['nose_ear_eaream'];
      $data['nose_ear_earepm']=$row['nose_ear_earepm'];
      $data['nose_ear_earenight']=$row['nose_ear_earenight'];
      $data['physioam']=$row['physioam'];
      $data['physiopm']=$row['physiopm'];
      $data['physionight']=$row['physionight'];
      $data['deepbreath_cougham']=$row['deepbreath_cougham'];
      $data['deepbreath_coughpm']=$row['deepbreath_coughpm'];
      $data['deepbreath_coughnight']=$row['deepbreath_coughnight'];
      $data['oett_tt_caream']=$row['oett_tt_caream'];
      $data['oett_tt_carepm']=$row['oett_tt_carepm'];
      $data['oett_tt_carenight']=$row['oett_tt_carenight'];
      $data['line_caream']=$row['line_caream'];
      $data['line_carepm']=$row['line_carepm'];
      $data['line_carenight']=$row['line_carenight'];
      $data['locationam']=$row['locationam'];
      $data['locationpm']=$row['locationpm'];
      $data['locationnight']=$row['locationnight'];
      $data['insertion_dateam']=$row['insertion_dateam'];
      $data['insertion_datepm']=$row['insertion_datepm'];
      $data['insertion_datenight']=$row['insertion_datenight'];
      $data['status_of_siteam']=$row['status_of_siteam'];
      $data['status_of_sitepm']=$row['status_of_sitepm'];
      $data['status_of_sitenight']=$row['status_of_sitenight'];
      $data['redressedam']=$row['redressedam'];
      $data['redressedpm']=$row['redressedpm'];
      $data['redressednight']=$row['redressednight'];
      $data['location_2am']=$row['location_2am'];
      $data['location_2pm']=$row['location_2pm'];
      $data['location_2night']=$row['location_2night'];
      $data['insertion_date_2am']=$row['insertion_date_2am'];
      $data['insertion_date_2pm']=$row['insertion_date_2pm'];
      $data['insertion_date_2night']=$row['insertion_date_2night'];
      $data['status_of_site_2am']=$row['status_of_site_2am'];
      $data['status_of_site_2pm']=$row['status_of_site_2pm'];
      $data['status_of_site_2night']=$row['status_of_site_2night'];
      $data['redressed_2am']=$row['redressed_2am'];
      $data['redressed_2pm']=$row['redressed_2pm'];
      $data['redressed_2night']=$row['redressed_2night'];
      $data['location_3am']=$row['location_3am'];
      $data['location_3pm']=$row['location_3pm'];
      $data['location_3night']=$row['location_3night'];
      $data['insertion_date_3am']=$row['insertion_date_3am'];
      $data['insertion_date_3pm']=$row['insertion_date_3pm'];
      $data['insertion_date_3night']=$row['insertion_date_3night'];
      $data['status_of_site_3am']=$row['status_of_site_3am'];
      $data['status_of_site_3pm']=$row['status_of_site_3pm'];
      $data['status_of_site_3night']=$row['status_of_site_3night'];
      $data['redressed_3am']=$row['redressed_3am'];
      $data['redressed_3pm']=$row['redressed_3pm'];
      $data['redressed_3night']=$row['redressed_3night'];


    }
    return $data;
  }else{
    echo mysqli_error($conn);
  }
}

?>
