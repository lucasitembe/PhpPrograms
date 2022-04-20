<?php

include("../includes/connection.php");

$registration_id = mysqli_real_escape_string($conn, trim($_POST['registration_id']));

$admission_id = mysqli_real_escape_string($conn, trim($_POST['admission_id']));

$date_time = mysqli_real_escape_string($conn, trim($_POST['date_time']));

$temperature = mysqli_real_escape_string($conn, trim($_POST['temperature']));

$colour = mysqli_real_escape_string($conn, trim($_POST['colour']));

$specific_gravity = mysqli_real_escape_string($conn, trim($_POST['specific_gravity']));

$pulse = mysqli_real_escape_string($conn, trim($_POST['pulse']));

$ph = mysqli_real_escape_string($conn, trim($_POST['ph']));

$blood = mysqli_real_escape_string($conn, trim($_POST['blood']));

$resp = mysqli_real_escape_string($conn, trim($_POST['resp']));

$albumin = mysqli_real_escape_string($conn, trim($_POST['albumin']));

$sugar = mysqli_real_escape_string($conn, trim($_POST['sugar']));

$state_of_admission = mysqli_real_escape_string($conn, trim($_POST['state_of_admission']));

$bp = mysqli_real_escape_string($conn, trim($_POST['bp']));

$leucocytes = mysqli_real_escape_string($conn, trim($_POST['leucocytes']));

$ketones = mysqli_real_escape_string($conn, trim($_POST['ketones']));

$clinical_appearance = mysqli_real_escape_string($conn, trim($_POST['clinical_appearance']));

$hb = mysqli_real_escape_string($conn, trim($_POST['hb']));

$vdrl = mysqli_real_escape_string($conn, trim($_POST['vdrl']));

$elisa = mysqli_real_escape_string($conn, trim($_POST['elisa']));

$varicose_veins = mysqli_real_escape_string($conn, trim($_POST['varicose_veins']));

$blood2 = mysqli_real_escape_string($conn, trim($_POST['blood2']));

$oedema = mysqli_real_escape_string($conn, trim($_POST['oedema']));

$mental_status = mysqli_real_escape_string($conn, trim($_POST['mental_status']));

$shape = mysqli_real_escape_string($conn, trim($_POST['shape']));

$scars = mysqli_real_escape_string($conn, trim($_POST['scars']));

$inspection = mysqli_real_escape_string($conn, trim($_POST['inspection']));

$fundal_height = mysqli_real_escape_string($conn, trim($_POST['fundal_height']));

$lie = mysqli_real_escape_string($conn, trim($_POST['lie']));

$presentation = mysqli_real_escape_string($conn, trim($_POST['presentation']));

$position = mysqli_real_escape_string($conn, trim($_POST['position']));

$brim = mysqli_real_escape_string($conn, trim($_POST['brim']));

$contraction = mysqli_real_escape_string($conn, trim($_POST['contraction']));

$fhr = mysqli_real_escape_string($conn, trim($_POST['fhr']));

$date_time2 = mysqli_real_escape_string($conn, trim($_POST['date_time2']));

$examinaer = mysqli_real_escape_string($conn, trim($_POST['examinaer']));

$cervic_state = mysqli_real_escape_string($conn, trim($_POST['cervic_state']));

$dilation = mysqli_real_escape_string($conn, trim($_POST['dilation']));

$presenting_part = mysqli_real_escape_string($conn, trim($_POST['presenting_part']));

$station = mysqli_real_escape_string($conn, trim($_POST['station']));

$position2 = mysqli_real_escape_string($conn, trim($_POST['position2']));

$moulding = mysqli_real_escape_string($conn, trim($_POST['moulding']));

$caput = mysqli_real_escape_string($conn, trim($_POST['caput']));

$membrane_liquor = mysqli_real_escape_string($conn, trim($_POST['membrane_liquor']));

$rapture = mysqli_real_escape_string($conn, trim($_POST['rapture']));

$rapture_date = mysqli_real_escape_string($conn, trim($_POST['rapture_date']));

$sacral_promontory = mysqli_real_escape_string($conn, trim($_POST['sacral_promontory']));

$sacral_curve = mysqli_real_escape_string($conn, trim($_POST['sacral_curve']));

