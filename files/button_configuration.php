<?php

function getButtonStatus($buttonName) {
	global $conn;
    $sql = "SELECT status FROM tbl_dashboard_buttons WHERE button_name='$buttonName'";
    $result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
    while ($row = mysqli_fetch_array($result)) {
        $status = $row['status'];
    }
    return $status;
}
