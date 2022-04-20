<?php

$location = '';
if (isset($_GET['location']) && $_GET['location'] == 'otherdepartment') {
    $location = 'location=otherdepartment&';
}

@session_start();
include("./includes/connection.php");

//get employee id
if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
} else {
    $Employee_ID = 0;
}

if (isset($_SESSION['supervisor'])) {
    $Supervisor_ID = $_SESSION['supervisor']['Employee_ID'];
} else {
    $Supervisor_ID = $Employee_ID;
}

//get branch id
if (isset($_SESSION['userinfo']['Branch_ID'])) {
    $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
} else {
    $Branch_ID = 0;
}


//get registration id
if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
} else {
    $Registration_ID = 0;
}


$slct = mysqli_query($conn,"select enab_auto_billing from  tbl_sponsor sp,tbl_patient_registration pr where
                            pr.Sponsor_ID = sp.Sponsor_ID and
                            pr.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
$n_rows = mysqli_num_rows($slct);
//echo $n_rows;
//exit();
if ($n_rows > 0) {
    while ($dtz = mysqli_fetch_array($slct)) {
        $enab_auto_billing = strtolower($dtz['enab_auto_billing']);
    }
} else {
    $enab_auto_billing = 'no';
}

//get folio number & claim form number
$select = mysqli_query($conn,"select Folio_Number, Claim_Form_Number from tbl_patient_payments where Registration_ID = '$Registration_ID' order by Patient_Payment_ID desc limit 1") or die(mysqli_error($conn));
$nums = mysqli_num_rows($select);
if ($nums > 0) {
    while ($row = mysqli_fetch_array($select)) {
        $Folio_Number = $row['Folio_Number'];
        $Claim_Form_Number = $row['Claim_Form_Number'];
    }
} else {
    $Folio_Number = 0;
    $Claim_Form_Number = '';
}

if (empty($Claim_Form_Number)) {
    $clm = mysqli_query($conn,"SELECT Claim_Form_Number  FROM tbl_check_in_details WHERE Registration_ID = '$Registration_ID' order by Check_In_Details_ID desc limit 1") or die(mysqli_error($conn));
    $row2 = mysqli_fetch_array($clm);
    $Claim_Form_Number = $row2['Claim_Form_Number'];
}


//get today date
$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;

    if ($Employee_ID != 0 && $Registration_ID != 0 && $Branch_ID != 0) {
        //select all data from the departmental cache table
        $select = mysqli_query($conn,"select * from tbl_inpatient_items_list_cache where Registration_ID = '$Registration_ID' and
                                Employee_ID = '$Employee_ID' ") or die(mysqli_error($conn));
        $num_rows = mysqli_num_rows($select);
        if ($num_rows > 0) {
            while ($data = mysqli_fetch_array($select)) {
                $Sponsor_ID = $data['Sponsor_ID'];
                $Sponsor_Name = $data['Sponsor_Name'];
                $Billing_Type = $data['Billing_Type'];
            }


            //generate transaction type
            if (strtolower($Billing_Type) == 'inpatient cash') {
                $Transaction_Type = 'Cash';
            } else if (strtolower($Billing_Type) == 'inpatient credit') {
                $Transaction_Type = 'Credit';
            }

            //insert data to payment cache
            $insert_data = mysqli_query($conn,"INSERT INTO tbl_payment_cache(
                                        Registration_ID, Employee_ID, Payment_Date_And_Time,
                                        Folio_Number, Sponsor_ID, Sponsor_Name,
                                        Billing_Type, Receipt_Date, branch_id)
                                            
                                        values('$Registration_ID','$Employee_ID',(select now()),
                                                '$Folio_Number','$Sponsor_ID','$Sponsor_Name',
                                                '$Billing_Type',(select now()),'$Branch_ID')") or die(mysqli_error($conn));
            if ($insert_data) {
                //get the last Payment_Cache_ID (foreign key)
                $select = mysqli_query($conn,"select Payment_Cache_ID from tbl_payment_cache where Registration_ID = '$Registration_ID' and
                                        Employee_ID = '$Employee_ID' order by Payment_Cache_ID desc limit 1") or die(mysqli_error($conn));
                $no = mysqli_num_rows($select);
                if ($no > 0) {
                    while ($row = mysqli_fetch_array($select)) {
                        $Payment_Cache_ID = $row['Payment_Cache_ID'];
                    }
                    //insert data
                    $select_details = mysqli_query($conn,"select * from tbl_inpatient_items_list_cache
                                                    where Registration_ID = '$Registration_ID' and
                                                        Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
                    $numrows = mysqli_num_rows($select_details);
                    if ($numrows > 0) {
                        while ($dt = mysqli_fetch_array($select_details)) {
                            $Item_ID = $dt['Item_ID'];
                            $Price = $dt['Price'];
                            $Quantity = $dt['Quantity'];
                            $Discount = $dt['Discount'];
                            $Consultant_ID = $Employee_ID;
                            $Comment = $dt['Comment'];
                            $Sub_Department_ID = $dt['Sub_Department_ID'];
                            $Type_Of_Check_In = $dt['Type_Of_Check_In'];

                            $insert = mysqli_query($conn,"INSERT INTO tbl_item_list_cache(
                                                Check_In_Type, Item_ID,Price,Discount,
                                                Quantity, Patient_Direction, Consultant_ID,
                                                Payment_Cache_ID, Transaction_Date_And_Time, Doctor_Comment,
                                                Sub_Department_ID, Transaction_Type, Service_Date_And_Time
                                                ) values(
                                                    '$Type_Of_Check_In','$Item_ID','$Price','$Discount',
                                                    '$Quantity','others','$Consultant_ID',
                                                    '$Payment_Cache_ID',NOW(),
                                                    '$Comment','$Sub_Department_ID','$Transaction_Type',NOW())") or die(mysqli_error($conn));
                        }
                    }

                    if ($insert) {
                        //check if direct repartmental payment auto bill is enabled as well as sponsor Auto Billing then bill
                        if (isset($_SESSION['systeminfo']['Allow_Direct_Departmental_Payments_Auto_Billing']) && strtolower($_SESSION['systeminfo']['Allow_Direct_Departmental_Payments_Auto_Billing']) == 'yes' && $enab_auto_billing == 'yes' && (strtolower($Billing_Type) == 'inpatient credit' || (strtolower($Billing_Type) == 'inpatient cash' && $_SESSION['systeminfo']['Inpatient_Prepaid'] == 'no'))) {
                            //insert data
                            //get the last check in id
                           
                            $get_check_id = mysqli_query($conn,"select Check_In_ID from tbl_check_in where Registration_ID = '$Registration_ID' order by Check_In_ID desc limit 1") or die(mysqli_error($conn));
                            $nums = mysqli_num_rows($get_check_id);
                            if ($nums > 0) {
                                while ($dta = mysqli_fetch_array($get_check_id)) {
                                    $Check_In_ID = $dta['Check_In_ID'];
                                }
                            } else {
                                //try to fetch it from tbl_patient_payments
                                $slct = mysqli_query($conn,"select Check_In_ID from tbl_patient_payments where Registration_ID = '$Registration_ID' order by Patient_Payment_ID desc limit 1") or die(mysqli_error($conn));
                                $num_slct = mysqli_num_rows($slct);
                                if ($num_slct > 0) {
                                    while ($rt = mysqli_fetch_array($slct)) {
                                        $Check_In_ID = $rt['Check_In_ID'];
                                    }
                                } else {
                                    $insert2 = mysqli_query($conn,"insert into tbl_check_in(Registration_ID, Visit_Date,Employee_ID, 
                                                            check_in_date_and_time, Check_In_Status, Branch_ID, 
                                                            Saved_Date_And_Time, Check_In_Date, Type_Of_Check_In, Folio_Status) 
                                                        values ('$Registration_ID',(select now()),'$Employee_ID',
                                                                (select now()),'saved','$Branch_ID',
                                                                (select now()),(select now()),'Afresh','generated')") or die(mysqli_error($conn));

                                    if ($insert2) {
                                        $get_check_id = mysqli_query($conn,"select Check_In_ID from tbl_check_in where Registration_ID = '$Registration_ID' 
                                                                        order by Check_In_ID desc limit 1") or die(mysqli_error($conn));
                                        $nums = mysqli_num_rows($get_check_id);
                                        if ($nums > 0) {
                                            while ($dta = mysqli_fetch_array($get_check_id)) {
                                                $Check_In_ID = $dta['Check_In_ID'];
                                            }
                                        } else {
                                            $Check_In_ID = 0;
                                        }
                                    }
                                }
                            }

                            //get the last patient bill number
                            $get_bill_id = mysqli_query($conn,"select Patient_Bill_ID from tbl_patient_bill where Registration_ID = '$Registration_ID' order by Patient_Bill_ID desc limit 1") or die(mysqli_error($conn));
                            $numss = mysqli_num_rows($get_bill_id);
                            if ($numss > 0) {
                                while ($mydata = mysqli_fetch_array($get_bill_id)) {
                                    $Patient_Bill_ID = $mydata['Patient_Bill_ID'];
                                }
                            } else {
                                $insert3 = mysqli_query($conn,"insert into tbl_patient_bill(Registration_ID,Date_Time,Patient_Status) values('$Registration_ID',(select now()),'Inpatient')") or die(mysqli_error($conn));
                                if ($insert3) {
                                    $get_bill_id = mysqli_query($conn,"select Patient_Bill_ID from tbl_patient_bill where Registration_ID = '$Registration_ID' order by Patient_Bill_ID desc limit 1") or die(mysqli_error($conn));
                                    $numss = mysqli_num_rows($get_bill_id);
                                    if ($numss > 0) {
                                        while ($mydata = mysqli_fetch_array($get_bill_id)) {
                                            $Patient_Bill_ID = $mydata['Patient_Bill_ID'];
                                        }
                                    }
                                }
                            }

                            include("./includes/Get_Patient_Hospital_Ward_ID.php");
                            //insert details to tbl_patient_payments
                            $insert_details = mysqli_query($conn,"insert into tbl_patient_payments(
                                                        Registration_ID, Supervisor_ID, Employee_ID,
                                                        Payment_Date_And_Time, Folio_Number,
                                                        Claim_Form_Number, Sponsor_ID, Sponsor_Name,
                                                        Billing_Type, Receipt_Date, branch_id,Check_In_ID,Patient_Bill_ID,payment_type,Hospital_Ward_ID)
                                                        
                                                        values ('$Registration_ID','$Supervisor_ID','$Employee_ID',
                                                            (select now()),'$Folio_Number',
                                                            '$Claim_Form_Number','$Sponsor_ID','$Sponsor_Name',
                                                            '$Billing_Type',(select now()),'$Branch_ID','$Check_In_ID','$Patient_Bill_ID','post','$Hospital_Ward_ID')") or die(mysqli_error($conn));


                            if ($insert_details) {
                                //get the last Patient Payment ID
                                $select = mysqli_query($conn,"select Patient_Payment_ID, Receipt_Date, Payment_Date_And_Time from tbl_patient_payments
                                                        where Registration_ID = '$Registration_ID' and
                                                            Employee_ID = '$Employee_ID' order by Patient_Payment_ID desc limit 1") or die(mysqli_error($conn));
                                $num = mysqli_num_rows($select);
                                if ($num > 0) {
                                    while ($row = mysqli_fetch_array($select)) {
                                        $Patient_Payment_ID = $row['Patient_Payment_ID'];
                                        $Receipt_Date = $row['Receipt_Date'];
                                        $Payment_Date_And_Time = $row['Payment_Date_And_Time'];
                                    }

                                    //insert data to tbl_patient_payment_item_list
                                    $select_details = mysqli_query($conn,"select * from tbl_inpatient_items_list_cache
                                                        where Registration_ID = '$Registration_ID' and
                                                        Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));

                                    while ($dt = mysqli_fetch_array($select_details)) {
                                        $Item_ID = $dt['Item_ID'];
                                        $Price = $dt['Price'];
                                        $Discount = $dt['Discount'];
                                        $Quantity = $dt['Quantity'];
                                        $Comment = $dt['Comment'];
                                        $Type_Of_Check_In = $dt['Type_Of_Check_In'];
                                        $insert = mysqli_query($conn,"insert into tbl_patient_payment_item_list(
                                                                Check_In_Type, Item_ID, Price,Discount,
                                                                Quantity, Patient_Direction, Consultant,
                                                                Consultant_ID, Patient_Payment_ID, Transaction_Date_And_Time)
                                                                
                                                                values ('$Type_Of_Check_In','$Item_ID','$Price','$Discount',
                                                                '$Quantity','others','others',
                                                                '$Consultant_ID', '$Patient_Payment_ID', (select now()))") or die(mysqli_error($conn));
                                    }
                                    if ($insert) {
                                        //update tbl_item_list_cache
                                        mysqli_query($conn,"update tbl_item_list_cache set Status = 'paid',
                                                        Patient_Payment_ID = '$Patient_Payment_ID',
                                                            Payment_Date_And_Time = '$Receipt_Date' where
                                                                Payment_Cache_ID = '$Payment_Cache_ID'") or die(mysqli_error($conn));
                                        //delete all data from cache
                                        mysqli_query($conn,"delete from tbl_inpatient_items_list_cache where Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
                                        header("Location: ./departmentalothersworks.php?Section=Inpatient&Registration_ID=$Registration_ID&Patient_Payment_ID=$Patient_Payment_ID&PatientBilling=PatientBillingThisPage");
                                    } else {
                                        header("Location: ./adhocinpatientpage.php?" . $location . "Registration_ID=$Registration_ID&AdhocInpatient=AdhocInpatientThisForm");
                                    }
                                }
                            }
                        } else {
						
						//echo 'something is here....';
                            //delete all data from cache
                            mysqli_query($conn,"delete from tbl_inpatient_items_list_cache where Registration_ID = '$Registration_ID' and Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
                            header("Location: ./adhocinpatientlist.php?" . $location . "AdhocInpatientList=AdhocInpatientListThisPage");
                        }
                    } else {
                        header("Location: ./adhocinpatientpage.php?" . $location . "Registration_ID=$Registration_ID&AdhocInpatient=AdhocInpatientThisForm");
                    }
                }
            }
        }
    }
}
?>