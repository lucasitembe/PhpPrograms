<?php

@session_start();
include("./includes/connection.php");
require_once './functions/items.php';

//get employee id
if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
} else {
    $Employee_ID = 0;
}

if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
} else {
    $Registration_ID = 0;
}

//for offline payment
//'$manual_offline','$auth_code','$terminal_id'
if(isset($_GET['auth_code'])){
    $auth_code=$_GET['auth_code'];
}else{
   $auth_code=""; 
}
if(isset($_GET['terminal_id'])){
    $terminal_id=$_GET['terminal_id'];
}else{
   $terminal_id=""; 
}
$manual_offline=$_GET['manual_offline'];
//get branch id
if (isset($_SESSION['userinfo']['Branch_ID'])) {
    $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
} else {
    $Branch_ID = '';
}

if (isset($_SESSION['supervisor']['Employee_ID'])) {
    $Supervisor_ID = $_SESSION['supervisor']['Employee_ID'];
} else {
    $Supervisor_ID = 0;
}

if (isset($_GET['Pre_Paid_Transaction'])) {
    $Pre_Paid_Transaction = $_GET['Pre_Paid_Transaction'];
} else {
    $Pre_Paid_Transaction = '';
}
if($Pre_Paid_Transaction=="yes"){
   include "create_out_patient_bill.php"; 
}

