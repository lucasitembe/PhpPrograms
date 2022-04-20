<?php
include("./includes/connection.php");
include_once("./functions/items.php");
if (isset($_GET['grn_local_purchase_order_cache_id'])) {
    $grn_local_purchase_order_cache_id = $_GET['grn_local_purchase_order_cache_id'];
} else {
    $grn_local_purchase_order_cache_id = 0;
}
if (isset($_GET['local_purchase_order_id'])) {
    $local_purchase_order_id = $_GET['local_purchase_order_id'];
} else {
    $local_purchase_order_id = 0;
}

$HAS_ERROR = false;
Start_Transaction();
$sql_select_from_tbl_grn_local_purchase_order_cache_result = mysqli_query($conn, "SELECT local_purchase_order_id,receiver_Employee_ID,Sub_Department_ID,Supplier_ID,Delivery_Note_Number,Invoice_Number,Delivery_Date,Delivery_Person,RV_Number FROM tbl_grn_local_purchase_order_cache WHERE grn_local_purchase_order_cache_id='$grn_local_purchase_order_cache_id'") or die($mysqli_error($conn));
if (mysqli_num_rows($sql_select_from_tbl_grn_local_purchase_order_cache_result) > 0) {
    while ($grn_lpo_cache_rows = mysqli_fetch_assoc($sql_select_from_tbl_grn_local_purchase_order_cache_result)) {
        $local_purchase_order_id = $grn_lpo_cache_rows['local_purchase_order_id'];
        $receiver_Employee_ID = $grn_lpo_cache_rows['receiver_Employee_ID'];
        $Sub_Department_ID = $grn_lpo_cache_rows['Sub_Department_ID'];
        $Supplier_ID = $grn_lpo_cache_rows['Supplier_ID'];
        $Delivery_Note_Number = $grn_lpo_cache_rows['Delivery_Note_Number'];
        $Invoice_Number = $grn_lpo_cache_rows['Invoice_Number'];
        $Delivery_Date = $grn_lpo_cache_rows['Delivery_Date'];
        $Delivery_Person = $grn_lpo_cache_rows['Delivery_Person'];
        $RV_Number = $grn_lpo_cache_rows['RV_Number'];

        $insert_value = "INSERT INTO tbl_grn_purchase_order(
                              Sub_Department_ID,Created_Date,Created_Date_Time,Employee_ID,
							  Supplier_ID,Purchase_Order_ID,
							  Debit_Note_Number, Invoice_Number, Delivery_Date, Delivery_Person,RV_Number)
						 VALUES
						      ('$Sub_Department_ID',(SELECT now()),(SELECT now()),'$receiver_Employee_ID',
							  '$Supplier_ID','$local_purchase_order_id',
							  '$Delivery_Note_Number', '$Invoice_Number', '$Delivery_Date', '$Delivery_Person',
                              '$RV_Number')";

        $insert_value_result = mysqli_query($conn, $insert_value) or die(mysqli_error($conn));
        if (!$insert_value_result) {
            $HAS_ERROR = TRUE;
            echo "===1";
        }

        $get_Grn_Purchase_Order_ID = mysqli_query($conn, "SELECT Grn_Purchase_Order_ID FROM  tbl_grn_purchase_order WHERE  employee_id = '$receiver_Employee_ID' AND Purchase_order_id = '$local_purchase_order_id' AND
                                                          supplier_id = '$Supplier_ID' ORDER BY Grn_Purchase_Order_ID DESC LIMIT 1") or die(mysqli_error($conn));

        if (mysqli_num_rows($get_Grn_Purchase_Order_ID) > 0) {
            $data = mysqli_fetch_array($get_Grn_Purchase_Order_ID);
            $Grn_Purchase_Order_ID = $data['Grn_Purchase_Order_ID'];
            $Insert_Status = 'true';
        } else {
            $HAS_ERROR = TRUE;
            echo "===2";
        }

        //select  items for grn
        $sql_select_grn_items_frm_cache_result = mysqli_query($conn, "SELECT buying_price,Item_ID,container_quantity,item_per_container,quantity_required_,expiredate,batch_no,Grn_Status,rejected_quantity,rejection_reason FROM tbl_grn_local_purchase_order_items_cache WHERE grn_local_purchase_order_cache_id='$grn_local_purchase_order_cache_id' AND Grn_Status IN('RECEIVED','OUTSTANDING','PENDING','REJECTED')") or die(mysqli_error($conn));
        if (mysqli_num_rows($sql_select_grn_items_frm_cache_result) > 0) {
            while ($grn_items_frm_cache_rows = mysqli_fetch_assoc($sql_select_grn_items_frm_cache_result)) {
                $Item_ID = $grn_items_frm_cache_rows['Item_ID'];
                $container_quantity = $grn_items_frm_cache_rows['container_quantity'];
                $item_per_container = $grn_items_frm_cache_rows['item_per_container'];
                $quantity_required_ = $grn_items_frm_cache_rows['quantity_required_'];
                $expiredate = $grn_items_frm_cache_rows['expiredate'];
                $batch_no = $grn_items_frm_cache_rows['batch_no'];
                $Grn_Status = $grn_items_frm_cache_rows['Grn_Status'];
                $rejected_quantity = $grn_items_frm_cache_rows['rejected_quantity'];
                $rejection_reason = $grn_items_frm_cache_rows['rejection_reason'];
                $buying_price = $grn_items_frm_cache_rows['buying_price'];

                //insert items for grn
                $sql_insert_grn_items_result = mysqli_query($conn, "INSERT INTO tbl_local_purchase_order_items(local_purchase_order_id,Item_ID,Quantity_Received,Containers_Received,Items_Per_Container,quantity_rejected,Buying_Price,Grn_Purchase_Order_ID,Expire_Date,Grn_Status,rejection_reason)
                        VALUES('$local_purchase_order_id','$Item_ID','$quantity_required_','$container_quantity','$item_per_container','$rejected_quantity','$buying_price','$Grn_Purchase_Order_ID','$expiredate','$Grn_Status','$rejection_reason')   
                        ") or die(mysqli_error($conn));
                if (!$sql_insert_grn_items_result) {
                    $HAS_ERROR = TRUE;
                    echo "===3";
                }

                //get pre balance
                $slct_balance = mysqli_query($conn, "SELECT Item_Balance from tbl_items_balance where Item_ID = '$Item_ID' and Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
                $slct_balance_num = mysqli_num_rows($slct_balance);
                if ($slct_balance_num > 0) {
                    while ($sl = mysqli_fetch_array($slct_balance)) {
                        $Pre_Balance = $sl['Item_Balance'];
                    }
                } else {
                    $ins1 = mysqli_query($conn, "INSERT INTO tbl_items_balance(Item_ID,Sub_Department_ID) VALUES('$Item_ID','$Sub_Department_ID')") or die(mysqli_error($conn));
                    $Pre_Balance = 0;

                    if (!$ins1) {
                        $HAS_ERROR = true;
                        echo "===4";
                    }
                }

                //update balance by adding items received
                $b_update = mysqli_query($conn, "UPDATE tbl_items_balance set Item_Balance = Item_Balance + " . str_replace(',', '', $quantity_required_) . " WHERE Item_ID = '$Item_ID' and Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));

                if (!$b_update) {
                    $HAS_ERROR = true;
                    echo "===5";
                }

                
                //insert data into tbl_stock_ledger_controler for auditing
                $insert_audit = mysqli_query($conn, "INSERT INTO tbl_stock_ledger_controler(Item_ID, Sub_Department_ID,Movement_Type, External_Source,Pre_Balance, Post_Balance, Movement_Date, Movement_Date_Time, Document_Number)
                                                     VALUES('$Item_ID','$Sub_Department_ID','From External','$Supplier_ID','$Pre_Balance',($Pre_Balance + " . str_replace(',', '', $quantity_required_) . "),(SELECT now()),(select now()),'$Grn_Purchase_Order_ID')") or die(mysqli_error($conn));

                if (!$insert_audit) {
                    $HAS_ERROR = true;
                    echo "===6";
                }

                //update stock value details
                $get_items_details = mysqli_query($conn, "SELECT Last_Buy_Price from tbl_items where Item_ID = '$Item_ID'") or die(mysqli_error($conn));
                $num_items_details = mysqli_num_rows($get_items_details);
                if ($num_items_details > 0) {
                    while ($dt_items = mysqli_fetch_array($get_items_details)) {
                        $Last_Buy_Price = $dt_items['Last_Buy_Price'];
                    }
                } else {
                    $Last_Buy_Price = 0;
                }

                //update last buyying price
                $update_last_buying_price = "UPDATE tbl_items SET Last_Buy_Price='" . str_replace(',', '', $buying_price) . "' WHERE Item_ID='$Item_ID'";

                $result23 = mysqli_query($conn, $update_last_buying_price) or die(mysqli_error($conn));
                if (!$result23) {
                    $HAS_ERROR = true;
                    echo "===7";
                }
                //end last buyying price
            }
        } else {
            $HAS_ERROR = TRUE;
            echo "===8";
        }
    }
}

$sql_delete_cache_result = mysqli_query($conn, "DELETE FROM tbl_grn_local_purchase_order_cache WHERE local_purchase_order_id='$local_purchase_order_id'") or die(mysqli_error($conn));
if (!$sql_delete_cache_result) {
    $HAS_ERROR = TRUE;
    echo "===9";
}
$sql_delete_cache_item_result = mysqli_query($conn, "DELETE FROM tbl_grn_local_purchase_order_items_cache WHERE grn_local_purchase_order_cache_id='$grn_local_purchase_order_cache_id'") or die(mysqli_error($conn));
if (!$sql_delete_cache_item_result) {
    $HAS_ERROR = TRUE;
    echo "===9";
}
if (!$HAS_ERROR) {
    Commit_Transaction();
    echo 1;
} else {
    Rollback_Transaction();
    echo "Process Fail";
}
