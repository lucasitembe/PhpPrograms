<?php

include("../includes/connection.php");

session_start();

$Employee_ID = $_SESSION['userinfo']['Employee_ID'];

$times = date("H:i");

$patient_id = mysqli_real_escape_string($conn, trim($_POST['patient_id']));

$admission_id = mysqli_real_escape_string($conn, trim($_POST['admission_id']));

$temp = mysqli_real_escape_string($conn, trim($_POST['temp']));

$time = mysqli_real_escape_string($conn, trim($_POST['time']));

$insert_temp = "INSERT INTO tbl_temp_resp(patient_id, admission_id, temp, tr_time, actual_temp_resp_time, time_hour, Employee_ID) 
                        VALUES(?, ?, ?, ?, NOW(), ?, ?)";

$insert_stmt = mysqli_prepare($conn, $insert_temp);

mysqli_stmt_bind_param($insert_stmt, "iiddsi", $patient_id, $admission_id, $temp, $time, $times, $Employee_ID);

if (mysqli_stmt_execute($insert_stmt)) 
{
    echo "Data insertion succesfully";
    
}else{

    echo "Data insertion failure";

}