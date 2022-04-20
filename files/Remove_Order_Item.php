<?php
    @session_start();
    include("./includes/connection.php");
    
    
    //get order item id
    
    if(isset($_GET['Order_Item_ID'])){
        $Order_Item_ID = $_GET['Order_Item_ID'];
    }else{
        $Order_Item_ID = 0;
    }
    
    if(isset($_GET['Purchase_Order_ID'])){
        $Purchase_Order_ID = $_GET['Purchase_Order_ID'];
    }else{
        $Purchase_Order_ID = 0;
    }
    
    $delete_item = mysqli_query($conn,"delete from tbl_purchase_order_items where Order_Item_ID = '$Order_Item_ID'") or die(mysqli_error($conn));
    
    if($delete_item){
        echo "<script>
                document.location = 'purchace_items_Iframe.php?Purchase_Order_ID=".$Purchase_Order_ID."';
                </script>";
    }      
    
?>