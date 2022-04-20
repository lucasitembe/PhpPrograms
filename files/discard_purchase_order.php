<?php

@session_start();
include("./includes/connection.php");
$temp = 1;
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

if (isset($_GET['Order_Item_ID'])) {
    $Order_Item_ID = $_GET['Order_Item_ID'];
} else {
    $Order_Item_ID = '';
}

if (isset($_GET['Purchase_Order_ID'])) {
    $Purchase_Order_ID = $_GET['Purchase_Order_ID'];
} else {
    $Purchase_Order_ID = '';
}
?>

<?php

//type=order& 
if (isset($_GET['type']) && $_GET['type'] == 'order') {
    $slck = mysqli_query($conn,"UPDATE  tbl_purchase_order_items SET Grn_Status='DISCARD' WHERE Purchase_Order_ID = '$Purchase_Order_ID'") or die(mysqli_error($conn));    
} if (isset($_GET['type']) && $_GET['type'] == 'single') {
    //get requisition id & item id
    $slck = mysqli_query($conn,"UPDATE  tbl_purchase_order_items SET Grn_Status='DISCARD' WHERE Order_Item_ID = '$Order_Item_ID'") or die(mysqli_error($conn));
    $nm = mysqli_num_rows($slck);
}
        