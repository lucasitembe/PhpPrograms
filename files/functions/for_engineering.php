<?php
    include_once("./includes/connection.php");
    include_once("./functions/database.php");

    function Get_Requisition($Requisition_ID) {
        global $conn;
        $Requisition = array();

        $Requisition_Result = Get_From("tbl_engineering_requisition", array("Requisition_ID", "=", $Requisition_ID), array(), 1);
        $hasError = $Requisition_Result["error"];
        if (!$hasError) {
            $Requisition = $Requisition_Result["data"][0];
        } else {
            echo $Requisition_Result["errorMsg"];
        }

        return $Requisition;
    }

    /*function Get_Requisition_Item($Requisition_ID, $Item_ID) {
        global $conn;
        $Requisition = array();

        $Requisition_Result = Get_From("tbl_requisition_items", array("Requisition_ID", "=", $Requisition_ID), array("Item_ID", "=", $Item_ID), 1);
        $hasError = $Requisition_Result["error"];
        if (!$hasError) {
            if (!empty($Requisition_Result["data"])) {
                $Requisition = $Requisition_Result["data"][0];
            }
        } else {
            echo $Requisition_Result["errorMsg"];
        }

        return $Requisition;
    }*/

    function List_Requisition($Sub_Department_ID, $Requisition_Status, $Start_Date, $End_Date, $Requisition_ID, $Limit = 0) {
        global $conn;
        $Requisition_List = array();

        $Requisition_ID_Statement = "";
        $Sub_Department_ID_Statement = "";
        $Start_And_End_Date_Statement = "";

        if ($Requisition_ID != null) {
            $Requisition_ID_Statement = "AND Requisition_ID like '{$Requisition_ID}'";
        } else {
            if ($Sub_Department_ID != null) {
                $Sub_Department_ID_Statement = "AND Store_Need = {$Sub_Department_ID}";
            }

            if ($Start_Date != null && $End_Date != null){
                $Start_And_End_Date_Statement = "AND Created_Date BETWEEN '{$Start_Date}' AND '{$End_Date}'";
            }
        }

        $Limit_Statement = "";
        if ($Limit > 0) {
            $Limit_Statement = "LIMIT {$Limit}";
        }

        $Requisition_Status_Statement = "";
        if (is_array($Requisition_Status)) {
            $Requisition_Status_Value = "";
            for ($i=0;$i < count($Requisition_Status); $i++) {
                $Requisition_Status_Value .= "'{$Requisition_Status[$i]}'";
                if ($i < count($Requisition_Status) - 1) {
                    $Requisition_Status_Value .= ",";
                }
            }
            $Requisition_Status_Statement = "Requisition_Status IN ({$Requisition_Status_Value})";
        } else {
            $Requisition_Status_Statement = "Requisition_Status='{$Requisition_Status}'";
        }

        $Requisition_List_Result = Query_DB("SELECT Created_Date, Requisition_ID, Requisition_Status,
                    (SELECT Employee_Name FROM tbl_employee emp WHERE emp.Employee_ID = ri.Employee_ID) as Employee_Name,
                    (SELECT Sub_Department_Name FROM tbl_sub_department sd WHERE sd.Sub_Department_ID = ri.Store_Need) as Store_Requesting,
                    (SELECT COUNT(Item_ID) FROM tbl_requisition_items ii WHERE ii.Requisition_ID = ri.Requisition_ID) as NumItems
                    FROM tbl_requisition ri WHERE {$Requisition_Status_Statement}
                    {$Requisition_ID_Statement}
                    {$Start_And_End_Date_Statement}
                    {$Sub_Department_ID_Statement}
                    ORDER BY Requisition_ID DESC {$Limit_Statement}");

        $hasError = $Requisition_List_Result["error"];
        if (!$hasError) {
            $Requisition_List = array_merge($Requisition_List, $Requisition_List_Result["data"]);
        } else {
            echo $Requisition_List_Result["errorMsg"];
        }

        return $Requisition_List;
    }

    function Get_Requisition_Items($Requisition_ID) {
        global $conn;
        $Requisition_Item_List = array();


    function Update_Requisition($Requisition_ID, $Requisition = array()){
        global $conn;
        return Update_DB("tbl_requisition", $Requisition, array("Requisition_ID", "=", $Requisition_ID), array());
    }

    function Update_Requisition_Status($Requisition_ID, $Requisition_Status){
        global $conn;
        return Update_DB("tbl_requisition", array("Requisition_Status" => $Requisition_Status), array("Requisition_ID", "=", $Requisition_ID), array());
    }

    function Update_Quantity_Required($Requisition_Item_ID, $Quantity_Required){
        global $conn;
        return Update_DB("tbl_requisition_items", array("Quantity_Required" => $Quantity_Required), array("Requisition_Item_ID", "=", $Requisition_Item_ID), array());
    }

    function Update_Item_Remark($Requisition_Item_ID, $Item_Remark){
        global $conn;
        return Update_DB("tbl_requisition_items", array("Item_Remark" => $Item_Remark), array("Requisition_Item_ID", "=", $Requisition_Item_ID), array());
    }

    function Remove_Requisition_Items_By_Requisition_ID($Requisition_ID) {
        global $conn;
        $Items_Requisition_Item_Delete = Delete_From("tbl_requisition_items", array("Requisition_ID", "=", $Requisition_ID), 0);
        $hasError = $Items_Requisition_Item_Delete["error"];
        if (!$hasError) {
            return true;
        } else {
            echo $Items_Requisition_Item_Delete["errorMsg"];
            return false;
        }
    }

    function Remove_Requisition_Item($Requisition_Item_ID) {
        global $conn;
        $Requisition_Item_Delete = Delete_From("tbl_requisition_items", array("Requisition_Item_ID", "=", $Requisition_Item_ID), 0);
        $hasError = $Requisition_Item_Delete["error"];
        if (!$hasError) {
            return true;
        } else {
            echo $Requisition_Item_Delete["errorMsg"];
            return false;
        }
    }

    function Check_Requisition_Item($Item_ID, $Requisition_ID) {
        global $conn;
        $Check_Requisition_Item = Query_DB("SELECT Requisition_Item_ID
                                                    FROM tbl_requisition_items
                                                    WHERE Requisition_ID = {$Requisition_ID}
                                                    AND Item_ID = {$Item_ID}");
        $hasError = $Check_Requisition_Item["error"];
        if (!$hasError) {
            if ($Check_Requisition_Item["count"] > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            echo $Check_Requisition_Item["errorMsg"];
            return false;
        }
    }
?>