<?php

session_start();
include("./includes/connection.php");
include_once("./functions/items.php");


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

$lookup = '';
$getsource = '';
$location = '';

if (isset($_GET['src']) && $_GET['src'] == 'drinp') {//
    $lookup = 'tbl_inpatient_items_list_cache';
    $getsource = 'drinp';
    $location = 'adhocinpatientpage.php';
} else if (isset($_GET['src']) && $_GET['src'] == 'droup') {
    $lookup = 'tbl_departmental_items_list_cache';
    $getsource = 'droup';
    $location = 'departmentalothersworkspage.php';
} else {
    $location = 'index.php';
}

$select = mysqli_query($conn,"select sum((Price - Discount)*Quantity) as Amount from $lookup where
							Employee_ID = '$Employee_ID' and
							Registration_ID = '$Registration_ID'") or die(mysqli_error($conn) . 'this');
$num = mysqli_num_rows($select);
if ($num > 0) {
    while ($data = mysqli_fetch_array($select)) {
        $Amount = $data['Amount'];
    }
} else {
    $Amount = 0;
}

$_SESSION['HAS_ERROR'] = false;
Start_Transaction();

if ($Amount > 0) {
    $insert = mysqli_query($conn,"insert into tbl_bank_transaction_cache(
								Registration_ID, Amount_Required, Employee_ID, Transaction_Date_Time, Transaction_Date, Source) 
							values ('$Registration_ID','$Amount','$Employee_ID',(select now()),(select now()),'Revenue Center')") or die(mysqli_error($conn));
    if (!$insert) {
        $_SESSION['HAS_ERROR'] = true;
    }
    $select_result = mysqli_query($conn,"select Transaction_ID from tbl_bank_transaction_cache where 
											Registration_ID = '$Registration_ID' and 
											Employee_ID = '$Employee_ID' order by Transaction_ID desc limit 1") or die(mysqli_error($conn));
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
//        $get_invoice = mysqli_query($conn,"select Invoice_Number from tbl_bank_invoice_numbers where Invoice_ID = '$Transaction_ID'") or die(mysqli_error($conn));
//        $mynum = mysqli_num_rows($get_invoice);
//        if ($mynum > 0) {
//            while ($data2 = mysqli_fetch_array($get_invoice)) {
        $retrieve_rs = mysqli_query($conn,"SELECT hospital_id FROM tbl_system_configuration WHERE Branch_ID='" . $_SESSION['userinfo']['Branch_ID'] . "'");
        $data_hosp_id = mysqli_fetch_assoc($retrieve_rs);
        $hospital_id = $data_hosp_id['hospital_id'];
        
        $Invoice_Number = str_pad($hospital_id, 2, "0", STR_PAD_LEFT).str_pad($Transaction_ID, 11, "0", STR_PAD_LEFT);
        //}
//update code
        $update = mysqli_query($conn,"update tbl_bank_transaction_cache set Payment_Code = '$Invoice_Number' where Transaction_ID = '$Transaction_ID'") or die(mysqli_error($conn));
        if (!$update) {
            $_SESSION['HAS_ERROR'] = true;
        }
//get items
        $insert_cache = mysqli_query($conn,"select * from $lookup where
													Employee_ID = '$Employee_ID' and
													Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));

        $Payment_Cache_ID = 0;

        $mn = mysqli_num_rows($insert_cache);
        if ($mn > 0) {
            while ($row2 = mysqli_fetch_array($insert_cache)) {
                $Check_In_Type = $row2['Type_Of_Check_In'];
                $Billing_Type = $row2['Billing_Type'];
                $Sponsor_ID = $row2['Sponsor_ID'];
                $Item_ID = $row2['Item_ID'];
                $Discount = $row2['Discount'];
                $Price = $row2['Price'];
                $Quantity = $row2['Quantity'];
                $Patient_Direction = $row2['Patient_Direction'];
                $Consultant_ID = $row2['Consultant_ID'];
                $Comment = $row2['Comment'];
                $Fast_Track = $row2['Fast_Track'];
                $Sub_Department_ID = $row2['Sub_Department_ID'];
                $finance_department_id = $row2['finance_department_id'];
                $Clinic_ID = $row2['Clinic_ID'];
                $clinic_location_id = $row2['clinic_location_id'];

                if (strtolower($Billing_Type) == 'outpatient cash' || strtolower($Billing_Type) == 'inpatient cash') {
                    $Transaction_Type = 'Cash';
                } else if (strtolower($Billing_Type) == 'outpatient credit' || strtolower($Billing_Type) == 'inpatient credit') {
                    $Transaction_Type = 'Credit';
                }

                if ($getsource == 'droup' || $getsource == 'drinp') {

                    if ($Payment_Cache_ID == 0) {
                        require_once ("./includes/Folio_Number_Generator_temp.php");

                        $query_max_cons_id = mysqli_query($conn,"SELECT MAX(consultation_ID) as consultation_id FROM  tbl_check_in_details WHERE Registration_ID='$Registration_ID'");
                        
                        $consultation_id = mysqli_fetch_assoc($query_max_cons_id)['consultation_id'];


                        if(!empty($consultation_id)){

                            $insert_data_cash = mysqli_query($conn,"INSERT INTO tbl_payment_cache(
                                Registration_ID, Employee_ID,consultation_id,Payment_Date_And_Time,
                                Folio_Number, Sponsor_ID,
                                Billing_Type, Receipt_Date, branch_id,Fast_Track)
                                    
                                values('$Registration_ID','$Employee_ID','$consultation_id',(select now()),
                                        '$Folio_Number','$Sponsor_ID',
                                        '$Billing_Type',(select now()),'$Branch_ID','$Fast_Track')") or die(mysqli_error($conn));
                        }else{
                            $insert_data_cash = mysqli_query($conn,"INSERT INTO tbl_payment_cache(
                                Registration_ID, Employee_ID,Payment_Date_And_Time,
                                Folio_Number, Sponsor_ID,
                                Billing_Type, Receipt_Date, branch_id,Fast_Track)
                                    
                                values('$Registration_ID','$Employee_ID',(select now()),
                                        '$Folio_Number','$Sponsor_ID',
                                        '$Billing_Type',(select now()),'$Branch_ID','$Fast_Track')") or die(mysqli_error($conn));
                        }
                        
                       

                        if (!$insert_data_cash) {
                            $_SESSION['HAS_ERROR'] = true;
                        }

                        $select_data_cash = mysqli_query($conn,"select Payment_Cache_ID from tbl_payment_cache where Registration_ID = '$Registration_ID' and
                                        Employee_ID = '$Employee_ID' order by Payment_Cache_ID desc limit 1") or die(mysqli_error($conn));
                        while ($row_data_cash = mysqli_fetch_array($select_data_cash)) {
                            $Payment_Cache_ID = $row_data_cash['Payment_Cache_ID'];
                        }
                    }

                    $insert_list_cache = mysqli_query($conn,"INSERT INTO tbl_item_list_cache(
                                                Check_In_Type, Item_ID,Price,Discount,
                                                Quantity, Patient_Direction, Consultant_ID,
                                                Payment_Cache_ID, Transaction_Date_And_Time, Doctor_Comment,
                                                Sub_Department_ID, Transaction_Type, Service_Date_And_Time,Transaction_ID
                                                ,Clinic_ID,finance_department_id,clinic_location_id) values(
                                                    '$Check_In_Type','$Item_ID','$Price','$Discount',
                                                    '$Quantity','others','$Consultant_ID',
                                                    '$Payment_Cache_ID',(select now()),
                                                        '$Comment','$Sub_Department_ID','$Transaction_Type',(select now()),'$Transaction_ID','$Clinic_ID','$finance_department_id','$clinic_location_id')") or die(mysqli_error($conn));

                    if (!$insert_list_cache) {
                        $_SESSION['HAS_ERROR'] = true;
                    }
                }

//move data
                $move = mysqli_query($conn,"insert into tbl_bank_items_detail_cache(Check_In_Type, Billing_Type, Sponsor_ID, 
													Item_ID, Discount, Price, Quantity, 
													Patient_Direction, Consultant_ID, 
													Employee_ID, Registration_ID, Transaction_ID,
													Transaction_Date_And_Time, Transaction_Date,Branch_ID,Clinic_ID,finance_department_id,clinic_location_id)

													VALUES ('$Check_In_Type','$Billing_Type','$Sponsor_ID',
															'$Item_ID','$Discount','$Price','$Quantity',
															'Others','$Consultant_ID',
															'$Employee_ID','$Registration_ID','$Transaction_ID',
															(select now()), (select now()), '$Branch_ID','$Clinic_ID','$finance_department_id','$clinic_location_id')") or die(mysqli_error($conn));

                if (!$move) {
                    $_SESSION['HAS_ERROR'] = true;
                }
            }
            if ($move) {
                $delete_cache = mysqli_query($conn,"delete from $lookup where Employee_ID ='$Employee_ID'") or die(mysqli_error($conn));

                if (!$delete_cache) {
                    $_SESSION['HAS_ERROR'] = true;
                }

                if (!$_SESSION['HAS_ERROR']) {
                    Commit_Transaction();
                    $_SESSION['Transaction_ID'] = $Transaction_ID;
                    header("Location: ./crdbtransactiondetails.php?Section=$Section&CRDBTransactionDetails=CRDBTransactionDetailsThisPage");
                } else {
                    Rollback_Transaction();
                    echo "<script>
                                alert('Process Fail! Please Try Again');
                                document.location = '$location';
                        </script>";
                }
            }
        }
    }
}// document.location = '$location';
?>