<?php

function get_labour_record($conn, $registration_id, $admission_id)
{
    $select_labour_record = "SELECT date, general_state_of_admission ,temp ,pulse ,respiration ,
                                bloodpressure ,colour ,specific_gravity ,ph ,albumin ,sugar ,blood ,
                                leucocytes ,keytones ,clinical_appearance ,varicose_veins , blood2,
                                oedema ,mental_status ,inspection ,hb ,vdrl ,elisa ,shape ,
                                scars ,fundal_height ,lie ,presentation ,position ,
                                engagement_in_relation_to_brim ,frequency_and_type_of_contractions ,
                                foetal_heart_rate ,date_and_time ,cervic_state ,dilation ,presenting_part ,
                                station ,position_assessment ,moulding ,caput ,membrane_liqour ,
                                if_ruptured_date ,admitted_by ,examiner ,sacral_promontory ,
                                sacral_curve ,ischial_spines ,subpubic_angle ,sacral_tuberosites ,
                                expected_mode_of_delivery ,remarks ,
                                obstrecian_pedstrician_informed_by_name
                            FROM tbl_labour_record 
                            WHERE patient_id=? AND admission_id=?";

    $stmt = mysqli_prepare($conn, $select_labour_record);

    mysqli_stmt_bind_param($stmt, "ii", $registration_id, $admission_id);

    mysqli_stmt_execute($stmt);

    mysqli_stmt_bind_result(
        $stmt,
        $date_time,
        $state_of_admission,
        $temperature,
        $pulse,
        $resp,
        $bp,
        $colour,
        $specific_gravity,
        $ph,
        $albumin,
        $sugar,
        $blood,
        $leucocytes,
        $ketones,
        $clinical_appearance,
        $varicose_veins,
        $blood2,
        $oedema,
        $mental_status,
        $inspection,
        $hb,
        $vdrl,
        $elisa,
        $shape,
        $scars,
        $fundal_height,
        $lie,
        $presentation,
        $position,
        $brim,
        $contraction,
        $fhr,
        $date_time2,
        $cervic_state,
        $dilation,
        $presenting_part,
        $station,
        $position2,
        $moulding,
        $caput,
        $membrane_liquor,
        $rapture_date,
        $admitted_by,
        $examinaer,
        $sacral_promontory,
        $sacral_curve,
        $ischial_spine,
        $subpubic_angle,
        $sacral_tuberosites,
        $expected_mode_of_delivery,
        $remarks,
        $informed_by
    );

    mysqli_stmt_fetch($stmt);

    return array(
        $date_time,
        $state_of_admission,
        $temperature,
        $pulse,
        $resp,
        $bp,
        $colour,
        $specific_gravity,
        $ph,
        $albumin,
        $sugar,
        $blood,
        $leucocytes,
        $ketones,
        $clinical_appearance,
        $varicose_veins,
        $blood2,
        $oedema,
        $mental_status,
        $inspection,
        $hb,
        $vdrl,
        $elisa,
        $shape,
        $scars,
        $fundal_height,
        $lie,
        $presentation,
        $position,
        $brim,
        $contraction,
        $fhr,
        $date_time2,
        $cervic_state,
        $dilation,
        $presenting_part,
        $station,
        $position2,
        $moulding,
        $caput,
        $membrane_liquor,
        $rapture_date,
        $admitted_by,
        $examinaer,
        $sacral_promontory,
        $sacral_curve,
        $ischial_spine,
        $subpubic_angle,
        $sacral_tuberosites,
        $expected_mode_of_delivery,
        $remarks,
        $informed_by
    );
}

