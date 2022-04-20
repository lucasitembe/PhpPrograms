<?php
include("./includes/connection.php");
//include("./includes/connection_online.php");

$reference_number_filter="";
if($_POST['reference_number']){
   $reference_number=$_POST['reference_number'];
   if($reference_number!=""){
       $filter=" AND bill_payment_code='$reference_number'";
   }
}


function Start_Transaction(){
    global $conn;
   // mysqli_query($conn,"START TRANSACTION");
    mysqli_autocommit($conn,FALSE);
}

function Commit_Transaction(){
    global $conn;
    //mysqli_query($conn,"COMMIT");
    mysqli_commit($conn);
}

function Rollback_Transaction(){
    global $conn;
   // mysqli_query($conn,"ROLLBACK");
    mysqli_rollback($conn);
}
$html = "";
$qr = "SELECT tm.Payment_Number,tm.receiptNumber,tm.Operator_name FROM tbl_card_and_mobile_payment_transaction tc,tbl_mobile_payemts tm WHERE tc.bill_payment_code=tm.Payment_Number AND tc.transaction_status='pending' and  Payment_Number = '$reference_number' GROUP BY Payment_Number";
$sql_select_all_pending_transaction=mysqli_query($conn,$qr) or die(mysqli_error($conn));
if(mysqli_num_rows($sql_select_all_pending_transaction)>0){
    while($transaction_rows=mysqli_fetch_assoc($sql_select_all_pending_transaction)){
        $invoiceNumber = $transaction_rows['Payment_Number'];
	$receiptNumber = $transaction_rows['receiptNumber'];
        $sql_select_all_pending_transaction_result=mysqli_query($conn,"SELECT Registration_ID,Employee_ID,card_and_mobile_payment_transaction_id,Payment_Cache_ID,payment_direction FROM tbl_card_and_mobile_payment_transaction WHERE transaction_status='pending' and bill_payment_code='$reference_number'") or die(mysqli_error($conn));// AND DATE(transaction_date_time)=CURDATE()
        if(mysqli_num_rows($sql_select_all_pending_transaction_result)>0){
            while($transaction_id_rows=mysqli_fetch_assoc($sql_select_all_pending_transaction_result)){
                $card_and_mobile_payment_transaction_id="10001".$transaction_id_rows['card_and_mobile_payment_transaction_id'];
                $Payment_Cache_ID=$transaction_id_rows['Payment_Cache_ID'];
                $Employee_ID=$transaction_id_rows['Employee_ID'];
                $Registration_ID=$transaction_id_rows['Registration_ID'];
                $payment_direction=$transaction_id_rows['payment_direction'];

                //select detail for creating receipt
                $sql_select_receipt_details_result = mysqli_query($conn,"SELECT Check_In_ID FROM tbl_check_in WHERE Registration_ID='$Registration_ID' ORDER BY Check_In_ID DESC LIMIT 1") or die(mysqli_error($conn));
                if (mysqli_num_rows($sql_select_receipt_details_result) > 0) {
                    $check_in_id_rows = mysqli_fetch_assoc($sql_select_receipt_details_result);
                    $Check_In_ID = $check_in_id_rows['Check_In_ID'];
            $log.= "===>11";
                    $sql_other_payment_detail_result = mysqli_query($conn,"SELECT Folio_Number,Sponsor_ID,Billing_Type FROM tbl_payment_cache WHERE Payment_Cache_ID='$Payment_Cache_ID'") or die(mysqli_error($conn));
                    if (mysqli_num_rows($sql_other_payment_detail_result) > 0) {
                        $other_payment_deta_rows = mysqli_fetch_assoc($sql_other_payment_detail_result);
                        $Folio_Number = $other_payment_deta_rows['Folio_Number'];
                        $Sponsor_ID = $other_payment_deta_rows['Sponsor_ID'];
                        $Billing_Type = $other_payment_deta_rows['Billing_Type'];

                    $log.= "===>22=>";
        $log.= $Registration_ID."==>";
                        //get bill id
                        $sql_get_bill_id_result = mysqli_query($conn,"SELECT Patient_Bill_ID FROM tbl_patient_bill WHERE Registration_ID='$Registration_ID' AND Status='active'") or die(mysqli_error($conn));
        $log.= mysqli_num_rows($sql_get_bill_id_result);
                        if (mysqli_num_rows($sql_get_bill_id_result) > 0) {
        $log.= "===>33=";
                            $Patient_Bill_ID = mysqli_fetch_assoc($sql_get_bill_id_result)['Patient_Bill_ID'];
                            ///create receipt id
                            if(empty($Branch_ID)){
                                    $Branch_ID = 1;
                            }
                             $Branch_ID = 1;
                            $chq = mysqli_query($conn,"SELECT Check_In_Type FROM tbl_item_list_cache WHERE card_and_mobile_payment_transaction_id='$invoiceNumber'") or die(mysqli_error($conn));
                            $checkintype = mysqli_fetch_assoc($chq)['Check_In_Type'];
                            if($checkintype == "Direct Cash"){
                                $sql_create_receipt_result = mysqli_query($conn,"INSERT INTO tbl_patient_payments(Registration_ID,Supervisor_ID,Employee_ID,Folio_Number,Check_In_ID,Sponsor_ID,Billing_Type,payment_type,Patient_Bill_ID,auth_code,manual_offline,Payment_Date_And_Time,Receipt_Date,payment_direction,Transaction_type,branch_id) VALUES('$Registration_ID','$Employee_ID','$Employee_ID','$Folio_Number','$Check_In_ID','$Sponsor_ID','$Billing_Type','pre','$Patient_Bill_ID','$receiptNumber','Mobile Online',NOW(),CURDATE(),'$payment_direction','Direct Cash','$Branch_ID')") or die(mysqli_error($conn));
                            }else{
                                $sql_create_receipt_result = mysqli_query($conn,"INSERT INTO tbl_patient_payments(Registration_ID,Supervisor_ID,Employee_ID,Folio_Number,Check_In_ID,Sponsor_ID,Billing_Type,payment_type,Patient_Bill_ID,auth_code,manual_offline,Payment_Date_And_Time,Receipt_Date,payment_direction,branch_id) VALUES('$Registration_ID','$Employee_ID','$Employee_ID','$Folio_Number','$Check_In_ID','$Sponsor_ID','$Billing_Type','pre','$Patient_Bill_ID','$receiptNumber','Mobile Online',NOW(),CURDATE(),'$payment_direction','$Branch_ID')") or die(mysqli_error($conn));
                            }
//                            $sql_create_receipt_result = mysqli_query($conn,"INSERT INTO tbl_patient_payments(Registration_ID,Supervisor_ID,Employee_ID,Folio_Number,Check_In_ID,Sponsor_ID,Billing_Type,payment_type,Patient_Bill_ID,auth_code,manual_offline,Payment_Date_And_Time,Receipt_Date,branch_id) VALUES('$Registration_ID','$Employee_ID','$Employee_ID','$Folio_Number','$Check_In_ID','$Sponsor_ID','$Billing_Type','pre','$Patient_Bill_ID','$receiptNumber','Mobile Online',NOW(),CURDATE(),'1')") or die(mysqli_error($conn));

                            if (!$sql_create_receipt_result) {
                                $an_error_occured = TRUE;
                $log.= "yajuu~~!ERROR";
                            }
                        $log.= "===>3";
                        $Patient_Payment_ID = mysqli_insert_id($conn);
                            $log.= "===>$card_and_mobile_payment_transaction_id<<<====!";
                            echo "pay_id=>$Patient_Payment_ID";
                            if($card_and_mobile_payment_transaction_id=="55"){echo "breaking_point";break;}
                            //select receipt item
                            $sql_select_receipt_items_result = mysqli_query($conn,"SELECT Edited_Quantity,Check_In_Type,Category,Item_ID,Discount,Price,Quantity,Patient_Direction,Consultant,Consultant_ID,Sub_Department_ID,Clinic_ID,finance_department_id,clinic_location_id FROM tbl_item_list_cache WHERE card_and_mobile_payment_transaction_id='$invoiceNumber'") or die(mysqli_error($conn));
        $log.= "~~returned".mysqli_num_rows($sql_select_receipt_items_result)."~returnedrowse~";
                            if (mysqli_num_rows($sql_select_receipt_items_result) > 0) {
                                while ($receipt_item_rows = mysqli_fetch_assoc($sql_select_receipt_items_result)) {
                                    $Check_In_Type = $receipt_item_rows['Check_In_Type'];
                                    $Category = $receipt_item_rows['Category'];
                                    $Item_ID = $receipt_item_rows['Item_ID'];
                                    $Discount = $receipt_item_rows['Discount'];
                                    $Price = $receipt_item_rows['Price'];
                                    $Quantity = $receipt_item_rows['Quantity'];
                                    $Edited_Quantity = $receipt_item_rows['Edited_Quantity'];
                                    $Patient_Direction = $receipt_item_rows['Patient_Direction'];
                                    $Consultant = $receipt_item_rows['Consultant'];
                                    $Consultant_ID = $receipt_item_rows['Consultant_ID'];
                                    $Sub_Department_ID = $receipt_item_rows['Sub_Department_ID'];
                                    $Clinic_ID = $receipt_item_rows['Clinic_ID'];
                                    $finance_department_id = $receipt_item_rows['finance_department_id'];
                                    $clinic_location_id = $receipt_item_rows['clinic_location_id'];
                                    $log.= "===>4~~!ERROR";
                                    if ($Edited_Quantity > 0) {
                                        $Quantity = $Edited_Quantity;
                                    }


                                    //create receipt item
                                    $sql_select_receipt_item_result = mysqli_query($conn,"INSERT INTO tbl_patient_payment_item_list(Check_In_Type,Category,Item_ID,Discount,Price,Quantity,Patient_Direction,Consultant,Consultant_ID,Clinic_ID,Patient_Payment_ID,Sub_Department_ID,finance_department_id,clinic_location_id,Transaction_Date_And_Time) VALUES('$Check_In_Type','$Category','$Item_ID','$Discount','$Price','$Quantity','$Patient_Direction','$Consultant','$Consultant_ID','$Clinic_ID','$Patient_Payment_ID','$Sub_Department_ID','$finance_department_id','$clinic_location_id',NOW())") or die("concultant=========================>>".mysqli_error($conn));
                                    if (!$sql_select_receipt_item_result) {
                                        $an_error_occured = TRUE;
                                        $log.= "===>5~~!ERROR";
                                    }

                                    //update all paid item under this transaction
        //                               $sql_update_transaction_result=mysqli_query("UPDATE tbl_card_and_mobile_payment_transaction SET transaction_status='completed' WHERE card_and_mobile_payment_transaction_id='$card_and_mobile_payment_transaction_id'") or die(mysql_error());
                                    $sql_update_transaction_result = mysqli_query($conn,"UPDATE tbl_card_and_mobile_payment_transaction SET transaction_status='completed' WHERE bill_payment_code='$invoiceNumber'") or die(mysqli_error($conn));
                                    if (!$sql_update_transaction_result) {
                                        $an_error_occured = TRUE;
                                        $log.= "===>8~~!ERROR";
                                    }
                                    //update paid item
                                    $sql_update_paid_item_result = mysqli_query($conn,"UPDATE tbl_item_list_cache SET Patient_Payment_ID='$Patient_Payment_ID',card_and_mobile_payment_status='completed',Status='paid' WHERE card_and_mobile_payment_transaction_id='$invoiceNumber'") or die(mysqli_error($conn));
                                    if (!$sql_update_paid_item_result) {
                                        $an_error_occured = TRUE;
                                        $log.= "===>7~~!ERROR";
                                    }
                                }
                            }
                        }else{
                            $checkin_status = "";
                            $check_query = mysqli_query($conn,"SELECT `Admision_ID` FROM `tbl_admission` WHERE `Admission_Status`='Admitted' AND `Registration_ID`='$Registration_ID'")or die(mysqli_error($conn));
                            if(mysqli_num_rows($sql_get_bill_id_result) > 0) $checkin_status = "Inpatient";
                            else $checkin_status = "Outpatient";
                            $insert_query = mysqli_query($conn,"INSERT INTO `tbl_patient_bill` (`Registration_ID`, `Patient_Status`, `Date_Time`, `Status`) VALUES ('$Registration_ID', '$checkin_status', 'now()', 'active');")or die(mysqli_error($conn));
                            $Patient_Bill_ID = mysqli_insert_id($conn);
                            $sql_create_receipt_result = mysqli_query($conn,"INSERT INTO tbl_patient_payments(Registration_ID,Supervisor_ID,Employee_ID,Folio_Number,Check_In_ID,Sponsor_ID,Billing_Type,payment_type,Patient_Bill_ID,auth_code,manual_offline,Payment_Date_And_Time,Receipt_Date) VALUES('$Registration_ID','$Employee_ID','$Employee_ID','$Folio_Number','$Check_In_ID','$Sponsor_ID','$Billing_Type','pre','$Patient_Bill_ID','$receiptNumber','Mobile Online',NOW(),CURDATE())") or die(mysqli_error($conn));

                            if (!$sql_create_receipt_result) {
                                $an_error_occured = TRUE;
                $log.= "yajuu~~!ERROR";
                            }
                        $log.= "===>3";
                        $Patient_Payment_ID = mysqli_insert_id($conn);
                            $log.= "===>$card_and_mobile_payment_transaction_id<<<====!";
                            echo "pay_id=>$Patient_Payment_ID";
                            if($card_and_mobile_payment_transaction_id=="55"){echo "breaking_point";break;}
                            //select receipt item
                            $sql_select_receipt_items_result = mysqli_query($conn,"SELECT Edited_Quantity,Check_In_Type,Category,Item_ID,Discount,Price,Quantity,Patient_Direction,Consultant,Consultant_ID,Sub_Department_ID,Clinic_ID,finance_department_id,clinic_location_id FROM tbl_item_list_cache WHERE card_and_mobile_payment_transaction_id='$invoiceNumber'") or die(mysqli_error($conn));
        $log.= "~~returned".mysqli_num_rows($sql_select_receipt_items_result)."~returnedrowse~";
                            if (mysqli_num_rows($sql_select_receipt_items_result) > 0) {
                                while ($receipt_item_rows = mysqli_fetch_assoc($sql_select_receipt_items_result)) {
                                    $Check_In_Type = $receipt_item_rows['Check_In_Type'];
                                    $Category = $receipt_item_rows['Category'];
                                    $Item_ID = $receipt_item_rows['Item_ID'];
                                    $Discount = $receipt_item_rows['Discount'];
                                    $Price = $receipt_item_rows['Price'];
                                    $Quantity = $receipt_item_rows['Quantity'];
                                    $Edited_Quantity = $receipt_item_rows['Edited_Quantity'];
                                    $Patient_Direction = $receipt_item_rows['Patient_Direction'];
                                    $Consultant = $receipt_item_rows['Consultant'];
                                    $Consultant_ID = $receipt_item_rows['Consultant_ID'];
                                    $Sub_Department_ID = $receipt_item_rows['Sub_Department_ID'];
                                    $Clinic_ID = $receipt_item_rows['Clinic_ID'];
                                    $finance_department_id = $receipt_item_rows['finance_department_id'];
                                    $clinic_location_id = $receipt_item_rows['clinic_location_id'];
                                    $log.= "===>4~~!ERROR";
                                    if ($Quantity <= 0) {
                                        $Quantity = $Edited_Quantity;
                                    }


                                    //create receipt item
                                    $sql_select_receipt_item_result = mysqli_query($conn,"INSERT INTO tbl_patient_payment_item_list(Check_In_Type,Category,Item_ID,Discount,Price,Quantity,Patient_Direction,Consultant,Consultant_ID,Clinic_ID,Patient_Payment_ID,Sub_Department_ID,finance_department_id,clinic_location_id,Transaction_Date_And_Time) VALUES('$Check_In_Type','$Category','$Item_ID','$Discount','$Price','$Quantity','$Patient_Direction','$Consultant','$Consultant_ID','$Clinic_ID','$Patient_Payment_ID','$Sub_Department_ID','$finance_department_id','$clinic_location_id',NOW())") or die("concultant=========================>>".mysqli_error($conn));
                                    if (!$sql_select_receipt_item_result) {
                                        $an_error_occured = TRUE;
                                        $log.= "===>5~~!ERROR";
                                    }

                                    //update all paid item under this transaction
        //                               $sql_update_transaction_result=mysqli_query("UPDATE tbl_card_and_mobile_payment_transaction SET transaction_status='completed' WHERE card_and_mobile_payment_transaction_id='$card_and_mobile_payment_transaction_id'") or die(mysql_error());
                                    $sql_update_transaction_result = mysqli_query($conn,"UPDATE tbl_card_and_mobile_payment_transaction SET transaction_status='completed' WHERE bill_payment_code='$invoiceNumber'") or die(mysqli_error($conn));
                                    if (!$sql_update_transaction_result) {
                                        $an_error_occured = TRUE;
                                        $log.= "===>8~~!ERROR";
                                    }
                                    //update paid item
                                    $sql_update_paid_item_result = mysqli_query($conn,"UPDATE tbl_item_list_cache SET Patient_Payment_ID='$Patient_Payment_ID',card_and_mobile_payment_status='completed',Status='paid' WHERE card_and_mobile_payment_transaction_id='$invoiceNumber'") or die(mysqli_error($conn));
                                    if (!$sql_update_paid_item_result) {
                                        $an_error_occured = TRUE;
                                        $log.= "===>7~~!ERROR";
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
//comit or rol back transaction
if(!$an_error_occured){
    Commit_Transaction();
}else{
    Rollback_Transaction();
}

$query_out = "SELECT manual_offline,pp.auth_code,pr.Registration_ID, pr.Patient_Name, cm.patient_phone, cm.payment_amount, cm.bill_payment_code, pp.Patient_Payment_ID,pp.Payment_Date_And_Time,mp.Operator_name,cm.transaction_status FROM tbl_patient_registration pr,tbl_card_and_mobile_payment_transaction cm,tbl_patient_payments pp,tbl_item_list_cache itl,tbl_mobile_payemts mp WHERE pr.Registration_ID=cm.Registration_ID AND cm.Payment_Cache_ID = itl.Payment_Cache_ID and pp.Patient_Payment_ID = itl.Patient_Payment_ID AND cm.bill_payment_code=mp.Payment_Number AND cm.transaction_status='completed' $filter GROUP by cm.bill_payment_code DESC";

$sql_select_all_payment_receipt_result=mysqli_query($conn,$query_out) or die(mysqli_error($conn));
if(mysqli_num_rows($sql_select_all_payment_receipt_result)>0){
    $count_sn=1;
    $total_receipt_amount=0;
    while($receipt_rows=mysqli_fetch_assoc($sql_select_all_payment_receipt_result)){
        $Registration_ID=$receipt_rows['Registration_ID'];
        $Patient_Name=$receipt_rows['Patient_Name'];
        $Patient_Payment_ID=$receipt_rows['Patient_Payment_ID'];
        $auth_code=$receipt_rows['auth_code'];
        $Payment_Date_And_Time=$receipt_rows['Payment_Date_And_Time'];
        $manual_offline=$receipt_rows['manual_offline'];

        $patient_phone=$receipt_rows['patient_phone'];
        $bill_payment_code=$receipt_rows['bill_payment_code'];
        $Operator_name=$receipt_rows['Operator_name'];;
        $receipt_amount=$receipt_rows['payment_amount'];
        $total_receipt_amount+=$receipt_amount;

        $new_query = "SELECT PayCtrNum,PyrCellNum,PspReceiptNumber,PspName FROM tbl_billpymentinfo bpi,tbl_item_list_cache ilc WHERE bpi.BillId=ilc.gepg_bill_id AND Patient_Payment_ID='$Patient_Payment_ID' LIMIT 1";
        $sql_fetch_gepg_receipt_details_result=mysqli_query($conn,$new_query) or die(mysqli_error($conn));
        if(mysqli_num_rows($sql_fetch_gepg_receipt_details_result)>0){
            $gepg_fedbck_detail_rows=mysqli_fetch_assoc($sql_fetch_gepg_receipt_details_result);
            $bill_payment_code=$gepg_fedbck_detail_rows['PayCtrNum'];
            $patient_phone=$gepg_fedbck_detail_rows['PyrCellNum'];
            $auth_code=$gepg_fedbck_detail_rows['PspReceiptNumber'];
            $Operator_name=$gepg_fedbck_detail_rows['PspName'];
        }
        $html .= "<tr>
                <td>$count_sn.</td>
                <td>$Patient_Name</td>
                <td>$Registration_ID</td>
                <td>0$patient_phone</td>
                <td class='rows_list' onclick='Print_Receipt_Payment(\"$Patient_Payment_ID\")'>$Patient_Payment_ID</td>
                <td class='rows_list' onclick='Print_Receipt_Payment(\"$Patient_Payment_ID\")'>$bill_payment_code</td>
                <td class='rows_list' onclick='Print_Receipt_Payment(\"$Patient_Payment_ID\")'>$auth_code</td>
                <td>$Payment_Date_And_Time</td>
                <td>". number_format($receipt_amount)."</td>
                <td>$Operator_name</td>
                <td>$manual_offline</td>
            </tr>";
        $count_sn++;
    }
    $html .= "<tr><td colspan='8'><b>TOTAL</b></td><td><b>".number_format($total_receipt_amount)."</b></td></tr>";
}
mysqli_close($conn);

echo $html;
?>

