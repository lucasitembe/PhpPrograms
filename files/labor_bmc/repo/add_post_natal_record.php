<?php

include("../includes/connection.php");

$registration_id = mysqli_real_escape_string($conn, trim($_POST['registration_id']));

$admission_id = mysqli_real_escape_string($conn, trim($_POST['admission_id']));

$hospital_duration = mysqli_real_escape_string($conn, trim($_POST['hospital_duration']));

$next_appointment = mysqli_real_escape_string($conn, trim($_POST['next_appointment']));

$peurperium = mysqli_real_escape_string($conn, trim($_POST['peurperium']));

$uterus = mysqli_real_escape_string($conn, trim($_POST['uterus']));

$lochia = mysqli_real_escape_string($conn, trim($_POST['lochia']));

$midwife_name = mysqli_real_escape_string($conn, trim($_POST['midwife_name']));

$episiotomy = mysqli_real_escape_string($conn, trim($_POST['episiotomy']));

$breasts = mysqli_real_escape_string($conn, trim($_POST['breasts']));

$abdominal_scars = mysqli_real_escape_string($conn, trim($_POST['abdominal_scars']));

$general_condition = mysqli_real_escape_string($conn, trim($_POST['general_condition']));

$anaemia = mysqli_real_escape_string($conn, trim($_POST['anaemia']));

$breasts2 = mysqli_real_escape_string($conn, trim($_POST['breasts2']));

$cervix = mysqli_real_escape_string($conn, trim($_POST['cervix']));

$vagina = mysqli_real_escape_string($conn, trim($_POST['vagina']));

$episiotomy2 = mysqli_real_escape_string($conn, trim($_POST['episiotomy2']));

$stress_incontinence = mysqli_real_escape_string($conn, trim($_POST['stress_incontinence']));

$anus = mysqli_real_escape_string($conn, trim($_POST['anus']));

$tenderness = mysqli_real_escape_string($conn, trim($_POST['tenderness']));

$remarks = mysqli_real_escape_string($conn, trim($_POST['remarks']));

$temperature = mysqli_real_escape_string($conn, trim($_POST['temperature']));

$pulse = mysqli_real_escape_string($conn, trim($_POST['pulse']));

$bp = mysqli_real_escape_string($conn, trim($_POST['bp']));

$baby_condition = mysqli_real_escape_string($conn, trim($_POST['baby_condition']));

$mother_remarks = mysqli_real_escape_string($conn, trim($_POST['mother_remarks']));

$midwife_name2 = mysqli_real_escape_string($conn, trim($_POST['midwife_name2']));

$select_post_natal = "SELECT hospital_duration, next_appointment, peurperium, uterus, lochia, 
                                midwife_name, episiotomy, breasts, abdominal_scars, general_condition, 
                                anaemia, breasts2, cervix, vagina, episiotomy2, stress_incontinence, anus, 
                                tenderness, remarks, temperature, pulse, bp, baby_condition, 
                                mother_remarks, midwife_name2
                            FROM tbl_post_natal_record 
                            WHERE patient_id=? AND admission_id=?";

$stmt = mysqli_prepare($conn, $select_post_natal);

mysqli_stmt_bind_param($stmt, "ii", $registration_id, $admission_id);

mysqli_stmt_execute($stmt);

mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) > 0) 
{
    $update_post_natal = "UPDATE tbl_post_natal_record
                            SET hospital_duration = ?, next_appointment = ?, peurperium = ?, uterus = ?, lochia = ?, 
                                midwife_name = ?, episiotomy = ?, breasts = ?, abdominal_scars = ?, general_condition = ?, 
                                anaemia = ?, breasts2 = ?, cervix = ?, vagina = ?, episiotomy2 = ?, stress_incontinence = ?, anus = ?, 
                                tenderness = ?, remarks = ?, temperature = ?, pulse = ?, bp = ?, baby_condition = ?, 
                                mother_remarks = ?, midwife_name2 = ?
                            WHERE patient_id=? AND admission_id=?";

    $update_stmt = mysqli_prepare($conn, $update_post_natal);

    mysqli_stmt_bind_param(
        $update_stmt,
        "sssssssssssssssssssssssssii",
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
        $midwife_name2,
        $registration_id,
        $admission_id
    );

    if (mysqli_stmt_execute($update_stmt)) {

        echo "Updated successfully";
    } else {

        echo "Data updated failure";
    }

} else {

    $insert_post_natal = "INSERT INTO tbl_post_natal_record
                                (patient_id, admission_id, hospital_duration, next_appointment, peurperium, uterus, lochia, 
                                midwife_name, episiotomy, breasts, abdominal_scars, general_condition, 
                                anaemia, breasts2, cervix, vagina, episiotomy2, stress_incontinence, anus, 
                                tenderness, remarks, temperature, pulse, bp, baby_condition, 
                                mother_remarks, midwife_name2) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $insert_stmt = mysqli_prepare($conn, $insert_post_natal);

    mysqli_stmt_bind_param(
        $insert_stmt,
        "iisssssssssssssssssssssssss",
        $registration_id,
        $admission_id,
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

    if (mysqli_stmt_execute($insert_stmt)) {

        echo "Added successfully";
    } else {

        echo "FAILURE";
    }
}
