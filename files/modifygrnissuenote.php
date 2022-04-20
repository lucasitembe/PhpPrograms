<?php

@session_start();
include("./includes/connection.php");
include("./functions/items.php");
include("./includes/cleaninput.php");
if (isset($_POST)) {
    $_POST = sanitize_input($_POST);
}
if (isset($_POST['action'])) {
    $action = $_POST['action'];
    if ($action == 'edit') {
        $Requisition_Item_ID = $_POST['Requisition_Item_ID'];
        $New_Quantity_Received = $_POST['New_Quantity_Received'];
        $action = $_POST['action'];

        $sql = "SELECT Quantity_Received,Issue_ID, rq.Store_Need, rq.Store_Issue,ri.Item_ID FROM tbl_requisition_items  ri
            JOIN tbl_requisition rq ON rq.Requisition_ID = ri.Requisition_ID
              WHERE Requisition_Item_ID = $Requisition_Item_ID ";

        $last = mysqli_query($conn,$sql) or die(mysqli_error($conn));
        $getData = mysqli_fetch_assoc($last);

        $Quantity_Received = $getData['Quantity_Received'];
        $Issue_ID = $getData['Issue_ID'];
        $Item_ID = $getData['Item_ID'];
        $Store_Need = $getData['Store_Need'];
        $Store_Issue = $getData['Store_Issue'];
        $Quantity = (int) $Quantity_Received - (int) $New_Quantity_Received;

        $status = null;

        if ($Quantity > 0) {
            $status = Update_Item_Balance($Item_ID, $Store_Need, 'GRN Agains Issue Note', $Store_Issue, null, null, $Issue_ID, Get_Time_Now(), abs($Quantity), false);
        } else {
            $status = Update_Item_Balance($Item_ID, $Store_Need, 'GRN Agains Issue Note', $Store_Issue, null, null, $Issue_ID, Get_Time_Now(), abs($Quantity), true);
        }

        if ($status) {
            mysqli_query($conn,"UPDATE tbl_requisition_items SET Quantity_Received= $New_Quantity_Received WHERE Requisition_Item_ID = $Requisition_Item_ID ") or die(mysqli_error($conn));
            echo 1;
        } else {
            echo 0;
        }
    }
}if (isset($_POST['actiongrpord'])) { //type
    if (isset($_SESSION['Storage'])) {
        $Sub_Department_Name = $_SESSION['Storage'];

        $sql_select = mysqli_query($conn,"select Sub_Department_ID from tbl_sub_department where Sub_Department_Name = '$Sub_Department_Name'") or die(mysqli_error($conn));
        $no_rows = mysqli_num_rows($sql_select);
        if ($no_rows > 0) {
            while ($row = mysqli_fetch_array($sql_select)) {
                $Sub_Department_ID = $row['Sub_Department_ID'];
            }
        } else {
            $Sub_Department_ID = 0;
        }
    }

    $orderid = $_POST['orderid'];
    $new_value = $_POST['new_value'];
    $type = $_POST['type'];

    if ($type == 'buyingprice') {
        if (isset($_POST['src']) && $_POST['src'] == 'pending') {
            $update_items = "UPDATE tbl_pending_purchase_order_items SET Buying_Price = '$new_value'  WHERE pending_purchase_id = '$orderid'";
            
              //get Item ID
            $getItemID= mysqli_query($conn,"SELECT Item_ID FROM tbl_pending_purchase_order_items  WHERE pending_purchase_id = '$orderid'") or die(mysqli_error($conn));
           $rowWeb = mysqli_fetch_array($getItemID);
           $itID=$rowWeb['Item_ID'];
            //update last buyying price
            $update_last_buying_price = "UPDATE tbl_items SET Last_Buy_Price='" . str_replace(',', '', $new_value) . "' WHERE Item_ID='$itID'";
            
            $result23 = mysqli_query($conn,$update_last_buying_price) or die(mysqli_error($conn));
            if (!$result23) {
                $HAS_ERROR = true;
            }
            //end last buyying price
        } else {
            $update_items = "UPDATE tbl_purchase_order_items SET Buying_Price = '$new_value'  WHERE Order_Item_ID = '$orderid'";

                 //get Item ID
            $getItemID= mysqli_query($conn,"SELECT Item_ID FROM tbl_purchase_order_items  WHERE Order_Item_ID = '$orderid'") or die(mysqli_error($conn));
           $rowWeb = mysqli_fetch_array($getItemID);
           $itID=$rowWeb['Item_ID'];
            //update last buyying price
            $update_last_buying_price = "UPDATE tbl_items SET Last_Buy_Price='" . str_replace(',', '', $new_value) . "' WHERE Item_ID='$itID'";
            
            $result23 = mysqli_query($conn,$update_last_buying_price) or die(mysqli_error($conn));
            if (!$result23) {
                $HAS_ERROR = true;
            }
            //end last buyying price
        }

        mysqli_query($conn,$update_items) or die(mysqli_error($conn));

        echo 1;
    } else if ($type == 'qtyreceived') {
        if (isset($_POST['src']) && $_POST['src'] == 'pending') {
            $sql = "SELECT Quantity_Received,Item_ID,poi.Grn_Purchase_Order_ID,supplier_id FROM  tbl_pending_purchase_order_items  poi
            JOIN tbl_grn_purchase_order gpo ON gpo.Grn_Purchase_Order_ID = poi.Grn_Purchase_Order_ID
              WHERE  pending_purchase_id = '$orderid'";
        } else {
            $sql = "SELECT Quantity_Received,Item_ID,poi.Grn_Purchase_Order_ID,supplier_id FROM  tbl_purchase_order_items  poi
            JOIN tbl_grn_purchase_order gpo ON gpo.Grn_Purchase_Order_ID = poi.Grn_Purchase_Order_ID
              WHERE  Order_Item_ID = '$orderid'";
        }


        $last = mysqli_query($conn,$sql) or die(mysqli_error($conn));
        $getData = mysqli_fetch_assoc($last);

        $Quantity_Received = $getData['Quantity_Received']; //
        $Item_ID = $getData['Item_ID'];
        $Supplier_ID = $getData['supplier_id'];
        $Grn_Purchase_Order_ID = $getData['Grn_Purchase_Order_ID'];
        $Store_Need = $Sub_Department_ID;
        $Store_Issue = null;
        $Quantity = (int) $Quantity_Received - (int) $new_value;

        $status = null;

        if ($Quantity > 0) {
            $status = Update_Item_Balance($Item_ID, $Store_Need, 'From External', null, $Supplier_ID, null, $Grn_Purchase_Order_ID, Get_Time_Now(), abs($Quantity), false);
        } else {
            $status = Update_Item_Balance($Item_ID, $Store_Need, 'From External', null, $Supplier_ID, null, $Grn_Purchase_Order_ID, Get_Time_Now(), abs($Quantity), true);
        }

        if ($status) {
            if (isset($_POST['src']) && $_POST['src'] == 'pending') {
                $update_items = "UPDATE tbl_pending_purchase_order_items SET Quantity_Received= '$new_value'  WHERE pending_purchase_id = '$orderid'";
            } else {
                $update_items = "UPDATE tbl_purchase_order_items SET Quantity_Received= '$new_value'  WHERE Order_Item_ID = '$orderid'";
            }

            mysqli_query($conn,$update_items) or die(mysqli_error($conn));

            echo 1;
        } else {
            echo 0;
        }
    }
}if (isset($_POST['actiongropenbalance'])) {
    $Grn_Open_Balance_ID = $_POST['Grn_Open_Balance_ID'];
    $new_value = $_POST['new_value'];
    $type = $_POST['type'];

    $slck = mysqli_query($conn,"select Sub_Department_ID from tbl_grn_open_balance where Grn_Open_Balance_ID = '$Grn_Open_Balance_ID'") or die(mysqli_error($conn));
    $nm = mysqli_num_rows($slck);
    if ($nm > 0) {
        while ($dt = mysqli_fetch_array($slck)) {
            $Sub_Department_ID = $dt['Sub_Department_ID'];
        }
    } else {
        $Sub_Department_ID = 0;
    }

    if ($type == 'buyingprice') {
        $update_items = "UPDATE tbl_grn_open_balance_items SET Buying_Price = '$new_value'  WHERE Open_Balance_Item_ID = '$Grn_Open_Balance_ID'";

        mysqli_query($conn,$update_items) or die(mysqli_error($conn));
        
         //get Item ID
            $getItemID= mysqli_query($conn,"SELECT Item_ID FROM tbl_grn_open_balance_items  WHERE Open_Balance_Item_ID = '$Grn_Open_Balance_ID'") or die(mysqli_error($conn));
           $rowWeb = mysqli_fetch_array($getItemID);
           $itID=$rowWeb['Item_ID'];
            //update last buyying price
            $update_last_buying_price = "UPDATE tbl_items SET Last_Buy_Price='" . str_replace(',', '', $new_value) . "' WHERE Item_ID='$itID'";
            
            $result23 = mysqli_query($conn,$update_last_buying_price) or die(mysqli_error($conn));
            if (!$result23) {
                $HAS_ERROR = true;
            }
            //end last buyying price

        echo 1;
    } else if ($type == 'qtyreceived') {
        $sql = "SELECT Sub_Department_ID,Item_Quantity,Item_ID FROM  tbl_grn_open_balance_items obi 
            JOIN tbl_grn_open_balance go ON go.Grn_Open_Balance_ID = obi.Grn_Open_Balance_ID
              WHERE  obi.Open_Balance_Item_ID = '$Grn_Open_Balance_ID'";

        $last = mysqli_query($conn,$sql) or die(mysqli_error($conn));
        $getData = mysqli_fetch_assoc($last);

        $Quantity_Received = $getData['Item_Quantity']; //
        $Item_ID = $getData['Item_ID'];
        $Supplier_ID = null;
        //$Grn_Purchase_Order_ID = $getData['Grn_Open_Balance_ID'];
        $Store_Need = $getData['Sub_Department_ID'];
        $Store_Issue = null;
        $Quantity = (int) $Quantity_Received - (int) $new_value;

        $status = null;

        if ($Quantity > 0) {
            $status = Update_Item_Balance($Item_ID, $Store_Need, 'Open Balance', null, $Supplier_ID, null, $Grn_Open_Balance_ID, Get_Time_Now(), abs($Quantity), false);
        } else {
            $status = Update_Item_Balance($Item_ID, $Store_Need, 'Open Balance', null, $Supplier_ID, null, $Grn_Open_Balance_ID, Get_Time_Now(), abs($Quantity), true);
        }

        if ($status) {
            $update_items = "UPDATE tbl_grn_open_balance_items SET Item_Quantity = '$new_value'  WHERE Open_Balance_Item_ID = '$Grn_Open_Balance_ID'";

            mysqli_query($conn,$update_items) or die(mysqli_error($conn));
            
            echo 1;
        } else {
            echo 0;
        }
    }
}if (isset($_POST['actionpurchaseorderinfo'])) {
    if ($_POST['actionpurchaseorderinfo'] == 'update') {
        $field = $_POST['field'];
        $val = $_POST['val'];
        $Purchase_Order_ID = $_POST['Purchase_Order_ID'];

        $update_value = "UPDATE tbl_grn_purchase_order SET $field='$val' WHERE Purchase_Order_ID='$Purchase_Order_ID'";

        mysqli_query($conn,$update_value) or die(mysqli_error($conn));
    }
}

