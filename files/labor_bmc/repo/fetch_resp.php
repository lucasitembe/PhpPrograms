<?php

include("../includes/connection.php");

$data = array();

$d = array();

$patient_id = mysqli_real_escape_string($conn, trim($_POST['patient_id']));

$admission_id = mysqli_real_escape_string($conn, trim($_POST['admission_id']));

$select_resp = "SELECT resp, resp_time
                            FROM tbl_resp
                            WHERE patient_id=? AND admission_id=?";

$stmt = mysqli_prepare($conn, $select_resp);

mysqli_stmt_bind_param($stmt, "ii", $patient_id, $admission_id);

mysqli_stmt_execute($stmt);

mysqli_stmt_bind_result($stmt, $resp, $resp_time);

while (mysqli_stmt_fetch($stmt)) 
{

    $d['resp'] = $resp;

    $d['resp_time'] = $resp_time;

    array_push($data, $d);
}

echo json_encode($data);