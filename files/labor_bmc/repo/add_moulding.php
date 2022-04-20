<?php

include("../includes/connection.php");

session_start();

$Employee_ID = $_SESSION['userinfo']['Employee_ID'];

$time = date("H:i");

$patient_id = mysqli_real_escape_string($conn, trim($_POST['patient_id']));

$admission_id = mysqli_real_escape_string($conn, trim($_POST['admission_id']));

$moulding = mysqli_real_escape_string($conn, trim($_POST['moulding']));

$moulding_time = mysqli_real_escape_string($conn, trim($_POST['moulding_time']));

$insert_moulding = "INSERT INTO tbl_moulding (patient_id, admission_id, moulding, moulding_time, date_time, time_hour, Employee_ID)
                            VALUES (?, ?, ?, ?, NOW(), ?, ?)";

$insert_stmt = mysqli_prepare($conn, $insert_moulding);

mysqli_stmt_bind_param($insert_stmt, "iisdsi", $patient_id, $admission_id, $moulding, $moulding_time, $time, $Employee_ID);

if (mysqli_stmt_execute($insert_stmt)) 
{
    echo "Data insertion succesfully";
    
}else{

    echo "Data insertion failure";

}