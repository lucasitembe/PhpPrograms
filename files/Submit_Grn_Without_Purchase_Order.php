<?php

session_start();
include("./includes/connection.php");
include_once("./functions/items.php");

if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
} else {
    $Employee_ID = 0;
}
$Control = 'no';

if (isset($_GET['Supervisor_Name'])) {
    $Employee_Name = $_GET['Supervisor_Name'];
} else {
    $Employee_Name = '';
}

// lpo_number
if (isset($_GET['lpo'])) {
    $lpo = $_GET['lpo'];
} else {
    $lpo = '';
}

if (isset($_GET['Supervisor_Password'])) {
    $Supervisor_Password = md5($_GET['Supervisor_Password']);
} else {
    $Supervisor_Password = '';
}

if (isset($_GET['Supervisor_Comment'])) {
    $Supervisor_Comment = $_GET['Supervisor_Comment'];
} else {
    $Supervisor_Comment = '';
}

if (isset($_GET['Sub_Department_ID'])) {
    $Sub_Department_ID = $_GET['Sub_Department_ID'];
} else {
    $Sub_Department_ID = '';
}

if (isset($_GET['Supplier_ID'])) {
    $Supplier_ID = $_GET['Supplier_ID'];
} else {
    $Supplier_ID = '';
}

if (isset($_GET['Debit_Note_Number'])) {
    $Debit_Note_Number = $_GET['Debit_Note_Number'];
} else {
    $Debit_Note_Number = '';
}

if (isset($_GET['Invoice_Number'])) {
    $Invoice_Number = $_GET['Invoice_Number'];
} else {
    $Invoice_Number = '';
}

if (isset($_GET['RV_Number'])) {
    $RV_Number = $_GET['RV_Number'];
} else {
    $RV_Number = '';
}
if (isset($_GET['grn_without_id'])) {
    $grn_without_id = $_GET['grn_without_id'];
} else {
    $grn_without_id = '';
}
if (isset($_GET['batch_no'])) {
    $batch_no = $_GET['batch_no'];
} else {
    $batch_no = '';
}


if (isset($_GET['Delivery_Date'])) {
    $Delivery_Date = $_GET['Delivery_Date'];
} else {
    $Delivery_Date = '';
}

$HAS_ERROR = false;
Start_Transaction();

//get supervisor id
$select = mysqli_query($conn,"select Employee_ID from tbl_privileges where Given_Username = '$Employee_Name' and Given_Password = '$Supervisor_Password'") or die(mysqli_error($conn));

$no = mysqli_num_rows($select);
if ($no > 0) {
    while ($data = mysqli_fetch_array($select)) {
        $Supervisor_ID = $data['Employee_ID'];
    }
} else {
    $Supervisor_ID = 0;
}

