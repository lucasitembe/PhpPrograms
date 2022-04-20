<?php 

    include("./includes/connection.php");

    if(isset($_GET['request'])){
        $id = (isset($_GET['id'])) ? $_GET['id'] : 0;
        $value = (isset($_GET['value'])) ? $_GET['value'] : 0;

        $item_list_cache_id = mysqli_query($conn,"UPDATE tbl_item_list_cache SET dose = $value,Edited_Quantity = $value WHERE Payment_Item_Cache_List_ID = $id") or die(mysqli_errno($conn));
        $result = ($item_list_cache_id) ? "Update" : mysqli_error($conn);

        echo $result;
    }

    if(isset($_GET['request_one'])){
        $id = (isset($_GET['id'])) ? $_GET['id'] : 0;
        $value = (isset($_GET['value'])) ? $_GET['value'] : 0;

        $item_list_cache_id = mysqli_query($conn,"UPDATE tbl_item_list_cache SET Edited_Quantity = $value WHERE Payment_Item_Cache_List_ID = $id") or die(mysqli_errno($conn));
        
        $result = ($item_list_cache_id) ? "Update" : mysqli_error($conn);

        echo $result;
    }

    if(isset($_POST['update_duration'])){
        $dosage_duration = $_POST['get_value'];
        $Payment_Item_Cache_List_ID = $_POST['Id'];
        $Update_duration = mysqli_query($conn,"UPDATE tbl_item_list_cache SET dosage_duration = $dosage_duration WHERE Payment_Item_Cache_List_ID = $Payment_Item_Cache_List_ID") or die(mysqli_error($conn));
        if($Update_duration){
            echo "Updated";
        }else{
            echo "Failed to update";
        }
    }
?>