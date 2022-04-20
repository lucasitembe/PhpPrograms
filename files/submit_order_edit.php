<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } ?>
<?php

    include("./includes/connection.php");
    include_once("./functions/purchaseorder.php");
    include_once("./functions/parser.php");

    if(isset($_GET['Purchase_Order_ID'])){
        $Purchase_Order_ID = $_GET['Purchase_Order_ID'];
    }else{
        $Purchase_Order_ID = 0;
    }

    if(isset($_GET['Store_Order_ID'])){
        $Store_Order_ID = $_GET['Store_Order_ID'];
    }else{
        $Store_Order_ID = 0;
    }

    if(isset($_GET['Supplier_ID'])){
        $Supplier_ID = $_GET['Supplier_ID'];
    }else{
        $Supplier_ID = 0;
    }

    if (isset($_SESSION['userinfo']['Employee_Name'])) {
        $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
    } else {
        $Employee_Name = 'Unknown Employee';
    }

    if (isset($_SESSION['userinfo']['Employee_ID'])) {
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    } else {
        $Employee_ID = 'Unknown Employee';
    }

    if ($Purchase_Order_ID > 0 && $Store_Order_ID > 0 && $Employee_ID > 0){
        Start_Transaction();

        $Purchase_Order_Item_List = Get_Purchase_Order_Items($Purchase_Order_ID);
        $Purchase_Order_Approval_Process = Get_Purchase_Order_Approval_Process($Purchase_Order_ID);
        $Current_Edit = array(
            "Edit_Date" => Get_Time_Now(),
            "Edited_By" => $Employee_Name,
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

        if (Remove_Purchase_Order_Items_By_Purchase_Order_ID($Purchase_Order_ID)) {
            $Edited_Purchase_Order_Items = Get_Purchase_Order_Cache_Items($Employee_ID, $Store_Order_ID);

            $error_code = 0;
            foreach($Edited_Purchase_Order_Items as $Edited_Purchase_Order_Item) {

                $Insert_Items_Disposal_Item = Insert_DB("tbl_purchase_order_items", array(
                    "Purchase_Order_ID" => $Purchase_Order_ID,
                    "Containers_Required" => $Edited_Purchase_Order_Item['Container_Qty'],
                    "Items_Per_Container_Required" => $Edited_Purchase_Order_Item['Items_Per_Container'],
                    "Quantity_Required" => $Edited_Purchase_Order_Item['Quantity_Required'],
                    "Price" => $Edited_Purchase_Order_Item['Price'],
                    "Remark" => $Edited_Purchase_Order_Item['Item_Remark'],
                    "Item_ID" => $Edited_Purchase_Order_Item['Item_ID']
                ));

                $hasError = $Insert_Items_Disposal_Item["error"];
                if ($hasError) {
                    $error_code = 1;
                    echo $Insert_Items_Disposal_Item['errorMsg'];Rollback_Transaction();
                    break;
                }
            }

            //For Those items that existed in the previous document, but no longer exist after editing the document
            //These items need to show up in the original store order
            foreach($Purchase_Order_Item_List as $Purchase_Order_Item){
                $Does_Not_Exist = true;
                foreach($Edited_Purchase_Order_Items as $Edited_Purchase_Order_Item) {
                    if ($Edited_Purchase_Order_Item['Item_ID'] == $Purchase_Order_Item['Item_ID']) {
                        $Does_Not_Exist = false;
                        break;
                    }
                }

                if ($Does_Not_Exist) {
                    $Update_StoreOrderItem = Update_DB("tbl_store_order_items", array(
                            "Procurement_Status" => "active"
                        ),
                        array("Store_Order_ID", "=", $Purchase_Order["Store_Order_ID"]),
                        array("Item_ID", "=", $Purchase_Order_Item["Item_ID"])
                    );
                    $hasError = $Update_StoreOrderItem["error"];
                    if ($hasError) {
                        $error_code = 2;
                        echo $Update_StoreOrderItem["errorMsg"]; Rollback_Transaction();
                        break;
                    }
                }
            }

            if ($error_code == 0) {
                $Update_Status = Update_DB(" tbl_purchase_order", array(
                        "Supplier_ID" => $Supplier_ID,
                        "Order_Status" => "submitted",
                        "Approval_Level" => 1
                    ),
                    array("Purchase_Order_ID", "=", $Purchase_Order_ID),
                    array());
                $hasError = $Update_Status["error"];
                if (!$hasError) {
                    Commit_Transaction();

                    //delete all data from cache table
                    mysqli_query($conn,"delete from tbl_purchase_cache where Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
                    $_SESSION['Purchase_Order_ID'] = $Purchase_Order_ID;
                    header("Location: ./purchaseorderpreview.php?PurchaseOrderPreview=PurchaseOrderPreviewThisPage");
                } else {
                    echo $Update_Status["errorMsg"]; Rollback_Transaction();
                }
            }

        } else {
            echo "Unable to remove Previous Purchase Order Items!!";Rollback_Transaction();
        }

    } else {
        echo "Invalid Request";
    }
?>