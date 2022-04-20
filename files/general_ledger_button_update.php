<?php

include("./includes/connection.php");
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $status = $_GET['status'];
    $sql = "UPDATE tbl_general_ledger_button SET general_ledger_button_status='$status' WHERE general_ledger_button_id='$id'";
    $query = mysqli_query($conn,$sql) or die(mysqli_error($conn));
    if ($query) {
        echo "Success";
    } else {
        echo "Failed";
    }
}else{
    echo "no data";
}