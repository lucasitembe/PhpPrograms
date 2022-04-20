<?php
    include("./includes/connection.php");
    include_once("./functions/database.php");

    function Purchase_Order($Purchase_Order_ID) {
        $Purchase_Order = null;
        $select = mysqli_query($conn,"select * from tbl_purchase_order po, tbl_sub_department sd, tbl_supplier sp, tbl_employee emp where
                                po.Sub_Department_ID = sd.Sub_Department_ID and
                                po.Supplier_ID = sp.Supplier_ID and
                                po.Employee_ID = emp.Employee_ID and
                                po.Purchase_Order_ID = '$Purchase_Order_ID'
                                LIMIT 1") or die(mysqli_error($conn));
        $no_of_rows = mysqli_num_rows($select);
        if($no_of_rows){
            while($data = mysqli_fetch_array($select)){
                $Purchase_Order = $data;
            }
        }

        return $Purchase_Order;
    }

    function Get_Purchase_Order_Items($Purchase_Order_ID){
        $Purchase_Order_Items = array();

        $Purchase_Order_Items_Result = Query_DB("SELECT *
                                                 FROM tbl_purchase_order_items
                                                 WHERE Purchase_Order_ID = '{$Purchase_Order_ID}'");

        $hasError = $Purchase_Order_Items_Result["error"];
        if (!$hasError) {
            $Purchase_Order_Items = array_merge($Purchase_Order_Items, $Purchase_Order_Items_Result["data"]);
        } else {
            echo $Purchase_Order_Items_Result["errorMsg"];
        }

        return $Purchase_Order_Items;
    }

    function Get_Purchase_Order_Approval_Process($Purchase_Order_ID){
        $Purchase_Order_Approval_Process= array();

        $Purchase_Order_Approval_Process_Result = Query_DB("SELECT *
                                                                 FROM tbl_purchase_order_approval_process
                                                                 WHERE Purchase_Order_ID = '{$Purchase_Order_ID}'");

        $hasError = $Purchase_Order_Approval_Process_Result["error"];
        if (!$hasError) {
            $Purchase_Order_Approval_Process = array_merge($Purchase_Order_Approval_Process, $Purchase_Order_Approval_Process_Result["data"]);
        } else {
            echo $Purchase_Order_Approval_Process_Result["errorMsg"];
        }

        return $Purchase_Order_Approval_Process;
    }

    function Get_Purchase_Order_Cache_Items($Employee_ID, $Store_Order_Id){
        $Purchase_Order_Items = array();

        $Purchase_Order_Items_Result = Query_DB("SELECT *
                                                     FROM tbl_purchase_cache
                                                     WHERE Employee_ID = '{$Employee_ID}'
                                                     AND Store_Order_Id = '{$Store_Order_Id}'");

        $hasError = $Purchase_Order_Items_Result["error"];
        if (!$hasError) {
            $Purchase_Order_Items = array_merge($Purchase_Order_Items, $Purchase_Order_Items_Result["data"]);
        } else {
            echo $Purchase_Order_Items_Result["errorMsg"];
        }

        return $Purchase_Order_Items;
    }

    function Approval_Levels ($Purchase_Order_ID) {
        $Approval_Levels = 2;
        $select_level = mysqli_query($conn,"select Approvals from tbl_purchase_order where Purchase_Order_ID = '$Purchase_Order_ID'") or die(mysqli_error($conn));
        $num = mysqli_num_rows($select_level);
        if($num > 0){
            while ($row = mysqli_fetch_array($select_level)) {
                $Approval_Levels = $row['Approvals'];
            }
        }
        return $Approval_Levels;
    }

    function Approval_Level($Approval_ID) {
        $Approval_Level = null;
        $select = mysqli_query($conn,"SELECT * FROM tbl_approval_level WHERE Approval_ID = '$Approval_ID'") or die(mysqli_error($conn));
        $num = mysqli_num_rows($select);
        if($num > 0){
            while ($row = mysqli_fetch_array($select)) {
                $Approval_Level = $row;
            }
        }
        return $Approval_Level;
    }

    function Last_Approval_Level() {
        $Approval_Level = null;
        $select = mysqli_query($conn,"SELECT * FROM tbl_approval_level ORDER BY Approval_ID DESC LIMIT 1") or die(mysqli_error($conn));
        $num = mysqli_num_rows($select);
        if($num > 0){
            while ($row = mysqli_fetch_array($select)) {
                $Approval_Level = $row;
            }
        }
        return $Approval_Level;
    }

    function Get_Supplier_List_From_PO_Cache($Employee_ID, $Store_Need, $Store_Order_ID){
        $Supplier_List = array();

        $Supplier_List_Result = Query_DB("SELECT DISTINCT Supplier_ID
                                                FROM tbl_purchase_cache
                                                WHERE Employee_ID = '$Employee_ID' AND
                                                Quantity_Required <> 0 and Price <> 0 AND
                                                Store_need = '$Store_Need' AND
                                                Store_Order_Id = '$Store_Order_ID'");

        $hasError = $Supplier_List_Result["error"];
        if (!$hasError) {
            $Supplier_List = array_merge($Supplier_List, $Supplier_List_Result["data"]);
        } else {
            echo $Supplier_List_Result["errorMsg"];
        }

        return $Supplier_List;
    }

    function Remove_Purchase_Order_Items_By_Purchase_Order_ID($Purchase_Order_ID) {
        $Purchase_Order_Item_Delete = Delete_From("tbl_purchase_order_items", array("Purchase_Order_ID", "=", $Purchase_Order_ID), 0);
        $hasError = $Purchase_Order_Item_Delete["error"];
        if (!$hasError) {

            $Purchase_Order_Approval_Process_Delete = Delete_From("tbl_purchase_order_approval_process", array("Purchase_Order_ID", "=", $Purchase_Order_ID), 0);
            $hasError = $Purchase_Order_Approval_Process_Delete["error"];
            if (!$hasError) {
                return true;
            } else {
                echo $Purchase_Order_Approval_Process_Delete["errorMsg"];
                return false;
            }
        } else {
            echo $Purchase_Order_Item_Delete["errorMsg"];
            return false;
        }
    }
?>