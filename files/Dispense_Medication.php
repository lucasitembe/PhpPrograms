<?php

@session_start();
require_once './includes/constants.php';
include("./includes/connection.php");
include_once("./functions/items.php");

//get posted values
$employee_id = $_SESSION['userinfo']['Employee_ID'];
$employee_name = $_SESSION['userinfo']['Employee_Name'];

//die($employee_id);
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
if (isset($_GET['Sub_Department_Name'])) {
    $Sub_Department_Name = $_GET['Sub_Department_Name'];
} else {
    $Sub_Department_Name = '';
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
if(isset($_GET['from_billing_work'])){
    $from_billing_work=$_GET['from_billing_work'];
}else{
   $from_billing_work="";
}
if(isset($_GET['Check_In_ID'])){
    $Check_In_ID=$_GET['Check_In_ID'];
}else{
   $Check_In_ID="";
}
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
    $sql = mysqli_query($conn,"SELECT Sub_Department_ID from tbl_Sub_Department where Sub_Department_Name = '$Sub_Department_Name'");
    $no = mysqli_num_rows($sql);
    if ($no > 0) {
        while ($row = mysqli_fetch_array($sql)) {
            $Sub_Department_ID = $row['Sub_Department_ID'];
        }
    } else {
        $Sub_Department_ID = 0;
    }
}


$HAS_ERROR = false;
Start_Transaction();

//get all paid items then reduce the number of items from respected sub department
$select_item = mysqli_query($conn,"SELECT ch.Payment_Item_Cache_List_ID,itm.Last_Buy_Price,ch.Price,itm.Product_Name,itm.Consultation_Type,Quantity, Edited_Quantity, Patient_Payment_ID, itm.Item_ID from tbl_item_list_cache ch join tbl_items itm on itm.Item_ID=ch.Item_ID where ch.status = 'paid'  and Payment_Cache_ID = '$Payment_Cache_ID' AND ch.Check_In_Type='$Check_In_Type'") or die(mysqli_error($conn) . 'Two');
$num_of_rows = mysqli_num_rows($select_item);

