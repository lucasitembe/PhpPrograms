<?php

include("../includes/connection.php");

$patient_id = mysqli_real_escape_string($conn, trim($_POST['patient_id']));

$admission_id = mysqli_real_escape_string($conn, trim($_POST['admission_id']));

$time = mysqli_real_escape_string($conn, trim($_POST['time']));

$hour = mysqli_real_escape_string($conn, trim($_POST['time_remark']));

$update_time_hour = "UPDATE tbl_urine tu, tbl_acetone ta, tbl_volume tv 
                        SET tu.time_hour = ?, ta.time_hour = ?, tv.time_hour = ?
                        WHERE  tu.patient_id= ? AND tu.admission_id= ? AND tu.urine_time = ?
                        AND ta.patient_id= ? AND ta.admission_id= ? AND ta.acetone_time = ?
                        AND tv.patient_id= ? AND tv.admission_id= ? AND tv.volume_time = ?";

$update_stmt = mysqli_prepare($conn, $update_time_hour);

mysqli_stmt_bind_param($update_stmt, "sssiidiidiid", $hour, $hour,  $hour, $patient_id, $admission_id, $time, $patient_id, $admission_id, $time, $patient_id, $admission_id, $time);

if (mysqli_stmt_execute($update_stmt)) 
{
    echo "Data updated succesfully";
    
}else{

    echo "Data update failure";

}

