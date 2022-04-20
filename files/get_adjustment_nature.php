<?php 
    session_start();
    include './includes/connection.php';

    $value = $_GET['value'];
    echo $get_nature_for_adjustment = mysqli_fetch_assoc(mysqli_query($conn,"SELECT nature FROM tbl_adjustment WHERE id = $value"))['nature'];
?>