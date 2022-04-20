<?php

include("../includes/connection.php");

$registration_id = mysqli_real_escape_string($conn, trim($_POST['registration_id']));

$admission_id = mysqli_real_escape_string($conn, trim($_POST['admission_id']));

$temperature = mysqli_real_escape_string($conn, trim($_POST['temperature']));

$pr = mysqli_real_escape_string($conn, trim($_POST['pr']));

$bp = mysqli_real_escape_string($conn, trim($_POST['bp']));

$fundal_height = mysqli_real_escape_string($conn, trim($_POST['fundal_height']));

$state_of_cervix = mysqli_real_escape_string($conn, trim($_POST['state_of_cervix']));

$state_of_perinium = mysqli_real_escape_string($conn, trim($_POST['state_of_perinium']));

$blood_loss = mysqli_real_escape_string($conn, trim($_POST['blood_loss']));

$recommendations = mysqli_real_escape_string($conn, trim($_POST['recommendations']));

$select_fourth_stage = "SELECT temp, pr, bp, fundal_height, state_of_cervix, state_of_perinium, 
                                blood_loss, doctor_midwife_recommendation
                            FROM tbl_fourth_stage_of_labour 
                            WHERE patient_id=? AND admission_id=?";

$stmt = mysqli_prepare($conn, $select_fourth_stage);

mysqli_stmt_bind_param($stmt, "ii", $registration_id, $admission_id);

mysqli_stmt_execute($stmt);

mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) > 0) 
{
    $update_fourth_stage = "UPDATE tbl_fourth_stage_of_labour
                                SET temp = ?, pr = ?, bp = ?, fundal_height = ?, state_of_cervix = ?, 
                                state_of_perinium = ?, blood_loss = ?, doctor_midwife_recommendation = ?
                                WHERE patient_id=? AND admission_id=?";

    $update_stmt = mysqli_prepare($conn, $update_fourth_stage);

    mysqli_stmt_bind_param(
        $update_stmt,
        "ssssssssii",
        $temperature,
        $pr,
        $bp,
        $fundal_height,
        $state_of_cervix,
        $state_of_perinium,
        $blood_loss,
        $recommendations,
        $registration_id,
        $admission_id
    );

    if (mysqli_stmt_execute($update_stmt)) {

        echo "Updated successfully";
    } else {

        echo "Data updated failure";
    }

} else {

    $insert_fourth_stage = "INSERT INTO tbl_fourth_stage_of_labour
                                ( patient_id, admission_id, temp, pr, bp, fundal_height, state_of_cervix, state_of_perinium, 
                                blood_loss, doctor_midwife_recommendation) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $insert_stmt = mysqli_prepare($conn, $insert_fourth_stage);

    mysqli_stmt_bind_param(
        $insert_stmt,
        "iissssssss",
        $registration_id,
        $admission_id,
        $temperature,
        $pr,
        $bp,
        $fundal_height,
        $state_of_cervix,
        $state_of_perinium,
        $blood_loss,
        $recommendations
    );

    if (mysqli_stmt_execute($insert_stmt)) {

        echo "Added successfully";
    } else {

        echo "FAILURE";
    }
}