function get_labor_record_($conn, $registration_id)
{
    $data = array();

    $d = array();

    $select_labour_record = "SELECT date, general_state_of_admission ,temp ,pulse ,respiration ,
                                bloodpressure ,colour ,specific_gravity ,ph ,albumin ,sugar ,blood ,
                                leucocytes ,keytones ,clinical_appearance ,varicose_veins , blood2,
                                oedema ,mental_status ,inspection ,hb ,vdrl ,elisa ,shape ,
                                scars ,fundal_height ,lie ,presentation ,position ,
                                engagement_in_relation_to_brim ,frequency_and_type_of_contractions ,
                                foetal_heart_rate ,date_and_time ,cervic_state ,dilation ,presenting_part ,
                                station ,position_assessment ,moulding ,caput ,membrane_liqour ,
                                if_ruptured_date ,admitted_by ,examiner ,sacral_promontory ,
                                sacral_curve ,ischial_spines ,subpubic_angle ,sacral_tuberosites ,
                                expected_mode_of_delivery ,remarks ,
                                obstrecian_pedstrician_informed_by_name
                            FROM tbl_labour_record 
                            WHERE patient_id=?";

    $stmt = mysqli_prepare($conn, $select_labour_record);

    mysqli_stmt_bind_param($stmt, "i", $registration_id);

    mysqli_stmt_execute($stmt) or die(mysqli_error($conn));

    mysqli_stmt_bind_result(
        $stmt,
        $date_time,
        $state_of_admission,
        $temperature,
        $pulse,
        $resp,
        $bp,
        $colour,
        $specific_gravity,
        $ph,
        $albumin,
        $sugar,
        $blood,
        $leucocytes,
        $ketones,
        $clinical_appearance,
        $varicose_veins,
        $blood2,
        $oedema,
        $mental_status,
        $inspection,
        $hb,
        $vdrl,
        $elisa,
        $group,
        $shape,
        $scars,
        $fundal_height,
        $lie,
        $presentation,
        $position,
        $brim,
        $contraction,
        $fhr,
        $date_time2,
        $cervic_state,
        $dilation,
        $presenting_part,
        $station,
        $position2,
        $moulding,
        $caput,
        $membrane_liquor,
        $rapture_date,
        $admitted_by,
        $examinaer,
        $sacral_promontory,
        $sacral_curve,
        $ischial_spine,
        $subpubic_angle,
        $sacral_tuberosites,
        $expected_mode_of_delivery,
        $remarks,
        $informed_by
    );

    while (mysqli_stmt_fetch($stmt)) {

        $d['date_time'] = $date_time;

        $d['state_of_admission'] = $state_of_admission;

        $d['temperature'] = $temperature;

        $d['pulse'] = $pulse;

        $d['resp'] = $resp;

        $d['bp'] = $bp;

        $d['colour'] = $colour;

        $d['specific_gravity'] = $specific_gravity;

        $d['ph'] = $ph;

        $d['albumin'] = $albumin;

        $d['sugar'] = $sugar;

        $d['blood'] = $blood;

        $d['leucocytes'] = $leucocytes;

        $d['ketones'] = $ketones;

        $d['clinical_appearance'] = $clinical_appearance;

        $d['varicose_veins'] = $varicose_veins;

        $d['oedema'] = $oedema;

        $d['mental_status'] = $mental_status;

        $d['inspection'] = $inspection;

        $d['admitted_by'] = $admitted_by;

        $d['examinaer'] = $examinaer;

        $d['expected_mode_of_delivery'] = $expected_mode_of_delivery;

        $d['remarks'] = $remarks;

        $d['informed_by'] = $informed_by;

        array_push($data, $d);
    }

    return $data;
}

function get_obstretic_record($conn, $registration_id, $admission_id)
{
    $select_obstretic_record = "SELECT paeditrician, anaesthetist, surgeon, physician, date_of_admission, date_of_anc, drug_allergies, 
                                    date_of_discharge, lmp_duration, edd_duration, ga_duration, para, gravida, blood_group, weight, 
                                    height, medical_surgical_history, family_history, reason_for_admission 
                            FROM tbl_obstretic_record 
                            WHERE Registration_ID=? AND Admission_ID=?";

    $stmt = mysqli_prepare($conn, $select_obstretic_record);

    mysqli_stmt_bind_param($stmt, "ii", $registration_id, $admission_id);

    mysqli_stmt_execute($stmt);

    mysqli_stmt_bind_result(
        $stmt,
        $paeditrician,
        $anaesthetist,
        $surgeon,
        $physician,
        $date_of_admission,
        $date_of_anc,
        $drug_allergies,
        $date_of_discharge,
        $lmp_duration,
        $edd_duration,
        $ga_duration,
        $para,
        $gravida,
        $blood_group,
        $weight,
        $height,
        $medical_surgical_history,
        $family_history,
        $reason_for_admission
    );

    mysqli_stmt_fetch($stmt);

    return array(
        $paeditrician,
        $anaesthetist,
        $surgeon,
        $physician,
        $date_of_admission,
        $date_of_anc,
        $drug_allergies,
        $date_of_discharge,
        $lmp_duration,
        $edd_duration,
        $ga_duration,
        $para,
        $gravida,
        $blood_group,
        $weight,
        $height,
        $medical_surgical_history,
        $family_history,
        $reason_for_admission
    );
}

