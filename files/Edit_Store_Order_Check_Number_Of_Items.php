<?php
    session_start();
    include("./includes/connection.php");
    
    if(isset($_SESSION['Edit_General_Order_ID'])){
        $Store_Order_ID = $_SESSION['Edit_General_Order_ID'];
        
        $sql_select = mysqli_query($conn,"select so.Store_Order_ID from tbl_store_orders so, tbl_store_order_items soi where
                                    so.Store_Order_ID = soi.Store_Order_ID and
                                    so.Order_Status = 'Submitted' and
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