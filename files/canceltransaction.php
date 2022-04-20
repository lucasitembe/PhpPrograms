<?php

@session_start();
include("./includes/connection.php");
include_once './functions/items.php';

//echo $Patient_Payment_Item_List_ID." ".$Type_Of_Check_In." ".$direction." ".$Consultant." ".$Item_Name." ".$Discount; exit;
//select privious record then insert into history table
if (isset($_GET['Patient_Payment_ID'])) {
    $Patient_Payment_ID = $_GET['Patient_Payment_ID'];
} else {
    $Patient_Payment_ID = 0;
}

        if(isset($_GET['cancel_reason'])){
            $cancel_reason= mysqli_real_escape_string($conn,$_GET['cancel_reason']);
        }else{
            $cancel_reason = '';
        }
//get employee id
if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
} else {
    $Employee_ID = 0;
}


if ($Employee_ID != 0 && $Patient_Payment_ID != 0) {
//    echo "hii je?";
    $Product_Array = array();
    mysqli_query($conn,"insert into tbl_cancelled_transaction_details(Cancelled_Date_And_Time,
                        Cancelled_Date,Employee_ID,Patient_Payment_ID,Cancel_transaction_reason)
                        
                        values((select now()),(select now()),'$Employee_ID','$Patient_Payment_ID','$cancel_reason')") or die(mysqli_error($conn));

    mysqli_query($conn,"update tbl_patient_payments set Transaction_status = 'cancelled',Cancel_transaction_reason='$cancel_reason'
                        where Patient_Payment_ID = '$Patient_Payment_ID'") or die(mysqli_error($conn));
//    mysqli_query($conn,"update tbl_item_list_cache set Status = 'cancelled'
//                        where Patient_Payment_ID = '$Patient_Payment_ID'") or die(mysqli_error($conn));
    $patient_payment_sql = mysqli_query($conn,"SELECT Registration_ID,Transaction_type FROM tbl_patient_payments WHERE Patient_Payment_ID='$Patient_Payment_ID'");
    $patient_payment_sql_data = mysqli_fetch_assoc($patient_payment_sql);
    $Registration_ID = $patient_payment_sql_data['Registration_ID'];
    $Transaction_type = $patient_payment_sql_data['Transaction_type'];
    $getAllItems = mysqli_query($conn,"SELECT itm.Last_Buy_Price,ch.Price,itm.Product_Name,itm.Consultation_Type,ch.Payment_Item_Cache_List_ID,Check_In_Type,ch.Item_ID,IF(Edited_Quantity > 0 ,Edited_Quantity,Quantity) AS Quantity,Sub_Department_ID,ch.Status FROM tbl_item_list_cache ch join tbl_items itm on itm.Item_ID=ch.Item_ID WHERE Patient_Payment_ID='$Patient_Payment_ID'") or die(mysqli_error($conn));
    $num_rows = mysqli_num_rows($getAllItems);
    
    if ($num_rows > 0) {
        while ($result = mysqli_fetch_assoc($getAllItems)) {
            $checkin = $result['Check_In_Type'];
            $Item_ID = $result['Item_ID'];
            $Quantity = $result['Quantity'];
            $Sub_Department_ID = $result['Sub_Department_ID'];
            $Status = $result['Status'];
            $Payment_Item_Cache_List_ID = $result['Payment_Item_Cache_List_ID'];
            
           
            if ($checkin == 'Pharmacy' && $Status == 'dispensed') {
               $Document_Number = $Patient_Payment_ID;
               
              $status = Update_Item_Balance($Item_ID, $Sub_Department_ID, 'Dispensed', null, null, $Registration_ID, $Document_Number, Get_Time_Now(), $Quantity , true);
           
            } elseif ($checkin == 'Laboratory' && $Status == 'Sample Collected') {
                $deleteResult = mysqli_query($conn,"DELETE FROM tbl_specimen_results WHERE payment_item_ID='" . $Payment_Item_Cache_List_ID . "'")  or die(mysqli_error($conn));
                $deleteResult2 = mysqli_query($conn,"DELETE FROM tbl_test_results WHERE payment_item_ID='" . $Payment_Item_Cache_List_ID . "'") or die(mysqli_error($conn));
            }

            mysqli_query($conn,"UPDATE tbl_item_list_cache SET Status='active',Patient_Payment_ID=NULL,Payment_Date_And_Time=NULL WHERE Payment_Item_Cache_List_ID='" . $Payment_Item_Cache_List_ID . "'") or die(mysqli_error($conn));
            //Array to be sent to gaccounting
            $Product_Name_Array = array(
                'ref_no' => $Patient_Payment_ID,
                'source_name' => 'ehms_phamarcy_despense',
                'comment' => $Product_Name . ", " . $Quantity . " item(s) @ " . number_format($unit_price, 2) . " Tsh.",
                'debit_entry_ledger' => 'Pharmacy-COGS',
                'credit_entry_ledger' => 'Pharmacy-INVENTORY',
                'sub_total' => "",
                'source_id' => $Patient_Payment_ID,
                'Employee_Name' => $_SESSION['userinfo']['Employee_Name'],
                'Employee_ID' => $_SESSION['userinfo']['Employee_ID']
            );

            array_push($Product_Array, $Product_Name_Array);
        }
             $endata = json_encode($Product_Array);
             $acc = gAccJournalEntry($endata, 'cancel');
//             echo $acc;
//             exit;
             //print_r($Product_Array);
    }
    if(strtolower($Transaction_type)==="direct cash"){
        $Product_Name_Array = array(
                'ref_no' => $Patient_Payment_ID,
                'source_name' => 'ehms_sales_cash',
                'comment' => "",
                'debit_entry_ledger' => '',
                'credit_entry_ledger' => '',
                'sub_total' => "",
                'source_id' => $Patient_Payment_ID,
                'Employee_Name' => $_SESSION['userinfo']['Employee_Name'],
                'Employee_ID' => $_SESSION['userinfo']['Employee_ID']
            );
        array_push($Product_Array, $Product_Name_Array);
        $endata = json_encode($Product_Array);
        $acc = gAccJournalEntry($endata, 'cancel');
    }
    
//  echo "<script>
//          alert(".$acc.");
//          </script>";
//  exit();
    echo "<script>
            document.location = 'patientbillingcancel.php?Patient_Payment_ID=" . $Patient_Payment_ID . "&Insurance=NHIF&Registration_ID=9&Selected=Selected&EditPayment=EditPaymentThisForm';
          </script>";
}
//header("Location: ./patientbillingedit.php?Patient_Payment_ID=$Patient_Payment_ID&Registration_ID=$Registration_ID&Insurance=$Sponsor_Name&Selected=Selected&EditPayment=EditPaymentThisForm");
?>