function get_obstretic_record_($conn, $registration_id)
{

    $data = array();

    $d = array();

    $select_obstretic_record = "SELECT paeditrician, anaesthetist, surgeon, physician, date_of_admission, date_of_anc, drug_allergies, 
                                    date_of_discharge, lmp_duration, edd_duration, ga_duration, para, gravida, blood_group, weight, 
                                    height, medical_surgical_history, family_history, reason_for_admission 
                            FROM tbl_obstretic_record 
                            WHERE Registration_ID=?";

    $stmt = mysqli_prepare($conn, $select_obstretic_record);

    mysqli_stmt_bind_param($stmt, "i", $registration_id);

    mysqli_stmt_execute($stmt);

    mysqli_stmt_bind_result(
        $stmt,
        $paeditrician,
        $anaesthetist,
        $surgeon,
        $physician,
        $date_of_admission,
        $date_of_anc,
        $drug_allergies,
        $date_of_discharge,
        $lmp_duration,
        $edd_duration,
        $ga_duration,
        $para,
        $gravida,
        $blood_group,
        $weight,
        $height,
        $medical_surgical_history,
        $family_history,
        $reason_for_admission
    );

    while (mysqli_stmt_fetch($stmt)) {

        $d['paeditrician'] = $paeditrician;

        $d['anaesthetist'] = $anaesthetist;

        $d['surgeon'] = $surgeon;

        $d['physician'] = $physician;

        $d['date_of_admission'] = $date_of_admission;

        $d['date_of_anc'] = $date_of_anc;

        $d['drug_allergies'] = $drug_allergies;

        $d['date_of_discharge'] = $date_of_discharge;

        $d['lmp_duration'] = $lmp_duration;

        $d['edd_duration'] = $edd_duration;

        $d['ga_duration'] = $ga_duration;

        $d['para'] = $para;

        $d['gravida'] = $gravida;

        $d['blood_group'] = $blood_group;

        $d['weight'] = $weight;

        $d['height'] = $height;

        $d['medical_surgical_history'] = $medical_surgical_history;

        $d['family_history'] = $family_history;

        $d['reason_for_admission'] = $reason_for_admission;

        array_push($data, $d);
    }

    return $data;
}

function get_first_stage($conn, $registration_id, $admission_id)
{
    $select_first_stage = "SELECT set_of_labour_time_and_date, admitted_at, state_of_membrane, 
                                time_and_date_of_rupture, time_elapsed_since_rupture, arm, 
                                no_of_vaginal_examination, abdomalities_of_first_stage, 
                                induction_of_labour, induction_reason, 
                                total_duration_of_first_stage_labour, drugs_given, remarks
                            FROM tbl_first_stage_of_labour 
                            WHERE patient_id=? AND admission_id=?";

    $stmt = mysqli_prepare($conn, $select_first_stage);

    mysqli_stmt_bind_param($stmt, "ii", $registration_id, $admission_id);

    mysqli_stmt_execute($stmt);

    mysqli_stmt_bind_result(
        $stmt,
        $onset_labor,
        $admitted_at,
        $membrane_liquor,
        $rapture_date,
        $rapture_duration,
        $arm,
        $no_of_examinations,
        $abnormalities,
        $induction_of_labor,
        $induction_of_labor_reason,
        $first_stage_duration,
        $drugs_given,
        $remarks
    );

    mysqli_stmt_fetch($stmt);

    return array(
        $onset_labor,
        $admitted_at,
        $membrane_liquor,
        $rapture_date,
        $rapture_duration,
        $arm,
        $no_of_examinations,
        $abnormalities,
        $induction_of_labor,
        $induction_of_labor_reason,
        $first_stage_duration,
        $drugs_given,
        $remarks
    );
}