//delete all records which are not related whith selected patient but prepared by the current employee
mysqli_query($conn,"delete from tbl_reception_items_list_cache where Employee_id = '$Employee_ID' and Registration_ID <> '$Registration_ID'") or die(mysqli_error($conn));
$Patient_Direction = '';
//Get Claim Form Number
$select_Claim = mysqli_query($conn,"select Claim_Form_Number,Patient_Direction, Fast_Track from tbl_reception_items_list_cache where
                                Employee_ID = '$Employee_ID' and
                                    Registration_ID = '$Registration_ID' limit 1") or die(mysqli_error($conn));

while ($row = mysqli_fetch_array($select_Claim)) {
    $Claim_Form_Number = $row['Claim_Form_Number'];
    $Patient_Direction = $row['Patient_Direction'];
    $Fast_Track = $row['Fast_Track'];
}

$HAS_ERROR = false;
Start_Transaction();


include("./includes/Folio_Number_Generator.php");

//get patient details(sponsor id & sponsor name)
$select_Deatils = mysqli_query($conn,"select pr.Sponsor_ID, Guarantor_Name as Sponsor_Name from tbl_patient_registration pr, tbl_sponsor sp where
                                    sp.Sponsor_ID = pr.Sponsor_ID and
                                        Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
while ($row = mysqli_fetch_array($select_Deatils)) {
    $Sponsor_ID = $row['Sponsor_ID'];
    $Sponsor_Name = $row['Sponsor_Name'];
}
$Payment_Date_And_Time = '(SELECT NOW())';
$Billing_Type = 'Outpatient Cash';
$Receipt_Date = '(SELECT NOW())';
$Transaction_status = 'submitted';

if (isset($_GET['Pre_Paid_Transaction']) && strtolower($Pre_Paid_Transaction) == 'yes') {
    $Pre_Paid = 1;
    //get Patient_Bill_ID
    $slct = mysqli_query($conn,"select Patient_Bill_ID from tbl_prepaid_details where Registration_ID = '$Registration_ID' and Status = 'pending' order by Prepaid_ID desc limit 1") or die(mysqli_error($conn));
    $nm = mysqli_num_rows($slct);
    if ($nm > 0) {
        while ($dt = mysqli_fetch_array($slct)) {
            $Patient_Bill_ID = $dt['Patient_Bill_ID'];
        }
    } else {
        include("./includes/Get_Patient_Transaction_Number.php");
    }
} else {
    $Pre_Paid = 0;
    include("./includes/Get_Patient_Transaction_Number.php");
}

//insert data into tbl_patient_payments
$insert_patient_qr = "INSERT INTO tbl_patient_payments(
                          Registration_ID, Employee_ID, Supervisor_ID, Claim_Form_Number,
                          Payment_Date_And_Time,Sponsor_ID, Sponsor_Name, Folio_Number,Check_In_ID,
                          Billing_Type, Receipt_Date, Branch_ID,Patient_Bill_ID,Fast_Track,Pre_Paid,manual_offline,auth_code,terminal_id)
                          
                          VALUES('$Registration_ID','$Employee_ID','$Supervisor_ID','$Claim_Form_Number',
                          $Payment_Date_And_Time,'$Sponsor_ID','$Sponsor_Name','$Folio_Number','$Check_In_ID',
                          '$Billing_Type',$Receipt_Date,'$Branch_ID','$Patient_Bill_ID','$Fast_Track','$Pre_Paid','$manual_offline','$auth_code','$terminal_id')";
if (!mysqli_query($conn,$insert_patient_qr)) {
    $HAS_ERROR = true;
    die(mysqli_error($conn));
} else {
    //get last insert id
    $id_result = mysqli_query($conn,"SELECT Patient_Payment_ID FROM tbl_patient_payments
                                    where Employee_ID = '$Employee_ID' and
                                        Registration_ID = '$Registration_ID' order by Patient_Payment_ID desc LIMIT 1") or die(mysqli_error($conn));
    $num_rows = mysqli_num_rows($id_result);
    if ($num_rows > 0) {
        while ($row = mysqli_fetch_array($id_result)) {
            $Patient_Payment_ID = $row['Patient_Payment_ID'];
        }
    } else {
        $Patient_Payment_ID = 0;
    }

    if ($Patient_Payment_ID != 0) {

        if ($Patient_Direction == 'Direct To Clinic' || $Patient_Direction == 'Direct To Clinic Via Nurse Station') {
            $sql_send = mysqli_query($conn,"insert into tbl_patient_payment_item_list(
                        Patient_Payment_ID, Check_In_Type, Item_ID,
                            Discount, Price, Quantity,
                                Patient_Direction, Consultant, Clinic_ID,
                                Transaction_Date_And_Time,finance_department_id,Sub_Department_ID)
                        
                        SELECT $Patient_Payment_ID, Check_In_Type, Item_ID,
                            Discount, Price, Quantity,
                                Patient_Direction, Consultant, Consultant_ID, (select now()),finance_department_id,clinic_location_id from tbl_reception_items_list_cache
                                    where Employee_ID = '$Employee_ID' and Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
        } else {
            $sql_send = mysqli_query($conn,"insert into tbl_patient_payment_item_list(
                        Patient_Payment_ID, Check_In_Type, Item_ID,
                            Discount, Price, Quantity,
                                Patient_Direction, Consultant, Consultant_ID,
                                Transaction_Date_And_Time,Clinic_ID,finance_department_id,Sub_Department_ID)
                        
                        SELECT $Patient_Payment_ID, Check_In_Type, Item_ID,
                            Discount, Price, Quantity,
                                Patient_Direction, Consultant, Consultant_ID, (select now()),Clinic_ID,finance_department_id,clinic_location_id from tbl_reception_items_list_cache
                                    where Employee_ID = '$Employee_ID' and Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
        }

        if (!$sql_send) {
            $HAS_ERROR = true;
        }
    }
}

//$payDetails = getPaymentsDetailsByReceiptNumber($Patient_Payment_ID);
//
//$Product_Array = array();
//
//$Product_Name_Array = array(
//    'ref_no' => $Patient_Payment_ID,
//    'source_name' => 'ehms_sales_cash',
//    'comment' => 'Receipt # ' . $Patient_Payment_ID . ", Date:" . $payDetails['Payment_Date_And_Time'] . ", Trans_Type:" . $payDetails['Payment_Mode'] . " Amount:  " . $payDetails['TotalAmount'] . " Tsh.",
//    'debit_entry_ledger' => 'CASH IN HAND',
//    'credit_entry_ledger' => 'SALES',
//    'sub_total' => $payDetails['TotalAmount'],
//    'source_id' => $Patient_Payment_ID,
//    'Employee_Name' => $_SESSION['userinfo']['Employee_Name'],
//    'Employee_ID' => $_SESSION['userinfo']['Employee_ID']
//);
//
//array_push($Product_Array, $Product_Name_Array);
//$endata = json_encode($Product_Array);
//$acc = gAccJournalEntry($endata);
////    echo $acc;
////    exit();
//if ($acc != "success") {
//    $HAS_ERROR = true;
//}
$sq1 = mysqli_query($conn,"insert into tbl_check_in_details(Registration_ID,Check_In_ID,Folio_Number,Sponsor_ID)
                        values('$Registration_ID','$Check_In_ID','$Folio_Number','$Sponsor_ID')") or die(mysqli_error($conn));
$sq2 = mysqli_query($conn,"delete from tbl_reception_items_list_cache where Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
$sq3 = mysqli_query($conn,"update tbl_check_in set Check_In_Status = 'saved' where registration_id = '$Registration_ID' and branch_id = '$Branch_ID'");

if (!$sq1)
    $HAS_ERROR = true;
if (!$sq2)
    $HAS_ERROR = true;
if (!$sq3)
    $HAS_ERROR = true;

if (!$HAS_ERROR) {
    Commit_Transaction();
    header("Location: ./receptionpatientbillingreview.php?Registration_ID=$Registration_ID&Patient_Payment_ID=$Patient_Payment_ID&PatientBilling=PatientBillingThisPage");
} else {
    Rollback_Transaction();
    echo $acc;
   // header("Location: ./patientbillingreception.php?Registration_ID=$Registration_ID&NR=True&PatientBillingReception=PatientBillingReceptionThisForm");
}
?>