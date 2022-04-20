<?php

include("./includes/connection.php");

session_start();

$Employee_ID = $_SESSION['userinfo']['Employee_ID'];

$registration_id = mysqli_real_escape_string($conn, trim($_POST['registration_id']));

$Sponsor_ID = mysqli_real_escape_string($conn, trim($_POST['Sponsor_ID']));

if (mysqli_real_escape_string($conn, trim($_POST['send_to_health'])) == '') {
    $send_to_health = 'No';
} else {
    $send_to_health = mysqli_real_escape_string($conn, trim($_POST['send_to_health']));
}

$data = array();

$insert_health_patients = "INSERT INTO tbl_health_patients(registration_id,Sponsor_ID,Employee_ID, send_to_health)
                                VALUES(?, ?, ?, ?)";

$insert_stmt = mysqli_prepare($conn, $insert_health_patients);

mysqli_stmt_bind_param($insert_stmt, "iiis", $registration_id, $Sponsor_ID, $Employee_ID, $send_to_health);

if (mysqli_stmt_execute($insert_stmt)) {

    array_push($data, "Patient sent to health successfully");

    echo json_encode($data);
} else {

    echo "Could not add to refill patient";
}