function get_first_stage_($conn, $registration_id)
{
    $data = array();

    $d = array();

    $select_first_stage = "SELECT set_of_labour_time_and_date, admitted_at, state_of_membrane, 
                                time_and_date_of_rupture, time_elapsed_since_rupture, arm, 
                                no_of_vaginal_examination, abdomalities_of_first_stage, 
                                induction_of_labour, induction_reason, 
                                total_duration_of_first_stage_labour, drugs_given, remarks
                            FROM tbl_first_stage_of_labour 
                            WHERE patient_id=?";

    $stmt = mysqli_prepare($conn, $select_first_stage);

    mysqli_stmt_bind_param($stmt, "i", $registration_id);

    mysqli_stmt_execute($stmt);

    mysqli_stmt_bind_result(
        $stmt,
        $onset_labor,
        $admitted_at,
        $membrane_liquor,
        $rapture_date,
        $rapture_duration,
        $arm,
        $no_of_examinations,
        $abnormalities,
        $induction_of_labor,
        $induction_of_labor_reason,
        $first_stage_duration,
        $drugs_given,
        $remarks
    );

    while (mysqli_stmt_fetch($stmt)) {

        $d['onset_labor'] = $onset_labor;

        $d['admitted_at'] = $admitted_at;

        $d['membrane_liquor'] = $membrane_liquor;

        $d['rapture_date'] = $rapture_date;

        $d['rapture_duration'] = $rapture_duration;

        $d['arm'] = $arm;

        $d['no_of_examinations'] = $no_of_examinations;

        $d['abnormalities'] = $abnormalities;

        $d['induction_of_labor'] = $induction_of_labor;

        $d['induction_of_labor_reason'] = $induction_of_labor_reason;

        $d['first_stage_duration'] = $first_stage_duration;

        $d['drugs_given'] = $drugs_given;

        $d['remarks'] = $remarks;

        array_push($data, $d);
    }

    return $data;
}

function get_second_stage($conn, $registration_id, $admission_id)
{
    $select_second_stage = "SELECT time_began, date_of_birth, duration, mode_of_delivery, drugs, remarks
                            FROM tbl_second_stage_of_labour 
                            WHERE patient_id=? AND admission_id=?";

    $stmt = mysqli_prepare($conn, $select_second_stage);

    mysqli_stmt_bind_param($stmt, "ii", $registration_id, $admission_id);

    mysqli_stmt_execute($stmt);

    mysqli_stmt_bind_result(
        $stmt,
        $time_began,
        $date_time,
        $duration,
        $mode_of_delivery,
        $drugs,
        $remarks
    );

    mysqli_stmt_fetch($stmt);

    return array(
        $time_began,
        $date_time,
        $duration,
        $mode_of_delivery,
        $drugs,
        $remarks
    );
}

function get_second_stage_($conn, $registration_id)
{
    $data = array();

    $d = array();

    $select_second_stage = "SELECT time_began, date_of_birth, duration, mode_of_delivery, drugs, remarks
                            FROM tbl_second_stage_of_labour 
                            WHERE patient_id=?";

    $stmt = mysqli_prepare($conn, $select_second_stage);

    mysqli_stmt_bind_param($stmt, "i", $registration_id);

    mysqli_stmt_execute($stmt);

    mysqli_stmt_bind_result(
        $stmt,
        $time_began,
        $date_time,
        $duration,
        $mode_of_delivery,
        $drugs,
        $remarks
    );

    while (mysqli_stmt_fetch($stmt)) {

        $d['time_began'] = $time_began;

        $d['date_time'] = $date_time;

        $d['duration'] = $duration;

        $d['mode_of_delivery'] = $mode_of_delivery;

        $d['drugs'] = $drugs;

        $d['remarks'] = $remarks;

        array_push($data, $d);
    }

    return $data;
}

