<?php 
    #connection
    include("./includes/connection.php");

    #get values
    $Payment_Cache_ID = $_POST['Payment_Cache_ID'];
    $Item_ID = $_POST['Item_ID'];

    $update_item_query = mysqli_query($conn,"UPDATE tbl_item_list_cache SET Status = 'active' 
                                             WHERE Payment_Cache_ID = $Payment_Cache_ID 
                                             AND Check_In_Type = 'Pharmacy' AND Item_ID = $Item_ID ");
    
    if($update_item_query){ 
        echo 'Item Returned Successfull';
    }else{
        echo 'Something went wrong';
    }
?>