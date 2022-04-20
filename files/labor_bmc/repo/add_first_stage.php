<?php

include("../includes/connection.php");

$registration_id = mysqli_real_escape_string($conn, trim($_POST['registration_id']));

$admission_id = mysqli_real_escape_string($conn, trim($_POST['admission_id']));

$onset_labor = mysqli_real_escape_string($conn, trim($_POST['onset_labor']));

$admitted_at = mysqli_real_escape_string($conn, trim($_POST['admitted_at']));

$cervix_dilation = mysqli_real_escape_string($conn, trim($_POST['cervix_dilation']));

$membrane_liquor = mysqli_real_escape_string($conn, trim($_POST['membrane_liquor']));

$rapture_date = mysqli_real_escape_string($conn, trim($_POST['rapture_date']));

$rapture_duration = mysqli_real_escape_string($conn, trim($_POST['rapture_duration']));

$arm = mysqli_real_escape_string($conn, trim($_POST['arm']));

$no_of_examinations = mysqli_real_escape_string($conn, trim($_POST['no_of_examinations']));

$abnormalities = mysqli_real_escape_string($conn, trim($_POST['abnormalities']));

$induction_of_labor = mysqli_real_escape_string($conn, trim($_POST['induction_of_labor']));

$induction_of_labor_reason = mysqli_real_escape_string($conn, trim($_POST['induction_of_labor_reason']));

$first_stage_duration = mysqli_real_escape_string($conn, trim($_POST['first_stage_duration']));

$drugs_given = mysqli_real_escape_string($conn, trim($_POST['drugs_given']));

$remarks = mysqli_real_escape_string($conn, trim($_POST['remarks']));

$select_first_stage = "SELECT set_of_labour_time_and_date, admitted_at, state_of_membrane, 
                                time_and_date_of_rupture, time_elapsed_since_rupture, arm, 
                                no_of_vaginal_examination, abdomalities_of_first_stage, 
                                induction_of_labour, induction_reason, 
                                total_duration_of_first_stage_labour, drugs_given, remarks, date_time
                        FROM tbl_first_stage_of_labour 
                        WHERE patient_id=? AND admission_id=?";

$stmt = mysqli_prepare($conn, $select_first_stage);

mysqli_stmt_bind_param($stmt, "ii", $registration_id, $admission_id);

mysqli_stmt_execute($stmt);

mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) > 0) {
    $update_first_stage = "UPDATE tbl_first_stage_of_labour
                            SET set_of_labour_time_and_date = ?, admitted_at = ?, state_of_membrane = ?, 
                                time_and_date_of_rupture = ?, time_elapsed_since_rupture = ?, arm = ?, 
                                no_of_vaginal_examination = ?, abdomalities_of_first_stage = ?, 
                                induction_of_labour = ?, induction_reason = ?, 
                                total_duration_of_first_stage_labour = ?, drugs_given = ?, remarks = ?, date_time = NOW()
                            WHERE patient_id=? AND admission_id=?";

    $update_stmt = mysqli_prepare($conn, $update_first_stage);

    mysqli_stmt_bind_param(
        $update_stmt,
        "sssssssssssssii",
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
        $remarks,
        $registration_id,
        $admission_id
    );

    if (mysqli_stmt_execute($update_stmt)) {

        echo "Updated successfully";
    } else {

        echo "Data update failure";
    }
} else {
    $insert_first_stage = "INSERT INTO tbl_first_stage_of_labour(patient_id, admission_id, 
                                    set_of_labour_time_and_date, admitted_at, state_of_membrane, 
                                    time_and_date_of_rupture, time_elapsed_since_rupture, 
                                    arm, no_of_vaginal_examination, abdomalities_of_first_stage, 
                                    induction_of_labour, induction_reason, total_duration_of_first_stage_labour, 
                                    drugs_given, remarks, date_time)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

    $insert_stmt = mysqli_prepare($conn, $insert_first_stage);

    mysqli_stmt_bind_param(
        $insert_stmt,
        "iisssssssssssss",
        $registration_id,
        $admission_id,
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

    if (mysqli_stmt_execute($insert_stmt) or die(mysqli_error($conn))) {
        echo "Inserted successfully";
    } else {

        echo "Data insertion failure";
    }
}
