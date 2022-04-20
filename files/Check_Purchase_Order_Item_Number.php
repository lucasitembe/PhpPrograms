<?php
    session_start();
    include("./includes/connection.php");
    
    if(isset($_SESSION['Purchase_Order_ID'])){
        $Purchase_Order_ID = $_SESSION['Purchase_Order_ID'];
        
        //check if the current order has atleast one item
        $sql = mysqli_query($conn,"select Order_Item_ID from tbl_purchase_order_items where
                                        Purchase_Order_ID = '$Purchase_Order_ID'") or die(mysqli_error($conn));
        $num = mysqli_num_rows($sql);
        
        if($num > 0){
            echo 'Yes';
        }else{
            echo 'No';
        }        
    }else{
        echo 'No';
    }
?>