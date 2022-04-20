<?php 
    include './includes/connection.php';

    $ID_ = array();
    $select = mysqli_query($conn,"SELECT `Payment_Item_Cache_List_ID` FROM `tbl_item_list_cache` WHERE `Sub_Department_ID` IN (195,192,203,199,204,202) AND `Check_In_Type` = 'Pharmacy' AND Status = 'dispensed' AND `dispensed_quantity` = 0 AND Quantity > 0 ORDER BY `Payment_Item_Cache_List_ID` DESC LIMIT 10");
    while($data = mysqli_fetch_assoc($select)){
        array_push($ID_,$data);
    }

    if(sizeof($ID_) > 0){
        foreach($ID_ as $id){
            $new_id_ = $id['Payment_Item_Cache_List_ID'];
            $select_consumables = mysqli_query($conn,"SELECT Quantity FROM tbl_item_list_cache WHERE Payment_Item_Cache_List_ID = $new_id_");
            while($data = mysqli_fetch_assoc($select_consumables)){
                $quantity = $data['Quantity'];
                $update = mysqli_query($conn,"UPDATE tbl_item_list_cache SET dispensed_quantity = $quantity,Edited_Quantity = $quantity,dose=$quantity WHERE Payment_Item_Cache_List_ID = $new_id_");
                if($update){
                    echo $new_id_." = Updated <br> ";
                }else{
                    echo "Not Updated * <br>";
                }
            }
        }
    }else{
        echo "NO ITEM FOUND";
    }
?>
