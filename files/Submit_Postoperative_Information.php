<?php

session_start();
include("./includes/connection.php");

if (isset($_GET['Payment_Item_Cache_List_ID'])) {
    $Payment_Item_Cache_List_ID = $_GET['Payment_Item_Cache_List_ID'];
} else {
    $Payment_Item_Cache_List_ID = 0;
}

if (isset($_SESSION['userinfo']['Branch_ID'])) {
    $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
} else {
    $Branch_ID = 0;
}

if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
} else {
    $Employee_ID = 0;
}

if (isset($_GET['Patient_Payment_Item_List_ID'])) {
    $Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];
} else {
    $Patient_Payment_Item_List_ID = 0;
}

if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
} else {
    $Registration_ID = 0;
}

if (isset($_GET['Position'])) {
    $Position = $_GET['Position'];
} else {
    $Position = 0;
}

if (isset($_GET['Type_Of_Anesthetic'])) {
    $Type_Of_Anesthetic = $_GET['Type_Of_Anesthetic'];
} else {
    $Type_Of_Anesthetic = 0;
}

if (isset($_GET['Patient_Payment_ID'])) {
    $Patient_Payment_ID = $_GET['Patient_Payment_ID'];
} else {
    $Patient_Payment_ID = 0;
}

if (isset($_GET['Sponsor_ID'])) {
    $Sponsor_ID = $_GET['Sponsor_ID'];
} else {
    $Sponsor_ID = 0;
}

//today's date
$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
}

