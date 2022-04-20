<?php

include("../includes/connection.php");

session_start();

$Employee_ID = $_SESSION['userinfo']['Employee_ID'];

$patient_id = mysqli_real_escape_string($conn, trim($_POST['patient_id']));

$admission_id = mysqli_real_escape_string($conn, trim($_POST['admission_id']));

$resp = mysqli_real_escape_string($conn, trim($_POST['resp']));

$time = mysqli_real_escape_string($conn, trim($_POST['time']));

$insert_resp = "INSERT INTO tbl_resp(patient_id, admission_id, resp, resp_time, actual_resp_time, Employee_ID) 
                        VALUES(?, ?, ?, ?, NOW(), ?)";

$insert_stmt = mysqli_prepare($conn, $insert_resp);

mysqli_stmt_bind_param($insert_stmt, "iidii", $patient_id, $admission_id, $resp, $time, $Employee_ID);

if (mysqli_stmt_execute($insert_stmt)) 
{
    echo "Data insertion succesfully";
    
}else{

    echo "Data insertion failure";

}