<?php session_start();
include ("./includes/connection.php");
if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
}
if (isset($_GET['Admision_ID'])) {
    $Admision_ID = $_GET['Admision_ID'];
}
if (isset($_GET['Patient_Bill_ID'])) {
    $Patient_Bill_ID = $_GET['Patient_Bill_ID'];
}
$Discharge_Employee_ID = $_SESSION['userinfo']['Employee_ID'];
$Discharge_Supervisor_ID = $Discharge_Employee_ID;
$Admission_Status = 'Discharged';
$credit_bill_trans = "";
$cash_bill_trans = "";

$sql_check_credit_bill_result = mysqli_query($conn, "SELECT Billing_Type FROM tbl_patient_payments WHERE (Billing_Type = 'Outpatient Credit' or Billing_Type = 'Inpatient Credit') AND Patient_Bill_ID='$Patient_Bill_ID' ORDER BY Patient_Payment_ID DESC LIMIT 1") or die(mysqli_error($conn));
if (mysqli_num_rows($sql_check_credit_bill_result) > 0) {
    $credit_bill_trans = "ipo";
}
$sql_check_cash_bill_result = mysqli_query($conn, "SELECT Billing_Type FROM tbl_patient_payments WHERE (Billing_Type = 'Outpatient Cash' or Billing_Type = 'Inpatient Cash') AND Patient_Bill_ID='$Patient_Bill_ID'") or die(mysqli_error($conn));
if (mysqli_num_rows($sql_check_cash_bill_result) > 0) {
    $cash_bill_trans = "ipo";
}

if ($cash_bill_trans == "ipo" && $credit_bill_trans == "ipo") {
    $sql_check_if_bill_creared_result = mysqli_query($conn, "SELECT Admision_ID FROM tbl_admission WHERE Admision_ID='$Admision_ID' AND (Cash_Bill_Status='cleared' AND Credit_Bill_Status='cleared')") or die(mysqli_error($conn));
} else {
    $sql_check_if_bill_creared_result = mysqli_query($conn, "SELECT Admision_ID FROM tbl_admission WHERE Admision_ID='$Admision_ID' AND (Cash_Bill_Status='cleared' OR Credit_Bill_Status='cleared')") or die(mysqli_error($conn));
}
if (mysqli_num_rows($sql_check_if_bill_creared_result) > 0) {
    $update_query = "UPDATE tbl_admission SET Discharge_Employee_ID='$Discharge_Employee_ID',
    Discharge_Supervisor_ID='$Discharge_Supervisor_ID',Discharge_Date_Time=(SELECT NOW()),
    Admission_Status='$Admission_Status', Discharge_Clearance_Status='cleared', Clearance_Date_Time=NOW() WHERE Admision_ID = $Admision_ID AND Registration_ID='$Registration_ID'";
    $update_result = mysqli_query($conn, $update_query) or die(mysqli_error($conn));
    if ($update_result) {
        echo "Discharged";
    } else {
        echo "fail";
    }
} else {
    echo "pending";
};
