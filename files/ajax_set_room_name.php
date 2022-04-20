<?php
include './includes/connection.php';

$room_name = "";


if(isset($_POST['room_name']) && $_POST['room_name'] != "" && $_POST['room_name'] != null) {
    $room_name = $_POST['room_name'];
}
if (isset($_POST['sub_dept_id']) && $_POST['sub_dept_id'] != "" && $_POST['sub_dept_id'] != null) {
    $sub_dept_id = $_POST['sub_dept_id'];
}


$find = "SELECT theater_room_name FROM tbl_theater_rooms WHERE theater_room_name LIKE '%$room_name%' AND Sub_Department_ID='$sub_dept_id'";
$run_find = mysqli_query($conn, $find);
$no_rows = mysqli_num_rows($run_find);

if($no_rows > 0) {
    echo "Room name exist";
} else {
    $room_name = strtolower($room_name);
    $room_name = ucwords($room_name);
    $query = "INSERT INTO tbl_theater_rooms(theater_room_name,Sub_Department_ID) VALUES('$room_name','$sub_dept_id')";
    $run = mysqli_query($conn, $query);
    if ($run) {
        echo "succeded";
    } else {
        echo "fail";
    }
}

mysqli_close($conn);
