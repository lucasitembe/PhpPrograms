<?php

include("../includes/connection.php");

$registration_id = mysqli_real_escape_string($conn, trim($_POST['registration_id']));

$admission_id = mysqli_real_escape_string($conn, trim($_POST['admission_id']));

$time_began = mysqli_real_escape_string($conn, trim($_POST['time_began']));

$date_time = mysqli_real_escape_string($conn, trim($_POST['date_time']));

$duration = mysqli_real_escape_string($conn, trim($_POST['duration']));

$mode_of_delivery = mysqli_real_escape_string($conn, trim($_POST['mode_of_delivery']));

$drugs = mysqli_real_escape_string($conn, trim($_POST['drugs']));

$remarks = mysqli_real_escape_string($conn, trim($_POST['remarks']));

$select_second_stage = "SELECT time_began, date_of_birth, duration, mode_of_delivery, drugs, 
                                remarks, date_time
                            FROM tbl_second_stage_of_labour 
                            WHERE patient_id=? AND admission_id=?";

$stmt = mysqli_prepare($conn, $select_second_stage);

mysqli_stmt_bind_param($stmt, "ii", $registration_id, $admission_id);

mysqli_stmt_execute($stmt);

mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) > 0) 
{
    $update_second_stage = "UPDATE tbl_second_stage_of_labour
                                SET time_began = ?, date_of_birth = ?, duration = ?, 
                                    mode_of_delivery = ?, drugs = ?, remarks = ?, date_time = NOW()
                                WHERE patient_id=? AND admission_id=?";

    $update_stmt = mysqli_prepare($conn, $update_second_stage);

    mysqli_stmt_bind_param(
        $update_stmt,
        "ssssssii",
        $time_began,
        $date_time,
        $duration,
        $mode_of_delivery,
        $drugs,
        $remarks,
        $registration_id,
        $admission_id
    );

    if (mysqli_stmt_execute($update_stmt)) {

        echo "Updated successfully";
    } else {

        echo "Data updated failure";
    }

} else {

    $insert_second_stage = "INSERT INTO tbl_second_stage_of_labour
                                ( patient_id, admission_id, time_began, date_of_birth, duration, mode_of_delivery, drugs, 
                                remarks, date_time) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";

    $insert_stmt = mysqli_prepare($conn, $insert_second_stage);

    mysqli_stmt_bind_param(
        $insert_stmt,
        "iissssss",
        $registration_id,
        $admission_id,
        $time_began,
        $date_time,
        $duration,
        $mode_of_delivery,
        $drugs,
        $remarks
    );

    if (mysqli_stmt_execute($insert_stmt)) {

        echo "Added successfully";
    } else {

        echo "FAILURE";
    }
}
