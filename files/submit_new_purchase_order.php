<?php

@session_start();
include("./includes/connection.php");
include_once("./functions/items.php");

if (isset($_SESSION['Purchase_Order_ID'])) {
    unset($_SESSION['Purchase_Order_ID']);
}
//get employee id
if (isset($_SESSION['userinfo'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
} else {
    $Employee_ID = 0;
}

//get branch id
if (isset($_SESSION['userinfo'])) {
    $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
} else {
    $Branch_ID = 0;
}


if (isset($_POST['Store_Need'])) {
    $Store_Need = $_POST['Store_Need'];
} else {
    $Store_Need = 0;
}

if (isset($_POST['Supplier_ID'])) {
    $Supplier_ID = $_POST['Supplier_ID'];
} else {
    $Supplier_ID = 0;
}


if (isset($_POST['Order_Description'])) {
    $Order_Description = $_POST['Order_Description'];
} else {
    $Order_Description = '';
}

if (isset($_POST['Purchase_Order_ID'])) {
    $PurchaseOrderID = $_POST['Purchase_Order_ID'];
} else {
    $PurchaseOrderID = 0;
}
//echo "select Purchase_Order_ID from tbl_purchase_order
//                                    where Employee_ID = '$Employee_ID' and
//                                        Supplier_ID = '$Supplier_ID' order by Purchase_Order_ID desc limit 1";exit;
//print_r($_POST);exit;
//$PurchaseOrderID 

$_SESSION['HAS_ERROR'] = false;
Start_Transaction();

//get approval setup value
$get_setup = mysqli_query($conn,"select Approval_Levels from tbl_system_configuration") or die(mysqli_error($conn));
$num = mysqli_num_rows($get_setup);
if ($num > 0) {
    while ($data = mysqli_fetch_array($get_setup)) {
        $Approval_Levels = $data['Approval_Levels'];
    }
} else {
    $Approval_Levels = 2;
}

if ($Store_Need != 0 && $Store_Need != null && $Supplier_ID != '' && $Supplier_ID != null) {
    //get store order
    
    $get_store_rder = mysqli_query($conn,"select Store_Order_ID from tbl_purchase_order WHERE Purchase_Order_ID = '$PurchaseOrderID' ") or die(mysqli_error($conn));
    $Store_Order_ID = mysqli_fetch_assoc($get_store_rder)['Store_Order_ID'];
    
    $insert1 = mysqli_query($conn,"insert into tbl_purchase_order(
                                Order_Description, Created_Date, Sent_Date,
                                Sub_Department_ID, Supplier_ID, Employee_ID,
                                Order_Status, Branch_ID, Approvals,Store_Order_ID)
                                
                            VALUES ('$Order_Description',(select now()),(select now()),
                                '$Store_Need','$Supplier_ID','$Employee_ID',
                                'submitted','$Branch_ID','$Approval_Levels','$Store_Order_ID')") or die(mysqli_error($conn));
    if ($insert1) {
        //get the last Purchase_Order_ID
        $select = mysqli_query($conn,"select Purchase_Order_ID from tbl_purchase_order
                                    where Employee_ID = '$Employee_ID' and
                                        Supplier_ID = '$Supplier_ID' order by Purchase_Order_ID desc limit 1") or die(mysqli_error($conn));
        $num_row = mysqli_num_rows($select);
        if ($num_row > 0) {
            while ($data = mysqli_fetch_array($select)) {
                $Purchase_Order_ID = $data['Purchase_Order_ID'];
            }
        } else {
            $Purchase_Order_ID = 0;
        }
    } else {
        $_SESSION['HAS_ERROR'] = true;
    }
}


if ($Purchase_Order_ID != 0) {
    //process all items with price & quantity
    $select_order_items = mysqli_query($conn,"SELECT poi.Order_Item_ID,poi.Item_ID, poi.Quantity_Required, poi.Price,
                                              poi.Containers_Required, poi.Items_Per_Container_Required,poi.Quantity_Received, poi.Item_ID, poi.Buying_Price,(poi.Quantity_Required-poi.Quantity_Received) AS OutStanding
									   FROM  tbl_purchase_order_items poi
									   WHERE 
                                                                            LOWER(poi.Grn_Status) = 'outstanding' AND 
								            poi.Purchase_Order_ID = '$PurchaseOrderID' ") or die(mysqli_error($conn));

    $no = mysqli_num_rows($select);
    if ($no > 0) {
        $Store_Order_ID = 0;
       
        while ($row = mysqli_fetch_array($select_order_items)) {
            $Order_Item_ID = $row['Order_Item_ID'];
            $Quantity_Required = $row['OutStanding'];
            $Item_ID = $row['Item_ID'];
            $Buying_Price = $row['Buying_Price'];
            $Containers_Required = $row['Containers_Required'];
            $Items_Per_Container_Required = $row['OutStanding'];

            //insert data from cache table
            $insert2 = mysqli_query($conn,"INSERT INTO tbl_purchase_order_items(
                                        Purchase_Order_ID, Quantity_Required, Buying_Price,
                                        Item_ID, Containers_Required, Items_Per_Container_Required)
                                        
                                        VALUES ('$Purchase_Order_ID','$Quantity_Required','$Buying_Price',
                                        '$Item_ID','$Containers_Required','$Items_Per_Container_Required')") or die(mysqli_error($conn));
 
            if (!$insert2) {
                $_SESSION['HAS_ERROR'] = true;
            } else {
                $upOrdered = mysqli_query($conn,"UPDATE tbl_purchase_order_items poi SET Grn_Status = 'REORDERED' WHERE Order_Item_ID = '$Order_Item_ID'") or die(mysqli_error($conn));

                if (!$upOrdered) {
                    $_SESSION['HAS_ERROR'] = true;
                }
            }
        }
    } else {
       $_SESSION['HAS_ERROR'] = true;
    }
} else {
    $_SESSION['HAS_ERROR'] = true;
}

if (!$_SESSION['HAS_ERROR']) {
    Commit_Transaction();
    echo json_encode(array('status' => 1,'data' =>$Purchase_Order_ID));
} else {
    Rollback_Transaction();
    echo json_encode(array('status' => 0,'data' => ''));
}
?>