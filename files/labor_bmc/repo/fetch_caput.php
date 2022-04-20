<?php

include("../includes/connection.php");

$data = array();

$d = array();

$patient_id = mysqli_real_escape_string($conn, trim($_POST['patient_id']));

$admission_id = mysqli_real_escape_string($conn, trim($_POST['admission_id']));

$select_caput = "SELECT caput_remark, caput_remark_time
                            FROM tbl_caput
                            WHERE patient_id=? AND admission_id=?";

$stmt = mysqli_prepare($conn, $select_caput);

mysqli_stmt_bind_param($stmt, "ii", $patient_id, $admission_id);

mysqli_stmt_execute($stmt);

mysqli_stmt_bind_result($stmt, $caput_remark, $caput_remark_time);

while (mysqli_stmt_fetch($stmt)) 
{

    $d['caput_remark'] = $caput_remark;

    $d['caput_remark_time'] = $caput_remark_time;

    array_push($data, $d);
}

echo json_encode($data);