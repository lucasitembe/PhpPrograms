<?php

include("../includes/connection.php");

$data = array();

$d = array();

$patient_id = mysqli_real_escape_string($conn, trim($_POST['patient_id']));

$admission_id = mysqli_real_escape_string($conn, trim($_POST['admission_id']));

$select_temp = "SELECT protein, urine_time
                            FROM tbl_urine
                            WHERE patient_id=? AND admission_id=?";

$stmt = mysqli_prepare($conn, $select_temp);

mysqli_stmt_bind_param($stmt, "ii", $patient_id, $admission_id);

mysqli_stmt_execute($stmt);

mysqli_stmt_bind_result($stmt, $protein, $urine_time);

while (mysqli_stmt_fetch($stmt)) 
{

    $d['protein'] = $protein;

    $d['urine_time'] = $urine_time;

    array_push($data, $d);
}

echo json_encode($data);