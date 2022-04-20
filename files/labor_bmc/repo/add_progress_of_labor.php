<?php

include("../includes/connection.php");

session_start();

$Employee_ID = $_SESSION['userinfo']['Employee_ID'];

$patient_id = mysqli_real_escape_string($conn, trim($_POST['patient_id']));

$admission_id = mysqli_real_escape_string($conn, trim($_POST['admission_id']));

$time_hour = mysqli_real_escape_string($conn, trim($_POST['time_hour']));

$fx = mysqli_real_escape_string($conn, trim($_POST['fx']));

$fy = mysqli_real_escape_string($conn, trim($_POST['fy']));

$sy = mysqli_real_escape_string($conn, trim($_POST['sy']));

$fetal_position = mysqli_real_escape_string($conn, trim($_POST['fetal_position']));

$data = array();

$insert_progress_of_labor = "INSERT INTO tbl_progress_of_labour(patient_id, admission_id, fx, fy, sy, fetal_position, date_time, time_hours, Employee_ID)
                                VALUES(?, ?, ?, ?, ?, ?, NOW(), ?, ?)";

$insert_stmt = mysqli_prepare($conn, $insert_progress_of_labor);

mysqli_stmt_bind_param($insert_stmt, "iiiiissi", $patient_id, $admission_id, $fx, $fy, $sy, $fetal_position, $time_hour, $Employee_ID);

if (mysqli_stmt_execute($insert_stmt)) {

    array_push($data, $fy);

    array_push($data, $sy);

    array_push($data, $fx);

    array_push($data, $time_hour);

    array_push($data, $fetal_position);

    echo json_encode($data);
    
}else{

    echo "Data insertion failure";

}
