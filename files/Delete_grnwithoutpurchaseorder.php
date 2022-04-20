<?php

session_start();
include_once("./includes/connection.php");
include_once("./functions/items.php");
include_once("./functions/supplier.php");
include_once("./functions/grnpurchasecache.php");

if (isset($_SESSION['Storage_Info'])) {
    $Sub_Department_ID = $_SESSION['Storage_Info']['Sub_Department_ID'];
}

if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
} else {
    $Employee_ID = 0;
}

if (isset($_POST['action'])) {

    if ($_POST['action'] == 'DeleteItem') {
        $Purchase_Order_Item_ID = $_POST['Purchase_Order_Item_ID'];
        $Quantity = $_POST['Quantity'];
        $item_ID = $_POST['item_ID'];
        $GRN_ID = $_POST['GRN_ID'];
        $has_error = false;

        Start_Transaction();


        $current_qr = mysqli_query($conn,"SELECT Item_ID,Quantity_Required,gr.Grn_ID,Supplier_ID,Sub_Department_ID FROM tbl_grn_without_purchase_order_items gri JOIN tbl_grn_without_purchase_order gr ON gri.Grn_ID=gr.Grn_ID  WHERE Purchase_Order_Item_ID='$Purchase_Order_Item_ID'") or die(mysqli_error($conn));
        $item_dt = mysqli_fetch_assoc($current_qr);
        $Quantity_Received = $item_dt['Quantity_Required'];
        $Grn_ID = $item_dt['Grn_ID'];
        $Supplier_ID = $item_dt['Supplier_ID'];
        $Item_ID = $item_dt['Item_ID'];
        $Sub_Department_ID = $item_dt['Sub_Department_ID'];

        $Quantity = (int) $Quantity_Received;

        $status = Update_Item_Balance($Item_ID, $Sub_Department_ID, 'Without Purchase', null, $Supplier_ID, null, $Grn_ID, Get_Time_Now(), $Quantity , false);
           
        if ($status) {
            $keepItemHistory=mysqli_query($conn,"INSERT INTO tbl_grn_without_purchase_order_items_history SELECT * FROM tbl_grn_without_purchase_order_items WHERE Purchase_Order_Item_ID='$Purchase_Order_Item_ID'");
           if($keepItemHistory){
               $has_error = true; 
           }else{
            $delete = mysqli_query($conn,"DELETE FROM tbl_grn_without_purchase_order_items WHERE Purchase_Order_Item_ID='$Purchase_Order_Item_ID'") or die(mysqli_error($conn));
   
            if (!$delete) {
                 $has_error = true;
            }
           }
        } else {
            $has_error = true;
        }

        if (!$has_error) {
            Commit_Transaction();
        } else {
            Rollback_Transaction();
        }
    } elseif ($_POST['action'] == 'Add_GRN') {
        $Item_ID = mysqli_real_escape_string($conn,$_POST['Item_ID']);
        $Price = mysqli_real_escape_string($conn,$_POST['Price']);
        $Container = mysqli_real_escape_string($conn,$_POST['Container']);
        $Items_per_Container = mysqli_real_escape_string($conn,$_POST['Items_per_Container']);
        $GRN_ID = mysqli_real_escape_string($conn,$_POST['GRN_ID']);
        $Quantity = mysqli_real_escape_string($conn,$_POST['Quantity']);
        $date = mysqli_real_escape_string($conn,$_POST['date']);
        $exists = mysqli_query($conn,"SELECT * FROM tbl_grn_without_purchase_order_items WHERE Grn_ID='$GRN_ID' AND Item_ID='$Item_ID'");
        $num_rows = mysqli_num_rows($exists);
        if ($num_rows > 0) {
            echo 'This item is already added,choose another item or edit it';
        } else {


            $insert = mysqli_query($conn,"INSERT INTO tbl_grn_without_purchase_order_items (Grn_ID,Item_ID,Quantity_Required,Container_Qty,Items_Per_Container,Price,Expire_Date) VALUES ('$GRN_ID','$Item_ID','$Quantity','$Container','$Items_per_Container','$Price','$date')");
            if ($insert) {
                $checkExistence = mysqli_query($conn,"SELECT Item_ID,Sub_Department_ID,Item_Balance FROM tbl_items_balance WHERE Sub_Department_ID='$Sub_Department_ID' AND Item_ID='$Item_ID'");
                $num_rows = mysqli_num_rows($checkExistence);
                $Ibalance = mysqli_fetch_assoc($checkExistence);
                $prebalance = $Ibalance['Item_Balance'];
                $postbalance = $Quantity + $prebalance;
                if ($num_rows > 0) {
                    $query = "UPDATE tbl_items_balance SET Item_Balance=(Item_Balance+'$Quantity') WHERE Sub_Department_ID='$Sub_Department_ID' AND Item_ID='$Item_ID'";
                } else {

                    $query = "INSERT INTO tbl_items_balance (Item_ID,Item_Balance,Sub_Department_ID,Sub_Department_Type) VALUES ('$Item_ID','$Quantity','$Sub_Department_ID','Storage')";
                }

                $q1 = mysqli_query($conn,$query);
                if ($q1) {
                    if ($num_rows > 0) {

                        $update_ledger = mysqli_query($conn,"INSERT INTO tbl_stock_ledger_controler (Item_ID,Sub_Department_ID,Movement_Type,Registration_ID,Pre_Balance,Post_Balance,Movement_Date,Document_Number,Movement_Date_Time) VALUES ('$Item_ID','$Sub_Department_ID','Without Purchase order','$Employee_ID','$prebalance','$postbalance',NOW(),'$GRN_ID',NOW())");
                    } else {

                        $update_ledger = mysqli_query($conn,"INSERT INTO tbl_stock_ledger_controler (Item_ID,Sub_Department_ID,Movement_Type,Registration_ID,Pre_Balance,Post_Balance,Movement_Date,Document_Number,Movement_Date_Time) VALUES ('$Item_ID','$Sub_Department_ID','Without Purchase order','$Employee_ID','0','$Quantity',NOW(),'$GRN_ID',NOW())");
                    }

                    if ($update_ledger) {
                        echo 'Item added successfully';
                        mysqli_query($conn,"COMMIT");
                    } else {
                        echo mysqli_error($conn);
                        mysqli_query($conn,"ROLLBACK");
                    }
                } else {
                    echo mysqli_error($conn);
                    mysqli_query($conn,"ROLLBACK");
                }
            } else {
                echo mysqli_error($conn);
            }
        }
    }
}
?>
