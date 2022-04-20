<?php
    include_once("./includes/connection.php");
    include_once("./functions/database.php");

    function Get_Return_Inward($Inward_ID) {
        $Return_Inward = array();

        $Return_Inward_Result = Get_From("tbl_return_inward", array("Inward_ID", "=", $Inward_ID), array(), 1);
        $hasError = $Return_Inward_Result["error"];
        if (!$hasError) {
            $Return_Inward = $Return_Inward_Result["data"][0];
        } else {
            echo $Return_Inward_Result["errorMsg"];
        }

        return $Return_Inward;
    }

    function Get_Return_Inward_Item($Inward_ID, $Item_ID) {
        $Return_Inward = array();

        $Return_Inward_Result = Get_From("tbl_return_inward_items", array("Inward_ID", "=", $Inward_ID), array("Item_ID", "=", $Item_ID), 1);
        $hasError = $Return_Inward_Result["error"];
        if (!$hasError) {
            if (!empty($Return_Inward_Result["data"])) {
                $Return_Inward = $Return_Inward_Result["data"][0];
            }
        } else {
            echo $Return_Inward_Result["errorMsg"];
        }

        return $Return_Inward;
    }

    function List_Return_Inward($Inward_Status, $Start_Date, $End_Date, $Limit = 0) {
        $Return_Inward_List = array();

        $Start_And_End_Date_Statement = "";
        if ($Start_Date != null && $End_Date != null){
            $Start_And_End_Date_Statement = "AND Inward_Date BETWEEN '{$Start_Date}' AND '{$End_Date}'";
        }

        $Limit_Statement = "";
        if ($Limit > 0) {
            $Limit_Statement = "LIMIT {$Limit}";
        }

        $Inward_Status_Statement = "";
        if (is_array($Inward_Status)) {
            $Inward_Status_Value = "";
            for ($i=0;$i < count($Inward_Status); $i++) {
                $Inward_Status_Value .= "'{$Inward_Status[$i]}'";
                if ($i < count($Inward_Status) - 1) {
                    $Inward_Status_Value .= ",";
                }
            }
            $Inward_Status_Statement = "Inward_Status IN ({$Inward_Status_Value})";
        } else {
            $Inward_Status_Statement = "Inward_Status='{$Inward_Status}'";
        }

        $Return_Inward_List_Result = Query_DB("SELECT Inward_Date, Inward_ID,
                    (SELECT Employee_Name FROM tbl_employee emp WHERE emp.Employee_ID = ri.Employee_ID) AS Employee_Name,
                    (SELECT Sub_Department_Name FROM tbl_sub_department sd WHERE sd.Sub_Department_ID = ri.Store_Sub_Department_ID) AS Store_Receiving,
                    (SELECT Sub_Department_Name FROM tbl_sub_department ssd WHERE ssd.Sub_Department_ID = ri.Return_Sub_Department_ID) AS Store_Returning,
                    (SELECT COUNT(Item_ID) FROM tbl_return_inward_items ii WHERE ii.Inward_ID = ri.Inward_ID) AS NumItems
                    FROM tbl_return_inward ri WHERE {$Inward_Status_Statement}
                    {$Start_And_End_Date_Statement}
                    ORDER BY Inward_ID DESC {$Limit_Statement}");

        $hasError = $Return_Inward_List_Result["error"];
        if (!$hasError) {
            $Return_Inward_List = array_merge($Return_Inward_List, $Return_Inward_List_Result["data"]);
        } else {
            echo $Return_Inward_List_Result["errorMsg"];
        }

        return $Return_Inward_List;
    }

    function Get_Return_Inward_Items($Inward_ID) {
        $Return_Inward_Item_List = array();

        $Return_Inward_Item_Result = Query_DB("SELECT i.Product_Name, ii.Inward_Item_ID, ii.Item_ID,
                                                ii.Quantity_Returned 	, ii.Item_Remark,
                                                im.Store_Sub_Department_ID, im.Return_Sub_Department_ID,
                                                (SELECT Item_Balance FROM tbl_items_balance ib
                                                WHERE ib.Item_ID = ii.Item_ID
                                                AND ib.Sub_Department_ID = im.Store_Sub_Department_ID) as Store_Balance
                                                FROM tbl_return_inward_items ii, tbl_items i, tbl_return_inward im
                                                WHERE ii.Item_ID = i.Item_ID
                                                AND ii.Inward_ID = im.Inward_ID
                                                AND ii.Inward_ID = '{$Inward_ID}'");
        $hasError = $Return_Inward_Item_Result["error"];
        if (!$hasError) {
            $Return_Inward_Item_List = $Return_Inward_Item_Result["data"];
        } else {
            echo $Return_Inward_Item_Result["errorMsg"];
        }

        return $Return_Inward_Item_List;
    }

    function Update_Return_Inward($Inward_ID, $Return_Inward = array()){
        return Update_DB("tbl_return_inward", $Return_Inward, array("Inward_ID", "=", $Inward_ID), array());
    }

    function Update_Return_Inward_Status($Inward_ID, $Inward_Status){
        return Update_DB("tbl_return_inward", array("Inward_Status" => $Inward_Status), array("Inward_ID", "=", $Inward_ID), array());
    }

    function Update_Quantity_Returned($Inward_Item_ID, $Quantity_Returned){
        return Update_DB("tbl_return_inward_items", array("Quantity_Returned" => $Quantity_Returned), array("Inward_Item_ID", "=", $Inward_Item_ID), array());
    }

    function Update_Item_Remark($Inward_Item_ID, $Item_Remark){
        return Update_DB("tbl_return_inward_items", array("Item_Remark" => $Item_Remark), array("Inward_Item_ID", "=", $Inward_Item_ID), array());
    }

    function Remove_Return_Inward_Items_By_Return_Inward_ID($Inward_ID) {
        $Items_Return_Inward_Item_Delete = Delete_From("tbl_return_inward_items", array("Inward_ID", "=", $Inward_ID), 0);
        $hasError = $Items_Return_Inward_Item_Delete["error"];
        if (!$hasError) {
            return true;
        } else {
            echo $Items_Return_Inward_Item_Delete["errorMsg"];
            return false;
        }
    }

    function Remove_Return_Inward_Item($Inward_Item_ID) {
        $Return_Inward_Item_Delete = Delete_From("tbl_return_inward_items", array("Inward_Item_ID", "=", $Inward_Item_ID), 0);
        $hasError = $Return_Inward_Item_Delete["error"];
        if (!$hasError) {
            return true;
        } else {
            echo $Return_Inward_Item_Delete["errorMsg"];
            return false;
        }
    }

    function Check_Return_Inward_Item($Item_ID, $Inward_ID) {
        $Check_Return_Inward_Item = Query_DB("SELECT Inward_Item_ID
                                                    FROM tbl_return_inward_items
                                                    WHERE Inward_ID = {$Inward_ID}
                                                    AND Item_ID = {$Item_ID}");
        $hasError = $Check_Return_Inward_Item["error"];
        if (!$hasError) {
            if ($Check_Return_Inward_Item["count"] > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            echo $Check_Return_Inward_Item["errorMsg"];
            return false;
        }
    }

    function Sub_Department_Is_Eligible_For_Stock_Balance($Sub_Department_ID){
        $List_Of_Sub_Department = Get_Sub_Department_By_List_Of_Department_Nature(
            array(
                array("nature" => "Storage And Supply"),
                array("nature" => "Pharmacy")
            ));
        $exist_in_the_list = false;
        foreach($List_Of_Sub_Department as $Sub_Department) {
            if($Sub_Department_ID == $Sub_Department['Sub_Department_ID']) {
                $exist_in_the_list = true;
                break;
            }
        }

        return $exist_in_the_list;
    }
?>