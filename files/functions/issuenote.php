<?php
    include_once("./includes/connection.php");
    include_once("./functions/database.php");

    function Get_Issue_Note($Issue_ID) {
        $Issue_Note = array();

        $Issue_Note_Result = Get_From("tbl_issues", array("Issue_ID", "=", $Issue_ID), array(), 1);
        $hasError = $Issue_Note_Result["error"];
        if (!$hasError) {
            $Issue_Note = $Issue_Note_Result["data"][0];
        } else {
            echo $Issue_Note_Result["errorMsg"];
        }

        return $Issue_Note;
    }

    function Get_Issue_Note_Item($Issue_ID, $Item_ID) {
        $Issue_Note = array();

        $Issue_Note_Result = Get_From("tbl_issues_items", array("Issue_ID", "=", $Issue_ID), array("Item_ID", "=", $Item_ID), 1);
        $hasError = $Issue_Note_Result["error"];
        if (!$hasError) {
            if (!empty($Issue_Note_Result["data"])) {
                $Issue_Note = $Issue_Note_Result["data"][0];
            }
        } else {
            echo $Issue_Note_Result["errorMsg"];
        }

        return $Issue_Note;
    }

    function List_Issue_Note($Issue_Status, $Start_Date, $End_Date, $Limit = 0) {
        $Issue_Note_List = array();

        $Start_And_End_Date_Statement = "";
        if ($Start_Date != null && $End_Date != null){
            $Start_And_End_Date_Statement = "AND  Issue_Date BETWEEN '{$Start_Date}' AND '{$End_Date}'";
        }

        $Limit_Statement = "";
        if ($Limit > 0) {
            $Limit_Statement = "LIMIT {$Limit}";
        }

        $Issue_Status_Statement = "";
        if (is_array($Issue_Status)) {
            $Issue_Status_Value = "";
            for ($i=0;$i < count($Issue_Status); $i++) {
                $Issue_Status_Value .= "'{$Issue_Status[$i]}'";
                if ($i < count($Issue_Status) - 1) {
                    $Issue_Status_Value .= ",";
                }
            }
            $Issue_Status_Statement = "Issue_Status IN ({$Issue_Status_Value})";
        } else {
            $Issue_Status_Statement = "Issue_Status='{$Issue_Status}'";
        }

        $Issue_Note_List_Result = Query_DB("SELECT  Issue_Date, Issue_ID,
                    (SELECT Employee_Name FROM tbl_employee emp WHERE emp.Employee_ID = ri.Employee_ID) AS Employee_Name,
                    (SELECT Sub_Department_Name FROM tbl_sub_department sd WHERE sd.Sub_Department_ID = ri.Store_Sub_Department_ID) AS Store_Receiving,
                    (SELECT Sub_Department_Name FROM tbl_sub_department ssd WHERE ssd.Sub_Department_ID = ri.Return_Sub_Department_ID) AS Store_Returning,
                    (SELECT COUNT(Item_ID) FROM tbl_issues_items ii WHERE ii.Issue_ID = ri.Issue_ID) AS NumItems
                    FROM tbl_issues ri WHERE {$Issue_Status_Statement}
                    {$Start_And_End_Date_Statement}
                    ORDER BY Issue_ID DESC {$Limit_Statement}");

        $hasError = $Issue_Note_List_Result["error"];
        if (!$hasError) {
            $Issue_Note_List = array_merge($Issue_Note_List, $Issue_Note_List_Result["data"]);
        } else {
            echo $Issue_Note_List_Result["errorMsg"];
        }

        return $Issue_Note_List;
    }

    function Get_Issue_Note_Items($Issue_ID) {
        $Issue_Note_Item_List = array();

        $Issue_Note_Item_Result = Query_DB("SELECT i.Product_Name, ii.Inward_Item_ID, ii.Item_ID,
                                                ii.Quantity_Returned 	, ii.Item_Remark,
                                                im.Store_Sub_Department_ID, im.Return_Sub_Department_ID,
                                                (SELECT Item_Balance FROM tbl_items_balance ib
                                                WHERE ib.Item_ID = ii.Item_ID
                                                AND ib.Sub_Department_ID = im.Store_Sub_Department_ID) as Store_Balance
                                                FROM tbl_issues_items ii, tbl_items i, tbl_issues im
                                                WHERE ii.Item_ID = i.Item_ID
                                                AND ii.Issue_ID = im.Issue_ID
                                                AND ii.Issue_ID = '{$Issue_ID}'");
        $hasError = $Issue_Note_Item_Result["error"];
        if (!$hasError) {
            $Issue_Note_Item_List = $Issue_Note_Item_Result["data"];
        } else {
            echo $Issue_Note_Item_Result["errorMsg"];
        }

        return $Issue_Note_Item_List;
    }

    function Update_Issue_Note($Issue_ID, $Issue_Note = array()){
        return Update_DB("tbl_issues", $Issue_Note, array("Issue_ID", "=", $Issue_ID), array());
    }

    function Update_Issue_Note_Status($Issue_ID, $Issue_Status){
        return Update_DB("tbl_issues", array("Issue_Status" => $Issue_Status), array("Issue_ID", "=", $Issue_ID), array());
    }

    function Update_Quantity_Returned($Inward_Item_ID, $Quantity_Returned){
        return Update_DB("tbl_issues_items", array("Quantity_Returned" => $Quantity_Returned), array("Inward_Item_ID", "=", $Inward_Item_ID), array());
    }

    function Update_Item_Remark($Inward_Item_ID, $Item_Remark){
        return Update_DB("tbl_issues_items", array("Item_Remark" => $Item_Remark), array("Inward_Item_ID", "=", $Inward_Item_ID), array());
    }

    function Remove_Issue_Note_Items_By_Issue_Note_ID($Issue_ID) {
        $Items_Issue_Note_Item_Delete = Delete_From("tbl_issues_items", array("Issue_ID", "=", $Issue_ID), 0);
        $hasError = $Items_Issue_Note_Item_Delete["error"];
        if (!$hasError) {
            return true;
        } else {
            echo $Items_Issue_Note_Item_Delete["errorMsg"];
            return false;
        }
    }

    function Remove_Issue_Note_Item($Inward_Item_ID) {
        $Issue_Note_Item_Delete = Delete_From("tbl_issues_items", array("Inward_Item_ID", "=", $Inward_Item_ID), 0);
        $hasError = $Issue_Note_Item_Delete["error"];
        if (!$hasError) {
            return true;
        } else {
            echo $Issue_Note_Item_Delete["errorMsg"];
            return false;
        }
    }

    function Check_Issue_Note_Item($Item_ID, $Issue_ID) {
        $Check_Issue_Note_Item = Query_DB("SELECT Inward_Item_ID
                                                    FROM tbl_issues_items
                                                    WHERE Issue_ID = {$Issue_ID}
                                                    AND Item_ID = {$Item_ID}");
        $hasError = $Check_Issue_Note_Item["error"];
        if (!$hasError) {
            if ($Check_Issue_Note_Item["count"] > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            echo $Check_Issue_Note_Item["errorMsg"];
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