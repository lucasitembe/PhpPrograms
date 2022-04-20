<?php

include("../includes/connection.php");

$patient_id = mysqli_real_escape_string($conn, trim($_POST['patient_id']));

$admission_id = mysqli_real_escape_string($conn, trim($_POST['admission_id']));

$time = mysqli_real_escape_string($conn, trim($_POST['time']));

$hour = mysqli_real_escape_string($conn, trim($_POST['time_remark']));

$update_time_hour = "UPDATE tbl_moulding tm, tbl_mould_liqour tml 
                        SET tm.time_hour = ?, tml.time_hour = ?
                        WHERE  tml.patient_id= ? AND tml.admission_id= ? AND tml.liqour_remark_time = ?
                        AND tm.patient_id= ? AND tm.admission_id= ? AND tm.moulding_time = ?";

$update_stmt = mysqli_prepare($conn, $update_time_hour);

mysqli_stmt_bind_param($update_stmt, "ssiidiid", $hour, $hour, $patient_id, $admission_id, $time, $patient_id, $admission_id, $time);

if (mysqli_stmt_execute($update_stmt)) 
{
    echo "Data updated succesfully";
    
}else{

    echo "Data update failure";

}

