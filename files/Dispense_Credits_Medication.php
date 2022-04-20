<?php
error_reporting(0);
@session_start();
require_once './includes/constants.php'; 
include("./includes/connection.php");
include_once("./functions/items.php");
//echo $Supervisor = $_SESSION['supervisor']['Employee_Name'];
//get all required information
if (isset($_GET['Payment_Cache_ID'])) {
    $Payment_Cache_ID = $_GET['Payment_Cache_ID'];
} else {
    $Payment_Cache_ID = '';
}
if (isset($_GET['Transaction_Type'])) {
    $Transaction_Type = $_GET['Transaction_Type'];
} else {
    $Transaction_Type = '';
}

if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
} else {
    $Registration_ID = '';
}

if(isset($_GET['Check_In_Type'])){
    $Check_In_Type=$_GET['Check_In_Type'];
}else{
   $Check_In_Type=""; 
}

if (isset($_GET['Billing_Type'])) {
    $Temp_Billing_Type = strtolower($_GET['Billing_Type']);
} else {
    $Temp_Billing_Type = '';
}

if ($Temp_Billing_Type == 'outpatient cash' && strtolower($Transaction_Type) == 'cash') {
    $Billing_Type = 'Outpatient Cash';
} elseif ($Temp_Billing_Type == 'outpatient cash' && strtolower($Transaction_Type) == 'credit') {
    $Billing_Type = 'Outpatient Credit';
} elseif ($Temp_Billing_Type == 'outpatient credit' && strtolower($Transaction_Type) == 'cash') {
    $Billing_Type = 'Outpatient Cash';
} elseif ($Temp_Billing_Type == 'outpatient credit' && strtolower($Transaction_Type) == 'credit') {
    $Billing_Type = 'Outpatient Credit';
} elseif ($Temp_Billing_Type == 'inpatient cash' && strtolower($Transaction_Type) == 'cash') {
    $Billing_Type = 'Inpatient Cash';
} elseif ($Temp_Billing_Type == 'inpatient cash' && strtolower($Transaction_Type) == 'credit') {
    $Billing_Type = 'Inpatient Credit';
} elseif ($Temp_Billing_Type == 'inpatient credit' && strtolower($Transaction_Type) == 'cash') {
    $Billing_Type = 'Inpatient Cash';
} elseif ($Temp_Billing_Type == 'inpatient credit' && strtolower($Transaction_Type) == 'credit') {
    $Billing_Type = 'Inpatient Credit';
} else {
    $Billing_Type = 'Patient From Outside';
}


$HAS_ERROR = false;
Start_Transaction();

////////////////////////////////make approve before make payment///////////////////////////////////////////////
  $Sub_Department_ID = $_SESSION['Pharmacy_ID'];
  $update = "update tbl_item_list_cache set status = 'approved',Sub_Department_ID = '$Sub_Department_ID' where
                    status = 'active' and 
                        Payment_Cache_ID = '$Payment_Cache_ID' and
                            Transaction_Type = '$Transaction_Type' and
                                Check_In_Type='$Check_In_Type'
                                ";
  if(isset($_GET['approve_yes'])){
      
    $update_result= mysqli_query($conn,$update) or die(mysqli_error($conn)); 
    if($update_result){
     ?>
         <script>//alert("approved")</script>
         <?php
    }
  }
