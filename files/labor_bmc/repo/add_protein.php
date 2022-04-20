<?php

include("../includes/connection.php");

session_start();

$Employee_ID = $_SESSION['userinfo']['Employee_ID'];

$times = date("H:i");

$patient_id = mysqli_real_escape_string($conn, trim($_POST['patient_id']));

$admission_id = mysqli_real_escape_string($conn, trim($_POST['admission_id']));

$protein = mysqli_real_escape_string($conn, trim($_POST['protein']));

$time = mysqli_real_escape_string($conn, trim($_POST['time']));

$insert_protein = "INSERT INTO tbl_urine(patient_id, admission_id, protein, urine_time, actual_urine_time, time_hour, Employee_ID) 
                        VALUES(?, ?, ?, ?, NOW(), ?, ?)";

$insert_stmt = mysqli_prepare($conn, $insert_protein);

mysqli_stmt_bind_param($insert_stmt, "iiddsi", $patient_id, $admission_id, $protein, $time, $times, $Employee_ID);

if (mysqli_stmt_execute($insert_stmt)) 
{
    echo "Data insertion succesfully";
    
}else{

    echo "Data insertion failure";

}