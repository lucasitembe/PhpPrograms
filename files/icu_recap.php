<?php
    session_start();
    include '../middleware/middleware.php';
    date_default_timezone_set("Africa/Nairobi");

    if(isset($_POST['form_six'])):
        $date = date('Y-m-d h:i:s');
        $data = array(
            array(
                'respiratoryam' => $_POST['respiratory_am'],
                'respiratorypm' => $_POST['respiratory_pm'],
                'respiratorynight' => $_POST['respiratory_night'],

                'air_entryam' => $_POST['air_entry_am'],
                'air_entrypm' => $_POST['air_entry_pm'],
                'air_entrynight' => $_POST['air_entry_night'],

                'breath_soundam' => $_POST['breath_sound_am'],
                'breath_soundpm' => $_POST['breath_sound_pm'],
                'breath_soundnight' => $_POST['breath_sound_nght'],

                'chest_expansionam' => $_POST['chest_expansion_am'],
                'chest_expansionpm' => $_POST['chest_expansion_pm'],
                'chest_expansionnight' => $_POST['chest_expansion_night'],

                'use_of_accessory_muscleam' => $_POST['use_of_accessory_muscle_am'],
                'use_of_accessory_musclepm' => $_POST['use_of_accessory_muscle_pm'],
                'use_of_accessory_musclenight' => $_POST['use_of_accessory_muscle_night'],

                'ability_to_cougham' => $_POST['ability_to_cough_am'],
                'ability_to_coughpm' => $_POST['ability_to_cough_pm'],
                'ability_to_coughnight' => $_POST['ability_to_cough_night'],

                'cvam' => $_POST['cv_am'],
                'cvpm' => $_POST['cv_pm'],
                'cvnight' => $_POST['cv_night'],

                'rythmam' => $_POST['rythm_am'],
                'rythmpm' => $_POST['rythm_pm'],
                'rythmnight' => $_POST['rythm_night'],

                'daily_weightam' => $_POST['daily_weight_am'],
                'daily_weightpm ' => $_POST['daily_weight_pm'],
                'daily_weightnight' => $_POST['daily_weight_night'],

                'capillary_refilam' => $_POST['capillary_refil_am'],
                'capillary_refilpm' => $_POST['capillary_refil_pm'],
                'capillary_refilnight' => $_POST['capillary_refil_night'],

                'skin_conditionam' => $_POST['skin_condition_am'],
                'skin_conditionpm' => $_POST['skin_condition_pm'],
                'skin_conditionnight' => $_POST['skin_condition_night'],

                'colour_pink_pale_cynotic_juandiceam' => $_POST['colour_pink_pale_cynotic_juandice_am'],
                'colour_pink_pale_cynotic_juandicepm' => $_POST['colour_pink_pale_cynotic_juandice_pm'],
                'colour_pink_pale_cynotic_juandicenight' => $_POST['colour_pink_pale_cynotic_juandice_night'],

                'turgor_normal_loose_ight_shinyam' => $_POST['turgor_normal_loose_ight_shiny_am'],
                'turgor_normal_loose_ight_shinypm' => $_POST['turgor_normal_loose_ight_shiny_pm'],
                'turgor_normal_loose_ight_shinynight' => $_POST['turgor_normal_loose_ight_shiny_night'],

                'texture_dry_moistam' => $_POST['texture_dry_moist_am'],
                'texture_dry_moistpm' => $_POST['texture_dry_moist_pm'],
                'texture_dry_moistnight' => $_POST['texture_dry_moist_night'],

                'odema_sitesam' => $_POST['odema_sites_am'],
                'odema_sitespm' => $_POST['odema_sites_pm'],
                'odema_sitesnight' => $_POST['odema_sites_night'],

                'giam' => $_POST['gi_am'],
                'gipm' => $_POST['gi_pm'],
                'ginight' => $_POST['gi_night'],

                'abdomen_soft_hard_distended_tenderam' => $_POST['abdomen_soft_hard_distended_tender_am'],
                'abdomen_soft_hard_distended_tenderpm' => $_POST['abdomen_soft_hard_distended_tender_pm'],
                'abdomen_soft_hard_distended_tendernight' => $_POST['abdomen_soft_hard_distended_tender_night'],

                'bowel_sound_normal_hyperactiveam' => $_POST['bowel_sound_normal_hyperactive_am'],
                'bowel_sound_normal_hyperactivepm' => $_POST['bowel_sound_normal_hyperactive_pm'],
                'bowel_sound_normal_hyperactivenight' => $_POST['bowel_sound_normal_hyperactive_night'],

                'hypoactive_absentam' => $_POST['hypoactive_absent_am'],
                'hypoactive_absentpm' => $_POST['hypoactive_absent_pm'],
                'hypoactive_absentnight' => $_POST['hypoactive_absent_night'],

                'ng_tube_insertion_date_na_clamped_cont_suction_intam' => $_POST['ng_tube_insertion_date_na_clamped_cont_suction_int_am'],
                'ng_tube_insertion_date_na_clamped_cont_suction_intpm' => $_POST['ng_tube_insertion_date_na_clamped_cont_suction_int_pm'],
                'ng_tube_insertion_date_na_clamped_cont_suction_intnight' => $_POST['ng_tube_insertion_date_na_clamped_cont_suction_int_night'],

                'diet_restrictedam' => $_POST['diet_restricted_am'],
                'diet_restrictedpm' => $_POST['diet_restricted_pm'],
                'diet_restrictednight' => $_POST['diet_restricted_night'],

                'activityam' => $_POST['activity_am'],
                'activitypm' => $_POST['activity_pm'],
                'activitynight' => $_POST['activity_night'],

                'level_of_mobilityam' => $_POST['level_of_mobility_am'],
                'level_of_mobilitypm' => $_POST['level_of_mobility_pm'],
                'level_of_mobilitynight' => $_POST['level_of_mobility_night'],


                'cbr_up_to_washroomam' => $_POST['cbr_up_to_washroom_am'],
                'cbr_up_to_washroompm' => $_POST['cbr_up_to_washroom_pm'],
                'cbr_up_to_washroomnight' => $_POST['cbr_up_to_washroom_night'],

                'activity_assisted_selfam' => $_POST['activity_assisted_self_am'],
                'activity_assisted_selfpm' => $_POST['activity_assisted_self_pm'],
                'activity_assisted_selfnight' => $_POST['activity_assisted_self_night'],
                
                'drains_na_type_locationam' => $_POST['drains_na_type_location_am'],
                'drains_na_type_locationpm' => $_POST['drains_na_type_location_pm'],
                'drains_na_type_locationnight' => $_POST['drains_na_type_location_night'],
                
                'characteram' => $_POST['character_am'],
                'characterpm' => $_POST['character_pm'],
                'characternight' => $_POST['character_night'],
                
                'vomitus_amount_colouram' => $_POST['vomitus_amount_colour_am'],
                'vomitus_amount_colourpm' => $_POST['vomitus_amount_colour_pm'],
                'vomitus_amount_colournight' => $_POST['vomitus_amount_colour_night'],
                
                'stool_consistencyam' => $_POST['stool_consistency_am'],
                'stool_consistencypm' => $_POST['stool_consistency_pm'],
                'stool_consistencynight' => $_POST['stool_consistency_night'],
                
                'amount_small_m_l_nilam' => $_POST['amount_small_m_l_nil_am'],
                'amount_small_m_l_nilpm' => $_POST['amount_small_m_l_nil_pm'],
                'amount_small_m_l_nilnight' => $_POST['amount_small_m_l_nil_night'],
                
                'guam' => $_POST['gu_am'],
                'gupm' => $_POST['gu_pm'],
                'gunight' => $_POST['gu_night'],

                'urine_colouram' => $_POST['urine_colour_am'],
                'urine_colourpm' => $_POST['urine_colour_pm'],
                'urine_colournight' => $_POST['urine_colour_night'],

                'foleys_isertion_dateam' => $_POST['foleys_isertion_date_am'],
                'foleys_isertion_datepm' => $_POST['foleys_isertion_date_pm'],
                'foleys_isertion_datenight' => $_POST['foleys_isertion_date_night'],

                'dialysisam' => $_POST['dialysis_am'],
                'dialysispm' => $_POST['dialysis_pm'],
                'dialysisnight' => $_POST['dialysis_night'],

                'pulse_codeam' => $_POST['pulse_code_am'],
                'pulse_codepm' => $_POST['pulse_code_pm'],
                'pulse_codenight' => $_POST['pulse_code_night'],

                'absent_radialam' => $_POST['absent_radial_am'],
                'absent_radialpm' => $_POST['absent_radial_pm'],
                'absent_radialnight' => $_POST['absent_radial_night'],

                'weak_femoralam' => $_POST['weak_femoral_am'],
                'weak_femoralpm' => $_POST['weak_femoral_pm'],
                'weak_femoralnight' => $_POST['weak_femoral_night'],

                'normal_dor_pedam' => $_POST['normal_dor_ped_am'],
                'normal_dor_pedpm' => $_POST['normal_dor_ped_pm'],
                'normal_dor_pednight' => $_POST['normal_dor_ped_night'],


                'strong_post_tipam' => $_POST['strong_post_tip_am'],
                'strong_post_tippm' => $_POST['strong_post_tip_pm'],
                'strong_post_tipnight' => $_POST['strong_post_tip_night'],

                'boundingam' => $_POST['bounding_am'],
                'boundingpm' => $_POST['bounding_pm'],
                'boundingnight' => $_POST['bounding_night'],

                'nurse_family_interactionam' => $_POST['nurse_family_interaction_am'],
                'nurse_family_interactionpm' => $_POST['nurse_family_interaction_pm'],
                'nurse_family_interactionnight' => $_POST['nurse_family_interaction_night'],

                'date_time' => $date
            )
        );

        $receive_data = json_encode($data);
		function save_assesment_record($receive_data){
			  $json_data=json_encode(array("table"=>"tbl_icu_form_six",
				"data"=>json_decode($receive_data,true)
		));
		  return $json_data;
        }
		query_insert(save_assesment_record($receive_data));
        echo 'Done';
    endif;

?>