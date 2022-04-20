<?php

include("./includes/connection.php");

$check_patient_if_signedoff = mysqli_query($conn,
        "SELECT ppl.Patient_Payment_Item_List_ID,ppl.Patient_Payment_ID,Patient_Direction,Consultant_ID,Clinic_ID,ppl.Process_Status FROM tbl_patient_payment_item_list ppl 
                                         WHERE
                                             ppl.Process_Status IN ('not served','served') AND
                                             ppl.Patient_Direction IN ('Direct To Doctor','Direct To Doctor Via Nurse Station','Direct To Clinic','Direct To Clinic Via Nurse Station') AND
                                             Transaction_Date_And_Time  <= '2016-03-07 23:59:59'                              
") or die(mysqli_error($conn));

while ($row = mysqli_fetch_array($check_patient_if_signedoff)) {
    $Process_Status = $row['Process_Status'];
    $ppil = $row['Patient_Payment_Item_List_ID'];
    $Patient_Payment_ID = $row['Patient_Payment_ID'];

    if ($Process_Status == 'not served') {
        mysqli_query($conn,"UPDATE tbl_patient_payment_item_list SET Process_Status='no show' WHERE Patient_Payment_Item_List_ID='$ppil'") or die(mysqli_error($conn));
        mysqli_query($conn,"UPDATE tbl_patient_payments SET Transaction_status='cancelled' WHERE Patient_Payment_ID = '$Patient_Payment_ID'") or die(mysqli_error($conn));
    } else if ($Process_Status == 'served') {
        $resultcons = mysqli_query($conn,"SELECT c.consultation_ID,ch.employee_ID FROM tbl_consultation c JOIN tbl_consultation_history ch ON ch.consultation_ID = c.consultation_ID  WHERE Patient_Payment_Item_List_ID='$ppil' AND Saved='yes'") or die(mysqli_error($conn));
        $consultati_row = mysqli_fetch_assoc($resultcons);
        $consultation_ID = $consultati_row['consultation_ID'];
        $employee_ID = $consultati_row['employee_ID'];
        $disease_consultation = mysqli_query($conn,"SELECT diagnosis_type,Disease_Consultation_ID FROM tbl_disease_consultation WHERE consultation_ID='$consultation_ID' AND employee_ID='$employee_ID' AND diagnosis_type='diagnosis'") or die(mysqli_error($conn));
        if (mysqli_num_rows($disease_consultation) > 0) {
            mysqli_query($conn,"UPDATE tbl_patient_payment_item_list SET Process_Status='signedoff' WHERE Patient_Payment_Item_List_ID='$ppil'") or die(mysqli_error($conn));
        } else {
            $disease_consultation2 = mysqli_query($conn,"SELECT diagnosis_type,Disease_Consultation_ID FROM tbl_disease_consultation WHERE consultation_ID='$consultation_ID' AND employee_ID='$employee_ID' AND diagnosis_type='provisional_diagnosis' ORDER BY Disease_Consultation_Date_And_Time DESC LIMIT 1") or die(mysqli_error($conn));

            $diseaseConsult = mysqli_fetch_assoc($disease_consultation2);
            $diagnosis_type = $diseaseConsult['diagnosis_type'];
            $Disease_Consultation_ID = $diseaseConsult['Disease_Consultation_ID'];
            if (mysqli_num_rows($disease_consultation2) > 0) {
                if ($diagnosis_type == 'diagnosis') {
                    mysqli_query($conn,"UPDATE tbl_patient_payment_item_list SET Process_Status='signedoff' WHERE Patient_Payment_Item_List_ID='$ppil'") or die(mysqli_error($conn));
                } else if ($diagnosis_type == 'provisional_diagnosis') {
                    mysqli_query($conn,"INSERT INTO tbl_disease_consultation (disease_ID, consultation_ID, employee_ID, diagnosis_type, Disease_Consultation_Date_And_Time) SELECT disease_ID, consultation_ID, employee_ID, 'diagnosis', NOW()  FROM tbl_disease_consultation WHERE consultation_ID='$consultation_ID' AND employee_ID='$employee_ID' AND diagnosis_type='provisional_diagnosis' AND Disease_Consultation_ID='$Disease_Consultation_ID' ") or die(mysqli_error($conn));
                    mysqli_query($conn,"UPDATE tbl_patient_payment_item_list SET Process_Status='signedoff' WHERE Patient_Payment_Item_List_ID='$ppil'") or die(mysqli_error($conn));
                }
            } else {
                if (mysqli_num_rows($resultcons) > 0) {
                    mysqli_query($conn,"INSERT INTO tbl_disease_consultation (disease_ID, consultation_ID, employee_ID, diagnosis_type, Disease_Consultation_Date_And_Time) VALUES ('86', '$consultation_ID', '$employee_ID', 'diagnosis', NOW())") or die(mysqli_error($conn));
                    mysqli_query($conn,"UPDATE tbl_patient_payment_item_list SET Process_Status='signedoff' WHERE Patient_Payment_ID = '$Patient_Payment_ID'") or die(mysqli_error($conn));
                }
            }
        }
    }
}

echo 'suucess';
