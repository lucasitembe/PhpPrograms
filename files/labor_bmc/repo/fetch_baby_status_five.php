<?php

include("../includes/connection.php");

$data = array();

$d = array();

$patient_id = mysqli_real_escape_string($conn, trim($_POST['patient_id']));

$admission_id = mysqli_real_escape_string($conn, trim($_POST['admission_id']));

$select_baby_status = "SELECT heart_rate, respiration, muscle_tone, reflexe, color, sum
                            FROM tbl_baby_status_five
                            WHERE patient_id=? AND admission_id=?";

$stmt = mysqli_prepare($conn, $select_baby_status);

mysqli_stmt_bind_param($stmt, "ii", $patient_id, $admission_id);

mysqli_stmt_execute($stmt);

mysqli_stmt_bind_result($stmt, $heart_rate, $respiration, $muscle_tone, $reflexe, $color, $sum);

while (mysqli_stmt_fetch($stmt)) 
{

    $d['heart_rate'] = $heart_rate;

    $d['respiration'] = $respiration;

    $d['muscle_tone'] = $muscle_tone;

    $d['reflexe'] = $reflexe;

    $d['color'] = $color;

    $d['sum'] = $sum;

    array_push($data, $d);
}

echo json_encode($data);