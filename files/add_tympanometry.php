<?php

include("./includes/connection.php");

session_start();

$Employee_ID = $_SESSION['userinfo']['Employee_ID'];

$registration_id = mysqli_real_escape_string($conn, trim($_POST['registration_id']));

$payment_item_cache_list_id = mysqli_real_escape_string($conn, trim($_POST['payment_item_cache_list_id']));

$jerger_type_right = mysqli_real_escape_string($conn, trim($_POST['jerger_type_right']));

$admittance_right = mysqli_real_escape_string($conn, trim($_POST['admittance_right']));

$pressure_right = mysqli_real_escape_string($conn, trim($_POST['pressure_right']));

$width_right = mysqli_real_escape_string($conn, trim($_POST['width_right']));

$volume_right = mysqli_real_escape_string($conn, trim($_POST['volume_right']));

$jerger_type_left = mysqli_real_escape_string($conn, trim($_POST['jerger_type_left']));

$admittance_left = mysqli_real_escape_string($conn, trim($_POST['admittance_left']));

$pressure_left = mysqli_real_escape_string($conn, trim($_POST['pressure_left']));

$width_left = mysqli_real_escape_string($conn, trim($_POST['width_left']));

$volume_left = mysqli_real_escape_string($conn, trim($_POST['volume_left']));

$data = array();

$insert_tympanometry = "INSERT INTO tbl_tympanometry(registration_id,payment_item_cache_list_id,Employee_ID, jerger_type_right, admittance_right, pressure_right, width_right, volume_right, jerger_type_left, admittance_left, pressure_left, width_left, volume_left)
                                VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$insert_stmt = mysqli_prepare($conn, $insert_tympanometry);

mysqli_stmt_bind_param($insert_stmt, "iiiiiiiiiiiii", $registration_id, $payment_item_cache_list_id, $Employee_ID, $jerger_type_right, $admittance_right, $pressure_right, $width_right, $volume_right, $jerger_type_left, $admittance_left, $pressure_left, $width_left, $volume_left);

if (mysqli_stmt_execute($insert_stmt)) {

    array_push($data, $jerger_type_right);

    array_push($data, $admittance_right);

    array_push($data, $pressure_right);

    array_push($data, $width_right);

    array_push($data, $volume_right);

    array_push($data, $jerger_type_left);

    array_push($data, $admittance_left);

    array_push($data, $pressure_left);

    array_push($data, $width_left);

    array_push($data, $volume_left);

    echo json_encode($data);
} else {

    echo "Data insertion failure";
}