function get_third_stage($conn, $registration_id, $admission_id)
{
    $select_third_stage = "SELECT methodology_delivery_placenter, date_and_time, duration, placenter_weight, 
                                stage_of_placent, colour, cord, membranes, disposal, state_of_cervix, 
                                episiotomy_tear, repaired_with_sutures, total_blood_loss, t, p, r, 
                                bp, lochia, state_of_uterus, remarks
                            FROM tbl_third_stage_of_labour 
                            WHERE patient_id=? AND admission_id=?";

    $stmt = mysqli_prepare($conn, $select_third_stage);

    mysqli_stmt_bind_param($stmt, "ii", $registration_id, $admission_id);

    mysqli_stmt_execute($stmt);

    mysqli_stmt_bind_result(
        $stmt,
        $placenta_method_of_delivery,
        $date_time,
        $duration,
        $placenta_weight,
        $stage_of_placenta,
        $colour,
        $cord,
        $membranes,
        $disposal,
        $state_of_cervix,
        $tear,
        $repaired_with_sutures,
        $total_blood_loss,
        $temperature,
        $pulse,
        $resp,
        $bp,
        $lochia,
        $state_of_uterus,
        $remarks
    );

    mysqli_stmt_fetch($stmt);

    return array(
        $placenta_method_of_delivery,
        $date_time,
        $duration,
        $placenta_weight,
        $stage_of_placenta,
        $colour,
        $cord,
        $membranes,
        $disposal,
        $state_of_cervix,
        $tear,
        $repaired_with_sutures,
        $total_blood_loss,
        $temperature,
        $pulse,
        $resp,
        $bp,
        $lochia,
        $state_of_uterus,
        $remarks
    );
}

function get_third_stage_($conn, $registration_id)
{
    $data = array();

    $d = array();

    $select_third_stage = "SELECT methodology_delivery_placenter, date_and_time, duration, placenter_weight, 
                                stage_of_placent, colour, cord, membranes, disposal, state_of_cervix, 
                                episiotomy_tear, repaired_with_sutures, total_blood_loss, t, p, r, 
                                bp, lochia, state_of_uterus, remarks
                            FROM tbl_third_stage_of_labour 
                            WHERE patient_id=?";

    $stmt = mysqli_prepare($conn, $select_third_stage);

    mysqli_stmt_bind_param($stmt, "i", $registration_id);

    mysqli_stmt_execute($stmt);

    mysqli_stmt_bind_result(
        $stmt,
        $placenta_method_of_delivery,
        $date_time,
        $duration,
        $placenta_weight,
        $stage_of_placenta,
        $colour,
        $cord,
        $membranes,
        $disposal,
        $state_of_cervix,
        $tear,
        $repaired_with_sutures,
        $total_blood_loss,
        $temperature,
        $pulse,
        $resp,
        $bp,
        $lochia,
        $state_of_uterus,
        $remarks
    );

    while (mysqli_stmt_fetch($stmt)) {

        $d['placenta_method_of_delivery'] = $placenta_method_of_delivery;

        $d['date_time'] = $date_time;

        $d['duration'] = $duration;

        $d['placenta_weight'] = $placenta_weight;

        $d['stage_of_placenta'] = $stage_of_placenta;

        $d['colour'] = $colour;

        $d['cord'] = $cord;

        $d['membranes'] = $membranes;

        $d['disposal'] = $disposal;

        $d['state_of_cervix'] = $state_of_cervix;

        $d['tear'] = $tear;

        $d['repaired_with_sutures'] = $repaired_with_sutures;

        $d['total_blood_loss'] = $total_blood_loss;

        $d['temperature'] = $temperature;

        $d['pulse'] = $pulse;

        $d['resp'] = $resp;

        $d['bp'] = $bp;

        $d['lochia'] = $lochia;

        $d['state_of_uterus'] = $state_of_uterus;

        $d['remarks'] = $remarks;

        array_push($data, $d);
    }

    return $data;
}

function get_fourth_stage($conn, $registration_id, $admission_id)
{
    $select_fourth_stage = "SELECT temp, pr, bp, fundal_height, state_of_cervix, state_of_perinium, 
                                blood_loss, doctor_midwife_recommendation
                            FROM tbl_fourth_stage_of_labour 
                            WHERE patient_id=? AND admission_id=?";

    $stmt = mysqli_prepare($conn, $select_fourth_stage);

    mysqli_stmt_bind_param($stmt, "ii", $registration_id, $admission_id);

    mysqli_stmt_execute($stmt);

    mysqli_stmt_bind_result(
        $stmt,
        $temperature,
        $pr,
        $bp,
        $fundal_height,
        $state_of_cervix,
        $state_of_perinium,
        $blood_loss,
        $recommendations
    );

    mysqli_stmt_fetch($stmt);

    return array(
        $temperature,
        $pr,
        $bp,
        $fundal_height,
        $state_of_cervix,
        $state_of_perinium,
        $blood_loss,
        $recommendations
    );
}

