<?php
    include_once("./includes/connection.php");
    include_once("./functions/database.php");

    function Get_Return_Outward($Outward_ID) {
        $Return_Outward = array();

        $Return_Outward_Result = Get_From("tbl_return_outward", array("Outward_ID", "=", $Outward_ID), array(), 1);
        $hasError = $Return_Outward_Result["error"];
        if (!$hasError) {
            $Return_Outward = $Return_Outward_Result["data"][0];
        } else {
            echo $Return_Outward_Result["errorMsg"];
        }

        return $Return_Outward;
    }

    function Get_Return_Outward_Item($Outward_ID, $Item_ID) {
        $Return_Outward = array();

        $Return_Outward_Result = Get_From("tbl_return_outward_items", array("Outward_ID", "=", $Outward_ID), array("Item_ID", "=", $Item_ID), 1);
        $hasError = $Return_Outward_Result["error"];
        if (!$hasError) {
            if (!empty($Return_Outward_Result["data"])) {
                $Return_Outward = $Return_Outward_Result["data"][0];
            }
        } else {
            echo $Return_Outward_Result["errorMsg"];
        }

        return $Return_Outward;
    }

    function List_Return_Outward($Outward_Status, $Start_Date, $End_Date, $Limit = 0) {
        $Return_Outward_List = array();

        $Start_And_End_Date_Statement = "";
        if ($Start_Date != null && $End_Date != null){
            $Start_And_End_Date_Statement = "AND Outward_Date BETWEEN '{$Start_Date}' AND '{$End_Date}'";
        }

        $Limit_Statement = "";
        if ($Limit > 0) {
            $Limit_Statement = "LIMIT {$Limit}";
        }

        $Outward_Status_Statement = "";
        if (is_array($Outward_Status)) {
            $Outward_Status_Value = "";
            for ($i=0;$i < count($Outward_Status); $i++) {
                $Outward_Status_Value .= "'{$Outward_Status[$i]}'";
                if ($i < count($Outward_Status) - 1) {
                    $Outward_Status_Value .= ",";
                }
            }
            $Outward_Status_Statement = "Outward_Status IN ({$Outward_Status_Value})";
        } else {
            $Outward_Status_Statement = "Outward_Status='{$Outward_Status}'";
        }

        $Return_Outward_List_Result = Query_DB("SELECT Outward_Date, Outward_ID,
                    (SELECT Employee_Name FROM tbl_employee emp WHERE emp.Employee_ID = ri.Employee_ID) AS Employee_Name,
                    (SELECT Sub_Department_Name FROM tbl_sub_department sd WHERE sd.Sub_Department_ID = ri.Sub_Department_ID) AS Sub_Department,
                    (SELECT Supplier_Name FROM tbl_supplier ssd WHERE ssd.Supplier_ID = ri.Supplier_ID) AS Supplier,
                    (SELECT COUNT(Item_ID) FROM tbl_return_outward_items ii WHERE ii.Outward_ID = ri.Outward_ID) AS NumItems
                    FROM tbl_return_outward ri WHERE {$Outward_Status_Statement}
                    {$Start_And_End_Date_Statement}
                    ORDER BY Outward_ID DESC {$Limit_Statement}");

        $hasError = $Return_Outward_List_Result["error"];
        if (!$hasError) {
            $Return_Outward_List = array_merge($Return_Outward_List, $Return_Outward_List_Result["data"]);
        } else {
            echo $Return_Outward_List_Result["errorMsg"];
        }

        return $Return_Outward_List;
    }

    function Get_Return_Outward_Items($Outward_ID) {
        $Return_Outward_Item_List = array();

        $Return_Outward_Item_Result = Query_DB("SELECT i.Product_Name, ii.Outward_Item_ID, ii.Item_ID,
                                                ii.Quantity_Returned 	, ii.Item_Remark,
                                                im.Sub_Department_ID, im.Supplier_ID,
                                                (SELECT Item_Balance FROM tbl_items_balance ib
                                                WHERE ib.Item_ID = ii.Item_ID
                                                AND ib.Sub_Department_ID = im.Sub_Department_ID) as Store_Balance
                                                FROM tbl_return_outward_items ii, tbl_items i, tbl_return_outward im
                                                WHERE ii.Item_ID = i.Item_ID
                                                AND ii.Outward_ID = im.Outward_ID
                                                AND ii.Outward_ID = '{$Outward_ID}'");
        $hasError = $Return_Outward_Item_Result["error"];
        if (!$hasError) {
            $Return_Outward_Item_List = $Return_Outward_Item_Result["data"];
        } else {
            echo $Return_Outward_Item_Result["errorMsg"];
        }

        return $Return_Outward_Item_List;
    }

    function Update_Return_Outward($Outward_ID, $Return_Outward = array()){
        return Update_DB("tbl_return_outward", $Return_Outward, array("Outward_ID", "=", $Outward_ID), array());
    }

    function Update_Return_Outward_Status($Outward_ID, $Outward_Status){
        return Update_DB("tbl_return_outward", array("Outward_Status" => $Outward_Status), array("Outward_ID", "=", $Outward_ID), array());
    }

    function Update_Quantity_Returned($Outward_Item_ID, $Quantity_Returned){
        return Update_DB("tbl_return_outward_items", array("Quantity_Returned" => $Quantity_Returned), array("Outward_Item_ID", "=", $Outward_Item_ID), array());
    }

    function Update_Item_Remark($Outward_Item_ID, $Item_Remark){
        return Update_DB("tbl_return_outward_items", array("Item_Remark" => $Item_Remark), array("Outward_Item_ID", "=", $Outward_Item_ID), array());
    }

    function Remove_Return_Outward_Items_By_Return_Outward_ID($Outward_ID) {
        $Items_Return_Outward_Item_Delete = Delete_From("tbl_return_outward_items", array("Outward_ID", "=", $Outward_ID), 0);
        $hasError = $Items_Return_Outward_Item_Delete["error"];
        if (!$hasError) {
            return true;
        } else {
            echo $Items_Return_Outward_Item_Delete["errorMsg"];
            return false;
        }
    }

    function Remove_Return_Outward_Item($Outward_Item_ID) {
        $Return_Outward_Item_Delete = Delete_From("tbl_return_outward_items", array("Outward_Item_ID", "=", $Outward_Item_ID), 0);
        $hasError = $Return_Outward_Item_Delete["error"];
        if (!$hasError) {
            return true;
        } else {
            echo $Return_Outward_Item_Delete["errorMsg"];
            return false;
        }
    }

    function Check_Return_Outward_Item($Item_ID, $Outward_ID) {
        $Check_Return_Outward_Item = Query_DB("SELECT Outward_Item_ID
                                                    FROM tbl_return_outward_items
                                                    WHERE Outward_ID = {$Outward_ID}
                                                    AND Item_ID = {$Item_ID}");
        $hasError = $Check_Return_Outward_Item["error"];
        if (!$hasError) {
            if ($Check_Return_Outward_Item["count"] > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            echo $Check_Return_Outward_Item["errorMsg"];
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