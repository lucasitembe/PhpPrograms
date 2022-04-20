<?php

include("./includes/connection.php");

session_start();

$Employee_ID = $_SESSION['userinfo']['Employee_ID'];

$registration_id = mysqli_real_escape_string($conn, trim($_POST['registration_id']));

$payment_item_cache_list_id = mysqli_real_escape_string($conn, trim($_POST['payment_item_cache_list_id']));

$left_frequency_unmasked_ac = mysqli_real_escape_string($conn, trim($_POST['left_frequency_unmasked_ac']));

$left_hearing_unmasked_ac = mysqli_real_escape_string($conn, trim($_POST['left_hearing_unmasked_ac']));

$left_frequency_masked_ac = mysqli_real_escape_string($conn, trim($_POST['left_frequency_masked_ac']));

$left_hearing_masked_ac = mysqli_real_escape_string($conn, trim($_POST['left_hearing_masked_ac']));

$left_frequency_unmasked_bc = mysqli_real_escape_string($conn, trim($_POST['left_frequency_unmasked_bc']));

$left_hearing_unmasked_bc = mysqli_real_escape_string($conn, trim($_POST['left_hearing_unmasked_bc']));

$left_frequency_masked_bc = mysqli_real_escape_string($conn, trim($_POST['left_frequency_masked_bc']));

$left_hearing_masked_bc = mysqli_real_escape_string($conn, trim($_POST['left_hearing_masked_bc']));

$left_frequency_no_reponse = mysqli_real_escape_string($conn, trim($_POST['left_frequency_no_reponse']));

$left_hearing_no_response = mysqli_real_escape_string($conn, trim($_POST['left_hearing_no_response']));

$data = array();

$insert_audiogram_left = "INSERT INTO tbl_audiogram_left(registration_id,payment_item_cache_list_id,Employee_ID, frequency_unmasked_ac, hearing_unmasked_ac, frequency_masked_ac, hearing_masked_ac, frequency_unmasked_bc, hearing_unmasked_bc, frequency_masked_bc, hearing_masked_bc, frequency_no_reponse, hearing_no_response)
                                VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$insert_stmt = mysqli_prepare($conn, $insert_audiogram_left);

mysqli_stmt_bind_param($insert_stmt, "iiiiiiiiiiiii", $registration_id, $payment_item_cache_list_id, $Employee_ID, $left_frequency_unmasked_ac, $left_hearing_unmasked_ac, $left_frequency_masked_ac, $left_hearing_masked_ac, $left_frequency_unmasked_bc, $left_hearing_unmasked_bc, $left_frequency_masked_bc, $left_hearing_masked_bc, $left_frequency_no_reponse, $left_hearing_no_response);

if (mysqli_stmt_execute($insert_stmt)) {

    array_push($data, $left_frequency_unmasked_ac);

    array_push($data, $left_hearing_unmasked_ac);

    array_push($data, $left_frequency_masked_ac);

    array_push($data, $left_hearing_masked_ac);

    array_push($data, $left_frequency_unmasked_bc);

    array_push($data, $left_hearing_unmasked_bc);

    array_push($data, $left_frequency_masked_bc);

    array_push($data, $left_hearing_masked_bc);

    array_push($data, $left_frequency_no_reponse);

    array_push($data, $left_hearing_no_response);

    array_push($data, "UNMASKED AC");

    array_push($data, "MASKED AC");

    array_push($data, "UNMASKED BC");

    array_push($data, "MASKED BC");

    array_push($data, "NO RESPONSE");

    echo json_encode($data);
} else {

    echo "Data insertion failure";
}
