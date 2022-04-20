<?php
    include_once("./includes/connection.php");
    include_once("./functions/database.php");

    function Get_Stock_Taking($Stock_Taking_ID) {
        $Stock_Taking = array();

        $Stock_Taking_Result = Get_From("tbl_stocktaking", array("Stock_Taking_ID", "=", $Stock_Taking_ID), array(), 1);
        $hasError = $Stock_Taking_Result["error"];
        if (!$hasError) {
            $Stock_Taking = $Stock_Taking_Result["data"][0];
        } else {
            echo $Stock_Taking_Result["errorMsg"];
        }

        return $Stock_Taking;
    }

    function Get_Stock_Taking_Item($Stock_Taking_ID, $Item_ID) {
        $Stock_Taking = array();

        $Stock_Taking_Result = Get_From("tbl_stocktaking_items", array("Stock_Taking_ID", "=", $Stock_Taking_ID), array("Item_ID", "=", $Item_ID), 1);
        $hasError = $Stock_Taking_Result["error"];
        if (!$hasError) {
            if (!empty($Stock_Taking_Result["data"])) {
                $Stock_Taking = $Stock_Taking_Result["data"][0];
            }
        } else {
            echo $Stock_Taking_Result["errorMsg"];
        }

        return $Stock_Taking;
    }

    function Get_Stock_Taking_Items($Stock_Taking_ID) {
        $Stock_Taking_Item_List = array();

        $Stock_Taking_Item_Result = Query_DB("SELECT i.Product_Name, sti.Stock_Taking_Item_ID, sti.Item_ID,
                                                    sti.Over_Quantity, sti.Under_Quantity, sti.Item_Remark,
                                                    st.Sub_Department_ID,

                                                    IFNULL((SELECT Item_Balance FROM tbl_items_balance ib
                                                            WHERE sti.Item_ID = ib.Item_ID
                                                            AND st.Sub_Department_ID = ib.Sub_Department_ID), '0') as Store_Balance

                                                    FROM tbl_stocktaking_items sti, tbl_items i, tbl_stocktaking st
                                                    WHERE sti.Item_ID = i.Item_ID
                                                    AND sti.Stock_Taking_ID = st.Stock_Taking_ID
                                                    AND sti.Stock_Taking_ID = '{$Stock_Taking_ID}'");
        $hasError = $Stock_Taking_Item_Result["error"];
        if (!$hasError) {
            $Stock_Taking_Item_List = $Stock_Taking_Item_Result["data"];
        } else {
            echo $Stock_Taking_Item_Result["errorMsg"];
        }

        return $Stock_Taking_Item_List;
    }

    function List_Stock_Taking($Stock_Taking_Status, $Start_Date, $End_Date, $Limit = 0) {
        $Stock_Taking_List = array();

        $Start_And_End_Date_Statement = "";
        if ($Start_Date != null && $End_Date != null){
            $Start_And_End_Date_Statement = "AND Stock_Taking_Date BETWEEN '{$Start_Date}' AND '{$End_Date}'";
        }

        $Limit_Statement = "";
        if ($Limit > 0) {
            $Limit_Statement = "LIMIT {$Limit}";
        }

        $Stock_Taking_Status_Statement = "";
        if (is_array($Stock_Taking_Status)) {
            $Stock_Taking_Status_Value = "";
            for ($i=0;$i < count($Stock_Taking_Status); $i++) {
                $Stock_Taking_Status_Value .= "'{$Stock_Taking_Status[$i]}'";
                if ($i < count($Stock_Taking_Status) - 1) {
                    $Stock_Taking_Status_Value .= ",";
                }
            }
            $Stock_Taking_Status_Statement = "Stock_Taking_Status IN ({$Stock_Taking_Status_Value})";
        } else {
            $Stock_Taking_Status_Statement = "Stock_Taking_Status='{$Stock_Taking_Status}'";
        }

        $Stock_Taking_List_Result = Query_DB("SELECT Stock_Taking_Date, Stock_Taking_ID,
                    (SELECT Employee_Name FROM tbl_employee e WHERE e.Employee_ID = tbl_stocktaking.Employee_ID) AS Stock_Taking_Officer,
                    (SELECT Sub_Department_Name FROM tbl_sub_department sd WHERE sd.Sub_Department_ID = tbl_stocktaking.Sub_Department_ID LIMIT 1) AS Stock_Taking_Location,
                    (SELECT COUNT(Item_ID) FROM tbl_stocktaking_items di WHERE di.Stock_Taking_ID = tbl_stocktaking.Stock_Taking_ID) AS NumItems
                    FROM  tbl_stocktaking WHERE {$Stock_Taking_Status_Statement}
                    {$Start_And_End_Date_Statement}
                    ORDER BY Stock_Taking_ID DESC {$Limit_Statement}");

        $hasError = $Stock_Taking_List_Result["error"];
        if (!$hasError) {
            $Stock_Taking_List = array_merge($Stock_Taking_List, $Stock_Taking_List_Result["data"]);
        } else {
            echo $Stock_Taking_List_Result["errorMsg"];
        }

        return $Stock_Taking_List;
    }

    function Update_Stock_Taking($Stock_Taking_ID, $Stock_Taking = array()){
        return Update_DB("tbl_stocktaking", $Stock_Taking, array("Stock_Taking_ID", "=", $Stock_Taking_ID), array());
    }

    function Update_Stock_Taking_Status($Stock_Taking_ID, $Stock_Taking_Status){
        return Update_DB("tbl_stocktaking", array("Stock_Taking_Status" => $Stock_Taking_Status), array("Stock_Taking_ID", "=", $Stock_Taking_ID), array());
    }

    function Update_Over_Quantity($Stock_Taking_Item_ID, $Over_Quantity){
        return Update_DB(" tbl_stocktaking_items", array("Over_Quantity" => $Over_Quantity), array("Stock_Taking_Item_ID", "=", $Stock_Taking_Item_ID), array());
    }

    function Update_Under_Quantity($Stock_Taking_Item_ID, $Under_Quantity){
        return Update_DB(" tbl_stocktaking_items", array("Under_Quantity" => $Under_Quantity), array("Stock_Taking_Item_ID", "=", $Stock_Taking_Item_ID), array());
    }

    function Update_Item_Remark($Stock_Taking_Item_ID, $Item_Remark){
        return Update_DB(" tbl_stocktaking_items", array("Item_Remark" => $Item_Remark), array("Stock_Taking_Item_ID", "=", $Stock_Taking_Item_ID), array());
    }

    function Remove_Stock_Taking_Item($Stock_Taking_Item_ID) {
        $Stock_Taking_Item_Delete = Delete_From("tbl_stocktaking_items", array("Stock_Taking_Item_ID", "=", $Stock_Taking_Item_ID), 0);
        $hasError = $Stock_Taking_Item_Delete["error"];
        if (!$hasError) {
            return true;
        } else {
            echo $Stock_Taking_Item_Delete["errorMsg"];
            return false;
        }
    }

    function Remove_Stock_Taking_Items_By_Stock_Taking_ID($Stock_Taking_ID) {
        $Stock_Taking_Items_Delete = Delete_From("tbl_stocktaking_items", array("Stock_Taking_ID", "=", $Stock_Taking_ID), 0);
        $hasError = $Stock_Taking_Items_Delete["error"];
        if (!$hasError) {
            return true;
        } else {
            echo $Stock_Taking_Items_Delete["errorMsg"];
            return false;
        }
    }

    function Check_Stock_Taking_Item($Item_ID, $Stock_Taking_ID) {
        $Check_Stock_Taking_Item = Query_DB("SELECT Stock_Taking_Item_ID
                                                    FROM tbl_stocktaking_items
                                                    WHERE Stock_Taking_ID = {$Stock_Taking_ID}
                                                    AND Item_ID = {$Item_ID}");
        $hasError = $Check_Stock_Taking_Item["error"];
        if (!$hasError) {
            if ($Check_Stock_Taking_Item["count"] > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            echo $Check_Stock_Taking_Item["errorMsg"];
            return false;
        }
    }
?>