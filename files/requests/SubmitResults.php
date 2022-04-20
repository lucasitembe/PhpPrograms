<?php

session_start();
include("../includes/connection.php");
if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
} else {
    $Employee_ID = 0;
}

$val = $_POST['payID'];
$explode = explode(",", $val);
$message = '';
$status = '';
$total_Item = count($explode);
$counter = 0;

foreach ($explode as $value) {
    $checkExist = "SELECT * FROM tbl_test_results WHERE payment_item_ID='" . $value . "' AND collection_Status='collected'";
    $GetNumber = mysqli_query($conn,$checkExist);
    $numrows = mysqli_num_rows($GetNumber);
    if ($numrows > 0) {
        $message = 'Sucessfully submitted to Results Team';
    } else {
        $checkPAymentStatus = "SELECT * FROM tbl_item_list_cache as ilc INNER JOIN tbl_payment_cache as pc ON ilc.Payment_Cache_ID=pc.Payment_Cache_ID JOIN tbl_sponsor sp ON sp.Sponsor_ID=pc.Sponsor_ID  WHERE Payment_Item_Cache_List_ID='" . $value . "'";
        $paymentQuery = mysqli_query($conn,$checkPAymentStatus);
        $rows = mysqli_fetch_assoc($paymentQuery);
        $iniStatus=$rows['Status'];
        
        if (($rows['Billing_Type'] == 'Outpatient Cash') && ($rows['Status'] == 'active') && ($rows['Transaction_Type'] == 'Cash')) {
            $message = 'Payment not done for this test';
        } else if (($rows['Billing_Type'] == 'Outpatient Credit') && ($rows['Status'] == 'active') && ($rows['Transaction_Type'] == 'Cash')) {
            $message = 'Payment not done for this test';
        } else if (($rows['Billing_Type'] == 'Inpatient Cash') && ($rows['Status'] == 'active') && ($rows['payment_type'] == 'pre')) {
            $message = 'Payment not done for this test';
        } else {
            $checkSpecimenCollection = mysqli_query($conn,"SELECT * FROM tbl_specimen_results WHERE payment_item_ID='" . $value . "' AND collection_Status='collected'");
            $numItems = mysqli_num_rows($checkSpecimenCollection);
            if ($numItems > 0) {
                $insert = "INSERT INTO tbl_test_results (payment_item_ID,Employee_ID,TimeSubmitted,organismName,reagentUsed,resultType,datesaved,remarks,labTechn) VALUES('$value','$Employee_ID',NOW(),'','','',NOW(),'',0)";
                $result = mysqli_query($conn,$insert) or die(mysqli_error($conn));
                if ($result) {
                    if (($rows['Billing_Type'] == 'Inpatient Cash') && ($rows['Status'] == 'paid')) {
                        $setUpdates = "UPDATE tbl_item_list_cache SET Status='Sample Collected' WHERE Payment_Item_Cache_List_ID='" . $value . "'";
                        $updateQuery = mysqli_query($conn,$setUpdates) or die(mysqli_error($conn));
                        if ($updateQuery) {
                            $status = 'Note_Completed';
                            $message = "Sucessfully submitted to Results Team!";
                        } else {
                            $status = 'Note_Completed';
                            $message = mysqli_error($conn);
                        }
                    } elseif (($rows['Billing_Type'] == 'Outpatient Credit') && ($rows['Status'] == 'paid') && $rows['Require_Document_To_Sign_At_receiption'] == 'Mandatory') {
                        $setUpdates = "UPDATE tbl_item_list_cache SET Status='Sample Collected' WHERE Payment_Item_Cache_List_ID='" . $value . "'";
                        $updateQuery = mysqli_query($conn,$setUpdates) or die(mysqli_error($conn));
                        if ($updateQuery) {
                            $status = 'Note_Completed';
                            $message = "Sucessfully submitted to Results Team!";
                        } else {
                            $status = 'Note_Completed';
                            $message = mysqli_error($conn);
                        }
                    } else {
                        $setUpdates = "UPDATE tbl_item_list_cache SET Status='Sample Collected' WHERE Payment_Item_Cache_List_ID='" . $value . "'";
                        $updateQuery = mysqli_query($conn,$setUpdates) or die(mysqli_error($conn));
                        if ($updateQuery) {
                            $checkPAymentStatus = "SELECT * FROM tbl_item_list_cache as ilc INNER JOIN tbl_payment_cache as pc ON ilc.Payment_Cache_ID=pc.Payment_Cache_ID WHERE Payment_Item_Cache_List_ID='" . $value . "'";
                            $paymentQuery = mysqli_query($conn,$checkPAymentStatus) or die(mysqli_error($conn));
                            $rows = mysqli_fetch_assoc($paymentQuery);

                            if (($rows['Billing_Type'] == 'Outpatient Credit') && ($rows['Transaction_Type'] == 'Credit')) {
                                $Billing_Type = 'Outpatient Credit';
                            } else if (($rows['Billing_Type'] == 'Inpatient Credit') && ($rows['Transaction_Type'] == 'Cash')) {
                                $Billing_Type = 'Inpatient Cash';
                            } else if (($rows['Billing_Type'] == 'Inpatient Credit') && ($rows['Transaction_Type'] == 'Credit')) {
                                $Billing_Type = 'Inpatient Credit';
                            } else if (($rows['Billing_Type'] == 'Inpatient Cash') && ($rows['Transaction_Type'] == 'Cash')) {
                                $Billing_Type = 'Inpatient Cash';
                            } else {
                                $Billing_Type = "";
                            }

                            if (!is_null($rows['Patient_Payment_ID']) || !empty($rows['Patient_Payment_ID'])) {
                                $checkIfAutoBukking = mysqli_query($conn,"SELECT enab_auto_billing FROM tbl_sponsor WHERE Sponsor_ID = '" . $rows['Sponsor_ID'] . "' and  enab_auto_billing='yes'") or die(mysqli_error($conn));

                                if (mysqli_num_rows($checkIfAutoBukking) > 0) {
                                    $counter++;
                                    continue;
                                }
                            }
                           
                            if($iniStatus=='paid'){
                              $counter++;
                              continue;  
                            }



                            if (!empty($Billing_Type)) {
                                $has_no_folio = false;
                                $Folio_Number = '';
                                $Registration_ID = $_POST['Registration_ID'];
                                $sql_check = mysqli_query($conn,"select Check_In_ID from tbl_check_in
                                where Registration_ID = '$Registration_ID'
                                    order by Check_In_ID desc limit 1") or die(mysqli_error($conn));


                                $Check_In_ID = mysqli_fetch_assoc($sql_check)['Check_In_ID'];

                                $sql_sponsor = mysqli_query($conn,"SELECT Sponsor_ID FROM tbl_patient_registration WHERE Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
                                $Sponsor_ID = mysqli_fetch_assoc($sql_sponsor)['Sponsor_ID'];

                                $select = mysqli_query($conn,"select Folio_Number,Sponsor_ID,Sponsor_Name,Claim_Form_Number,Billing_Type from tbl_patient_payments where Registration_ID = '" . $Registration_ID . "' AND Check_In_ID = '" . $Check_In_ID . "'  AND Sponsor_ID = '" . $Sponsor_ID . "' order by Patient_Payment_ID desc limit 1") or die(mysqli_error($conn));



                                if (mysqli_num_rows($select)) {
                                    $rows_info = mysqli_fetch_array($select);
                                    $Folio_Number = $rows_info['Folio_Number'];
                                    $Sponsor_Name = $rows_info['Sponsor_Name'];
                                    $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
                                    $Claim_Form_Number = $rows_info['Claim_Form_Number'];
                                    //$Billing_Type = $Billing_Type;
                                    //get last check in id
                                } else {
                                    include("../includes/Folio_Number_Generator_Emergency.php");
                                    $select = mysqli_query($conn,"SELECT Claim_Form_Number,cd.Sponsor_ID,Guarantor_Name from tbl_check_in_details cd JOIN tbl_sponsor sp ON cd.Sponsor_ID=sp.Sponsor_ID  WHERE cd.Check_In_ID= '$Check_In_ID'") or die(mysqli_error($conn));
                                    $rows_info = mysqli_fetch_array($select);

                                    $Sponsor_Name = $rows_info['Guarantor_Name'];
                                    $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
                                    $Claim_Form_Number = $rows_info['Claim_Form_Number'];

                                    if (strtolower($Sponsor_Name) == 'cash') {
                                        $Billing_Type = "Inpatient Cash";
                                    } else {
                                        $Billing_Type = "Inpatient Credit";
                                    }

                                    $has_no_folio = true;
                                }

                                //get supervisor id
                                if (isset($_SESSION['Laboratory_Supervisor'])) {
                                    $Supervisor_ID = $_SESSION['Laboratory_Supervisor']['Employee_ID'];
                                } else {
                                    $Supervisor_ID = 0;
                                }

                                //get the last bill id
                                $select = mysqli_query($conn,"select Patient_Bill_ID from tbl_patient_bill where Registration_ID = '$Registration_ID' order by Patient_Bill_ID desc limit 1") or die(mysqli_error($conn));
                                $nums = mysqli_num_rows($select);
                                if ($nums > 0) {
                                    while ($row = mysqli_fetch_array($select)) {
                                        $Patient_Bill_ID = $row['Patient_Bill_ID'];
                                    }
                                } else {
                                    //insert data to tbl_patient_bill
                                    $insert = mysqli_query($conn,"INSERT INTO tbl_patient_bill(Registration_ID,Date_Time) VALUES ('$Registration_ID',(select now()))") or die(mysqli_error($conn));
                                    if ($insert) {
                                        $select = mysqli_query($conn,"select Patient_Bill_ID from tbl_patient_bill where Registration_ID = '$Registration_ID' order by Patient_Bill_ID desc limit 1") or die(mysqli_error($conn));
                                        $nums = mysqli_num_rows($select);
                                        while ($row = mysqli_fetch_array($select)) {
                                            $Patient_Bill_ID = $row['Patient_Bill_ID'];
                                        }
                                    }
                                }

                                //end of fetching patient bill id
                                //insert data to tbl_patient_payments
                                $insert = mysqli_query($conn,"insert into tbl_patient_payments(Registration_ID,Supervisor_ID,Employee_ID,
                                                Payment_Date_And_Time,Folio_Number,Check_In_ID,Claim_Form_Number,Sponsor_ID,
                                                Sponsor_Name,Billing_Type,Receipt_Date,branch_id,Patient_Bill_ID)
                                            values('$Registration_ID','$Supervisor_ID','$Employee_ID',(select now()),
                                            '$Folio_Number','$Check_In_ID','$Claim_Form_Number','$Sponsor_ID','$Sponsor_Name',
                                            '$Billing_Type',(select now()),'$Branch_ID','$Patient_Bill_ID')") or die(mysqli_error($conn));

                                if ($insert) {
                                    //get the last patient_payment_id & date
                                    $select_details = mysqli_query($conn,"select Patient_Payment_ID, Receipt_Date from tbl_patient_payments where Registration_ID = '$Registration_ID' and Employee_ID = '$Employee_ID' order by Patient_Payment_ID desc limit 1") or die(mysqli_error($conn));
                                    $num_row = mysqli_num_rows($select_details);
                                    if ($num_row > 0) {
                                        $details_data = mysqli_fetch_assoc($select_details);
                                        $Patient_Payment_ID = $details_data['Patient_Payment_ID'];
                                        $Receipt_Date = $details_data['Receipt_Date'];
                                    } else {
                                        $Patient_Payment_ID = 0;
                                        $Receipt_Date = '';
                                    }

                                    //get data from tbl_item_list_cache
                                    $Item_ID = $rows['Item_ID'];
                                    $Discount = $rows['Discount'];
                                    $Price = $rows['Price'];
                                    $Quantity = $rows['Quantity'];
                                    $Consultant = $rows['Consultant'];
                                    $Consultant_ID = $rows['Consultant_ID'];
                                    $Sub_Department_ID = $rows['Sub_Department_ID'];
                                    $finance_department_id = $rows['finance_department_id'];


                                    //insert data to tbl_patient_payment_item_list
                                    if ($Patient_Payment_ID != 0 && $Receipt_Date != '') {
                                        $insert = mysqli_query($conn,"insert into tbl_patient_payment_item_list(Check_In_Type,Item_ID,Discount,Price,Quantity,Patient_Direction,Consultant,Consultant_ID,status,Patient_Payment_ID,Transaction_Date_And_Time,ServedDateTime,ServedBy,ItemOrigin,Sub_Department_ID,finance_department_id)
                                                        values('Laboratory','$Item_ID','$Discount','$Price','$Quantity','others','$Consultant','$Consultant_ID','Served','$Patient_Payment_ID',(select now()),(select now()),'$Employee_ID','Doctor','$Sub_Department_ID','$finance_department_id')") or die(mysqli_error($conn));
                                        if ($insert) {
                                            $finalResult = mysqli_query($conn,"update tbl_item_list_cache set Patient_Payment_ID = '$Patient_Payment_ID', Payment_Date_And_Time = '$Receipt_Date' WHERE Payment_Item_Cache_List_ID='$value'") or die(mysqli_error($conn));

                                            if ($finalResult) {
                                                $message = "Sucessfully submitted to Results Team!";
                                            }
                                        }

                                        //check if this user has folio 

                                        if ($has_no_folio) {
                                            $result_cd = mysqli_query($conn,"SELECT Check_In_Details_ID FROM tbl_check_in_details WHERE Registration_ID = '$Registration_ID' AND Check_In_ID = '$Check_In_ID' AND consultation_ID IS NOT NULL ORDER BY Check_In_Details_ID DESC LIMIT 1") or die(mysqli_error($conn));
                                            $Check_In_Details_ID = mysqli_fetch_assoc($result_cd)['Check_In_Details_ID'];
                                            $update_checkin_details = "UPDATE tbl_check_in_details SET Folio_Number='$Folio_Number'
                                                    WHERE Check_In_Details_ID='$Check_In_Details_ID' ";
                                            mysqli_query($conn,$update_checkin_details) or die(mysqli_error($conn));
                                        }
                                    }
                                }
                            }
                        }
                    }
                } else {
                    $message = mysqli_error($conn);
                }
                $counter++;
            } else {
                $status = 'Note_Completed';
                $message = 'Specimen collection is not completed';
            }
        }
    }
}

if ($counter == $total_Item) {
    $message = "Sucessfully submitted to Results Team!";
} else {
    if ($counter == 1) {
        $status = 'Note_Completed';
        $message = '' . $counter . ' specimen has been submitted successifully. Specimen(s) collection  not completed';
    } else {
        $status = 'Note_Completed';
        $message = '' . $counter . ' specimens have been submitted successifully. Specimen(s) collection  not completed';
    }
}


echo $status . '*&&^^%%$' . $message;
?>