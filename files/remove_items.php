<?php
    include("./includes/connection.php");

    $Payment_Item_Cache_List_ID = $_POST['Id'];

    $remove_query = mysqli_query($conn,"UPDATE tbl_item_list_cache 
                                        SET Status = 'removed' 
                                        WHERE Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID' lIMIT 1 ");
    if($remove_query){
        echo 'Item Update';
    }else{
        echo 'Some went wrong try again';
    }
?>