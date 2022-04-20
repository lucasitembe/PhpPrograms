<?php
@session_start();
$Transaction_Type="Cash";
if (isset($_GET['Payment_Cache_ID'])) {
    $Payment_Cache_ID = $_GET['Payment_Cache_ID'];
} else {
    $Payment_Cache_ID = '';
}

if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
} else {
    $Registration_ID = '';
}



$HAS_ERROR = false;
Start_Transaction();

//get all paid items then reduce the number of items from respected sub department
$select_item = mysqli_query($conn,"select ch.Sub_Department_ID,ch.Payment_Item_Cache_List_ID,itm.Last_Buy_Price,ch.Price,itm.Product_Name,itm.Consultation_Type,Quantity,
        Edited_Quantity, Patient_Payment_ID, itm.Item_ID from tbl_item_list_cache ch join tbl_items itm on itm.Item_ID=ch.Item_ID where ch.status = 'paid'  and Payment_Cache_ID = '$Payment_Cache_ID'") or die(mysqli_error($conn) . 'Two');
$num_of_rows = mysqli_num_rows($select_item);

if ($num_of_rows > 0) {
    $Product_Array = array();
    while ($data = mysqli_fetch_array($select_item)) {

        $Consultation_Type = $data['Consultation_Type'];
        $Product_Name = $data['Product_Name'];
        $Quantity = $data['Quantity'];
        $Edited_Quantity = $data['Edited_Quantity'];
        $Item_ID = $data['Item_ID'];
        $Sub_Department_ID = $data['Sub_Department_ID'];
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
        $slct = mysqli_query($conn,"select Item_Balance from tbl_items_balance where Item_ID = '$Item_ID' and Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
        $nm = mysqli_num_rows($slct);
        if ($nm > 0) {
            while ($dt = mysqli_fetch_array($slct)) {
                $Pre_Balance = $dt['Item_Balance'];
            }
        } else {
            $rs1 = mysqli_query($conn,"insert into tbl_items_balance(Item_ID,Sub_Department_ID) values('$Item_ID','$Sub_Department_ID')") or die(mysqli_error($conn));

            if (!$rs1) {
                $HAS_ERROR = true;
            }

            $Pre_Balance = 0;
        }

        $update_balance = mysqli_query($conn,"update tbl_items_balance set Item_Balance = (Item_Balance - '$Temp_Quantity'),
                                        Item_Temporary_Balance  = (Item_Temporary_Balance - '$Temp_Quantity')
                                        where Sub_Department_ID = '$Sub_Department_ID' and
                                        Item_ID = '$Item_ID'") or die(mysqli_error($conn));
        if ($update_balance) {
            //update transaction item from tbl patient payment item list
            $update_status = mysqli_query($conn,"update tbl_patient_payment_item_list set Status = 'Served' 
                                            where Patient_Payment_ID = '$Patient_Payment_ID' and
                                                Item_ID = '$Item_ID'") or die(mysqli_error($conn));
            if (!$update_status) {
                $HAS_ERROR = true;
            }

            $update = mysqli_query($conn,"update tbl_item_list_cache set status = 'dispensed',Dispensor='$employee_id', Dispense_Date_Time = NOW() where
                    status = 'paid' and 					
                        Payment_Cache_ID = '$Payment_Cache_ID' and
                            Transaction_Type = '$Transaction_Type' and
                                Sub_Department_ID = '$Sub_Department_ID' and Item_ID = '$Item_ID' and Check_In_Type='Pharmacy'") or die(mysqli_error($conn));

            if (!$update) {
                $HAS_ERROR = true;
            }
            //if($Pre_Balance > 0){
            //insert data into tbl_stock_ledger_controler for auditing
            $insert_audit = mysqli_query($conn,"insert into tbl_stock_ledger_controler(
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

if (!$HAS_ERROR) {
    Commit_Transaction();
} else {
    Rollback_Transaction();
}