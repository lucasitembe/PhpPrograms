<?php

include("./includes/connection.php");

session_start();

$Employee_ID = $_SESSION['userinfo']['Employee_ID'];

$registration_id = mysqli_real_escape_string($conn, trim($_POST['registration_id']));

$payment_item_cache_list_id = mysqli_real_escape_string($conn, trim($_POST['payment_item_cache_list_id']));

$date = mysqli_real_escape_string($conn, trim($_POST['date']));

$equipment = mysqli_real_escape_string($conn, trim($_POST['equipment']));

$right_otoscopy = mysqli_real_escape_string($conn, trim($_POST['right_otoscopy']));

$left_otoscopy = mysqli_real_escape_string($conn, trim($_POST['left_otoscopy']));

$recommendation = mysqli_real_escape_string($conn, trim($_POST['recommendation']));

$data = array();

$insert_audiology = "INSERT INTO tbl_audiogram(registration_id,payment_item_cache_list_id,Employee_ID, date, equipment, right_otoscopy, left_otoscopy, recommendation)
                                VALUES(?, ?, ?, ?, ?, ?, ?, ?)";

$insert_stmt = mysqli_prepare($conn, $insert_audiology);

mysqli_stmt_bind_param($insert_stmt, "iiisssss", $registration_id, $payment_item_cache_list_id, $Employee_ID, $date, $equipment, $right_otoscopy, $left_otoscopy, $recommendation);

if (mysqli_stmt_execute($insert_stmt)) {

    array_push($data, $date);

    array_push($data, $equipment);

    array_push($data, $right_otoscopy);

    array_push($data, $left_otoscopy);

    array_push($data, $recommendation);

    array_push($data, "Data added successfully");

    echo json_encode($data);
} else {

    echo "Data insertion failure";
}
