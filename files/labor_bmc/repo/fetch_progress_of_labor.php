<?php

include("../includes/connection.php");

$data = array();

$d = array();

$patient_id = mysqli_real_escape_string($conn, trim($_POST['patient_id']));

$admission_id = mysqli_real_escape_string($conn, trim($_POST['admission_id']));

$select_progress_of_labor = "SELECT fx, fy, sy, fetal_position, time_hours
                             FROM tbl_progress_of_labour
                             WHERE patient_id=? AND admission_id=?";

$stmt = mysqli_prepare($conn, $select_progress_of_labor);

mysqli_stmt_bind_param($stmt, "ii", $patient_id, $admission_id);

mysqli_stmt_execute($stmt);

mysqli_stmt_bind_result($stmt, $fx, $fy, $sy, $fetal_position, $time_hours);

while (mysqli_stmt_fetch($stmt)) 
{

    $d['fx'] = $fx;

    $d['fy'] = $fy;

    $d['sy'] = $sy;

    $d['time_hours'] = $time_hours;

    $d['fetal_position'] = $fetal_position;

    array_push($data, $d);
}

echo json_encode($data);
