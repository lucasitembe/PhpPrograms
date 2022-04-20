<?php
include './includes/connection.php';

if (isset($_POST['Payment_Item_Cache_List_ID']) && $_POST['Payment_Item_Cache_List_ID'] != "" && $_POST['Payment_Item_Cache_List_ID'] != null && isset($_POST['room_id']) && $_POST['room_id'] != "" && $_POST['room_id'] != null) {
    $Payment_Item_Cache_List_ID = $_POST['Payment_Item_Cache_List_ID'];
    $room_id = $_POST['room_id'];

    $update_data = mysqli_query($conn, "UPDATE tbl_item_list_cache SET theater_room_id = '$room_id' WHERE Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'");

    if($update_data) {
        echo "Successfuly Updated";
    } else {
        echo "Fail To Update";
    }
}

mysqli_close($conn);

?>