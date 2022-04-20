<?php 
    session_start();
    include("./includes/connection.php");

    # Update order description
    $Store_Order_ID = (isset($_GET['Store_Order_ID']) ? $_GET['Store_Order_ID'] : '');
    $Order_Description = (isset($_GET['Order_Description'])) ? $_GET['Order_Description'] : 0;


    # Update order description query
    $update_query = mysqli_query($conn,"UPDATE tbl_store_orders SET Order_Description = '$Order_Description' WHERE Store_Order_ID = '$Store_Order_ID' ") or die($conn);
    if(!$update_query){
        die(mysqli_errno($conn));
    }
?>