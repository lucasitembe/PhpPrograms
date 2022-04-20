
function saveNonatal(act)
{
	if(act == "Save")
	{
    var Employee_ID = $("#Employee_ID").val();
    var Registration_ID = $("#Registration_ID").val();
    var Admision_ID = $("#Admision_ID").val();
    var consultation_id = $("#consultation_id").val();
    var referral_from = $("#referral_from").val();
    var transer_from_maternity = $("#transer_from_maternity").val();
    var date_birth = $("#date_birth").val();
    var admission_date = $("#admission_date").val();
    var length_cm = $("#length_cm").val();
    var head_circumference_cm = $("#head_circumference_cm").val();
    var gender =$ ("input[name='gender']:checked").val();
    var pmtct =$ ("input[name='pmtct']:checked").val();
    var apgar_score = $("#apgar_score").val();
    var ga = $("#ga").val();
    var birth_weight = $("#birth_weight").val();
    var chronical_maternal_illiness = $("#chronical_maternal_illiness").val();
    var family_illnesses = $("#family_illnesses").val();
    var gravida = $("#gravida").val();
    var para = $("#para").val();
    var number_of_living_children = $("#number_of_living_children").val();
    var known_problem_of_living_children = $("#known_problem_of_living_children").val();
    var complication_during_previous_pregnancies = $("#complication_during_previous_pregnancies").val();


    var pmtct =$ ("input[name='pmtct']:checked").val();
    var marital_status =$ ("input[name='marital_status']:checked").val();

    var lnmp = $("#lnmp").val();
    var edd = $("#edd").val();
    var vdrl =$ ("input[name='vdrl']:checked").val();
    var malaria =$ ("input[name='malaria']:checked").val();
    var hep_b =$ ("input[name='hep_b']:checked").val();
    var hb_level = $("#hb_level").val();

    var hypertension =$ ("input[name='hypertension']:checked").val();
    var drug_abuse =$ ("input[name='drug_abuse']:checked").val();
    var blood_pressure = $("#blood_pressure").val();

    var anc_attended =$ ("input[name='anc_attended']:checked").val();
    var blood_group_rh = $("#blood_group_rh").val();
    var where_anc_done =$ ("input[name='where_anc_done']:checked").val();

    var number_of_visits = $("#number_of_visits").val();
    var ga_at_1st_visit = $("#ga_at_1st_visit").val();
    var maternal_fever =$ ("input[name='maternal_fever']:checked").val();
    var prom =$ ("input[name='prom']:checked").val();
    var ab_treatment =$ ("input[name='ab_treatment']:checked").val();

    var ab_treatment_yes_drug = $("#ab_treatment_yes_drug").val();
    var amniotic_fluid =$ ("input[name='amniotic_fluid']:checked").val();
    var abnormalities_of_placenta =$ ("input[name='abnormalities_of_placenta']:checked").val();
    var abnormalities_of_placenta_yes =$ ("input[name='abnormalities_of_placenta_yes']:checked").val();

    var abnormal_presentation = $("#abnormal_presentation").val();
    var abnormal_presentation_yes = $("#abnormal_presentation_yes").val();


    var mode_of_delivery =$ ("input[name='mode_of_delivery']:checked").val();
    var cs =$ ("input[name='cs']:checked").val();
    var indication = $("#indication").val();
    var duration_of_cs = $("#duration_of_cs").val();
    var duration_of_labour_stage1 = $("#duration_of_labour_stage1").val();
    var duration_of_labour_stage2 = $("#duration_of_labour_stage2").val();
    var duration_of_labour_stage3 = $("#duration_of_labour_stage3").val();


    var obstructed_labour =$ ("input[name='obstructed_labour']:checked").val();
    var place_of_delivery =$ ("input[name='place_of_delivery']:checked").val();
    var delivery_attendant = $("#delivery_attendant").val();
    var if_assisted_delivery_why = $("#if_assisted_delivery_why").val();

    var problems_of_baby_after_birth =$ ("input[name='problems_of_baby_after_birth']:checked").val();
    var resuscitation =$ ("input[name='resuscitation']:checked").val();
    var resuscitation_yes =$ ("input[name='resuscitation_yes']:checked").val();
    var eye_prophylaxis =$ ("input[name='eye_prophylaxis']:checked").val();
    var vitamin_K_given =$ ("input[name='vitamin_K_given']:checked").val();

    var drugs_given =$ ("input[name='drugs_given']:checked").val();
    var feeding_started_within_1_hour =$ ("input[name='feeding_started_within_1_hour']:checked").val();

    var drugs_given_yes_which = $("#drugs_given_yes_which").val();
    var chief_complaints = $("#chief_complaints").val();
    var fever =$ ("input[name='fever']:checked").val();
    var vomiting =$ ("input[name='vomiting']:checked").val();
    var feeding =$ ("input[name='feeding']:checked").val();
    var enough_breast_milk =$ ("input[name='enough_breast_milk']:checked").val();


    var feeding_interval = $("#feeding_interval").val();
    var passage_of_urine =$ ("input[name='passage_of_urine']:checked").val();
    var passage_of_stool =$ ("input[name='passage_of_stool']:checked").val();
    var baby_recieve_any_vaccines =$ ("input[name='baby_recieve_any_vaccines']:checked").val();

    var quality = $("#quality").val();
    var other_complaints = $("#other_complaints").val();
    var Weight = $("#Weight").val();
    var temp = $("#temp").val();
    var pulse = $("#pulse").val();
    var resp_rate = $("#resp_rate").val();
    var SpO2 = $("#SpO2").val();
    var rbg = $("#rbg").val();
    var appearance_condition =$ ("input[name='appearance_condition']:checked").val();
    var appearance_activeness =$ ("input[name='appearance_activeness']:checked").val();
    var appearance_nourished =$ ("input[name='appearance_nourished']:checked").val();
    var appearance_Pathol =$ ("input[name='appearance_Pathol']:checked").val();
    var appearance_comment = $("#appearance_comment").val();

    var skin_temperature =$ ("input[name='skin_temperature']:checked").val();
    var skin_color =$ ("input[name='skin_color']:checked").val();
    var skin_turgor =$ ("input[name='skin_turgor']:checked").val();
    var skin_cyanosed =$ ("input[name='skin_cyanosed']:checked").val();
    var skin_cyanosed_yes =$ ("input[name='skin_cyanosed_yes']:checked").val();
    var skin_rashes =$ ("input[name='skin_rashes']:checked").val();
    var ctr = $("#ctr").val();

    var head1 =$ ("input[name='head1']:checked").val();
    var head1_shape =$ ("input[name='head1_shape']:checked").val();
    var head1_fontanelle =$ ("input[name='head1_fontanelle']:checked").val();
    var head1_sutures =$ ("input[name='head1_sutures']:checked").val();
    var head1_swelling_trauma =$ ("input[name='head1_swelling_trauma']:checked").val();

    var head1_size = $("#head1_size").val();
    var head2_other_malformation = $("#head2_other_malformation").val();
    var head2_eye_discharge =$ ("input[name='head2_eye_discharge']:checked").val();
    var neck_lymphadenopathy =$ ("input[name='neck_lymphadenopathy']:checked").val();
    var neck_lymphadenopathy_yes =$ ("input[name='neck_lymphadenopathy_yes']:checked").val();
    var neck_clavicle_fractured =$ ("input[name='neck_clavicle_fractured']:checked").val();

    var breathing_chest_movement =$ ("input[name='breathing_chest_movement']:checked").val();
    var breathing_indrawing =$ ("input[name='breathing_indrawing']:checked").val();
    var breathing_sounds =$ ("input[name='breathing_sounds']:checked").val();
    var breathing_preterm = $("#breathing_preterm").val();

    var heart_rhythm =$ ("input[name='heart_rhythm']:checked").val();
    var heart_murmurs =$ ("input[name='heart_murmurs']:checked").val();
    var breathing_preterm = $("#heart_describe").val();

    var abdomen_flat =$ ("input[name='abdomen_flat']:checked").val();
    var abdomen_sunken =$ ("input[name='abdomen_sunken']:checked").val();
    var abdomen_distended =$ ("input[name='abdomen_distended']:checked").val();
    var abdomen_abdominal =$ ("input[name='abdomen_abdominal']:checked").val();
    var abdomen_local =$ ("input[name='abdomen_local']:checked").val();
    var umbillical_cord =$ ("input[name='umbillical_cord']:checked").val();

    var genitalia_male =$ ("input[name='genitalia_male']:checked").val();
    var genitalia_testis =$ ("input[name='genitalia_testis']:checked").val();
    var genitalia_ambiguous =$ ("input[name='genitalia_ambiguous']:checked").val();
    var genitalia_female =$ ("input[name='genitalia_female']:checked").val();

    var genitalia_female_describe = $("#genitalia_female_describe").val();

    var anus_patent_no_describe = $("#anus_patent_no_describe").val();
    var anus_patent =$ ("input[name='anus_patent']:checked").val();
    var anus_abdnormality =$ ("input[name='anus_abdnormality']:checked").val();

    var back_posture =$ ("input[name='back_posture']:checked").val();
    var back_malformation =$ ("input[name='back_malformation']:checked").val();
    var back_malformation_hints =$ ("input[name='back_malformation_hints']:checked").val();
    var neurology_spotaneous_movement =$ ("input[name='neurology_spotaneous_movement']:checked").val();
    var neurology_musde_tone =$ ("input[name='neurology_musde_tone']:checked").val();
    var neurology_flexes_glasping =$ ("input[name='neurology_flexes_glasping']:checked").val();
    var neurology_flexes_sucking =$ ("input[name='neurology_flexes_sucking']:checked").val();
    var neurology_flexes_traction =$ ("input[name='neurology_flexes_traction']:checked").val();
    var neurology_flexes_moro =$ ("input[name='neurology_flexes_moro']:checked").val();

    var finnstroem_score = $("#finnstroem_score").val();
    var additional_findings = $("#additional_findings").val();
    var key_findings = $("#key_findings").val();
    var provisional_diagnoses = $("#provisional_diagnoses").val();
    var differential_diagnoses = $("#differential_diagnoses").val();
    var investigation = $("#investigation").val();

    var treatment = $("#treatment").val();
    var supportive_care = $("#supportive_care").val();
    var preventions = $("#preventions").val();



      if(confirm("Are you Sure you want to Save This?")){

        alert("ujinga")
        $.ajax({
          url:"../save_neonatal_care_admission.php",
          type:"POST",
          data:{
            name_of_baby:name_of_baby,
            referral_from:referral_from,
            Admision_ID:Admision_ID,
            consultation_id:consultation_id,
            Employee_ID:Employee_ID,
            Registration_ID:Registration_ID,
            transer_from_maternity:transer_from_maternity,
            date_birth:date_birth,
            admission_date:admission_date,
            length_cm:length_cm,
            head_circumference_cm:head_circumference_cm,
            pmtct:pmtct,
            gender:gender,
            apgar_score:apgar_score,
            ga:ga,
            birth_weight:birth_weight,
            chronical_maternal_illiness:chronical_maternal_illiness,
            family_illnesses:family_illnesses,
            gravida:gravida,
            para:para,
            number_of_living_children:number_of_living_children,
            known_problem_of_living_children:known_problem_of_living_children,
            complication_during_previous_pregnancies:complication_during_previous_pregnancies,
            marital_status:marital_status,
            lnmp:lnmp,
            edd:edd,
            vdrl:vdrl,
            malaria:malaria,
            hep_b:hep_b,
            hb_level:hb_level,
            hypertension:hypertension,
            blood_pressure:blood_pressure,
            drug_abuse:drug_abuse,
            blood_group_rh:blood_group_rh,
            anc_attended:anc_attended,
            where_anc_done:where_anc_done,
            number_of_visits:number_of_visits,
            ga_at_1st_visit:ga_at_1st_visit,
            maternal_fever:maternal_fever,
            ab_treatment:ab_treatment,
            ab_treatment_yes_drug:ab_treatment_yes_drug,
            prom:prom,
            prom_yes_hrs:prom_yes_hrs,
            amniotic_fluid:amniotic_fluid,
            abnormalities_of_placenta:abnormalities_of_placenta,
            abnormalities_of_placenta_yes:abnormalities_of_placenta_yes,
            abnormal_presentation:abnormal_presentation,
            abnormal_presentation_yes:abnormal_presentation_yes,
            mode_of_delivery:mode_of_delivery,
            cs:cs,
            indication:indication,
            duration_of_cs:duration_of_cs,
            duration_of_labour_stage1:duration_of_labour_stage1,
            duration_of_labour_stage2:duration_of_labour_stage2,
            duration_of_labour_stage3:duration_of_labour_stage3,
            obstructed_labour:obstructed_labour,
            place_of_delivery:place_of_delivery,
            delivery_attendant:delivery_attendant,
            if_assisted_delivery_why:if_assisted_delivery_why,
            problems_of_baby_after_birth:problems_of_baby_after_birth,
            resuscitation:resuscitation,
            resuscitation_yes:resuscitation_yes,
            eye_prophylaxis:eye_prophylaxis,
            vitamin_K_given:vitamin_K_given,
            drugs_given:drugs_given,
            drugs_given_yes_which:drugs_given_yes_which,
            feeding_started_within_1_hour:feeding_started_within_1_hour,
            chief_complaints:chief_complaints,
            fever:fever,
            vomiting:vomiting,
            feeding:feeding,
            enough_breast_milk:enough_breast_milk,
            feeding_interval:feeding_interval,
            passage_of_urine:passage_of_urine,
            passage_of_stool:passage_of_stool,
            quality:quality,
            other_complaints:other_complaints,
            baby_recieve_any_vaccines:baby_recieve_any_vaccines,
            weight:weight,
            temp:temp,
            pulse:pulse,
            resp_rate:resp_rate,
            SpO2:SpO2,
            rbg:rbg,
            appearance_condition:appearance_condition,
            appearance_activeness:appearance_activeness,
            appearance_nourished:appearance_nourished,
            appearance_Pathol:appearance_Pathol,
            appearance_comment:appearance_comment,
            skin_temperature:skin_temperature,
            skin_color:skin_color,
            skin_turgor:skin_turgor,
            skin_cyanosed:skin_cyanosed,
            skin_cyanosed_yes:skin_cyanosed_yes,
            skin_rashes:skin_rashes,
            skin_ctr:skin_ctr,
            head1:head1,
            head1_shape:head1_shape,
            head1_fontanelle:head1_fontanelle,
            head1_sutures:head1_sutures,
            head1_swelling_trauma:head1_swelling_trauma,
            head1_size:head1_size,
            head2_other_malformation:head2_other_malformation,
            head2_eye_discharge:head2_eye_discharge,
            neck_lymphadenopathy:neck_lymphadenopathy,
            neck_lymphadenopathy_yes:neck_lymphadenopathy_yes,
            neck_clavicle_fractured:neck_clavicle_fractured,
            breathing_chest_movement:breathing_chest_movement,
            breathing_indrawing:breathing_indrawing,
            breathing_sounds:breathing_sounds,
            breathing_preterm:breathing_preterm,
            heart_rhythm:heart_rhythm,
            heart_murmurs:heart_murmurs,
            heart_describe:heart_describe,
            abdomen:abdomen,
            umbillical_cord:umbillical_cord,
            genitalia_male:genitalia_male,
            genitalia_testis:genitalia_testis,
            genitalia_ambiguous:genitalia_ambiguous,
            genitalia_female:genitalia_female,
            genitalia_female_describe:genitalia_female_describe,
            anus_patent:anus_patent,
            anus_patent_no_describe:anus_patent_no_describe,
            anus_abdnormality:anus_abdnormality,
            back_posture:back_posture,
            back_malformation:back_malformation,
            back_malformation_hints:back_malformation_hints,
            neurology_spotaneous_movement:neurology_spotaneous_movement,
            neurology_musde_tone:neurology_musde_tone,
            neurology_flexes_glasping:neurology_flexes_glasping,
            neurology_flexes_sucking:neurology_flexes_sucking,
            neurology_flexes_traction:neurology_flexes_traction,
            neurology_flexes_moro:neurology_flexes_moro,
            finnstroem_score:finnstroem_score,
            additional_findings:additional_findings,
            key_findings:key_findings,
            provisional_diagnoses:provisional_diagnoses,
            differential_diagnoses:differential_diagnoses,
            investigation:investigation,
            treatment:treatment,
            supportive_care:supportive_care,
            preventions:preventions,
            action:"save_neonatal"
          },
          success:function(data){
            alert("data")

            //location.reload(true);

          }
        })
      } //end confirm
  } //end act

}
