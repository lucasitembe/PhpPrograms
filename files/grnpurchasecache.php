<?php
    include_once("./includes/connection.php");
    include_once("./functions/database.php");

    function Get_Purchase_Order_Cache_Items_By_Employee($Employee_ID) {
        $Purchase_Order_Cache_Item_List = array();
        $Sub_Department_ID = $_SESSION['Storage_Info']['Sub_Department_ID'];
        $Purchase_Order_Cache_Item_Result = Query_DB("SELECT
                                                            batch_no,itm.Product_Name, pc.Quantity_Required, pc.Item_Remark, pc.Purchase_Cache_ID,
                                                            pc.Price, pc.Container_Qty, pc.Items_Per_Container, pc.Expire_Date
                                                        FROM tbl_grn_purchase_cache pc, tbl_items itm
                                                        WHERE itm.Item_ID = pc.Item_ID
                                                        AND pc.Employee_ID ='{$Employee_ID}' AND pc.Sub_Department_ID='$Sub_Department_ID'");
        $hasError = $Purchase_Order_Cache_Item_Result["error"];
        if (!$hasError) {
            $Purchase_Order_Cache_Item_List = array_merge($Purchase_Order_Cache_Item_List, $Purchase_Order_Cache_Item_Result["data"]);
        } else {
            echo $Purchase_Order_Cache_Item_Result["errorMsg"];
        }

        return $Purchase_Order_Cache_Item_List;
    }
?>