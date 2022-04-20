<?php

session_start();

include_once("./functions/database.php");
include_once("./functions/employee.php");
include_once("./functions/itemsdisposal.php");
include_once("./functions/items.php");

$employee_id = $_SESSION['userinfo']['Employee_ID'];
$employee_name = $_SESSION['userinfo']['Employee_Name'];
if (isset($_GET['Supervisor_Username'])) {
    $Supervisor_Username = mysqli_real_escape_string($conn,$_GET['Supervisor_Username']);
}

    if (isset($_GET['Supervisor_Password'])) {
        $Supervisor_Password = mysqli_real_escape_string($conn,md5($_GET['Supervisor_Password']));
    }


if (isset($_GET['Disposal_ID'])) {
    $Disposal_ID = $_GET['Disposal_ID'];
}

if(isset($_GET['reason_for_adjustment'])){
    $reason_for_adjustment = $_GET['reason_for_adjustment'];
}

$get_nature_for_adjustment = mysqli_fetch_assoc(mysqli_query($conn,"SELECT nature FROM tbl_adjustment WHERE id = $reason_for_adjustment"))['nature'];
if($get_nature_for_adjustment == "adjustment_plus"){
    $nature = 'yes';
}else{
    $nature = 'no';
}


//Declaring array for items disposal to be sent to gaccounting
$Disposal_Array = array();
$error_code = 0;

if (Is_Logged_In_User($Supervisor_Username, $Supervisor_Password)) {
    $Items_Disposal_Item_List = Get_Items_Disposal_Items($Disposal_ID);

    Start_Transaction();
    $Document_Date = Get_Time_Now();
    foreach ($Items_Disposal_Item_List as $Items_Disposal_Item) {
        $Quantity_Disposed = $Items_Disposal_Item['Quantity_Disposed'];
        $Item_ID = $Items_Disposal_Item['Item_ID'];
        $Disposal_Location = $Items_Disposal_Item['Sub_Department_ID'];
        $Consultation_Type = $Items_Disposal_Item['Consultation_Type'];
        $Store_Balance = $Items_Disposal_Item['Store_Balance'];

        if ($Quantity_Disposed > $Store_Balance && $get_nature_for_adjustment == 'adjustment_minus') {
            $error_code = 2;
            break;
        } else {
            if (!Update_Item_Balance(
                    $Item_ID, 
                    $Disposal_Location, 
                    "ADJUSTMENT", 
                    null, 
                    null, 
                    null, 
                    $Disposal_ID, 
                    $Document_Date, 
                    $Quantity_Disposed,
                    $nature)) {
                $error_code = 3;
                break;
            }
        }
        if ($Consultation_Type == "Pharmacy") {
            $debit_entry_ledger = "DISPOSAL";
            $credit_entry_ledger = "Pharmacy-INVENTORY";
        } elseif ($Consultation_Type == "Laboratory") {
            $debit_entry_ledger = "DISPOSAL";
            $credit_entry_ledger = "Laboratory-INVENTORY";
        } elseif ($Consultation_Type == "Radiology") {
            $debit_entry_ledger = "11";
            $credit_entry_ledger = "Radiology-INVENTORY";
        } elseif ($Consultation_Type == "Surgery") {
            $debit_entry_ledger = "DISPOSAL";
            $credit_entry_ledger = "Surgery-INVENTORY";
        } elseif ($Consultation_Type == "Procedure") {
            $debit_entry_ledger = "DISPOSAL";
            $credit_entry_ledger = "Procedure-INVENTORY";
        } elseif ($Consultation_Type == "Optical") {
            $debit_entry_ledger = "DISPOSAL";
            $credit_entry_ledger = "Optical-INVENTORY";
        } elseif ($Consultation_Type == "Dialysis") {
            $debit_entry_ledger = "DISPOSAL";
            $credit_entry_ledger = "Dialysis-INVENTORY";
        } elseif ($Consultation_Type == "Others") {
            $debit_entry_ledger = "DISPOSAL";
            $credit_entry_ledger = "Others-COGS";
        }
        $subTotal = $Items_Disposal_Item['Last_Buying_Price'] * $Quantity_Disposed;
        $Disposal_items_Array = array(
            'ref_no'=>$Disposal_ID,
            'source_name' => 'ehms_disposal',
            'Consultation_Type' => $Consultation_Type,
            'comment' => $Items_Disposal_Item['Product_Name'] . ", " . $Quantity_Disposed . " item(s) @ " . $Items_Disposal_Item['Last_Buying_Price'] . " Tsh.",
            'debit_entry_ledger' => $debit_entry_ledger,
            'credit_entry_ledger' => $credit_entry_ledger,
            'sub_total' => $subTotal,
            'source_id' => $Disposal_ID,
            'Employee_Name' => $employee_name,
            'Employee_ID' => $employee_id
        );
        array_push($Disposal_Array, $Disposal_items_Array);
    }
    $endata = json_encode($Disposal_Array);
    $acc = gAccJournalEntry($endata);
//    echo $acc;
//    exit();
    $AccError=false;
    if($acc!="success"){
      $AccError=true;  
    }
    if ($error_code == 0) {
        $Update_Status = Update_Items_Disposal_Status($Disposal_ID, "saved");
        $hasError = $Update_Status["error"];
        if (!$hasError && $AccError==false) {
            $error_code = 1;
            Commit_Transaction();
            if (isset($_SESSION['Disposal_ID'])) {
                unset($_SESSION['Disposal_ID']);
            }
        } else {
            $error_code = 4;
            echo $Update_Status["errorMsg"];
            Rollback_Transaction();
        }
    } else {
        Rollback_Transaction();
    }
} else {
    $error_code = 0;
}

echo $error_code;