$select = mysqli_query($conn,"SELECT * from tbl_grn_without_purchase_order_approval_cache_items where grn_without_id='$grn_without_id'") or die(mysqli_error($conn));
$num = mysqli_num_rows($select);
if ($num > 0) {
    $insert = mysqli_query($conn,"INSERT into tbl_grn_without_purchase_order(
								Grn_Date_And_Time,
								Sub_Department_ID, Supplier_ID,
								Debit_Note_Number, Invoice_Number, Delivery_Date,RV_Number,lpo_number,grn_without_id)
								values((select now()),
								'$Sub_Department_ID','$Supplier_ID',
								'$Debit_Note_Number', '$Invoice_Number', '$Delivery_Date','$RV_Number','$lpo','$grn_without_id')") or die(mysqli_error($conn));
    if (!$insert) {
        $HAS_ERROR = true;
    }

    //get the last GRN_ID
    $get_grn_id = mysqli_query($conn,"select Grn_ID from tbl_grn_without_purchase_order where
									grn_without_id='$grn_without_id'
									order by Grn_ID desc limit 1") or die(mysqli_error($conn));
    $no = mysqli_num_rows($get_grn_id);
    if ($no > 0) {
        while ($row = mysqli_fetch_array($get_grn_id)) {
            $Grn_ID = $row['Grn_ID'];
        }
    } else {
        $Grn_ID = 0;
    }


    if ($Grn_ID > 0) {
        //insert items from cache table
        while ($data = mysqli_fetch_array($select)) {
            $Quantity_Required = str_replace(',', '', $data['Quantity_Required']);
            $Item_ID = $data['Item_ID'];
            $Container_Qty = str_replace(',', '', $data['Container_Qty']);
            $Items_Per_Container = str_replace(',', '', $data['Items_Per_Container']);
            $Price = str_replace(',', '', $data['Price']);
            $Expire_Date = $data['Expire_Date'];
            $rejected = $data['rejected'];
            $batch_no = $data['batch_no'];
            $insert_item = mysqli_query($conn,"INSERT into tbl_grn_without_purchase_order_items(
												Grn_ID, Quantity_Required, Container_Qty,
												Items_Per_Container, rejected,Price, Expire_Date, Item_ID,batch_no)

												values('$Grn_ID','$Quantity_Required','$Container_Qty',
												'$Items_Per_Container','$rejected','$Price','$Expire_Date','$Item_ID','$batch_no')") or die(mysqli_error($conn));
            if (!$insert_item) {
                $HAS_ERROR = true;
            }

            //get pre balance
            $slct_balance = mysqli_query($conn,"select Item_Balance from tbl_items_balance where Item_ID = '$Item_ID' and Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
            $slct_balance_num = mysqli_num_rows($slct_balance);
            if ($slct_balance_num > 0) {
                while ($sl = mysqli_fetch_array($slct_balance)) {
                    $Pre_Balance = $sl['Item_Balance'];
                }
            } else {
                $insertbalc = mysqli_query($conn,"insert into tbl_items_balance(Item_ID,Sub_Department_ID) values('$Item_ID','$Sub_Department_ID')") or die(mysqli_error($conn));
                $Pre_Balance = 0;

                if (!$insertbalc) {
                    $HAS_ERROR = true;
                }
            }
            //update item balance
            $b_update = mysqli_query($conn,"update tbl_items_balance set Item_Balance = (Item_Balance + $Quantity_Required) where Item_ID = '$Item_ID' and Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
            if (!$b_update) {
                $HAS_ERROR = true;
            }

            //insert data into tbl_stock_ledger_controler for auditing
            $insert_audit = mysqli_query($conn,"insert into tbl_stock_ledger_controler(
															Item_ID, Sub_Department_ID, Movement_Type, External_Source,
															Pre_Balance, Post_Balance, Movement_Date, Movement_Date_Time, Document_Number)

														values($Item_ID,$Sub_Department_ID,'Without Purchase',$Supplier_ID,
															'$Pre_Balance',($Pre_Balance + $Quantity_Required),(select now()),(select now()),$Grn_ID)") or die(mysqli_error($conn).' nimefika');


            if ($insert_audit) {
                //update stock value details
//                $get_items_details = mysqli_query($conn,"select Last_Buy_Price from tbl_items where Item_ID = '$Item_ID'") or die(mysqli_error($conn));
//                $num_items_details = mysqli_num_rows($get_items_details);
//                if ($num_items_details > 0) {
//                    while ($dt_items = mysqli_fetch_array($get_items_details)) {
//                        $Last_Buy_Price = $dt_items['Last_Buy_Price'];
//                    }
//                } else {
//                    $Last_Buy_Price = 0;
//                }
//
//                //calculate stock value => formula ((QtyA * pA) + (QtyB * pB) / (QtyA + QtyB))
//                $Last_P = ((($Pre_Balance * $Last_Buy_Price) + ($Quantity_Required * $Price)) / ($Pre_Balance + $Quantity_Required));
//
//                //update Last_Buy_Price
//                $update_previous_rec = mysqli_query($conn,"update tbl_items set Last_Buy_Price = '$Last_P' where Item_ID = '$Item_ID'") or die(mysqli_error($conn));
//
//                if (!$update_previous_rec) {
//                    $HAS_ERROR = true;
//                }
                 $update_last_buying_price = "UPDATE tbl_items SET Last_Buy_Price='" . str_replace(',', '', $Price) . "' WHERE Item_ID='$Item_ID'";
                    $result23 = mysqli_query($conn,$update_last_buying_price) or die(mysqli_error($conn));
                    if (!$result23) {
                        $HAS_ERROR = true;
                    }
            } else {
                $HAS_ERROR = true;
            }
        }
    }
}


if (!$HAS_ERROR) {

    Commit_Transaction();
    mysqli_query($conn,"delete from tbl_grn_without_purchase_order_approval_cache where grn_without_id='$grn_without_id'") or die(mysqli_error($conn));
    $_SESSION['Grn_ID'] = $Grn_ID;
    header("Location: ./previewgrnwithoutpurchaseorder.php?PreviewGrnWithoutPurchaseOrder=PreviewGrnWithoutPurchaseOrderThisPage");
} else {
    Rollback_Transaction();
    echo 'no';
}
?>
