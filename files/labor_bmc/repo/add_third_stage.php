<?php

include("../includes/connection.php");

$registration_id = mysqli_real_escape_string($conn, trim($_POST['registration_id']));

$admission_id = mysqli_real_escape_string($conn, trim($_POST['admission_id']));

$placenta_method_of_delivery = mysqli_real_escape_string($conn, trim($_POST['placenta_method_of_delivery']));

$date_time = mysqli_real_escape_string($conn, trim($_POST['date_time']));

$duration = mysqli_real_escape_string($conn, trim($_POST['duration']));

$placenta_weight = mysqli_real_escape_string($conn, trim($_POST['placenta_weight']));

$stage_of_placenta = mysqli_real_escape_string($conn, trim($_POST['stage_of_placenta']));

$colour = mysqli_real_escape_string($conn, trim($_POST['colour']));

$colour = mysqli_real_escape_string($conn, trim($_POST['colour']));

$cord = mysqli_real_escape_string($conn, trim($_POST['cord']));

$membranes = mysqli_real_escape_string($conn, trim($_POST['membranes']));

$disposal = mysqli_real_escape_string($conn, trim($_POST['disposal']));

$state_of_cervix = mysqli_real_escape_string($conn, trim($_POST['state_of_cervix']));

$tear = mysqli_real_escape_string($conn, trim($_POST['tear']));

$repaired_with_sutures = mysqli_real_escape_string($conn, trim($_POST['repaired_with_sutures']));

$total_blood_loss = mysqli_real_escape_string($conn, trim($_POST['total_blood_loss']));

$temperature = mysqli_real_escape_string($conn, trim($_POST['temperature']));

$pulse = mysqli_real_escape_string($conn, trim($_POST['pulse']));

$resp = mysqli_real_escape_string($conn, trim($_POST['resp']));

$bp = mysqli_real_escape_string($conn, trim($_POST['bp']));

$lochia = mysqli_real_escape_string($conn, trim($_POST['lochia']));

$state_of_uterus = mysqli_real_escape_string($conn, trim($_POST['state_of_uterus']));

$remarks = mysqli_real_escape_string($conn, trim($_POST['remarks']));

$select_third_stage = "SELECT methodology_delivery_placenter, date_and_time, duration, placenter_weight, 
                                stage_of_placent, colour, cord, membranes, disposal, state_of_cervix, 
                                episiotomy_tear, repaired_with_sutures, total_blood_loss, t, p, r, 
                                bp, lochia, state_of_uterus, remarks, date_time
                            FROM tbl_third_stage_of_labour 
                            WHERE patient_id=? AND admission_id=?";

$stmt = mysqli_prepare($conn, $select_third_stage);

mysqli_stmt_bind_param($stmt, "ii", $registration_id, $admission_id);

mysqli_stmt_execute($stmt);

mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) > 0) 
{
    $update_third_stage = "UPDATE tbl_third_stage_of_labour
                                SET methodology_delivery_placenter = ?, date_and_time = ?, duration = ?, placenter_weight = ?, 
                                stage_of_placent = ?, colour = ?, cord = ?, membranes = ?, disposal = ?, state_of_cervix = ?, 
                                episiotomy_tear = ?, repaired_with_sutures = ?, total_blood_loss = ?, t = ?, p = ?, r = ?, 
                                bp = ?, lochia = ?, state_of_uterus = ?, remarks = ?, date_time = NOW()
                                WHERE patient_id=? AND admission_id=?";

    $update_stmt = mysqli_prepare($conn, $update_third_stage);

    mysqli_stmt_bind_param(
        $update_stmt,
        "ssssssssssssssssssssii",
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

    $insert_third_stage = "INSERT INTO tbl_third_stage_of_labour
                                (patient_id, admission_id, methodology_delivery_placenter, date_and_time, duration, placenter_weight, 
                                stage_of_placent, colour, cord, membranes, disposal, state_of_cervix, 
                                episiotomy_tear, repaired_with_sutures, total_blood_loss, t, p, r, 
                                bp, lochia, state_of_uterus, remarks, date_time) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

    $insert_stmt = mysqli_prepare($conn, $insert_third_stage);

    mysqli_stmt_bind_param(
        $insert_stmt,
        "iissssssssssssssssssss",
        $registration_id,
        $admission_id,
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

    if (mysqli_stmt_execute($insert_stmt)) {

        echo "Added successfully";
    } else {

        echo "FAILURE";
    }
}
