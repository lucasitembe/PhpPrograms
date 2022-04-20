<?php
    include("./includes/connection.php");
    if(isset($_GET['payment_item_list_cache_ID'])){
        $payment_item_list_cache_ID = $_GET['payment_item_list_cache_ID'];
    }else{
        $payment_item_list_cache_ID = 0;
    }
    $delete_qr = "DELETE FROM tbl_payment_item_list_cache WHERE payment_item_list_cache_ID = $payment_item_list_cache_ID";
    if(mysqli_query($conn,$delete_qr)){
        echo "removed";
    }
?> 