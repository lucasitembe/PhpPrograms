<?php

include("../includes/connection.php");

session_start();

$Employee_ID = $_SESSION['userinfo']['Employee_ID'];

$patient_id = mysqli_real_escape_string($conn, trim($_POST['patient_id']));

$admission_id = mysqli_real_escape_string($conn, trim($_POST['admission_id']));

$time = mysqli_real_escape_string($conn, trim($_POST['time']));

$times = mysqli_real_escape_string($conn, trim($_POST['hour']));

$contraction = mysqli_real_escape_string($conn, trim($_POST['contraction']));

$contraction_lasts = mysqli_real_escape_string($conn, trim($_POST['contraction_lasts']));

$contraction_loop = mysqli_real_escape_string($conn, trim($_POST['contraction_loop']));

$data = array();

$insert_contraction = "INSERT INTO tbl_contraction(patient_id, admission_id, contraction, contraction_lasts, contraction_loop, c_time, actual_time, time_hour, Employee_ID)
                        VALUES(?, ?, ?, ?, ?, ?, NOW(), ?, ?)";

$insert_stmt = mysqli_prepare($conn, $insert_contraction);

mysqli_stmt_bind_param($insert_stmt, "iisiidsi", $patient_id, $admission_id, $contraction, $contraction_lasts, $contraction_loop, $time, $times, $Employee_ID);

if (mysqli_stmt_execute($insert_stmt)) 
{
    $data['time'] = $time;

    $data['contraction'] = $contraction;

    $data['contraction_lasts'] = $contraction_lasts;

    $data['contraction_loop'] = $contraction_loop;

    echo json_encode($data);
} else {

    echo "Data insertion failure";
}