function get_fourth_stage_($conn, $registration_id)
{
    $data = array();

    $d = array();

    $select_fourth_stage = "SELECT temp, pr, bp, fundal_height, state_of_cervix, state_of_perinium, 
                                blood_loss, doctor_midwife_recommendation
                            FROM tbl_fourth_stage_of_labour 
                            WHERE patient_id=?";

    $stmt = mysqli_prepare($conn, $select_fourth_stage);

    mysqli_stmt_bind_param($stmt, "i", $registration_id);

    mysqli_stmt_execute($stmt);

    mysqli_stmt_bind_result(
        $stmt,
        $temperature,
        $pr,
        $bp,
        $fundal_height,
        $state_of_cervix,
        $state_of_perinium,
        $blood_loss,
        $recommendations
    );

    while (mysqli_stmt_fetch($stmt)) {

        $d['temperature'] = $temperature;

        $d['pr'] = $pr;

        $d['bp'] = $bp;

        $d['fundal_height'] = $fundal_height;

        $d['state_of_cervix'] = $state_of_cervix;

        $d['state_of_perinium'] = $state_of_perinium;

        $d['blood_loss'] = $blood_loss;

        $d['recommendations'] = $recommendations;

        array_push($data, $d);
    }

    return $data;
}

function get_baby_record($conn, $registration_id, $admission_id)
{
    $select_baby_record = "SELECT sex, state_of_birth, apgar, birth_weight, length, head_circumference, 
                                abnormalities, drugs, paediatrician, transferred_to, reason, 
                                transferred_by, name, temperature
                            FROM tbl_baby_record 
                            WHERE registration_id=? AND admission_id=?";

    $stmt = mysqli_prepare($conn, $select_baby_record);

    mysqli_stmt_bind_param($stmt, "ii", $registration_id, $admission_id);

    mysqli_stmt_execute($stmt);

    mysqli_stmt_bind_result(
        $stmt,
        $sex,
        $state_of_birth,
        $apgar,
        $birth_weight,
        $length,
        $head_circumference,
        $abnormalities,
        $drugs,
        $paediatrician,
        $transferred_to,
        $reason,
        $transferred_by,
        $name,
        $temperature
    );

    mysqli_stmt_fetch($stmt);

    return array(
        $sex,
        $state_of_birth,
        $apgar,
        $birth_weight,
        $length,
        $head_circumference,
        $abnormalities,
        $drugs,
        $paediatrician,
        $transferred_to,
        $reason,
        $transferred_by,
        $name,
        $temperature
    );
}

function get_baby_record_($conn, $registration_id)
{
    $data = array();

    $d = array();

    $select_baby_record = "SELECT sex, state_of_birth, apgar, birth_weight, length, head_circumference, 
                                abnormalities, drugs, paediatrician, transferred_to, reason, 
                                transferred_by, name, temperature
                            FROM tbl_baby_record 
                            WHERE registration_id=?";

    $stmt = mysqli_prepare($conn, $select_baby_record);

    mysqli_stmt_bind_param($stmt, "i", $registration_id);

    mysqli_stmt_execute($stmt);

    mysqli_stmt_bind_result(
        $stmt,
        $sex,
        $state_of_birth,
        $apgar,
        $birth_weight,
        $length,
        $head_circumference,
        $abnormalities,
        $drugs,
        $paediatrician,
        $transferred_to,
        $reason,
        $transferred_by,
        $name,
        $temperature
    );

    while (mysqli_stmt_fetch($stmt)) {

        $d['sex'] = $sex;

        $d['state_of_birth'] = $state_of_birth;

        $d['apgar'] = $apgar;

        $d['birth_weight'] = $birth_weight;

        $d['length'] = $length;

        $d['head_circumference'] = $head_circumference;

        $d['abnormalities'] = $abnormalities;

        $d['drugs'] = $drugs;

        $d['paediatrician'] = $paediatrician;

        $d['transferred_to'] = $transferred_to;

        $d['reason'] = $reason;

        $d['transferred_by'] = $transferred_by;

        $d['name'] = $name;

        $d['temperature'] = $temperature;

        array_push($data, $d);
    }

    return $data;
}

