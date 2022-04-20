<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } ?>
<?php

    include_once("./functions/database.php");
    include_once("./functions/employee.php");
    include_once("./functions/purchaseorder.php");
    include_once("./functions/items.php");
    include_once("./functions/parser.php");

    if (isset($_GET['Purchase_Order_ID'])) {
        $Purchase_Order_ID = $_GET['Purchase_Order_ID'];
    }

    if (isset($_SESSION['userinfo']['Employee_Name'])) {
        $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
    } else {
        $Employee_Name = 'Unknown Employee';
    }

    $error_code = "yes";
    if ($Purchase_Order_ID > 0) {
        Start_Transaction();

        $Purchase_Order_Item_List = Get_Purchase_Order_Items($Purchase_Order_ID);
        $Purchase_Order_Approval_Process = Get_Purchase_Order_Approval_Process($Purchase_Order_ID);
        $Current_Edit = array(
            "Canceled_Date" => Get_Time_Now(),
            "Canceled_By" => $Employee_Name,
            "Previous_Data" => $Purchase_Order_Item_List,
            "Purchase_Order_Approval_Process" => $Purchase_Order_Approval_Process
        );

        $Purchase_Order = Purchase_Order($Purchase_Order_ID);
        if ($Purchase_Order['Previous_Purchase_Order_Data'] != "") {
            $Previous_Edit = jsonToArray($Purchase_Order['Previous_Purchase_Order_Data']);
            $Previous_Edit = array_merge($Previous_Edit, array($Current_Edit));
        } else {
            $Previous_Edit  = array_values(array("Previous_Disposal_Data" => $Current_Edit));
        }
        $Previous_Disposal_Data = toJson($Previous_Edit);

        foreach($Purchase_Order_Item_List as $Purchase_Order_Item) {
            //Return Items to the store order
            $Update_StoreOrderItem = Update_DB("tbl_store_order_items", array(
                    "Procurement_Status" => "active"
                ),
                array("Store_Order_ID", "=", $Purchase_Order["Store_Order_ID"]),
                array("Item_ID", "=", $Purchase_Order_Item["Item_ID"])
            );
            $hasError = $Update_StoreOrderItem["error"];
            if ($hasError) {
                $error_code = "no - unable return item to store order";
                echo $Update_StoreOrderItem["errorMsg"]; Rollback_Transaction();
                break;
            }
        }


        if ($error_code == "yes" && Remove_Purchase_Order_Items_By_Purchase_Order_ID($Purchase_Order_ID)) {
            $Update_Status = Update_DB(" tbl_purchase_order", array(
                    "Order_Status" => "canceled"
                ),
                array("Purchase_Order_ID", "=", $Purchase_Order_ID),
                array());
            $hasError = $Update_Status["error"];
            if (!$hasError) {
                $error_code = "yes"; Commit_Transaction();
                if (isset($_SESSION['Purchase_Order_ID'])) {
                    unset($_SESSION['Purchase_Order_ID']);
                }
            } else {
                $error_code = "no - unable to cancel Purchase Order";
                echo $Update_Status["errorMsg"];
                Rollback_Transaction();
            }
        }
    } else {
        $error_code = "no- no disposal id";
    }

    echo $error_code;