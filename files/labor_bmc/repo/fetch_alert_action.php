<?php

include("../includes/connection.php");

$data = array();

$d = array();

$select_alert_action = "SELECT alert_x1,alert_x2,alert_y1,alert_y2, action_x1, action_x2, action_y1, action_y2
                        FROM tbl_alert_action";

$stmt = mysqli_prepare($conn, $select_alert_action);

mysqli_stmt_execute($stmt);

mysqli_stmt_bind_result($stmt, $alert_x1, $alert_x2, $alert_y1, $alert_y2, $action_x1, $action_x2, $action_y1, $action_y2);

mysqli_stmt_fetch($stmt);

$d['alert_x1'] = $alert_x1;

$d['alert_x2'] = $alert_x2;

$d['alert_y1'] = $alert_y1;

$d['alert_y2'] = $alert_y2;

$d['action_x1'] = $action_x1;

$d['action_x2'] = $action_x2;

$d['action_y1'] = $action_y1;

$d['action_y2'] = $action_y2;

array_push($data, $d);

echo json_encode($data);