if ($num_of_rows > 0) {
    $Product_Array = array();
    while ($data = mysqli_fetch_array($select_item)) {

        $Consultation_Type = $data['Consultation_Type'];
        $Product_Name = $data['Product_Name'];
        $Quantity = $data['Quantity'];
        $Edited_Quantity = $data['Edited_Quantity'];
        $Item_ID = $data['Item_ID'];
        $Patient_Payment_ID = $data['Patient_Payment_ID'];
        $unit_price = $Last_Buy_Price = $data['Last_Buy_Price'];
        $Payment_Item_Cache_List_ID = $data['Payment_Item_Cache_List_ID'];
        if ($Edited_Quantity != 0) {
            $Temp_Quantity = $Edited_Quantity;
        } else {
            $Temp_Quantity = $Quantity;
        }
        $quantity = $Temp_Quantity;
        $subTotal = $Last_Buy_Price * $Temp_Quantity;
        //get last balance
        $slct = mysqli_query($conn,"SELECT Item_Balance from tbl_items_balance where Item_ID = '$Item_ID' and Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
        $nm = mysqli_num_rows($slct);
        if ($nm > 0) {
            while ($dt = mysqli_fetch_array($slct)) {
                $Pre_Balance = $dt['Item_Balance'];
            }
        } else {
            $rs1 = mysqli_query($conn,"INSERT into tbl_items_balance(Item_ID,Sub_Department_ID) values('$Item_ID','$Sub_Department_ID')") or die(mysqli_error($conn));

            if (!$rs1) {
                $HAS_ERROR = true;
            }

            $Pre_Balance = 0;
        }

        $update_balance = mysqli_query($conn,"UPDATE tbl_items_balance set Item_Balance = (Item_Balance - '$Temp_Quantity'),
                                        Item_Temporary_Balance  = (Item_Temporary_Balance - '$Temp_Quantity')
                                        where Sub_Department_ID = '$Sub_Department_ID' and
                                        Item_ID = '$Item_ID'") or die(mysqli_error($conn));
        if ($update_balance) {
            //update transaction item from tbl patient payment item list
            $update_status = mysqli_query($conn,"UPDATE tbl_patient_payment_item_list set Status = 'Served'
                                            where Patient_Payment_ID = '$Patient_Payment_ID' and
                                                Item_ID = '$Item_ID'") or die(mysqli_error($conn));
            if (!$update_status) {
                $HAS_ERROR = true;
            }

            $update = mysqli_query($conn,"UPDATE tbl_item_list_cache set status = 'dispensed',Dispensor='$employee_id', Dispense_Date_Time = NOW() where
                    status = 'paid' and
                        Payment_Cache_ID = '$Payment_Cache_ID' and
                            Transaction_Type = '$Transaction_Type' and
                                Sub_Department_ID = '$Sub_Department_ID' and Item_ID = '$Item_ID' and Check_In_Type='$Check_In_Type'") or die(mysqli_error($conn));

            if (!$update) {
                $HAS_ERROR = true;
            }
            //if($Pre_Balance > 0){
            //insert data into tbl_stock_ledger_controler for auditing
            $insert_audit = mysqli_query($conn,"INSERT into tbl_stock_ledger_controler(
                                                Item_ID, Sub_Department_ID, Movement_Type, Registration_ID,
                                                Pre_Balance, Post_Balance, Movement_Date, Movement_Date_Time, Document_Number)
                                        values('$Item_ID','$Sub_Department_ID','Dispensed','$Registration_ID',
                                                '$Pre_Balance',($Pre_Balance - $Temp_Quantity),(select now()),(select now()),'$Patient_Payment_ID')") or die(mysqli_error($conn));
            //}
            if (!$insert_audit) {
                $HAS_ERROR = true;
            }
        } else {
            $HAS_ERROR = true;
        }

//Array to be sent to gaccounting
        $Product_Name_Array = array(
                    'ref_no' => $Patient_Payment_ID,
                    'source_name' => 'ehms_phamarcy_despense',
                    'comment' => $Product_Name . ", " . $quantity . " item(s) @ " . number_format($unit_price,2) . " Tsh.",
                    'debit_entry_ledger'=>'Pharmacy-COGS',
                    'credit_entry_ledger'=>'Pharmacy-INVENTORY',
                    'sub_total' => $quantity*$unit_price,
                    'source_id' =>$Patient_Payment_ID,
                    'Employee_Name' => $employee_name,
                    'Employee_ID' => $Employee_ID
                );

        array_push($Product_Array, $Product_Name_Array);

        $Edited_Quantity = 0;
    }
    $endata = json_encode($Product_Array);

    $acc = gAccJournalEntry($endata);

    if ($acc != "success") {
        $HAS_ERROR = true;
    }

}

if ($_SESSION['userinfo']) {
    $employee_id = $_SESSION['userinfo']['Employee_ID'];
} else {
    $employee_id = 0;
}


if (!$HAS_ERROR) {
    Commit_Transaction();
   header("Location: ./pharmacyinpatientpage.php?section=Pharmacy&Registration_ID=$Registration_ID&Transaction_Type=$Transaction_Type&Payment_Cache_ID=$Payment_Cache_ID&NR=True&NTF=TruePharmacyWorks=PharmacyWorksThisPage&Check_In_Type=$Check_In_Type&nursecommunication=fromnursecommunication&Check_In_ID=$Check_In_ID");
} else {
    Rollback_Transaction();
    header("Location: ./pharmacyworkspage.php?section=Pharmacy&Registration_ID=$Registration_ID&Transaction_Type=$Transaction_Type&Payment_Cache_ID=$Payment_Cache_ID&NR=True&PharmacyWorks=PharmacyWorksThisPage&Check_In_Type=$Check_In_Type&from_billing_work=$from_billing_work&Check_In_ID=$Check_In_ID");
}

?>
