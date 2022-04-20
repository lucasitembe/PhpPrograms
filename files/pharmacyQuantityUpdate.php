<?php
    include("./includes/connection.php");
    if(isset($_GET['Payment_Item_Cache_List_ID'])){
        $Payment_Item_Cache_List_ID = $_GET['Payment_Item_Cache_List_ID'];
    }else{
        $Payment_Item_Cache_List_ID = 0;
    }
    if(isset($_GET['Quantity'])){
        $Quantity = $_GET['Quantity'];
    }else{
        $Quantity = '';
    }
    $update_qr = mysqli_query($conn,"update tbl_item_list_cache set edited_quantity = '$Quantity' where
                        Payment_Item_Cache_List_ID = $Payment_Item_Cache_List_ID") or die(mysqli_error($conn));
    
    if($update_qr){
        echo 1;
    }else{
        echo 0;
    }
?> 