function get_post_natal_record($conn, $registration_id, $admission_id)
{
    $select_post_natal_record = "SELECT hospital_duration, next_appointment, peurperium, uterus, lochia, 
                                    midwife_name, episiotomy, breasts, abdominal_scars, general_condition, 
                                    anaemia, breasts2, cervix, vagina, episiotomy2, stress_incontinence, anus, 
                                    tenderness, remarks, temperature, pulse, bp, baby_condition, 
                                    mother_remarks, midwife_name2
                                FROM tbl_post_natal_record 
                                WHERE patient_id=? AND admission_id=?";

    $stmt = mysqli_prepare($conn, $select_post_natal_record);

    mysqli_stmt_bind_param($stmt, "ii", $registration_id, $admission_id);

    mysqli_stmt_execute($stmt);

    mysqli_stmt_bind_result(
        $stmt,
        $hospital_duration,
        $next_appointment,
        $peurperium,
        $uterus,
        $lochia,
        $midwife_name,
        $episiotomy,
        $breasts,
        $abdominal_scars,
        $general_condition,
        $anaemia,
        $breasts2,
        $cervix,
        $vagina,
        $episiotomy2,
        $stress_incontinence,
        $anus,
        $tenderness,
        $remarks,
        $temperature,
        $pulse,
        $bp,
        $baby_condition,
        $mother_remarks,
        $midwife_name2
    );

    mysqli_stmt_fetch($stmt);

    return array(
        $hospital_duration,
        $next_appointment,
        $peurperium,
        $uterus,
        $lochia,
        $midwife_name,
        $episiotomy,
        $breasts,
        $abdominal_scars,
        $general_condition,
        $anaemia,
        $breasts2,
        $cervix,
        $vagina,
        $episiotomy2,
        $stress_incontinence,
        $anus,
        $tenderness,
        $remarks,
        $temperature,
        $pulse,
        $bp,
        $baby_condition,
        $mother_remarks,
        $midwife_name2
    );
}

function get_post_natal_record_($conn, $registration_id)
{
    $data = array();

    $d = array();

    $select_post_natal_record = "SELECT hospital_duration, next_appointment, peurperium, uterus, lochia, 
                                    midwife_name, episiotomy, breasts, abdominal_scars, general_condition, 
                                    anaemia, breasts2, cervix, vagina, episiotomy2, stress_incontinence, anus, 
                                    tenderness, remarks, temperature, pulse, bp, baby_condition, 
                                    mother_remarks, midwife_name2
                                FROM tbl_post_natal_record 
                                WHERE patient_id=?";

    $stmt = mysqli_prepare($conn, $select_post_natal_record);

    mysqli_stmt_bind_param($stmt, "i", $registration_id);

    mysqli_stmt_execute($stmt);

    mysqli_stmt_bind_result(
        $stmt,
        $hospital_duration,
        $next_appointment,
        $peurperium,
        $uterus,
        $lochia,
        $midwife_name,
        $episiotomy,
        $breasts,
        $abdominal_scars,
        $general_condition,
        $anaemia,
        $breasts2,
        $cervix,
        $vagina,
        $episiotomy2,
        $stress_incontinence,
        $anus,
        $tenderness,
        $remarks,
        $temperature,
        $pulse,
        $bp,
        $baby_condition,
        $mother_remarks,
        $midwife_name2
    );

    while (mysqli_stmt_fetch($stmt)) {

        $d['hospital_duration'] = $hospital_duration;

        $d['next_appointment'] = $next_appointment;

        $d['peurperium'] = $peurperium;

        $d['uterus'] = $uterus;

        $d['lochia'] = $lochia;

        $d['midwife_name'] = $midwife_name;

        $d['episiotomy'] = $episiotomy;

        $d['breasts'] = $breasts;

        $d['abdominal_scars'] = $abdominal_scars;

        $d['general_condition'] = $general_condition;

        $d['anaemia'] = $anaemia;

        $d['breasts2'] = $breasts2;

        $d['cervix'] = $cervix;

        $d['vagina'] = $vagina;

        $d['episiotomy2'] = $episiotomy2;

        $d['stress_incontinence'] = $stress_incontinence;

        $d['anus'] = $anus;

        $d['tenderness'] = $tenderness;

        $d['remarks'] = $remarks;

        $d['temperature'] = $temperature;

        $d['pulse'] = $pulse;

        $d['bp'] = $bp;

        $d['baby_condition'] = $baby_condition;

        $d['mother_remarks'] = $mother_remarks;

        $d['midwife_name2'] = $midwife_name2;

        array_push($data, $d);
    }

    return $data;
}

