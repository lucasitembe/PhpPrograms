<?php
    include("./includes/connection.php");
    if(isset($_GET['Payment_Item_Cache_List_ID'])){
        $Payment_Item_Cache_List_ID = $_GET['Payment_Item_Cache_List_ID'];
    }else{
        $Payment_Item_Cache_List_ID = 0;
    }
    $delete_qr = "DELETE FROM tbl_item_list_cache WHERE Payment_Item_Cache_List_ID = $Payment_Item_Cache_List_ID";
    if(mysqli_query($conn,$delete_qr)){
        echo "removed";
    }
?> 