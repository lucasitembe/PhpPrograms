<?php

include("../includes/connection.php");

$data = array();

$d = array();

$patient_id = mysqli_real_escape_string($conn, trim($_POST['patient_id']));

$admission_id = mysqli_real_escape_string($conn, trim($_POST['admission_id']));

$select_contraction = "SELECT contraction, c_time, contraction_lasts, contraction_loop, time_hour
                            FROM tbl_contraction
                            WHERE patient_id=? AND admission_id=?";

$stmt = mysqli_prepare($conn, $select_contraction);

mysqli_stmt_bind_param($stmt, "ii", $patient_id, $admission_id);

mysqli_stmt_execute($stmt);

mysqli_stmt_bind_result($stmt, $contraction, $time, $contraction_lasts, $contraction_loop, $hour);

while (mysqli_stmt_fetch($stmt)) 
{

    $d['contraction'] = $contraction;

    $d['time'] = $time;

    $d['contraction_lasts'] = $contraction_lasts;

    $d['contraction_loop'] = $contraction_loop;

    $d['hour'] = $hour;

    array_push($data, $d);
}

echo json_encode($data);