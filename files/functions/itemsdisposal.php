<?php
    include_once("./includes/connection.php");
    include_once("./functions/database.php");

    function Get_Items_Disposal($Disposal_ID) {
        global $conn;
        $Items_Disposal = array();

        $Items_Disposal_Result = Get_From("tbl_disposal", array("Disposal_ID", "=", $Disposal_ID), array(), 1);
        $hasError = $Items_Disposal_Result["error"];
        if (!$hasError) {
            $Items_Disposal = $Items_Disposal_Result["data"][0];
        } else {
            echo $Items_Disposal_Result["errorMsg"];
        }

        return $Items_Disposal;
    }

    function Get_Items_Disposal_Item($Disposal_ID, $Item_ID) {
        global $conn;
        $Items_Disposal = array();

        $Items_Disposal_Result = Get_From("tbl_disposal_items", array("Disposal_ID", "=", $Disposal_ID), array("Item_ID", "=", $Item_ID), 1);
        $hasError = $Items_Disposal_Result["error"];
        if (!$hasError) {
            if (!empty($Items_Disposal_Result["data"])) {
                $Items_Disposal = $Items_Disposal_Result["data"][0];
            }
        } else {
            echo $Items_Disposal_Result["errorMsg"];
        }

        return $Items_Disposal;
    }

    function Get_Items_Disposal_Items($Disposal_ID) {
        global $conn;
        $Disposal_Item_List = array();

        $Disposal_Item_Result = Query_DB("SELECT i.Product_Name, di.Disposal_Item_ID, di.Item_ID,i.Last_Buy_Price,
                                                    di.Quantity_Disposed, di.Item_Remark,i.Consultation_Type,batch_no,expire_date,
                                                    d.Sub_Department_ID,
                                                    (SELECT Item_Balance FROM tbl_items_balance ib
                                                    WHERE ib.Item_ID = di.Item_ID
                                                    AND ib.Sub_Department_ID = d.Sub_Department_ID) as Store_Balance
                                                    FROM tbl_disposal_items di, tbl_items i, tbl_disposal d
                                                    WHERE di.Item_ID = i.Item_ID
                                                    AND di.Disposal_ID = d.Disposal_ID
                                                    AND di.Disposal_ID = '{$Disposal_ID}'");
        $hasError = $Disposal_Item_Result["error"];
        if (!$hasError) {
            $Disposal_Item_List = $Disposal_Item_Result["data"];
        } else {
            echo $Disposal_Item_Result["errorMsg"];
        }

        return $Disposal_Item_List;
    }

    function List_Items_Disposal($Sub_Department_ID, $Disposal_Status, $Start_Date, $End_Date, $Limit = 0) {
        global $conn;
        $Issue_Manual_List = array();

        $Sub_Department_ID_Statement = "";
        if ($Sub_Department_ID != null) {
            $Sub_Department_ID_Statement = "AND tbl_disposal.Sub_Department_ID = {$Sub_Department_ID}";
        }

        $Start_And_End_Date_Statement = "";
        if ($Start_Date != null && $End_Date != null){
            $Start_And_End_Date_Statement = "AND Disposed_Date BETWEEN '{$Start_Date}' AND '{$End_Date}'";
        }

        $Disposal_Status_Statement = "";
        if (is_array($Disposal_Status)) {
            $Disposal_Status_Value = "";
            for ($i=0;$i < count($Disposal_Status); $i++) {
                $Disposal_Status_Value .= "'{$Disposal_Status[$i]}'";
                if ($i < count($Disposal_Status) - 1) {
                    $Disposal_Status_Value .= ",";
                }
            }
            $Disposal_Status_Statement = "Disposal_Status IN ({$Disposal_Status_Value})";
        } else {
            $Disposal_Status_Statement = "Disposal_Status='{$Disposal_Status}'";
        }

        $Limit_Statement = "";
        if ($Limit > 0) {
            $Limit_Statement = "LIMIT {$Limit}";
        }

        $sql = "SELECT Disposed_Date, Disposal_ID,
                    (SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = tbl_disposal.Employee_ID LIMIT 1) AS Disposal_Officer,
                    (SELECT Sub_Department_Name FROM tbl_sub_department sd WHERE sd.Sub_Department_ID = tbl_disposal.Sub_Department_ID LIMIT 1) AS Disposal_Location,
                    (SELECT COUNT(Item_ID) FROM tbl_disposal_items di WHERE di.Disposal_ID = tbl_disposal.Disposal_ID) AS NumItems
                    FROM  tbl_disposal WHERE {$Disposal_Status_Statement}
                    {$Start_And_End_Date_Statement}
                    {$Sub_Department_ID_Statement}
                    ORDER BY Disposal_ID DESC {$Limit_Statement}";
        $Issue_Manual_List_Result = Query_DB($sql);
        //echo $sql;

        $hasError = $Issue_Manual_List_Result["error"];
        if (!$hasError) {
            $Issue_Manual_List = array_merge($Issue_Manual_List, $Issue_Manual_List_Result["data"]);
        } else {
            echo $Issue_Manual_List_Result["errorMsg"];
        }

        return $Issue_Manual_List;
    }

    function Update_Items_Disposal_Status($Disposal_ID, $Disposal_Status){
        global $conn;
        return Update_DB("tbl_disposal", array("Disposal_Status" => $Disposal_Status), array("Disposal_ID", "=", $Disposal_ID), array());
    }

    function Update_Disposal($Disposal_ID, $Disposal = array()){
        global $conn;
        return Update_DB("tbl_disposal", $Disposal, array("Disposal_ID", "=", $Disposal_ID), array());
    }

    function Update_Quantity_Disposed($Disposal_Item_ID, $Quantity_Disposed){
        global $conn;
        return Update_DB(" tbl_disposal_items", array("Quantity_Disposed" => $Quantity_Disposed),array("batch_no " => 'UUY'),array("Disposal_Item_ID", "=", $Disposal_Item_ID), array());
    }

    function Update_Item_Remark($Disposal_Item_ID, $Item_Remark){
        global $conn;
        return Update_DB(" tbl_disposal_items", array("Item_Remark" => $Item_Remark), array("Disposal_Item_ID", "=", $Disposal_Item_ID), array());
    }

    function Remove_Items_Disposal_Item($Disposal_Item_ID) {
        global $conn;
        $Items_Disposal_Item_Delete = Delete_From("tbl_disposal_items", array("Disposal_Item_ID", "=", $Disposal_Item_ID), 0);
        $hasError = $Items_Disposal_Item_Delete["error"];
        if (!$hasError) {
            return true;
        } else {
            echo $Items_Disposal_Item_Delete["errorMsg"];
            return false;
        }
    }

    function Remove_Disposal_Items_By_Disposal_ID($Disposal_ID) {
        global $conn;
        $Items_Disposal_Item_Delete = Delete_From("tbl_disposal_items", array("Disposal_ID", "=", $Disposal_ID), 0);
        $hasError = $Items_Disposal_Item_Delete["error"];
        if (!$hasError) {
            return true;
        } else {
            echo $Items_Disposal_Item_Delete["errorMsg"];
            return false;
        }
    }

    function Check_Items_Disposal_Item($Item_ID, $Disposal_ID) {
        global $conn;
        $Check_Items_Disposal_Item = Query_DB("SELECT Disposal_Item_ID
                                                    FROM tbl_disposal_items
                                                    WHERE Disposal_ID = {$Disposal_ID}
                                                    AND Item_ID = {$Item_ID}");
        $hasError = $Check_Items_Disposal_Item["error"];
        if (!$hasError) {
            if ($Check_Items_Disposal_Item["count"] > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            echo $Check_Items_Disposal_Item["errorMsg"];
            return false;
        }
    }
?>