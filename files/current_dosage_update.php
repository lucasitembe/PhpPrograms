<?php 
    require_once('./includes/connection.php');

    $Payment_Item_Cache_List_ID = $_GET['Payment_Item_Cache_List_ID'];
    $Doctor_Comment = $_GET['Doctor_Comment'];
    $new_dosage = $_GET['new_dosage'];

    $update_query = mysqli_query($conn,"UPDATE tbl_item_list_cache SET Doctor_Comment = '$new_dosage' WHERE Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID' LIMIT 1");
    if($update_query){
        echo "Dose Updated";
    }else{
        echo "not updated";
    }
?>