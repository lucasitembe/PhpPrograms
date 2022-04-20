<?php

include("./includes/connection.php");
session_start();

if (isset($_GET['Patient_Payment_ID'])) {
    $Patient_Payment_ID = $_GET['Patient_Payment_ID'];
    $status = $_GET['status'];
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
}

$delete_qr = "UPDATE tbl_patient_payment_item_list SET Process_Status = '$status' WHERE Patient_Payment_ID = $Patient_Payment_ID ";
if (!mysqli_query($conn,$delete_qr)) {
    die(mysqli_error($conn));
} else {
    if ($status == 'not served') {
        mysqli_query($conn,"UPDATE tbl_patient_payments SET Transaction_status='active' WHERE Patient_Payment_ID = $Patient_Payment_ID") or die(mysqli_error($conn));
    }
    echo "changed";
}
?>