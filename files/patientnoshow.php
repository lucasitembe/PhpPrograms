<?php

include './functions/database.php';
include("./includes/connection.php");
session_start();

if (isset($_GET['Patient_Payment_Item_List_ID'])) {
    $Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
} else {
    $Patient_Payment_Item_List_ID = 0;
    $Employee_ID = 0;
}

Start_Transaction();

$subQr = "(SELECT pil.Patient_Payment_ID FROM tbl_patient_payment_item_list pil WHERE pil.Patient_Payment_Item_List_ID = $Patient_Payment_Item_List_ID)";
$result = mysqli_query($conn,$subQr);
$row = mysqli_fetch_assoc($result);
$Patient_Payment_ID = $row['Patient_Payment_ID'];
$delete_qr = "UPDATE tbl_patient_payment_item_list SET Process_Status = 'no show' WHERE Patient_Payment_ID = $Patient_Payment_ID AND
                (((Patient_Direction = 'Direct To Doctor' OR Patient_Direction='Direct To Doctor Via Nurse Station')
						    AND (Consultant_ID = $Employee_ID )) OR
						    ((Patient_Direction = 'Direct To Clinic' OR Patient_Direction='Direct To Clinic Via Nurse Station')
						    AND (Consultant_ID IN (SELECT ce.Clinic_ID FROM tbl_clinic_employee ce WHERE ce.Employee_ID = $Employee_ID)))
						    )";
$pplUpdate = mysqli_query($conn,$delete_qr) or die(mysqli_error($conn));

if ($pplUpdate) {
    $ppUpdate = mysqli_query($conn,"UPDATE tbl_patient_payments pp SET Transaction_status='cancelled' WHERE Patient_Payment_ID = $Patient_Payment_ID AND pp.Sponsor_ID != (SELECT Sponsor_ID FROM tbl_sponsor WHERE LOWER(Guarantor_Name) = 'cash')") or die(mysqli_error($conn));

    if ($ppUpdate) {
        Commit_Transaction();
        echo "removed";
    } else {
        Rollback_Transaction();
        echo "notremoved";
    }
} else {
    Rollback_Transaction();
    echo "notremoved";
}
?>