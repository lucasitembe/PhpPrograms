<?php

include("../includes/connection.php");

session_start();

$Employee_ID = $_SESSION['userinfo']['Employee_ID'];

$times = date("H:i");

$patient_id = mysqli_real_escape_string($conn, trim($_POST['patient_id']));

$admission_id = mysqli_real_escape_string($conn, trim($_POST['admission_id']));

$volume = mysqli_real_escape_string($conn, trim($_POST['volume']));

$time = mysqli_real_escape_string($conn, trim($_POST['time']));

$insert_temp = "INSERT INTO tbl_volume(patient_id, admission_id, volume,volume_time,actual_volume_time, time_hour, Employee_ID) 
                        VALUES(?, ?, ?, ?, NOW(), ?, ?)";

$insert_stmt = mysqli_prepare($conn, $insert_temp);

mysqli_stmt_bind_param($insert_stmt, "iiddsi", $patient_id, $admission_id, $volume, $time, $times, $Employee_ID);

if (mysqli_stmt_execute($insert_stmt)) 
{
    echo "Data insertion succesfully";
    
}else{

    echo "Data insertion failure";

}