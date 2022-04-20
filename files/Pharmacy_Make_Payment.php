<?php

@session_start();
include("./includes/connection.php");
include_once("./functions/items.php");


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



//get employee id
if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
} else {
    $Employee_ID = 0;
}

//get branch id
if (isset($_SESSION['userinfo']['Branch_ID'])) {
    $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
} else {
    $Branch_ID = 0;
}

//get supervisor id
if (isset($_SESSION['Pharmacy_Supervisor'])) {
    $Supervisor_ID = $_SESSION['Pharmacy_Supervisor']['Employee_ID'];
} else {
    $Supervisor_ID = 0;
}

    //get registration id
    if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID'];
    }else{
        $Registration_ID = 0;
    }
    
    //get claim form number
    if(isset($_GET['Claim_Form_Number'])){
        $Claim_Form_Number = $_GET['Claim_Form_Number'];
    }else{
        $Claim_Form_Number = 0;
    }
    
    //get Consultant_ID
    if(isset($_GET['Consultant_ID'])){
        $Consultant_ID = $_GET['Consultant_ID'];
    }else{
        $Consultant_ID = 0;
    }
    
    //get Folio Number
    if(isset($_GET['Folio_Number'])){
        $Folio_Number = $_GET['Folio_Number'];
    }else{
        $Folio_Number = 0;
    }
    
    if(isset($_SESSION['Pharmacy_ID'])){
        $Sub_Department_ID = $_SESSION['Pharmacy_ID'];
    }else{
        $Sub_Department_ID = 0;
    }

    include("./includes/Get_Patient_Check_In_Id.php");

