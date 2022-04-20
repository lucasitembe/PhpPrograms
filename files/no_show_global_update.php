<?php
include("./includes/connection.php");

$check_patient_if_signedoff = mysqli_query($conn,
        "SELECT ppl.Patient_Payment_Item_List_ID,ppl.Patient_Payment_ID,Patient_Direction,Consultant_ID,Clinic_ID,ppl.Process_Status FROM tbl_patient_payment_item_list ppl 
                                         WHERE
                                             ppl.Process_Status IN ('not served','no show','signedoff') AND
                                             ppl.Patient_Direction IN ('Direct To Doctor','Direct To Doctor Via Nurse Station','Direct To Clinic','Direct To Clinic Via Nurse Station') AND
                                             TIMESTAMPDIFF(DAY,Transaction_Date_And_Time,NOW()) >=3                              
") or die(mysqli_error($conn));

while ($row = mysqli_fetch_array($check_patient_if_signedoff)) {
    $Process_Status = $row['Process_Status'];
    $Patient_Payment_ID = $row['Patient_Payment_ID'];
    $ppil = $row['Patient_Payment_Item_List_ID'];

    if ($Process_Status == 'no show') {
        mysqli_query($conn,"UPDATE tbl_patient_payments SET Transaction_status='cancelled' WHERE Patient_Payment_ID = $Patient_Payment_ID") or die(mysqli_error($conn));
    }

    if ($Process_Status == 'not served') {
        mysqli_query($conn,"UPDATE tbl_patient_payment_item_list SET Process_Status='no show' WHERE Patient_Payment_Item_List_ID='$ppil'") or die(mysqli_error($conn));
        mysqli_query($conn,"UPDATE tbl_patient_payments SET Transaction_status='cancelled' WHERE Patient_Payment_ID = $Patient_Payment_ID") or die(mysqli_error($conn));
    }
    
     if ($Process_Status == 'signedoff') {
        mysqli_query($conn,"UPDATE tbl_patient_payment_item_list SET Process_Status='signedoff' WHERE Patient_Payment_ID = $Patient_Payment_ID") or die(mysqli_error($conn));
     }
}

echo 'Success';