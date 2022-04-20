<?php

include("../includes/connection.php");

session_start();

$Employee_ID = $_SESSION['userinfo']['Employee_ID'];

$times = date("H:i");

$patient_id = mysqli_real_escape_string($conn, trim($_POST['patient_id']));

$admission_id = mysqli_real_escape_string($conn, trim($_POST['admission_id']));

$oxytocine = mysqli_real_escape_string($conn, trim($_POST['oxytocine']));

$time = mysqli_real_escape_string($conn, trim($_POST['time']));

$insert_oxytocine = "INSERT INTO tbl_oxytocine(patient_id, admission_id, oxytocine, oxytocine_time, actual_oxytocine_time, time_hour, Employee_ID) 
                        VALUES(?, ?, ?, ?, NOW(), ?, ?)";

$insert_stmt = mysqli_prepare($conn, $insert_oxytocine);

mysqli_stmt_bind_param($insert_stmt, "iisdsi", $patient_id, $admission_id, $oxytocine, $time, $times, $Employee_ID);

if (mysqli_stmt_execute($insert_stmt)) 
{
    echo "Data insertion succesfully";
    
}else{

    echo "Data insertion failure";

}