$ischial_spine = mysqli_real_escape_string($conn, trim($_POST['ischial_spine']));

$subpubic_angle = mysqli_real_escape_string($conn, trim($_POST['subpubic_angle']));

$sacral_tuberosites = mysqli_real_escape_string($conn, trim($_POST['sacral_tuberosites']));

$expected_mode_of_delivery = mysqli_real_escape_string($conn, trim($_POST['expected_mode_of_delivery']));

$remarks = mysqli_real_escape_string($conn, trim($_POST['remarks']));

$admitted_by = mysqli_real_escape_string($conn, trim($_POST['admitted_by']));

$informed_by = mysqli_real_escape_string($conn, trim($_POST['informed_by']));

$select_labor_record = "SELECT date, general_state_of_admission ,temp ,pulse ,respiration ,
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

$stmt = mysqli_prepare($conn, $select_labor_record);

mysqli_stmt_bind_param($stmt, "ii", $registration_id, $admission_id);

mysqli_stmt_execute($stmt);

mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) > 0) {
    $update_labor_record = "UPDATE tbl_labour_record
                            SET date = ?, general_state_of_admission = ?,temp = ?,pulse = ?,respiration = ?,
                                bloodpressure = ?,colour = ?,specific_gravity = ?,ph = ? ,albumin = ?,sugar = ?,blood = ?,
                                leucocytes = ?,keytones = ?,clinical_appearance = ?,varicose_veins = ?, blood2 = ?,
                                oedema = ?,mental_status = ?,inspection = ?,hb = ?,vdrl = ?,elisa = ?,shape = ?,
                                scars = ?,fundal_height = ?,lie = ?,presentation = ?,position = ?,
                                engagement_in_relation_to_brim = ?,frequency_and_type_of_contractions = ?,
                                foetal_heart_rate = ?,date_and_time = ?,cervic_state = ?,dilation = ?,presenting_part = ?,
                                station = ?,position_assessment = ?,moulding = ?,caput = ?,membrane_liqour = ?,
                                if_ruptured_date = ?,admitted_by = ?,examiner = ?,sacral_promontory = ?,
                                sacral_curve = ?,ischial_spines = ?,subpubic_angle = ?,sacral_tuberosites = ?,
                                expected_mode_of_delivery = ?,remarks = ?,
                                obstrecian_pedstrician_informed_by_name = ?
                            WHERE patient_id=? AND admission_id=?";

    $update_stmt = mysqli_prepare($conn, $update_labor_record);

    mysqli_stmt_bind_param(
        $update_stmt,
        "ssssssssssssssssssssssssssssssssssssssssssssssssssssii",
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
        $informed_by,
        $registration_id,
        $admission_id
    );

    if (mysqli_stmt_execute($update_stmt)) {

        echo "Updated successfully";
    } else {

        echo "Data updated failure";
    }
} else {
    $insert_labor_record = "INSERT INTO tbl_labour_record(patient_id, admission_id, date, general_state_of_admission,temp,pulse,respiration,
                                    bloodpressure,colour,specific_gravity,ph ,albumin,sugar,blood,
                                    leucocytes,keytones,clinical_appearance,varicose_veins, blood2,
                                    oedema,mental_status,inspection,hb,vdrl,elisa,shape,
                                    scars,fundal_height,lie,presentation,position,
                                    engagement_in_relation_to_brim,frequency_and_type_of_contractions,
                                    foetal_heart_rate,date_and_time,cervic_state,dilation,presenting_part,
                                    station,position_assessment,moulding,caput,membrane_liqour,
                                    if_ruptured_date,admitted_by,examiner,sacral_promontory,
                                    sacral_curve,ischial_spines,subpubic_angle,sacral_tuberosites,
                                    expected_mode_of_delivery,remarks,
                                    obstrecian_pedstrician_informed_by_name)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
                                    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";


    $insert_record_stmt = mysqli_prepare($conn, $insert_labor_record);

    mysqli_stmt_bind_param(
        $insert_record_stmt,
        "iissssssssssssssssssssssssssssssssssssssssssssssssssss",
        $registration_id,
        $admission_id,
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

    if (mysqli_stmt_execute($insert_record_stmt) or die(mysqli_error($conn))) {
        echo "Inserted successfully";
    } else {

        echo "Data insertion failure";
    }
}
