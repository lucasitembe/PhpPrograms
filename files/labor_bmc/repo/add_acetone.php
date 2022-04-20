<?php

include("../includes/connection.php");

session_start();

$Employee_ID = $_SESSION['userinfo']['Employee_ID'];

$times = date("H:i");

$patient_id = mysqli_real_escape_string($conn, trim($_POST['patient_id']));

$admission_id = mysqli_real_escape_string($conn, trim($_POST['admission_id']));

$acetone = mysqli_real_escape_string($conn, trim($_POST['acetone']));

$time = mysqli_real_escape_string($conn, trim($_POST['time']));

$insert_temp = "INSERT INTO tbl_acetone(patient_id, admission_id, acetone, acetone_time, actual_acetone_time, time_hour, Employee_ID) 
                        VALUES(?, ?, ?, ?, NOW(), ?, ?)";

$insert_stmt = mysqli_prepare($conn, $insert_temp);

mysqli_stmt_bind_param($insert_stmt, "iiddsi", $patient_id, $admission_id, $acetone, $time, $times, $Employee_ID);

if (mysqli_stmt_execute($insert_stmt)) 
{
    echo "Data insertion succesfully";
    
}else{

    echo "Data insertion failure";

}