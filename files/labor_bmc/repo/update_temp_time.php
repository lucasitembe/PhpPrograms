<?php

include("../includes/connection.php");

$patient_id = mysqli_real_escape_string($conn, trim($_POST['patient_id']));

$admission_id = mysqli_real_escape_string($conn, trim($_POST['admission_id']));

$time = mysqli_real_escape_string($conn, trim($_POST['time']));

$hour = mysqli_real_escape_string($conn, trim($_POST['time_remark']));

$update_time_hour = "UPDATE tbl_temp_resp ttr, tbl_pressure tp 
                        SET ttr.time_hour = ?, tp.time_hour = ?
                        WHERE  ttr.patient_id= ? AND ttr.admission_id= ? AND ttr.tr_time = ?
                        AND tp.patient_id= ? AND tp.admission_id= ? AND tp.pressure_time = ?";

$update_stmt = mysqli_prepare($conn, $update_time_hour);

mysqli_stmt_bind_param($update_stmt, "ssiidiid", $hour, $hour, $patient_id, $admission_id, $time, $patient_id, $admission_id, $time);

if (mysqli_stmt_execute($update_stmt)) 
{
    echo "Data updated succesfully";
    
}else{

    echo "Data update failure";

}