if ($Employee_ID != 0 && $Registration_ID != 0 && $Branch_ID != 0 && $Sub_Department_ID != null && $Sub_Department_ID != 0) {
    //select all data from the pharmacy cache table
    $HAS_ERROR = false;
    Start_Transaction();
        $select = mysqli_query($conn,"select * from tbl_pharmacy_items_list_cache where Registration_ID = '$Registration_ID' and
                                Employee_ID = '$Employee_ID' ") or die(mysqli_error($conn));
        $num_rows = mysqli_num_rows($select);
        if($num_rows > 0){
            while($data = mysqli_fetch_array($select)){
                //$Folio_Number = $data['Folio_Number'];
                $Sponsor_ID = $data['Sponsor_ID'];
                $Sponsor_Name = $data['Sponsor_Name'];
                $Billing_Type = $data['Billing_Type'];
                //$Claim_Form_Number = $data['Claim_Form_Number'];
                $Sponsor_Name = $data['Sponsor_Name'];
                $Fast_Track = $data['Fast_Track'];
            }
            
            //generate transaction type
            if(strtolower($Billing_Type) == 'outpatient cash' || strtolower($Billing_Type) == 'inpatient cash'){
                $Transaction_Type = 'Cash';
            }else if(strtolower($Billing_Type) == 'outpatient credit' || strtolower($Billing_Type) == 'inpatient credit'){
                $Transaction_Type = 'Credit';
            }
            
            //check if patient has pending pre paid bill
            $slct = mysqli_query($conn,"select Patient_Bill_ID from tbl_prepaid_details where Registration_ID = '$Registration_ID' and Status = 'pending' order by Prepaid_ID desc limit 1") or die(mysqli_error($conn));
            $nm = mysqli_num_rows($slct);
            if($nm > 0 && strtolower($Billing_Type) == 'outpatient cash'){
                while ($dataz = mysqli_fetch_array($slct)) {
                    $Patient_Bill_ID = $dataz['Patient_Bill_ID'];
                }
                $Pre_Paid = 1;
            }else{
                include("./includes/Get_Patient_Transaction_Number.php");
                $Pre_Paid = 0;
            }
            //insert data to payment cache
            $insert_data = mysqli_query($conn,"INSERT INTO tbl_payment_cache(
                                        Registration_ID, Employee_ID, Payment_Date_And_Time,
                                        Folio_Number, Sponsor_ID, Sponsor_Name,
                                        Billing_Type, Receipt_Date, branch_id, Fast_Track)
                                            
                                        values('$Registration_ID','$Employee_ID',(select now()),
                                                '$Folio_Number','$Sponsor_ID','$Sponsor_Name',
                                                '$Billing_Type',(select now()),'$Branch_ID','$Fast_Track')") or die(mysqli_error($conn));
            if($insert_data){
                //get the last Payment_Cache_ID (foreign key)
                $select = mysqli_query($conn,"select Payment_Cache_ID from tbl_payment_cache where Registration_ID = '$Registration_ID' and
                                        Employee_ID = '$Employee_ID' order by Payment_Cache_ID desc limit 1") or die(mysqli_error($conn));
                $no = mysqli_num_rows($select);
                if($no > 0){
                    while($row = mysqli_fetch_array($select)){
                        $Payment_Cache_ID = $row['Payment_Cache_ID'];
                    }
                    //insert data
                    $select_details = mysqli_query($conn,"select * from tbl_pharmacy_items_list_cache
                                                    where Registration_ID = '$Registration_ID' and
                                                        Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
                    while($dt = mysqli_fetch_array($select_details)){
                        $Item_ID = $dt['Item_ID'];
                        $Price = $dt['Price'];
                        $Discount = $dt['Discount'];
                        $Quantity = $dt['Quantity'];
                        $Consultant_ID = $dt['Consultant_ID'];
                        $Dosage = $dt['Dosage'];
                        $Clinic_ID = $dt['Clinic_ID'];
                        $insert = mysqli_query($conn,"INSERT INTO tbl_item_list_cache(
                                            Check_In_Type, Item_ID,Price,Discount,
                                            Quantity, Patient_Direction, Consultant_ID,
                                            Status, Payment_Cache_ID, Transaction_Date_And_Time, Doctor_Comment,
                                            Sub_Department_ID, Transaction_Type, Service_Date_And_Time,
                                            Employee_Created, Created_Date_Time,Clinic_ID) values(
                                                'Pharmacy','$Item_ID','$Price','$Discount',
                                                '$Quantity','others','$Consultant_ID',
                                                'paid','$Payment_Cache_ID',(select now()),
                                                '$Dosage','$Sub_Department_ID','$Transaction_Type',(select now()),
                                                '$Employee_ID',(select now()),'$Clinic_ID')") or die(mysqli_error($conn));

                    if (!$insert) {
                        $HAS_ERROR = true;
                    }
                }

                    //insert details to tbl_patient_payments
                    $insert_details = mysqli_query($conn,"INSERT INTO tbl_patient_payments(
                                                        Registration_ID, Supervisor_ID, Employee_ID,
                                                        Payment_Date_And_Time, Folio_Number,
                                                        Claim_Form_Number, Sponsor_ID, Sponsor_Name,
                                                        Billing_Type, Receipt_Date, branch_id,Check_In_ID,Patient_Bill_ID,Fast_Track,Pre_Paid,terminal_id,auth_code,manual_offline)
                                                        
                                                        VALUES ('$Registration_ID','$Supervisor_ID','$Employee_ID',
                                                            (select now()),'$Folio_Number',
                                                            '$Claim_Form_Number','$Sponsor_ID','$Sponsor_Name',
                                                            '$Billing_Type',(select now()),'$Branch_ID','$Check_In_ID','$Patient_Bill_ID','$Fast_Track','$Pre_Paid','$terminal_id','$auth_code','$manual_offline')") or die(mysqli_error($conn));
                    if (!$insert_details) {
                                $HAS_ERROR = true;
                    }
                        //get the last Patient Payment ID
                        $select = mysqli_query($conn,"select Patient_Payment_ID, Receipt_Date from tbl_patient_payments
                                                    where Registration_ID = '$Registration_ID' and
                                                        Employee_ID = '$Employee_ID' order by Patient_Payment_ID desc limit 1") or die(mysqli_error($conn));
                            $num = mysqli_num_rows($select);
                            if($num > 0){
                                while($row = mysqli_fetch_array($select)){
                                    $Patient_Payment_ID = $row['Patient_Payment_ID'];
                                    $Receipt_Date = $row['Receipt_Date'];
                                }
                                
                                
                                //insert data to tbl_patient_payment_item_list
                                $select_details = mysqli_query($conn,"select * from tbl_pharmacy_items_list_cache
                                                    where Registration_ID = '$Registration_ID' and
                                                        Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
                                
                                while($dt = mysqli_fetch_array($select_details)){
                                    $Item_ID = $dt['Item_ID'];
                                    $Price = $dt['Price'];
                                    $Discount = $dt['Discount'];
                                    $Quantity = $dt['Quantity'];
                                    $Consultant_ID = $dt['Consultant_ID'];
                                    $Dosage = $dt['Dosage'];
                                    $Clinic_ID = $dt['Clinic_ID'];
                                    $insert = mysqli_query($conn,"INSERT INTO tbl_patient_payment_item_list(
                                                            Check_In_Type, Item_ID, Price, Discount,
                                                            Quantity, Patient_Direction, Consultant,
                                                            Consultant_ID, Patient_Payment_ID, Transaction_Date_And_Time,Clinic_ID,Sub_Department_ID,finance_department_id)
                                                            
                                                            VALUES ('Pharmacy','$Item_ID','$Price','$Discount',
                                                            '$Quantity','others','others',
                                                            '$Consultant_ID', '$Patient_Payment_ID', (select now()),'$Clinic_ID','$Sub_Department_ID','1')") or die(mysqli_error($conn));
                                if (!$insert) {
                                    $HAS_ERROR = true;
                                }
                            }

                            //update tbl_item_list_cache
                            $upt1 = mysqli_query($conn,"update tbl_item_list_cache set
                                                    Patient_Payment_ID = '$Patient_Payment_ID',
                                                        Payment_Date_And_Time = '$Receipt_Date' where
                                                            Payment_Cache_ID = '$Payment_Cache_ID'") or die(mysqli_error($conn));
                            if (!$upt1) {
                                $HAS_ERROR = true;
                            }

                            //delete all data from cache
                            $delt1 = mysqli_query($conn,"delete from tbl_pharmacy_items_list_cache where Registration_ID = '$Registration_ID' and Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
                            if (!$delt1) {
                                $HAS_ERROR = true;
                            }
                        /////////////////////////////////////////// Journal entry //////////////////////////////////////////
                        $payDetails = getPaymentsDetailsByReceiptNumber($Patient_Payment_ID);

                        $Product_Array=array();

                        $debit_entry_ledger = '';
                        if(strtolower($Sponsor_Name) == 'cash'){
                           $debit_entry_ledger = 'CASH IN HAND'; 
                        } else {
                            $debit_entry_ledger = $Sponsor_Name;
                        }

                        $Product_Name_Array = array(
                            'ref_no'=>$Patient_Payment_ID,
                            'source_name' => 'ehms_sales_cash',
                            'comment' => 'Receipt # ' . $Patient_Payment_ID . ", Date:" . $payDetails['Payment_Date_And_Time'] . ", Trans_Type:" . $payDetails['Payment_Mode'] . " Amount:  " . $payDetails['TotalAmount'] . " Tsh.",
                            'debit_entry_ledger' => $debit_entry_ledger,
                            'credit_entry_ledger' => 'SALES',
                            'sub_total' => $payDetails['TotalAmount'],
                            'source_id' => $Patient_Payment_ID,
                            'Employee_Name' => $_SESSION['userinfo']['Employee_Name'],
                            'Employee_ID' => $_SESSION['userinfo']['Employee_ID']
                        );

                        array_push($Product_Array, $Product_Name_Array);



                       $acc = gAccJournalEntry(json_encode($Product_Array));

                    ////////////////////////////////////////////////////////////////////////////////////////////////
                        }
            }
        } else {
            $HAS_ERROR = true;
        }
    }

    if (!$HAS_ERROR) {
        Commit_Transaction();
        header("Location: ./pharmacyworkspage.php?section=Pharmacy&Registration_ID=$Registration_ID&Transaction_Type=$Transaction_Type&Payment_Cache_ID=$Payment_Cache_ID&NR=True&PharmacyWorks=PharmacyWorksThisPage");
    } else {
        Rollback_Transaction();
        header("Location: ./pharmacyworkspage.php?section=Pharmacy&Registration_ID=$Registration_ID&Transaction_Type=$Transaction_Type&Payment_Cache_ID=$Payment_Cache_ID&NR=True&PharmacyWorks=PharmacyWorksThisPage");
    }
}
?>