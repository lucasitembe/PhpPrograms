<?php

include("../includes/connection.php");

$data = array();

$d = array();

$patient_id = mysqli_real_escape_string($conn, trim($_POST['patient_id']));

$admission_id = mysqli_real_escape_string($conn, trim($_POST['admission_id']));

$select_state_of_liquor = "SELECT liqour_remark, liqour_remark_time
                            FROM tbl_mould_liqour
                            WHERE patient_id=? AND admission_id=?";

$stmt = mysqli_prepare($conn, $select_state_of_liquor);

mysqli_stmt_bind_param($stmt, "ii", $patient_id, $admission_id);

mysqli_stmt_execute($stmt);

mysqli_stmt_bind_result($stmt, $liqour_remark, $liqour_remark_time);

while (mysqli_stmt_fetch($stmt)) 
{

    $d['liqour_remark'] = $liqour_remark;

    $d['liqour_remark_time'] = $liqour_remark_time;

    array_push($data, $d);
}

echo json_encode($data);