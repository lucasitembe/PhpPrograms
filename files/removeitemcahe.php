<?php

include("./includes/connection.php");
include_once("./functions/items.php");

if (isset($_GET['Payment_Item_Cache_List_ID'])) {
    $Payment_Item_Cache_List_ID = $_GET['Payment_Item_Cache_List_ID'];
} else {
    $Payment_Item_Cache_List_ID = 0;
}

$checkforupdate = mysqli_query($conn,"SELECT Status,Check_In_Type,Item_ID,Discount,Patient_Payment_ID FROM tbl_item_list_cache il WHERE Payment_Item_Cache_List_ID='$Payment_Item_Cache_List_ID' AND Status='active' AND Patient_Payment_ID IS NOT NULL") or die(mysqli_error($conn));

$_SESSION['HAS_ERROR'] = false;
Start_Transaction();

if (mysqli_num_rows($checkforupdate) > 0) {
    $rowup = mysqli_fetch_array($checkforupdate);

    $Item_ID = $rowup['Item_ID'];
    $Check_In_Type = $rowup['Check_In_Type'];
    $Patient_Payment_ID = $rowup['Patient_Payment_ID'];

    $isBillRemoved = mysqli_query($conn,"DELETE FROM tbl_patient_payment_item_list WHERE Item_ID='$Item_ID' AND Check_In_Type='$Check_In_Type' AND Patient_Payment_ID='$Patient_Payment_ID' ") or die(mysqli_error($conn));

    if (!$isBillRemoved) {
        $_SESSION['HAS_ERROR'] = true;
    }
}

$delete_qr = "DELETE FROM tbl_item_list_cache WHERE Payment_Item_Cache_List_ID = $Payment_Item_Cache_List_ID";
$delete = mysqli_query($conn,$delete_qr) or die(mysqli_error($conn));

if (!$delete) {
    $_SESSION['HAS_ERROR'] = true;
}

if (!$_SESSION['HAS_ERROR']) {
    Commit_Transaction();
    echo "removed";
} else {
    Rollback_Transaction();
}
?> 