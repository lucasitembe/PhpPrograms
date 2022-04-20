<?php

include("./includes/connection.php");
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $status = $_GET['status'];
    $sql = "UPDATE tbl_dashboard_buttons SET status='$status' WHERE id='$id'";
    $query = mysqli_query($conn,$sql) or die(mysqli_error($conn));
    if ($query) {
        echo "Success";
    } else {
        echo "Failed";
    }
}
