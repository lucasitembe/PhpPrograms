<?php

include("../includes/connection.php");

session_start();

$Employee_ID = $_SESSION['userinfo']['Employee_ID'];

$times = date("H:i");

$patient_id = mysqli_real_escape_string($conn, trim($_POST['patient_id']));

$admission_id = mysqli_real_escape_string($conn, trim($_POST['admission_id']));

$bp = mysqli_real_escape_string($conn, trim($_POST['bp']));

$time = mysqli_real_escape_string($conn, trim($_POST['time']));

$insert_bp = "INSERT INTO tbl_pressure(patient_id, admission_id, pressure,pressure_time,actual_pressure_time, time_hour, Employee_ID) 
                        VALUES(?, ?, ?, ?, NOW(), ?, ?)";

$insert_stmt = mysqli_prepare($conn, $insert_bp);

mysqli_stmt_bind_param($insert_stmt, "iiddsi", $patient_id, $admission_id, $bp, $time, $times, $Employee_ID);

if (mysqli_stmt_execute($insert_stmt)) 
{
    echo "Data insertion succesfully";
    
}else{

    echo "Data insertion failure";

}