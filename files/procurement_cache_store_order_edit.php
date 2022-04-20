<?php
    include_once("./includes/connection.php");
    include_once("./functions/items.php");
    include_once("./functions/purchaseorder.php");

    //Remove all items cached by this employee
    $delete = mysqli_query($conn,"delete from tbl_purchase_cache where Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));

    $Purchase_Order_Items = Get_Purchase_Order_Items($Purchase_Order_ID);
    foreach($Purchase_Order_Items as $Purchase_Order_Item) {

        $Insert = Insert_DB("tbl_purchase_cache", array(
            "Quantity_Required" => $Purchase_Order_Item["Quantity_Required"],
            "Container_Qty" => $Purchase_Order_Item["Containers_Required"],
            "Items_Per_Container" => $Purchase_Order_Item["Items_Per_Container_Required"],
            "Item_Remark" => $Purchase_Order_Item["Remark"],
            "Item_ID" => $Purchase_Order_Item["Item_ID"],
            "Store_Order_Id" => $Store_Order_ID,
            "Employee_ID" => $Employee_ID,
            "Price" => $Purchase_Order_Item["Price"],
            "Store_Need" => $Sub_Department_ID,
            "Supplier_ID" => $Supplier_ID
        ));
    }
?>