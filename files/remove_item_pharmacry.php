<?php
    include("./includes/connection.php");

    $Payment_Cache_ID = $_POST['id_param'];
    $Item_ID = $_POST['Item_ID'];
    $Employee_ID = $_POST['Employee_ID'];

    $remove_query = mysqli_query($conn,"UPDATE tbl_item_list_cache 
                                        SET Status = 'removed', Remove_Date = NOW(), Employee_Remove = '$Employee_ID'
                                        WHERE Payment_Item_Cache_List_ID = '$Payment_Cache_ID' AND Item_ID = $Item_ID");
    if($remove_query){
        echo 'Removed Successfully';

    }else{
        echo 'Some went wrong try again';
    }
?>