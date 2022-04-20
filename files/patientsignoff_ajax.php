<?php

include("./includes/connection.php");

$wherPP = '';

if (isset($_GET['Patient_Payment_ID'])) {
    $wherPP = "Patient_Payment_ID = '".$_GET['Patient_Payment_ID']."'";
} else if (isset($_GET['dataInfos'])) {
     $pps=  explode('^$*^%$', $_GET['dataInfos']);
     $wherPP = "Patient_Payment_ID IN (".  implode(',', $pps).")";
} else {
    die('Error');
}


$delete_qr = "UPDATE tbl_patient_payment_item_list ppl SET Process_Status = 'signedoff',Signedoff_Date_And_Time =NOW() 
                  WHERE $wherPP AND
                  ppl.Patient_Direction IN ('Direct To Doctor','Direct To Doctor Via Nurse Station','Direct To Clinic','Direct To Clinic Via Nurse Station')
		";


//    $delete_qr = "UPDATE tbl_patient_payment_item_list SET Process_Status = 'signedoff',Signedoff_Date_And_Time = (SELECT NOW()) WHERE Patient_Payment_ID = $Patient_Payment_ID AND
//                (((Patient_Direction = 'Direct To Doctor' OR Patient_Direction='Direct To Doctor Via Nurse Station')
//						    AND (Consultant_ID = $Employee_ID )) OR
//						    ((Patient_Direction = 'Direct To Clinic' OR Patient_Direction='Direct To Clinic Via Nurse Station')
//						    AND (Consultant_ID IN (SELECT ce.Clinic_ID FROM tbl_clinic_employee ce WHERE ce.Employee_ID = $Employee_ID)))
//						    )";

if (mysqli_query($conn,$delete_qr)) {
    echo "signedoff";
} else {
    die(mysqli_error($conn));
}
?>