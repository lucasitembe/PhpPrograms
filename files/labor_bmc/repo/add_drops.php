<?php

include("../includes/connection.php");

session_start();

$Employee_ID = $_SESSION['userinfo']['Employee_ID'];

$patient_id = mysqli_real_escape_string($conn, trim($_POST['patient_id']));

$admission_id = mysqli_real_escape_string($conn, trim($_POST['admission_id']));

$drops = mysqli_real_escape_string($conn, trim($_POST['drops']));

$time = mysqli_real_escape_string($conn, trim($_POST['time']));

$insert_drops = "INSERT INTO tbl_oxytocine_drops(patient_id, admission_id, drops, drops_time, actual_time, Employee_ID) 
                        VALUES(?, ?, ?, ?, NOW(), ?)";

$insert_stmt = mysqli_prepare($conn, $insert_drops);

mysqli_stmt_bind_param($insert_stmt, "iisdi", $patient_id, $admission_id, $drops, $time, $Employee_ID);

if (mysqli_stmt_execute($insert_stmt)) 
{
    echo "Data insertion succesfully";
    
}else{

    echo "Data insertion failure";

}