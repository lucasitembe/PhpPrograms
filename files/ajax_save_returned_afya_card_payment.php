<?php
include("./includes/connection.php");
function Start_Transaction(){
    mysqli_query($conn,"START TRANSACTION");
}

function Commit_Transaction(){
    mysqli_query($conn,"COMMIT");
}

function Rollback_Transaction(){
    mysqli_query($conn,"ROLLBACK");
}
Start_Transaction();
    $topupBankRef =  $_POST['topupBankRef'];
    $topupReceiptNumber = $_POST['topupReceiptNumber'];
    $payerName = $_POST['payerName'];
    $serviceName = $_POST['serviceName'];
    $serviceDescription = $_POST['serviceDescription'];
    $amountPaid = $_POST['amountPaid'];
    $transactionDate = $_POST['transactionDate'];

        //save the returned feedback
    $sql_save_the_afya_card_payment_result=mysqli_query($conn,"UPDATE tbl_card_and_mobile_payment_transaction SET topupBankRef='$topupBankRef',topupReceiptNumber='$topupReceiptNumber',payerName='$payerName',serviceName='$serviceName',serviceDescription='$serviceDescription',amountPaid='$amountPaid',transactionDate='$transactionDate' WHERE bill_payment_code='$topupBankRef'") or die(mysqli_error($conn));
        $sql_select_all_pending_transaction_result=mysqli_query($conn,"SELECT Registration_ID,Employee_ID,card_and_mobile_payment_transaction_id,Payment_Cache_ID FROM tbl_card_and_mobile_payment_transaction WHERE transaction_status='pending' and bill_payment_code='$topupBankRef'") or die(mysqli_error($conn));// AND DATE(transaction_date_time)=CURDATE()
        if(mysqli_num_rows($sql_select_all_pending_transaction_result)>0){
            while($transaction_id_rows=mysqli_fetch_assoc($sql_select_all_pending_transaction_result)){
                $card_and_mobile_payment_transaction_id="10001".$transaction_id_rows['card_and_mobile_payment_transaction_id'];
                $Payment_Cache_ID=$transaction_id_rows['Payment_Cache_ID'];
                $Employee_ID=$transaction_id_rows['Employee_ID'];
                $Registration_ID=$transaction_id_rows['Registration_ID'];

                //select detail for creating receipt
                $sql_select_receipt_details_result = mysqli_query($conn,"SELECT Check_In_ID FROM tbl_check_in WHERE Registration_ID='$Registration_ID' ORDER BY Check_In_ID DESC LIMIT 1") or die(mysqli_error($conn));
                if (mysqli_num_rows($sql_select_receipt_details_result) > 0) {
                    $check_in_id_rows = mysqli_fetch_assoc($sql_select_receipt_details_result);
                    $Check_In_ID = $check_in_id_rows['Check_In_ID'];
//                echo "===>1";
                    $sql_other_payment_detail_result = mysqli_query($conn,"SELECT Folio_Number,Sponsor_ID,Billing_Type FROM tbl_payment_cache WHERE Payment_Cache_ID='$Payment_Cache_ID'") or die(mysqli_error($conn));
                    if (mysqli_num_rows($sql_other_payment_detail_result) > 0) {
                        $other_payment_deta_rows = mysqli_fetch_assoc($sql_other_payment_detail_result);
                        $Folio_Number = $other_payment_deta_rows['Folio_Number'];
                        $Sponsor_ID = $other_payment_deta_rows['Sponsor_ID'];
                        $Billing_Type = $other_payment_deta_rows['Billing_Type'];

//                   echo "===>2";
                        //get bill id
                        $sql_get_bill_id_result = mysqli_query($conn,"SELECT Patient_Bill_ID FROM tbl_patient_bill WHERE Registration_ID='$Registration_ID' AND Status='active'") or die(mysqli_error($conn));
                        if (mysqli_num_rows($sql_get_bill_id_result) > 0) {
                            $Patient_Bill_ID = mysqli_fetch_assoc($sql_get_bill_id_result)['Patient_Bill_ID'];
                            ///create receipt id
                            $sql_create_receipt_result = mysqli_query($conn,"INSERT INTO tbl_patient_payments(Registration_ID,Supervisor_ID,Employee_ID,Folio_Number,Check_In_ID,Sponsor_ID,Billing_Type,payment_type,Patient_Bill_ID,auth_code,manual_offline,Payment_Date_And_Time,Receipt_Date) VALUES('$Registration_ID','$Employee_ID','$Employee_ID','$Folio_Number','$Check_In_ID','$Sponsor_ID','$Billing_Type','pre','$Patient_Bill_ID','$topupReceiptNumber','Afya Card Online',NOW(),CURDATE())") or die(mysqli_error($conn));

                            if (!$sql_create_receipt_result) {
                                $an_error_occured = TRUE;
				echo "yajuu~~!ERROR";
                            }
//                       echo "===>3";
                             $Patient_Payment_ID = mysql_insert_id();
//                            echo "===>$card_and_mobile_payment_transaction_id<<<====!";
//                       echo "pay_id=>$Patient_Payment_ID";
//                       if($card_and_mobile_payment_transaction_id=="55"){echo "breaking_point";break;}
                            //select receipt item
                            $sql_select_receipt_items_result = mysqli_query($conn,"SELECT Edited_Quantity,Check_In_Type,Category,Item_ID,Discount,Price,Quantity,Patient_Direction,Consultant,Consultant_ID,Sub_Department_ID,Clinic_ID,finance_department_id,clinic_location_id FROM tbl_item_list_cache WHERE card_and_mobile_payment_transaction_id='$topupBankRef'") or die(mysqli_error($conn));
//echo "~~returned".mysqli_num_rows($sql_select_receipt_items_result)."~returnedrowse~";
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
//                                    echo "--------------------------sub_department_id-->$Sub_Department_ID<<<---";
                                    $Clinic_ID = $receipt_item_rows['Clinic_ID'];
                                    $finance_department_id = $receipt_item_rows['finance_department_id'];
                                    $clinic_location_id = $receipt_item_rows['clinic_location_id'];
//                                    echo "===>4~~!ERROR";
                                    if ($Quantity <= 0) {
                                        $Quantity = $Edited_Quantity;
                                    }

                                    //create receipt item
                                    $sql_select_receipt_item_result = mysqli_query($conn,"INSERT INTO tbl_patient_payment_item_list(Check_In_Type,Category,Item_ID,Discount,Price,Quantity,Patient_Direction,Consultant,Consultant_ID,Clinic_ID,Patient_Payment_ID,Sub_Department_ID,finance_department_id,clinic_location_id,Transaction_Date_And_Time) VALUES('$Check_In_Type','$Category','$Item_ID','$Discount','$Price','$Quantity','$Patient_Direction','$Consultant','$Consultant_ID','$Clinic_ID','$Patient_Payment_ID','$Sub_Department_ID','$finance_department_id','$clinic_location_id',NOW())") or die(mysqli_error($conn));
                                    if (!$sql_select_receipt_item_result) {
                                        $an_error_occured = TRUE;
                                        echo "===>5~~!ERROR";
                                    }

                                    //update all paid item under this transaction
//                               $sql_update_transaction_result=mysqli_query($conn,"UPDATE tbl_card_and_mobile_payment_transaction SET transaction_status='completed' WHERE card_and_mobile_payment_transaction_id='$card_and_mobile_payment_transaction_id'") or die(mysqli_error($conn));
                                    $sql_update_transaction_result = mysqli_query($conn,"UPDATE tbl_card_and_mobile_payment_transaction SET transaction_status='completed' WHERE bill_payment_code='$topupBankRef'") or die(mysqli_error($conn));
                                    if (!$sql_update_transaction_result) {
                                        $an_error_occured = TRUE;
                                        echo "===>8~~!ERROR";
                                    }
                                    //update paid item
                                    $sql_update_paid_item_result = mysqli_query($conn,"UPDATE tbl_item_list_cache SET Patient_Payment_ID='$Patient_Payment_ID',card_and_mobile_payment_status='completed',Status='paid' WHERE card_and_mobile_payment_transaction_id='$topupBankRef'") or die(mysqli_error($conn));
                                    if (!$sql_update_paid_item_result) {
                                        $an_error_occured = TRUE;
                                        echo "===>7~~!ERROR";
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
    echo "$Patient_Payment_ID";
}else{
    Rollback_Transaction();
    echo "failed";
}