//get sponsor name
$get_sponsor = mysqli_query($conn,"select Guarantor_Name from tbl_patient_registration pr, tbl_sponsor sp where
                            pr.Sponsor_ID = sp.Sponsor_ID and
                            pr.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
$num = mysqli_num_rows($get_sponsor);
if($num > 0){
    while ($dtz = mysqli_fetch_array($get_sponsor)) {
        $Guarantor_Name = $dtz['Guarantor_Name'];
    }
}else{
    $Guarantor_Name = '';
}

//generate payment type
if(strtolower($Guarantor_Name) != 'cash' && $Billing_Type == 'Inpatient Cash'){
    $payment_type = 'pre';
}else{
    $payment_type = 'post';
}
if(isset($_GET['inpatient_cash_post'])&&$_GET['inpatient_cash_post']=='yes'){
   $payment_type = 'post'; 
}

include("./includes/Get_Patient_Check_In_Id.php");
include("./includes/Get_Patient_Transaction_Number.php");

//get sub department id
if (isset($_SESSION['Pharmacy'])) {
    $Sub_Department_Name = $_SESSION['Pharmacy'];
} else {
    $Sub_Department_Name = '';
}
//echo $Sub_Department_Name."This";


if (isset($_SESSION['Pharmacy_ID'])) {
    $Sub_Department_ID = $_SESSION['Pharmacy_ID'];
} else {
    $sql = mysqli_query($conn,"select Sub_Department_ID from tbl_Sub_Department where Sub_Department_Name = '$Sub_Department_Name'");
    $no = mysqli_num_rows($sql);
    if ($no > 0) {
        while ($row = mysqli_fetch_array($sql)) {
            $Sub_Department_ID = $row['Sub_Department_ID'];
        }
    } else {
        $Sub_Department_ID = 0;
    }
}


//---get supervisor id 
if (isset($_SESSION['Pharmacy_Supervisor'])) {
    $Supervisor_ID = $_SESSION['Pharmacy_Supervisor']['Employee_ID'];
}else{
    $Supervisor_ID = $Employee_ID;
}
//end of fetching supervisor id  
//get branch ID
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Branch_ID'])) {
        $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
    } else {
        $Branch_ID = '';
    }
} else {
    $Branch_ID = '';
}
//end of fetching branch ID
//get employee id
if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    $employee_name = $_SESSION['userinfo']['Employee_Name'];
} else {
    $Employee_ID = '';
}
$employee_id = $_SESSION['userinfo']['Employee_ID'];
$employee_name = $_SESSION['userinfo']['Employee_Name'];
//get last check in
$select = mysqli_query($conn,"select Check_In_ID, Patient_Bill_ID from tbl_patient_payments where Registration_ID = '$Registration_ID' order by Patient_Payment_ID desc limit 1") or die(mysqli_error($conn));
$nom = mysqli_num_rows($select);
if($nom > 0){
    while ($data = mysqli_fetch_array($select)) {
        $Check_In_ID = $data['Check_In_ID'];
        $Patient_Bill_ID = $data['Patient_Bill_ID'];
    }
}else{
    //create check in
    $insert_value = mysqli_query($conn,"insert into tbl_check_in(
                                    Registration_ID,Check_In_Date_And_Time,Visit_Date,
                                    Employee_ID,Branch_ID,Check_In_Date)                                
                                values(
                                    '$Registration_ID',(select now()),(select now()),
                                    '$Employee_ID','$Branch_ID',(select now()))") or die(mysqli_error($conn));
    
    if (!$insert_value) {
       $HAS_ERROR = true;
    }

    $select = mysqli_query($conn,"select Check_In_ID from tbl_check_in where Registration_ID = '$Registration_ID' order by Check_In_ID desc limit 1") or die(mysqli_error($conn));
    while ($data = mysqli_fetch_array($select)) {
        $Check_In_ID = $data['Check_In_ID'];
    }

    //create Patient_Bill_ID
    $create_Patient_Bill_ID = mysqli_query($conn,"insert into tbl_patient_bill(Registration_ID, Date_Time)
                                            values('$Registration_ID', (select now()))") or die(mysqli_error($conn));
    if($create_Patient_Bill_ID){
        $select = mysqli_query($conn,"select Patient_Bill_ID from tbl_patient_bill where Registration_ID = '$Registration_ID' order by Patient_Bill_ID desc limit 1") or die(mysqli_error($conn));
        $nm = mysqli_num_rows($select);
        if($nm > 0){
            while ($dt = mysqli_fetch_array($select)) {
                $Patient_Bill_ID = $dt['Patient_Bill_ID'];
            }
        }else{
            $Patient_Bill_ID = '';
        }
    }else{
       $HAS_ERROR = true; 
    }
}

//insert related information to generate receipt
//get data from cache table then insert

$select_cache_info = "select * from tbl_payment_cache where payment_cache_id = '$Payment_Cache_ID'";
$result = mysqli_query($conn,$select_cache_info) or die(mysqli_error($conn));
$has_no_folio = false;
$Folio_Number = '';
while ($row = mysqli_fetch_array($result)) {
    $Registration_ID = $row['Registration_ID'];
    $Folio_Number = $row['Folio_Number'];
    $Sponsor_ID = $row['Sponsor_ID'];
    $Sponsor_Name = $row['Sponsor_Name'];
    $Transaction_status = 'active';
    $Transaction_type = 'indirect cash';

    //select the last claim form number
//    $select_claim_form = mysqli_query($conn,"select Claim_Form_Number from tbl_patient_payments where
//                                                Check_In_ID = '$Check_In_ID' and
//                                                    Registration_ID = '$Registration_ID' and
//                                                        Sponsor_ID = '$Sponsor_ID'
//                                                            order by patient_payment_id desc limit 1");

    $select = mysqli_query($conn,"select Folio_Number, Claim_Form_Number from tbl_patient_payments where 
                            Registration_ID = '" . $Registration_ID . "' AND 
                            Check_In_ID = '" . $Check_In_ID . "'  AND 
                            Sponsor_ID = '" . $Sponsor_ID . "' order by Patient_Payment_ID desc limit 1") or die(mysqli_error($conn));

    if (mysqli_num_rows($select)) {
        $rows_info = mysqli_fetch_array($select);
        $Claim_Form_Number = $rows_info['Claim_Form_Number'];
        $Folio_Number = $rows_info['Folio_Number'];

        //get last check in id
    } else {
        include("./includes/Folio_Number_Generator_Emergency.php");
        $select = mysqli_query($conn,"SELECT Claim_Form_Number,cd.Sponsor_ID,Guarantor_Name from tbl_check_in_details cd JOIN tbl_sponsor sp ON cd.Sponsor_ID=sp.Sponsor_ID  WHERE cd.Check_In_ID= '$Check_In_ID'") or die(mysqli_error($conn));
        $rows_info = mysqli_fetch_array($select);
        $Claim_Form_Number = '';
        $has_no_folio = true;
    }

    //time to insert data
    if(strtolower($Billing_Type) == 'inpatient cash' || strtolower($Billing_Type) == 'inpatient credit'){
        include("./includes/Get_Patient_Hospital_Ward_ID.php");
        $insert = "insert into tbl_patient_payments(
                        Registration_ID,Supervisor_ID,Employee_ID,
                            Payment_Date_And_Time,Folio_Number,Claim_Form_Number,
                                Sponsor_ID,Sponsor_Name,Billing_Type,
                                    Receipt_Date,Transaction_status,Transaction_type,Branch_ID,Check_In_ID,Patient_Bill_ID,payment_type,Hospital_Ward_ID)
                                    
                    values('$Registration_ID','$Supervisor_ID','$Employee_ID',
                                (select now()),'$Folio_Number','$Claim_Form_Number',
                                    '$Sponsor_ID','$Sponsor_Name','$Billing_Type',
                                        (select now()),'$Transaction_status','$Transaction_type','$Branch_ID','$Check_In_ID','$Patient_Bill_ID','$payment_type','$Hospital_Ward_ID')";
    }else{
        $insert = "insert into tbl_patient_payments(
                        Registration_ID,Supervisor_ID,Employee_ID,
                            Payment_Date_And_Time,Folio_Number,Claim_Form_Number,
                                Sponsor_ID,Sponsor_Name,Billing_Type,
                                    Receipt_Date,Transaction_status,Transaction_type,Branch_ID,Check_In_ID,Patient_Bill_ID,payment_type)
                                    
                    values('$Registration_ID','$Supervisor_ID','$Employee_ID',
                                (select now()),'$Folio_Number','$Claim_Form_Number',
                                    '$Sponsor_ID','$Sponsor_Name','$Billing_Type',
                                        (select now()),'$Transaction_status','$Transaction_type','$Branch_ID','$Check_In_ID','$Patient_Bill_ID','$payment_type')";
    }
    $insert1=mysqli_query($conn,$insert) or die(mysqli_error($conn));
    if ($insert1) {
        //get patient payment id to use as a foreign key
        $select_patient_payment_id = mysqli_query($conn,"select Patient_Payment_ID, Payment_Date_And_Time from tbl_patient_payments where
                                            Registration_ID = '$Registration_ID' and
                                                Supervisor_ID = '$Supervisor_ID' and
                                                    Employee_ID = '$Employee_ID' order by patient_payment_id desc limit 1");
        $no = mysqli_num_rows($select_patient_payment_id);
        if ($no > 0) {
            while ($row2 = mysqli_fetch_array($select_patient_payment_id)) {
                $Patient_Payment_ID = $row2['Patient_Payment_ID'];
                $Payment_Date_And_Time = $row2['Payment_Date_And_Time'];
            }
        } else {
            $Patient_Payment_ID = '';
            $Payment_Date_And_Time = '';
        }

        if ($Patient_Payment_ID != '' && $Payment_Date_And_Time != '') {
            $Product_Array = array();
            //get all medication and then insert
            $select_medication = mysqli_query($conn,"
                        select itm.Product_Name,ch.Clinic_ID,ch.finance_department_id,ch.clinic_location_id,itm.Last_Buy_Price,itm.Consultation_Type,ch.Quantity,ch.Edited_Quantity,ch.Consultant_ID,
                        ch.Consultant,ch.Patient_Direction,ch.Price,ch.Discount,ch.Item_ID,
                        ch.Check_In_Type,ch.Payment_Item_Cache_List_ID 
                        from tbl_item_list_cache ch join tbl_items itm on itm.Item_ID=ch.Item_ID where
                           ch.payment_cache_id = '$Payment_Cache_ID' and ch.Check_In_Type='$Check_In_Type' and
                               ch.Transaction_Type = '$Transaction_Type' and
                                   (ch.status = 'active' or ch.status = 'approved')") or die(mysqli_error($conn));
            while ($row3 = mysqli_fetch_array($select_medication)) {
                $Consultation_Type = $row3['Consultation_Type'];
                $Product_Name = $row3['Product_Name'];
                $unit_price = $Last_Buy_Price = $row3['Last_Buy_Price'];
                $Payment_Item_Cache_List_ID = $row3['Payment_Item_Cache_List_ID'];
                $Check_In_Type = $row3['Check_In_Type'];
                $Item_ID = $row3['Item_ID'];
                $Discount = $row3['Discount'];
                $Price = $row3['Price'];
                $Patient_Direction = $row3['Patient_Direction'];
                $Consultant = $row3['Consultant'];
                $Consultant_ID = $row3['Consultant_ID'];
                $Clinic_ID = $row3['Clinic_ID'];
                $clinic_location_id = $row3['clinic_location_id'];
                $finance_department_id = $row3['finance_department_id'];
                if ($row3['Edited_Quantity'] > 0) {
                    $Quantity = $row3['Edited_Quantity'];
                } else {
                    $Quantity = $row3['Quantity'];
                }
                $quantity = $Quantity; 
                //insert selected record
                $Insert_Data_To_tbl_patient_payment_item_list = "insert into tbl_patient_payment_item_list(
                                    check_In_type,item_id,discount,
                                        price,quantity,patient_direction,
                                            consultant,consultant_id,patient_payment_id,Transaction_Date_And_Time,Clinic_ID,finance_department_id,clinic_location_id,Sub_Department_ID)
                                    values(
                                        '$Check_In_Type','$Item_ID','$Discount',
                                            '$Price','$Quantity','$Patient_Direction',
                                                '$Consultant','$Consultant_ID','$Patient_Payment_ID',(select now()),'$Clinic_ID','$finance_department_id','$clinic_location_id','$Sub_Department_ID')";
                if (!mysqli_query($conn,$Insert_Data_To_tbl_patient_payment_item_list)) {
                  $HAS_ERROR = true;
                } else {
                    $employee_id = $_SESSION['userinfo']['Employee_ID'];
                    $result = mysqli_query($conn,"update tbl_item_list_cache set status = 'dispensed', Dispensor='$employee_id',
                                            Patient_Payment_ID = '$Patient_Payment_ID', Dispense_Date_Time = (select now()),
                                            Payment_Date_And_Time = '$Payment_Date_And_Time' 
                                            where Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'and Check_In_Type='$Check_In_Type'");
                    if ($result) {
                        //get last balance
                        $slct = mysqli_query($conn,"select Item_Balance from tbl_items_balance where Item_ID = '$Item_ID' and Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
                        $nm = mysqli_num_rows($slct);
                        if($nm > 0){
                            while ($dt = mysqli_fetch_array($slct)) {
                                $Pre_Balance = $dt['Item_Balance'];
                            }
                        }else{
                            $ins1=mysqli_query($conn,"insert into tbl_items_balance(Item_ID,Sub_Department_ID) values('$Item_ID','$Sub_Department_ID')") or die(mysqli_error($conn));
                            
                            if (!$ins1) {
                              $HAS_ERROR = true;
                            }
                            
                           $Pre_Balance = 0;
                        }

                        //update sub department balance
                        $upd = mysqli_query($conn,"update tbl_items_balance set Item_Balance = (Item_Balance - '$Quantity'), 
                                        Item_Temporary_Balance  = (Item_Temporary_Balance - '$Quantity') where
                                        Item_ID = '$Item_ID' and Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
                        if(!$upd){
                         $HAS_ERROR = true;
                        }
                            //insert data into tbl_stock_ledger_controler for auditing
                            $insert_audit = mysqli_query($conn,"insert into tbl_stock_ledger_controler(
                                                            Item_ID, Sub_Department_ID, Movement_Type, Registration_ID,
                                                            Pre_Balance, Post_Balance, Movement_Date, Movement_Date_Time, Document_Number)
                                                    values('$Item_ID','$Sub_Department_ID','Dispensed','$Registration_ID',
                                                                '$Pre_Balance',($Pre_Balance - $Quantity),(select now()),(select now()),'$Patient_Payment_ID')") or die(mysqli_error($conn));
                        if (!$insert_audit) {
                           $HAS_ERROR = true;
                        }
                    }else{
                      $HAS_ERROR = true;
                    }

                    //check if this user has folio 

                    if ($has_no_folio) {
                        $result_cd = mysqli_query($conn,"SELECT Check_In_Details_ID FROM tbl_check_in_details WHERE Registration_ID = '$Registration_ID' AND Check_In_ID = '$Check_In_ID' AND consultation_ID IS NOT NULL ORDER BY Check_In_Details_ID DESC LIMIT 1") or die(mysqli_error($conn));
                        $Check_In_Details_ID = mysqli_fetch_assoc($result_cd)['Check_In_Details_ID'];
                        $update_checkin_details = "UPDATE tbl_check_in_details SET Folio_Number='$Folio_Number'
                                                        WHERE Check_In_Details_ID='$Check_In_Details_ID' ";
                        $upchkdt=mysqli_query($conn,$update_checkin_details) or die(mysqli_error($conn));
                        
                        if (!$upchkdt) {
                           $HAS_ERROR = true;
                        }
                    }
                }
                //header("Location: ./pharmacyworkspage.php?section=Pharmacy&Registration_ID=$Registration_ID&Transaction_Type=$Transaction_Type&Payment_Cache_ID=$Payment_Cache_ID&NR=True&Billing_Type=$Billing_Type&Patient_Payment_ID=$Patient_Payment_ID&Payment_Date_And_Time=$Payment_Date_And_Time&PharmacyWorks=PharmacyWorksThisPage");
                //Array to be sent to gaccounting
                /*
                $Product_Name_Array = array(
                    'ref_no' => $Patient_Payment_ID,
                    'source_name' => 'ehms_phamarcy_despense',
                    'comment' => $Product_Name . ", " . $quantity . " item(s) @ " . number_format($unit_price,2) . " Tsh.",
                    'debit_entry_ledger'=>'Pharmacy-COGS',
                    'credit_entry_ledger'=>'Pharmacy-INVENTORY',
                    'sub_total' => $quantity*$unit_price,
                    'source_id' =>$Patient_Payment_ID,// $Payment_Item_Cache_List_ID,
                    'Employee_Name' => $employee_name,
                    'Employee_ID' => $Employee_ID
                );

                array_push($Product_Array, $Product_Name_Array);*/
            }
 
            $endata = json_encode($Product_Array); 
            $acc = gAccJournalEntry($endata);
            //    exit();
            if ($acc != "success") {
                $HAS_ERROR = true;
            }
            /*
            if($Billing_Type != "Inpatient Cash" && $Billing_Type != "Inpatient Credit"){

            
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
                    'ref_no' => $Patient_Payment_ID,
                    'source_name' => 'ehms_sales_credit',
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
            }*/

        }
    } else {
        $HAS_ERROR = true;
    }
}

if (!$HAS_ERROR) {
    Commit_Transaction();
    header("Location: ./pharmacyworkspage.php?section=Pharmacy&Registration_ID=$Registration_ID&Transaction_Type=$Transaction_Type&Payment_Cache_ID=$Payment_Cache_ID&NR=True&Billing_Type=$Billing_Type&Patient_Payment_ID=$Patient_Payment_ID&Payment_Date_And_Time=$Payment_Date_And_Time&PharmacyWorks=PharmacyWorksThisPage&Check_In_Type=$Check_In_Type");
} else {
    Rollback_Transaction();
    header("Location: ./pharmacyworkspage.php?section=Pharmacy&Registration_ID=$Registration_ID&Transaction_Type=$Transaction_Type&Payment_Cache_ID=$Payment_Cache_ID&NR=True&Billing_Type=$Billing_Type&Patient_Payment_ID=$Patient_Payment_ID&Payment_Date_And_Time=$Payment_Date_And_Time&PharmacyWorks=PharmacyWorksThisPage&Check_In_Type=$Check_In_Type");
}
?>