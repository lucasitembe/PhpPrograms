<?php
    include("./includes/connection.php");
    if(isset($_GET['Payment_Item_Cache_List_ID'])){
        $Payment_Item_Cache_List_ID = $_GET['Payment_Item_Cache_List_ID'];
    }else{
        $Payment_Item_Cache_List_ID = 0;
    }
    if(isset($_GET['duration'])){
        $duration = $_GET['duration'];
    }else{
        $duration = '';
    }
    $update_qr = mysqli_query($conn,"update tbl_item_list_cache set dosage_duration = '$duration' where
                        Payment_Item_Cache_List_ID = $Payment_Item_Cache_List_ID") or die(mysqli_error($conn));
    
    if($update_qr){
        echo 1;
    }else{
        echo 0;
    }
?> 