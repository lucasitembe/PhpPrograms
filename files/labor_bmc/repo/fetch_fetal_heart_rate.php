<?php

include("../includes/connection.php");

$data = array();

$d = array();

$patient_id = mysqli_real_escape_string($conn, trim($_GET['patient_id']));

$admission_id = mysqli_real_escape_string($conn, trim($_GET['admission_id']));

$select_fetal_heart_rate = "SELECT x, y, time_hours, baby_no
                            FROM tbl_fetal_heart_rate_cache
                            WHERE patient_id=? AND admission_id=?";

$stmt = mysqli_prepare($conn, $select_fetal_heart_rate);

mysqli_stmt_bind_param($stmt, "ii", $patient_id, $admission_id);

mysqli_stmt_execute($stmt);

mysqli_stmt_bind_result($stmt, $x, $y, $time_hours, $baby_no);

while (mysqli_stmt_fetch($stmt)) 
{

    $d['x'] = $x;

    $d['y'] = $y;

    $d['time_hours'] = $time_hours;

    $d['baby_no'] = $baby_no;

    array_push($data, $d);
}

echo json_encode($data);