function get_obstretic_history($conn, $registration_id, $admission_id)
{
    $data = array();

    $d = array();

    $select_labour_history = "SELECT year_of_birth, matunity, sex, mode_of_delivery, birth_weight, place_of_birth, breastfed_duration, puerperium, child_condition
                                FROM tbl_obstretic_history
                                WHERE Registration_ID=? AND Admission_ID=?";

    $stmt = mysqli_prepare($conn, $select_labour_history);

    mysqli_stmt_bind_param($stmt, "ii", $registration_id, $admission_id);

    mysqli_stmt_execute($stmt);

    mysqli_stmt_bind_result($stmt, $year_of_birth, $matunity, $sex, $mode_of_delivery, $birth_weight, $place_of_birth, $breastfed_duration, $puerperium, $present_child_condition);

    while (mysqli_stmt_fetch($stmt)) {

        $d['year_of_birth'] = $year_of_birth;

        $d['matunity'] = $matunity;

        $d['sex'] = $sex;

        $d['mode_of_delivery'] = $mode_of_delivery;

        $d['birth_weight'] = $birth_weight;

        $d['place_of_birth'] = $place_of_birth;

        $d['breastfed_duration'] = $breastfed_duration;

        $d['puerperium'] = $puerperium;

        $d['present_child_condition'] = $present_child_condition;

        array_push($data, $d);
    }

    return $data;
}

function get_neonatal_record($conn, $registration_id, $admission_id)
{
    $select_neonatal_record = "SELECT dob, sex, weight, apgar, maturity, membranes_ruptured, amniotic_fluids, 
                                antenatal_care, diseases_complications, delivery_type, indication, 
                                fhr, placenta, placenta_weight, abnormalities, drugs_given, eye_drops, 
                                sent_to, delivered_by, prem_unit_by, recieved_by, condition_on_arrival, time
                            FROM tbl_neonatal_record 
                            WHERE patient_id=? AND admission_id=?";

    $stmt = mysqli_prepare($conn, $select_neonatal_record);

    mysqli_stmt_bind_param($stmt, "ii", $registration_id, $admission_id);

    mysqli_stmt_execute($stmt);

    mysqli_stmt_bind_result(
        $stmt,
        $dob,
        $sex,
        $wt,
        $apgar,
        $maturity,
        $membranes_ruptured,
        $amniotic_fluids,
        $antenatal_care,
        $diseases_complications,
        $delivery_type,
        $indication,
        $fhr,
        $placenta,
        $placenta_weight,
        $abnormalities,
        $drugs_given,
        $eye_drops,
        $sent_to,
        $delivered_by,
        $prem_unit_by,
        $recieved_by,
        $condition_on_arrival,
        $time
    );

    mysqli_stmt_fetch($stmt);

    return array(
        $dob,
        $sex,
        $wt,
        $apgar,
        $maturity,
        $membranes_ruptured,
        $amniotic_fluids,
        $antenatal_care,
        $diseases_complications,
        $delivery_type,
        $indication,
        $fhr,
        $placenta,
        $placenta_weight,
        $abnormalities,
        $drugs_given,
        $eye_drops,
        $sent_to,
        $delivered_by,
        $prem_unit_by,
        $recieved_by,
        $condition_on_arrival,
        $time
    );
}

function get_summary_of_labor($conn, $registration_id, $admission_id)
{
    $select_summary_labor = "SELECT sex, weight, abnormalities, resuscitation, drugs, eye_drop
                        FROM tbl_summary_labor 
                        WHERE Registration_ID=? AND admission_id=?";

    $stmt = mysqli_prepare($conn, $select_summary_labor);

    mysqli_stmt_bind_param($stmt, "ii", $registration_id, $admission_id);

    mysqli_stmt_execute($stmt);

    mysqli_stmt_bind_result(
        $stmt,
        $sex,
        $weight,
        $abnormalities,
        $resuscitation,
        $drugs,
        $eye_drop
    );

    mysqli_stmt_fetch($stmt);

    return array($sex, $weight, $abnormalities, $resuscitation, $drugs, $eye_drop);
}
