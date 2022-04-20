<?php

include("../includes/connection.php");

$alert_x1 = mysqli_real_escape_string($conn, trim($_POST['alert_x1']));

$alert_y1 = mysqli_real_escape_string($conn, trim($_POST['alert_y1']));

$alert_x2 = mysqli_real_escape_string($conn, trim($_POST['alert_x2']));

$alert_y2 = mysqli_real_escape_string($conn, trim($_POST['alert_y2']));

$action_x1 = mysqli_real_escape_string($conn, trim($_POST['action_x1']));

$action_y1 = mysqli_real_escape_string($conn, trim($_POST['action_y1']));

$action_x2 = mysqli_real_escape_string($conn, trim($_POST['action_x2']));

$action_y2 = mysqli_real_escape_string($conn, trim($_POST['action_y2']));

$data = array();

$select_alert_action = "SELECT alert_x1,alert_x2,alert_y1,alert_y2, action_x1, action_x2, action_y1, action_y2
                        FROM tbl_alert_action";

$stmt = mysqli_prepare($conn, $select_alert_action);

mysqli_stmt_execute($stmt);

mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) > 0) {

    $update_alert_action = "UPDATE tbl_alert_action
                            SET alert_x1=?, alert_x2=?, alert_y1=?, alert_y2=?, action_x1=?, action_x2=?, action_y1=?, action_y2=?";

    $update_stmt = mysqli_prepare($conn, $update_alert_action);

    mysqli_stmt_bind_param($update_stmt, "iiiiiiii", $alert_x1, $alert_x2, $alert_y1, $alert_y2, $action_x1, $action_x2, $action_y1, $action_y2);

    mysqli_stmt_execute($update_stmt);

    array_push($data, $alert_x1, $alert_y1, $alert_x2, $alert_y2, $action_x1, $action_y1, $action_x2, $action_y2);

    echo json_encode($data);

} else {
    $insert_alert_action = "INSERT INTO tbl_alert_action (alert_x1,alert_x2,alert_y1,alert_y2,action_x1,action_x2,action_y1,action_y2) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $insert_stmt = mysqli_prepare($conn, $insert_alert_action);

    mysqli_stmt_bind_param($insert_stmt, "iiiiiiii", $alert_x1, $alert_x2, $alert_y1, $alert_y2, $action_x1, $action_x2, $action_y1, $action_y2);

    mysqli_stmt_execute($insert_stmt);

    array_push($data, $alert_x1, $alert_y1, $alert_x2, $alert_y2, $action_x1, $action_y1, $action_x2, $action_y2);

    echo json_encode($data);
}
