<?php

session_start();
include("./includes/connection.php");

//get branch id
if (isset($_SESSION['userinfo']['Branch_ID'])) {
    $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
} else {
    $Branch_ID = 0;
}

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

if (isset($_GET['Section'])) {
    $Section = $_GET['Section'];
} else {
    $Section = '';
}
$Clinic_ID='';
$is_send_to_cashier = false;
$filter = " AND Employee_ID = '$Employee_ID'";
$table = "tbl_reception_items_list_cache";
$filterId = "Registration_ID = '$Registration_ID'";
$Patient_Payment_Cache_ID = 0;
$sponsor_id_cache = 0;
$Billing_Type_cache = '';

if (isset($_GET['src']) && $_GET['src'] == 'send_to_cash') {
    $is_send_to_cashier = true;
    $filter = '';
    $table = "tbl_patient_payment_item_list_cache";
    $gt = mysqli_query($conn,"SELECT Patient_Payment_Cache_ID,Sponsor_ID,Billing_Type FROM tbl_patient_payments_cache WHERE Registration_ID = '$Registration_ID' AND Transaction_status = 'submitted' ORDER BY Patient_Payment_Cache_ID DESC LIMIT 1") or die(mysqli_error($conn));

    $rs2 = $data = mysqli_fetch_array($gt);
    $filterId = "Patient_Payment_Cache_ID='" . $rs2['Patient_Payment_Cache_ID'] . "'";
    $Patient_Payment_Cache_ID = $rs2['Patient_Payment_Cache_ID'];
    $sponsor_id_cache = $rs2['Sponsor_ID'];
    $Billing_Type_cache = $rs2['Billing_Type'];
}

//echo $sponsor_id_cache;exit;


$select = mysqli_query($conn,"select sum((Price-Discount)*Quantity) as Amount from $table where $filterId $filter") or die(mysqli_error($conn) . 'this');
$num = mysqli_num_rows($select);
if ($num > 0) {
    while ($data = mysqli_fetch_array($select)) {
        $Amount = $data['Amount'];
    }
} else {
    $Amount = 0;
}


if ($Amount > 0) {
    
    $insert = mysqli_query($conn,"insert into tbl_bank_transaction_cache(
								Registration_ID, Amount_Required, Employee_ID, Transaction_Date_Time, Transaction_Date) 
							values ('$Registration_ID','$Amount','$Employee_ID',(select now()),(select now()))") or die(mysqli_error($conn) . 'One');
    if ($insert) {

        $select_result = mysqli_query($conn,"select Transaction_ID from tbl_bank_transaction_cache where 
											Registration_ID = '$Registration_ID' and 
											Employee_ID = '$Employee_ID' order by Transaction_ID desc limit 1") or die(mysqli_error($conn) . 'two');
        $no = mysqli_num_rows($select_result);
        if ($no > 0) {
            while ($row = mysqli_fetch_array($select_result)) {
                $Transaction_ID = $row['Transaction_ID'];
            }
        } else {
            $Transaction_ID = 0;
        }

        if ($Transaction_ID != 0) {

            //get Invoice_Number
//            $get_invoice = mysqli_query($conn,"select Invoice_Number from tbl_bank_invoice_numbers where Invoice_ID = '$Transaction_ID'") or die(mysqli_error($conn));
//            $mynum = mysqli_num_rows($get_invoice);
//            if ($mynum > 0) {
//                while ($data2 = mysqli_fetch_array($get_invoice)) {
//                    $Invoice_Number = $data2['Invoice_Number'];
//                }
            $retrieve_rs = mysqli_query($conn,"SELECT hospital_id FROM tbl_system_configuration WHERE Branch_ID='" . $_SESSION['userinfo']['Branch_ID'] . "'");
            $data_hosp_id = mysqli_fetch_assoc($retrieve_rs);
            $hospital_id = $data_hosp_id['hospital_id'];

            $Invoice_Number = str_pad($hospital_id, 2, "0", STR_PAD_LEFT) . str_pad($Transaction_ID, 11, "0", STR_PAD_LEFT);


            //update code
            $update = mysqli_query($conn,"update tbl_bank_transaction_cache set Payment_Code = '$Invoice_Number' where Transaction_ID = '$Transaction_ID'") or die(mysqli_error($conn));
            if ($update) {
                //get items
                $insert_cache = mysqli_query($conn,"select * from $table where $filterId $filter") or die(mysqli_error($conn));
                $mn = mysqli_num_rows($insert_cache);
                if ($mn > 0) {
                    while ($row2 = mysqli_fetch_array($insert_cache)) {
                        $Check_In_Type = $row2['Check_In_Type'];
                        $Billing_Type = $row2['Billing_Type'];
                        $Sponsor_ID = $row2['Sponsor_ID'];
                        $Item_ID = $row2['Item_ID'];
                        $Discount = $row2['Discount'];
                        $Price = $row2['Price'];
                        $Quantity = $row2['Quantity'];
                        $Patient_Direction = $row2['Patient_Direction'];
                        $Consultant = $row2['Consultant'];
                        $Consultant_ID = $row2['Consultant_ID'];
                        $Clinic_ID = $row2['Clinic_ID'];
                        $finance_department_id = $row2['finance_department_id'];
                        $clinic_location_id = $row2['clinic_location_id'];

                        if ($is_send_to_cashier) {
                            $Sponsor_ID = $sponsor_id_cache;
                            $Billing_Type = $Billing_Type_cache;
                        }
                        //move data
                        $move = mysqli_query($conn,"insert into tbl_bank_items_detail_cache(
													Check_In_Type, Billing_Type, Sponsor_ID, 
													Item_ID, Discount, Price, Quantity, 
													Patient_Direction, Consultant, Consultant_ID, 
													Employee_ID, Registration_ID, Transaction_ID,
													Transaction_Date_And_Time, Transaction_Date,Branch_ID,Clinic_ID,finance_department_id,clinic_location_id)

													VALUES ('$Check_In_Type','$Billing_Type','$Sponsor_ID',
															'$Item_ID','$Discount','$Price','$Quantity',
															'$Patient_Direction','$Consultant','$Consultant_ID',
															'$Employee_ID','$Registration_ID','$Transaction_ID',
															(select now()), (select now()), '$Branch_ID','$Clinic_ID','$finance_department_id','$clinic_location_id')") or die(mysqli_error($conn) . 'rrrr');
                    }
                    if ($move) {

                        if ($is_send_to_cashier) {
                            mysqli_query($conn,"delete from tbl_patient_payment_item_list_cache where Patient_Payment_Cache_ID ='$Patient_Payment_Cache_ID'") or die(mysqli_error($conn));
                            mysqli_query($conn,"delete from tbl_patient_payments_cache where Patient_Payment_Cache_ID ='$Patient_Payment_Cache_ID'") or die(mysqli_error($conn));
                        } else {
                            mysqli_query($conn,"delete from tbl_reception_items_list_cache where Employee_ID ='$Employee_ID'") or die(mysqli_error($conn));
                        }
                        $_SESSION['Transaction_ID'] = $Transaction_ID;
                        header("Location: ./crdbtransactiondetails.php?Section=$Section&CRDBTransactionDetails=CRDBTransactionDetailsThisPage&Clinic_ID=$Clinic_ID");
                    }
                }
            }
        }
    }
}
?>