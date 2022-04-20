<?php 
    include("./includes/connection.php");

    $item_id = (isset($_GET['item_id']) ? $_GET['item_id'] : "");
    $id = (isset($_GET['id']) ? $_GET['id'] : "");
    $user_id = (isset($_GET['user_id']) ? $_GET['user_id'] : "");

    $sql = mysqli_query($conn,"UPDATE tbl_purchase_requisition_items SET item_status = 'removed', removed_by = '$user_id' WHERE purchase_requisition_items_id = '$id'");

    if($sql){
        echo 'Item Removed';
    }else{
        echo 'Failed';
    }
?>