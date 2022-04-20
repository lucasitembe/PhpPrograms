<?php

include("../includes/connection.php");

session_start();

$Employee_ID = $_SESSION['userinfo']['Employee_ID'];

$times = date("H:i");

$patient_id = mysqli_real_escape_string($conn, trim($_POST['patient_id']));

$admission_id = mysqli_real_escape_string($conn, trim($_POST['admission_id']));

$caput_remark = mysqli_real_escape_string($conn, trim($_POST['caput_remark']));

$time = mysqli_real_escape_string($conn, trim($_POST['time']));

$insert_caput = "INSERT INTO tbl_caput (patient_id, admission_id, caput_remark, caput_remark_time, Employee_ID)
                            VALUES (?, ?, ?, ?, ?)";

$insert_stmt = mysqli_prepare($conn, $insert_caput);

mysqli_stmt_bind_param($insert_stmt, "iisdi", $patient_id, $admission_id, $caput_remark, $time, $Employee_ID);

if (mysqli_stmt_execute($insert_stmt)) 
{
    echo "Data insertion succesfully";
    
}else{

    echo "Data insertion failure";

}