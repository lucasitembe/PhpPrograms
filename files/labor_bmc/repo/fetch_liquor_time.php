<?php

include("../includes/connection.php");

$data = array();

$d = array();

$patient_id = mysqli_real_escape_string($conn, trim($_POST['patient_id']));

$admission_id = mysqli_real_escape_string($conn, trim($_POST['admission_id']));

$select_liquor_time = "SELECT moulding_time, time_hour
                        FROM tbl_moulding
                        WHERE patient_id=? AND admission_id=?";

$stmt = mysqli_prepare($conn, $select_liquor_time);

mysqli_stmt_bind_param($stmt, "ii", $patient_id, $admission_id);

mysqli_stmt_execute($stmt);

mysqli_stmt_bind_result($stmt, $moulding_time, $time_hour);

while (mysqli_stmt_fetch($stmt)) 
{

    $d['moulding_time'] = $moulding_time;

    $d['time_hour'] = $time_hour;

    array_push($data, $d);
}

echo json_encode($data);