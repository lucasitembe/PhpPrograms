<?php

session_start();
include("./includes/connection.php");
include_once("./functions/items.php");
include_once("./functions/supplier.php");
include_once("./functions/grnpurchasecache.php");

Start_Transaction();


$select_items = mysqli_query($conn,"SELECT
                                          *
                                     FROM
                                          tbl_purchase_order po, tbl_purchase_order_items poi, tbl_items itm
                                     WHERE
						                  po.Purchase_Order_ID = poi.Purchase_Order_ID AND
							              poi.item_id = itm.item_id AND
                                                                       po.order_status = 'served' ") or die(mysqli_error($conn));

$has_error = false;
while ($row = mysqli_fetch_array($select_items)) {
  //echo "SELECT Document_Number FROM tbl_stock_ledger_controler WHERE Movement_Type='From External' AND Document_Number='" . $row['Grn_Purchase_Order_ID'] . "' ";
    $checkIfGRNpresent = mysqli_query($conn,"SELECT Document_Number FROM tbl_stock_ledger_controler WHERE  Movement_Type='From External' AND Document_Number='" . $row['Grn_Purchase_Order_ID'] . "'");

    if (mysqli_num_rows($checkIfGRNpresent) > 0) {
       // echo 'No need to update <br/>';
         $status = Update_Item_Balance($row['Item_ID'], $row['Sub_Department_ID'], 'From External', null, null, $row['Supplier_ID'], $row['Grn_Purchase_Order_ID'], Get_Time_Now(),  $row['Quantity_Received']  , true);
         if(!$status){
            $has_error = true;  
         }  
    }else{
       echo 'No need to update '. $row['Grn_Purchase_Order_ID'].' <br/>'; 
        //get pre balance
        $slct_balance = mysqli_query($conn,"select Item_Balance from tbl_items_balance where Item_ID = '" . $row['Item_ID'] . "' and Sub_Department_ID = '" . $row['Sub_Department_ID'] . "'") or die(mysqli_error($conn));
        $slct_balance_num = mysqli_num_rows($slct_balance);
        if ($slct_balance_num > 0) {
            while ($sl = mysqli_fetch_array($slct_balance)) {
                $Pre_Balance = $sl['Item_Balance'];
            }
        } else {
            $qr = mysqli_query($conn,"insert into tbl_items_balance(Item_ID,Sub_Department_ID) values('" . $row['Item_ID'] . "','" . $row['Sub_Department_ID'] . "')") or die(mysqli_error($conn));
            $Pre_Balance = 0;


            if (!$qr) {
                $has_error = true;
            }
        }

        //update balance by adding items received
        $b_update = mysqli_query($conn,"update tbl_items_balance set Item_Balance = Item_Balance + '" . $row['Quantity_Received'] . "' where Item_ID = '" . $row['Item_ID'] . "' and Sub_Department_ID = '" . $row['Sub_Department_ID'] . "'") or die(mysqli_error($conn));
        if ($b_update) {
            //echo 'Item balance  updated <br/>';
            //insert data into tbl_stock_ledger_controler for auditing
            $insert_audit = mysqli_query($conn,"insert into tbl_stock_ledger_controler(
															Item_ID, Sub_Department_ID, Movement_Type, External_Source, 
															Pre_Balance, Post_Balance, Movement_Date, Movement_Date_Time, Document_Number)
														
														values('" . $row['Item_ID'] . "','" . $row['Sub_Department_ID'] . "','From External','" . $row['Supplier_ID'] . "',
															'$Pre_Balance',($Pre_Balance + " . $row['Quantity_Received'] . "),(select now()),(select now()),'" . $row['Grn_Purchase_Order_ID'] . "')") or die(mysqli_error($conn));

            if ($insert_audit) {
                echo 'Stockledger balance  updated <br/>';
                //update stock value details
                $get_items_details = mysqli_query($conn,"select Last_Buy_Price from tbl_items where Item_ID = '" . $row['Item_ID'] . "'") or die(mysqli_error($conn));
                $num_items_details = mysqli_num_rows($get_items_details);
                if ($num_items_details > 0) {
                    while ($dt_items = mysqli_fetch_array($get_items_details)) {
                        $Last_Buy_Price = $dt_items['Last_Buy_Price'];
                    }
                } else {
                    $Last_Buy_Price = 0;
                }

                //calculate stock value => formula ((QtyA * pA) + (QtyB * pB) / (QtyA + QtyB))
                $Last_P = ((($Pre_Balance * $Last_Buy_Price) + ($row['Quantity_Received'] * $row['Buying_Price'])) / ($Pre_Balance + $row['Quantity_Received']));

                //update Last_Buy_Price
                $update_previous_rec = mysqli_query($conn,"update tbl_items set Last_Buy_Price = '$Last_P' where Item_ID = '" . $row['Item_ID'] . "'") or die(mysqli_error($conn));
                if (!$update_previous_rec) {
                    $has_error = true;
                }
            } else {
                $has_error = true;
            }
            //insert the last buy price
            //$i_price = mysqli_query($conn,"update tbl_items set Last_Buy_Price = '$Buying_Price[$i]' where Item_ID = '".$row['Item_ID']."'") or die(mysqli_error($conn));
        } else {
            $has_error = true;
        }
    }
}

if (!$has_error) {
    Commit_Transaction();
    echo 'stock lerger updated successifully<br/>';
} else {
    Rollback_Transaction();
    echo 'Error updating stock lerger';
}

