<?php
    session_start();
    include("./includes/connection.php");

    if(isset($_GET['Store_Order_ID'])){
        $Store_Order_ID = $_GET['Store_Order_ID'];
    }else{
        $Store_Order_ID = 0;
    }

    if($Store_Order_ID > 0){
        //delete all items
        $clear = mysqli_query($conn,"DELETE FROM tbl_store_order_items where Store_Order_ID = '$Store_Order_ID'") or die(mysqli_error($conn));
        if($clear){
            echo 'Yes';
        }else{
            echo 'No';
        }
        
    }else{
        echo 'No';
    }

?>