<?php

include("../includes/connection.php");

session_start();

$Employee_ID = $_SESSION['userinfo']['Employee_ID'];

$time = date("H:i");

$patient_id = mysqli_real_escape_string($conn, trim($_POST['patient_id']));

$admission_id = mysqli_real_escape_string($conn, trim($_POST['admission_id']));

$liqour_remark = mysqli_real_escape_string($conn, trim($_POST['liqour_remark']));

$liqour_remark_time = mysqli_real_escape_string($conn, trim($_POST['liqour_remark_time']));

$insert_state_of_liquor = "INSERT INTO tbl_mould_liqour (patient_id, admission_id, liqour_remark, liqour_remark_time, date_time, time_hour, Employee_ID)
                            VALUES (?, ?, ?, ?, NOW(), ?, ?)";

$insert_stmt = mysqli_prepare($conn, $insert_state_of_liquor);

mysqli_stmt_bind_param($insert_stmt, "iisdsi", $patient_id, $admission_id, $liqour_remark, $liqour_remark_time, $time, $Employee_ID);

if (mysqli_stmt_execute($insert_stmt)) 
{
    echo "Data insertion succesfully";
    
}else{

    echo "Data insertion failure";

}