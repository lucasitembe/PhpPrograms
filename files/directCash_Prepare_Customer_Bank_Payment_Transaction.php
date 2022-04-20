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
$Check_In_Type = '';
$Billing_Type = '';

if (isset($_GET['src']) && $_GET['src'] == 'dircashout') {//
    $getsource = 'dircashout';
    $location = 'DirectCashsearchlistofoutpatientbilling.php?SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage';
    $Check_In_Type = 'Direct cash';
    $Billing_Type = 'Outpatient Cash';
} else if (isset($_GET['src']) && $_GET['src'] == 'dircashinp') {
    $getsource = 'dircashinp';
    $location = 'DirectCashsearchlistinpatientbilling.php?SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage.php';
    $Check_In_Type = 'Direct cash';
    $Billing_Type = 'Inpatient Cash';
} else {
    $location = 'index.php';
}

$select = mysqli_query($conn,"select sum(Amount*Quantity) as Amount from tbl_direct_cash_cache where
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
								Registration_ID, Amount_Required, Employee_ID, Transaction_Date_Time, Transaction_Date) 
							values ('$Registration_ID','$Amount','$Employee_ID',(select now()),(select now()))") or die(mysqli_error($conn) . 'One');

    if (!$insert) {
        $_SESSION['HAS_ERROR'] = true;
    }

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
//        $get_invoice = mysqli_query($conn,"select Invoice_Number from tbl_bank_invoice_numbers where Invoice_ID = '$Transaction_ID'") or die(mysqli_error($conn));
//        $mynum = mysqli_num_rows($get_invoice);
//        if ($mynum > 0) {
//            while ($data2 = mysqli_fetch_array($get_invoice)) {
//                $Invoice_Number = $data2['Invoice_Number'];
//            }

        $retrieve_rs = mysqli_query($conn,"SELECT hospital_id FROM tbl_system_configuration WHERE Branch_ID='" . $_SESSION['userinfo']['Branch_ID'] . "'");
        $data_hosp_id = mysqli_fetch_assoc($retrieve_rs);
        $hospital_id = $data_hosp_id['hospital_id'];

        $Invoice_Number = str_pad($hospital_id, 2, "0", STR_PAD_LEFT) . str_pad($Transaction_ID, 11, "0", STR_PAD_LEFT);

//update code
        $update = mysqli_query($conn,"update tbl_bank_transaction_cache set Payment_Code = '$Invoice_Number' where Transaction_ID = '$Transaction_ID'") or die(mysqli_error($conn));
        if (!$update) {
            $_SESSION['HAS_ERROR'] = true;
        }

//get items
        $insert_cache = mysqli_query($conn,"select *, IF(Quantity IS NULL,1,Quantity) AS Quantity from tbl_direct_cash_cache where
													Employee_ID = '$Employee_ID' and
													Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
        $mn = mysqli_num_rows($insert_cache);
        if ($mn > 0) {
            while ($row2 = mysqli_fetch_array($insert_cache)) {
                $Item_ID = $row2['Item_ID'];
                $Discount = 0;
                $Price = $row2['Amount'];
                $Quantity = $row2['Quantity'];
                $Patient_Direction = 'Others';
                $comments = $row2['Item_Description'];
                $Clinic_ID = $row2['Clinic_ID'];

//move data
                $move = mysqli_query($conn,"insert into tbl_bank_items_detail_cache( 
													Item_ID, Discount, Price, Quantity, 
													Patient_Direction, Consultant_ID, 
													Employee_ID, Registration_ID, Transaction_ID,
													Transaction_Date_And_Time, Transaction_Date,Branch_ID,comments,Clinic_ID)

													VALUES (
															'$Item_ID','$Discount','$Price','$Quantity',
															'Others',$Employee_ID,
															'$Employee_ID','$Registration_ID','$Transaction_ID',
			   												(select now()), (select now()), '$Branch_ID','$comments','$Clinic_ID')") or die(mysqli_error($conn).' hereee');

                if (!$move) {
                    $_SESSION['HAS_ERROR'] = true;
                }
            }
            $delete = mysqli_query($conn,"delete from tbl_direct_cash_cache where Employee_ID ='$Employee_ID'") or die(mysqli_error($conn));

            if (!$delete) {
                $_SESSION['HAS_ERROR'] = true;
            }

            if (!$_SESSION['HAS_ERROR']) {
                //echo "<script>alert('commit')</script>";
                Commit_Transaction();
                $_SESSION['Transaction_ID'] = $Transaction_ID;
                header("Location: ./crdbtransactioncustomerdetails.php?Section=$Section&CRDBTransactionDetails=CRDBTransactionDetailsThisPage&Sponsor_ID=$Sponsor_ID");
            } else {
               //  echo "<script>alert('rollback')</script>";
                Rollback_Transaction();
                echo "<script>
                                alert('Process Fail! Please Try Again');
                                document.location = '$location';
                        </script>";
            }
        }
    }
}
?>