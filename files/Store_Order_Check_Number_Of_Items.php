<?php
    session_start();
    include("./includes/connection.php");
    
    if(isset($_SESSION['General_Order_ID'])){
        $Store_Order_ID = $_SESSION['General_Order_ID'];
        
        $sql_select = mysqli_query($conn,"SELECT so.Store_Order_ID FROM tbl_store_orders so, tbl_store_order_items soi WHERE
                                          so.Store_Order_ID = soi.Store_Order_ID AND
                                          so.Order_Status IN ('pending', 'saved') AND
                                          so.Store_Order_ID = '$Store_Order_ID'") or die(mysqli_error($conn));
        $num = mysqli_num_rows($sql_select);
        if($num > 0 ){
            echo 'Yes';
        }else{
            echo 'No';
        }
    }else{
        echo 'No';
    }
?>