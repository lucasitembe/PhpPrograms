<?php
$status_request = "";
$room_id = "";
include './includes/connection.php';

if (isset($_POST['status_request']) && $_POST['status_request'] != "" && $_POST['status_request'] != null) {
    $status_request = $_POST['status_request'];
}
if (isset($_POST['my_id']) && $_POST['my_id'] != "" && $_POST['my_id'] != null) {
    $room_id = $_POST['my_id'];
}

if ($status_request == "status") {
    $room_status = mysqli_fetch_assoc(mysqli_query($conn, "SELECT room_status FROM tbl_theater_rooms WHERE theater_room_id = '$room_id'"))['room_status'];

    if(strtolower($room_status) == "active") {
        $update_query = "UPDATE tbl_theater_rooms SET room_status = 'not active' WHERE theater_room_id = '$room_id'";

        $update_query_run = mysqli_query($conn, $update_query);
        $status = "Disabled";
    } 
    if(strtolower($room_status) == "not active"){
        $update_query = "UPDATE tbl_theater_rooms SET room_status = 'active' WHERE theater_room_id = '$room_id'";

        $update_query_run = mysqli_query($conn, $update_query);
        $status = "Enabled";
    }


    if($update_query_run) {
        echo "Successfuly ". $status;
    }else {
        echo "Fail To Change Status";
    }

}

mysqli_close($conn);