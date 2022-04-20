<?php

include("../includes/connection.php");

$patient_id = mysqli_real_escape_string($conn, trim($_POST['patient_id']));

$admission_id = mysqli_real_escape_string($conn, trim($_POST['admission_id']));

$time = mysqli_real_escape_string($conn, trim($_POST['time']));

$hour = mysqli_real_escape_string($conn, trim($_POST['time_remark']));

$update_time_hour = "UPDATE tbl_oxytocine  
                        SET time_hour = ?
                        WHERE  patient_id= ? AND admission_id= ? AND oxytocine_time = ?";

$update_stmt = mysqli_prepare($conn, $update_time_hour);

mysqli_stmt_bind_param($update_stmt, "siid", $hour, $patient_id, $admission_id, $time);

if (mysqli_stmt_execute($update_stmt)) 
{
    echo "Data updated succesfully";
    
}else{

    echo "Data update failure";

}