//get patient details
$select = mysqli_query($conn,"SELECT Folio_Number, Check_In_ID, Claim_Form_Number, Sponsor_ID, Patient_Bill_ID from
							tbl_patient_payments where
							Patient_Payment_ID = '$Patient_Payment_ID'") or die(mysqli_error($conn));
$num = mysqli_num_rows($select);
if ($num > 0) {
    while ($data = mysqli_fetch_array($select)) {
        $Folio_Number = $data['Folio_Number'];
        $Check_In_ID = $data['Check_In_ID'];
        $Claim_Form_Number = $data['Claim_Form_Number'];
        $Sponsor_ID = $data['Sponsor_ID'];
        $Patient_Bill_ID = $data['Patient_Bill_ID'];
    }
} else {
    $Claim_Form_Number = '';

    //generate new folio number
    include("./includes/Folio_Number_Generator.php");

    //create new check in id
    $select = mysqli_query($conn,"SELECT Check_In_ID from tbl_check_in where
								Registration_ID = '$Registration_ID' order by Check_In_ID desc limit 1") or die(mysqli_error($conn));
    $nm = mysqli_num_rows($select);
    if ($nm > 0) {
        while ($row = mysqli_fetch_array($select)) {
            $Check_In_ID = $row['Check_In_ID'];
        }
    } else {
        // $insert = mysqli_query($conn,"INSERT into tbl_check_in(Registration_ID, Visit_Date, Employee_ID,
		// 							Check_In_Date_And_Time, Check_In_Status, Branch_ID, Check_In_Date,
		// 							Type_Of_Check_In, Folio_Status)
		// 						values('$Registration_ID',(select now()),'$Employee_ID',
		// 							(select now()),'saved','$Branch_ID',(select now()),
		// 							'Afresh','generated')") or die(mysqli_error($conn));
        // if ($insert) {
            $select = mysqli_query($conn,"SELECT Check_In_ID from tbl_check_in where Registration_ID = '$Registration_ID' order by Check_In_ID DESC limit 1") or die(mysqli_error($conn));
            $nm = mysqli_num_rows($select);
            if ($nm > 0) {
                while ($row = mysqli_fetch_array($select)) {
                    $Check_In_ID = $row['Check_In_ID'];
                }
            } else {
                $Check_In_ID = 0;
            }
        
    }

    //create new patient bill id
    $select = mysqli_query($conn,"select Patient_Bill_ID from tbl_patient_bill where Registration_ID = '$Registration_ID' order by Patient_Bill_ID desc limit 1") or die(mysqli_error($conn));
    $nm = mysqli_num_rows($select);
    if ($nm > 0) {
        while ($data = mysqli_fetch_array($select)) {
            $Patient_Bill_ID = $data['Patient_Bill_ID'];
        }
    } else {
        $insert = mysqli_query($conn,"insert into tbl_patient_bill(Registration_ID, Date_Time)
									values('$Registration_ID',(select now()))") or die(mysqli_error($conn));
        if ($insert) {
            $select = mysqli_query($conn,"select Patient_Bill_ID from tbl_patient_bill where Registration_ID = '$Registration_ID' order by Patient_Bill_ID desc limit 1") or die(mysqli_error($conn));
            $no = mysqli_num_rows($select);
            if ($no > 0) {
                while ($row = mysqli_fetch_array($select)) {
                    $Patient_Bill_ID = $row['Patient_Bill_ID'];
                }
            } else {
                $Patient_Bill_ID = 0;
            }
        } else {
            $Patient_Bill_ID = 0;
        }
    }
}

//get transaction status
$check = mysqli_query($conn,"select ilc.Status, ilc.Transaction_Type, pc.Billing_Type, ilc.Price, ilc.Quantity, ilc.Discount, Item_ID, ilc.payment_type, ilc.Consultant, ilc.Consultant_ID,ilc.Patient_Payment_ID,pc.Sponsor_ID from 
							tbl_payment_cache pc, tbl_item_list_cache ilc where
							pc.Payment_Cache_ID = ilc.Payment_Cache_ID and
							ilc.Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
$num_check = mysqli_num_rows($check);

if ($num_check > 0) {
    while ($data = mysqli_fetch_array($check)) {
        $Status = $data['Status'];
        $Transaction_Type = $data['Transaction_Type'];
        $Billing_Type = $data['Billing_Type'];
        $Price = $data['Price'];
        $Discount = $data['Discount'];
        $Quantity = $data['Quantity'];
        $payment_type = $data['payment_type'];
        $Item_ID = $data['Item_ID'];
        $Consultant = $data['Consultant'];
        $Consultant_ID = $data['Consultant_ID'];
        $PatientPaymentID = $data['Patient_Payment_ID'];
        $SponsorID = $data['Sponsor_ID'];
    }
} else {
    $Status = '';
    $Transaction_Type = '';
    $Billing_Type = '';
    $Price = 0;
    $Discount = 0;
    $Quantity = 0;
    $payment_type = 'pre';
    $Item_ID = '';
    $Consultant = '';
    $Consultant_ID = null;
    $SponsorID = '';
    $PatientPaymentID = '';
}

if ($Quantity == 0) {
    $Quantity = 1;
}
$Insert_Control = 'no';
if ((strtolower($Billing_Type) == 'outpatient cash' || strtolower($Billing_Type) == 'outpatient credit') && strtolower($Transaction_Type) == 'cash' && strtolower($Status) == 'active') {
    $Insert_Control = 'yes';
    $Temp_Billing_Type = 'Outpatient Cash';
} else if ((strtolower($Billing_Type) == 'outpatient cash' || strtolower($Billing_Type) == 'outpatient credit') && strtolower($Transaction_Type) == 'credit' && strtolower($Status) == 'active') {
    $Insert_Control = 'yes';
    $Temp_Billing_Type = 'Outpatient Credit';
} else if ((strtolower($Billing_Type) == 'inpatient cash' || strtolower($Billing_Type) == 'inpatient credit') && strtolower($Transaction_Type) == 'cash' && strtolower($Status) == 'active') {
    $Insert_Control = 'yes';
    $Temp_Billing_Type = 'Inpatient Cash';
} else if ((strtolower($Billing_Type) == 'inpatient cash' || strtolower($Billing_Type) == 'inpatient credit') && strtolower($Transaction_Type) == 'credit' && strtolower($Status) == 'active') {
    $Insert_Control = 'yes';
    $Temp_Billing_Type = 'Inpatient Credit';
}

if (!is_null($PatientPaymentID) || !empty($PatientPaymentID)) {

    $checkIfAutoBilling = mysqli_query($conn,"SELECT enab_auto_billing FROM tbl_sponsor WHERE Sponsor_ID = '" . $SponsorID . "' and  enab_auto_billing='yes'") or die(mysqli_error($conn));

    if (mysqli_num_rows($checkIfAutoBilling) > 0) {
        $Insert_Control = 'no';
    }
}



if ($Insert_Control == 'yes') {
    //make payment
    $sql = mysqli_query($conn,"insert into tbl_patient_payments(Registration_ID, Supervisor_ID, Employee_ID, Payment_Date_And_Time,
								Folio_Number, Check_In_ID, Claim_Form_Number, Sponsor_ID,
								Billing_Type, Receipt_Date, payment_type, branch_id,Patient_Bill_ID)
							values('$Registration_ID','$Employee_ID','$Employee_ID',(select now()),
								'$Folio_Number','$Check_In_ID','$Claim_Form_Number','$Sponsor_ID',
								'$Temp_Billing_Type',(select now()),'$payment_type','$Branch_ID','$Patient_Bill_ID')") or die(mysqli_error($conn));

    //get Receipt Number && Receipt Date
    $slct = mysqli_query($conn,"select Patient_Payment_ID, Payment_Date_And_Time from tbl_patient_payments where 
								Registration_ID = '$Registration_ID' and Employee_ID = '$Employee_ID' order by Patient_Payment_ID desc limit 1") or die(mysqli_error($conn));
    $mn = mysqli_num_rows($slct);
    if ($mn > 0) {
        while ($dtz = mysqli_fetch_array($slct)) {
            $Patient_Payment_ID = $dtz['Patient_Payment_ID'];
            $Payment_Date_And_Time = $dtz['Payment_Date_And_Time'];
        }

        //insert data into tbl_patient_payment_item_list
        $insert_data = mysqli_query($conn,"insert into tbl_patient_payment_item_list(
										check_In_type,item_id,discount,
										price,quantity,patient_direction,
										consultant,consultant_id,patient_payment_id,Transaction_Date_And_Time)
										values('Surgery','$Item_ID','$Discount',
										'$Price','$Quantity','Others',
										'$Consultant','$Consultant_ID','$Patient_Payment_ID',(select now()))") or die(mysqli_error($conn));
    } else {
        $Patient_Payment_ID = '';
        $Payment_Date_And_Time = '';
    }

    //update tbl_item_list_cache
    $update = mysqli_query($conn,"update tbl_item_list_cache set ServedDateTime = (select now()), 
								ServedBy = '$Employee_ID', Patient_Payment_ID = '$Patient_Payment_ID',
								Payment_Date_And_Time = '$Payment_Date_And_Time', Status = 'served' where Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
    if ($update) {

        $Delete_ID = mysqli_query($conn, "UPDATE tbl_surgery_appointment SET Surgery_Status = 'Completed', Final_Decision = 'Accepted' WHERE Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'");

        $update2 = mysqli_query($conn,"update tbl_post_operative_notes set 
									Post_Operative_Status = 'Saved',
									Position = '$Position',
									Type_Of_Anesthetic = '$Type_Of_Anesthetic' where Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
    }
} else {
    //update tbl_item_list_cache
    $update = mysqli_query($conn,"update tbl_item_list_cache set ServedDateTime = (select now()), 
								ServedBy = '$Employee_ID', Status = 'served' where Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
    if ($update) {
        $Delete_ID = mysqli_query($conn, "UPDATE tbl_surgery_appointment SET Surgery_Status = 'Completed', Final_Decision = 'Accepted' WHERE Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'");

        $update2 = mysqli_query($conn,"UPDATE tbl_post_operative_notes set Post_Operative_Status = 'Saved',
									Position = '$Position', Type_Of_Anesthetic = '$Type_Of_Anesthetic' where 
									Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
    }
}
?>
