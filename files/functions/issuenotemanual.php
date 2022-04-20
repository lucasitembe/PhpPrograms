<?php
    include_once("./includes/connection.php");
    include_once("./functions/database.php");

    function Get_Issue_Note_Manual($IssueManual_ID) {
        $Issue_Manual = array();

        $Issue_Manual_Result = Get_From("tbl_issuesmanual", array("Issue_ID", "=", $IssueManual_ID), array(), 1);
        $hasError = $Issue_Manual_Result["error"];
        if (!$hasError) {
            $Issue_Manual = $Issue_Manual_Result["data"][0];
        } else {
            echo $Issue_Manual_Result["errorMsg"];
        }

        return $Issue_Manual;
    }

    function Get_Issue_Note_Manual_Item($IssueManual_ID, $Item_ID) {
        $Issue_Manual = array();

        $Issue_Manual_Result = Get_From("tbl_issuemanual_items", array("Issue_ID", "=", $IssueManual_ID), array("Item_ID", "=", $Item_ID), 1);
        $hasError = $Issue_Manual_Result["error"];
        if (!$hasError) {
            if (!empty($Issue_Manual_Result["data"])) {
                $Issue_Manual = $Issue_Manual_Result["data"][0];
            }
        } else {
            echo $Issue_Manual_Result["errorMsg"];
        }

        return $Issue_Manual;
    }

    function List_Issue_Note_Manual($Sub_Department_ID, $Status, $Start_Date, $End_Date, $Issue_ID, $Store_Receiving_ID, $Limit = 0) {
        $Issue_Manual_List = array();

        $Sub_Department_ID_Statement = "";
        $Store_Receiving_ID_Statement = "";
        $Start_And_End_Date_Statement = "";
        $Issue_ID_Statement = "";


        if ($Issue_ID != null) {
            $Issue_ID_Statement = "AND Issue_ID like '{$Issue_ID}'";
        } else {
            if ($Sub_Department_ID != null) {
                $Sub_Department_ID_Statement = "AND Store_Issuing = {$Sub_Department_ID}";
            }

            if ($Store_Receiving_ID != null && $Store_Receiving_ID != ""&&$Store_Receiving_ID !="All") {
                $Store_Receiving_ID_Statement = "AND Store_Need = {$Store_Receiving_ID}";
            }

            if ($Start_Date != null && $End_Date != null){
                $Start_And_End_Date_Statement = "AND Issue_Date_And_Time BETWEEN '{$Start_Date}' AND '{$End_Date}'";
            }
        }

        $Limit_Statement = "";
        if ($Limit > 0) {
            $Limit_Statement = "LIMIT {$Limit}";
        }

        $Status_Statement = "";
        if (is_array($Status)) {
            $Status_Value = "";
            for ($i=0;$i < count($Status); $i++) {
                $Status_Value .= "'{$Status[$i]}'";
                if ($i < count($Status) - 1) {
                    $Status_Value .= ",";
                }
            }
            $Status_Statement = "status IN ({$Status_Value})";
        } else {
            $Status_Statement = "status='{$Status}'";
        }

        $Issue_Manual_List_Result = Query_DB("SELECT Issue_Date_And_Time, Issue_ID,
                    (SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = Employee_Issuing) AS EmployeeIssueing,
                    (SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = Employee_Receiving) AS EmployeeReceiving,
                    (SELECT Sub_Department_Name FROM tbl_sub_department WHERE Store_Issuing = Sub_Department_ID) AS StoreIssueing,
                    (SELECT Sub_Department_Name FROM tbl_sub_department WHERE Store_Need = Sub_Department_ID) AS StoreReiceiving,
                    (SELECT COUNT(Item_ID) FROM tbl_issuemanual_items ii WHERE ii.Issue_ID = tbl_issuesmanual.Issue_ID) AS NumItems
                    FROM tbl_issuesmanual WHERE {$Status_Statement}
                    {$Start_And_End_Date_Statement}
                    {$Sub_Department_ID_Statement}
                    {$Issue_ID_Statement}
                    {$Store_Receiving_ID_Statement}
                    ORDER BY Issue_ID DESC {$Limit_Statement}");

        $hasError = $Issue_Manual_List_Result["error"];
        if (!$hasError) {
            $Issue_Manual_List = array_merge($Issue_Manual_List, $Issue_Manual_List_Result["data"]);
        } else {
            echo $Issue_Manual_List_Result["errorMsg"];
        }

        return $Issue_Manual_List;
    }

    function Get_Issue_Note_Manual_Items($IssueManual_ID) {
        $Issue_Manual_Item_List = array();

        $Issue_Manual_Item_Result = Query_DB("SELECT i.Product_Name, ii.Requisition_Item_ID, ii.Item_ID,
                                                ii.Quantity_Required, ii.Quantity_Issued, ii.Item_Remark,
                                                im.Store_Issuing, im.Store_Need, ii.Requisition_Item_ID, ii.Buying_Price,ii.Selling_Price,
                                                (SELECT Item_Balance FROM tbl_items_balance ib
                                                WHERE ib.Item_ID = ii.Item_ID
                                                AND ib.Sub_Department_ID = im.Store_Issuing) as Store_Balance
                                                FROM tbl_issuemanual_items ii, tbl_items i, tbl_issuesmanual im
                                                WHERE ii.Item_ID = i.Item_ID
                                                AND ii.Issue_ID = im.Issue_ID
                                                AND ii.Issue_ID = '{$IssueManual_ID}'");
        $hasError = $Issue_Manual_Item_Result["error"];
        if (!$hasError) {
            $Issue_Manual_Item_List = $Issue_Manual_Item_Result["data"];
        } else {
            echo $Issue_Manual_Item_Result["errorMsg"];
        }

        return $Issue_Manual_Item_List;
    }

    function Update_Issue_Note_Manual($Issue_ID, $Issue_Note_Manual = array()){
        return Update_DB("tbl_issuesmanual", $Issue_Note_Manual, array("Issue_ID", "=", $Issue_ID), array());
    }

    function Update_Quantity_Required($Requisition_Item_ID, $Quantity_Required){
        return Update_DB("tbl_issuemanual_items", array("Quantity_Required" => $Quantity_Required), array("Requisition_Item_ID", "=", $Requisition_Item_ID), array());
    }

    function Update_Issue_Note_Manual_Status($Issue_ID, $Status){
        return Update_DB("tbl_issuesmanual", array("status" => $Status), array("Issue_ID", "=", $Issue_ID), array());
    }

    function Update_Quantity_Issued($Requisition_Item_ID, $Quantity_Issued){
        return Update_DB("tbl_issuemanual_items", array("Quantity_Issued" => $Quantity_Issued), array("Requisition_Item_ID", "=", $Requisition_Item_ID), array());
    }

    function Update_Item_Remark($Requisition_Item_ID, $Item_Remark){
        return Update_DB("tbl_issuemanual_items", array("Item_Remark" => $Item_Remark), array("Requisition_Item_ID", "=", $Requisition_Item_ID), array());
    }

    function Remove_Issue_Note_Manual_Items_By_Issue_Note_Manual_ID($Issue_ID) {
        $Items_Issue_Note_Manual_Item_Delete = Delete_From("tbl_issuemanual_items", array("Issue_ID", "=", $Issue_ID), 0);
        $hasError = $Items_Issue_Note_Manual_Item_Delete["error"];
        if (!$hasError) {
            return true;
        } else {
            echo $Items_Issue_Note_Manual_Item_Delete["errorMsg"];
            return false;
        }
    }

    function Remove_Issue_Note_Manual_Item($Requisition_Item_ID) {
        $Issue_Manual_Item_Delete = Delete_From("tbl_issuemanual_items", array("Requisition_Item_ID", "=", $Requisition_Item_ID), 0);
        $hasError = $Issue_Manual_Item_Delete["error"];
        if (!$hasError) {
            return true;
        } else {
            echo $Issue_Manual_Item_Delete["errorMsg"];
            return false;
        }
    }

    function Check_Issue_Note_Manual_Item($Item_ID, $IssueManual_ID) {
        $Check_Issue_Note_Manual_Item = Query_DB("SELECT Requisition_Item_ID
                                                    FROM tbl_issuemanual_items
                                                    WHERE Issue_ID = {$IssueManual_ID}
                                                    AND Item_ID = {$Item_ID}");
        $hasError = $Check_Issue_Note_Manual_Item["error"];
        if (!$hasError) {
            if ($Check_Issue_Note_Manual_Item["count"] > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            echo $Check_Issue_Note_Manual_Item["errorMsg"];
            return false;
        }
    }
?>