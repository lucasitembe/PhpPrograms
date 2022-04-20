<?php
include("./includes/connection.php");
if($_POST['start_date']){
   $start_date=$_POST['start_date'];
}
if($_POST['end_date']){
   $end_date=$_POST['end_date'];
}
if($_POST['Patient_Name']){
   $Patient_Name=$_POST['Patient_Name'];
}
$Registration_ID_filter="";
if($_POST['Registration_ID']){
   $Registration_ID=$_POST['Registration_ID'];
   if($Registration_ID!=""){
       $Registration_ID_filter="AND pr.Registration_ID='$Registration_ID'";
   }
}
$receipt_number_filter="";
if($_POST['receipt_number']){
   $receipt_number=$_POST['receipt_number'];
   if($receipt_number!=""){
       $receipt_number_filter="AND pp.Patient_Payment_ID='$receipt_number'";
   }
}
$reference_number_filter="";
if($_POST['reference_number']){
   $reference_number=$_POST['reference_number'];
   if($reference_number!=""){
       $reference_number_filter="AND pp.auth_code='$reference_number'";
   }
}
$transaction_md_filter="";
if($_POST['transaction_mode']){
   $transaction_mode=$_POST['transaction_mode'];
   if($transaction_mode!="all_transaction_mode"){
       if($transaction_mode=="online"){
           $transaction_md_filter=" AND pp.manual_offline IN('','online')"; 
       }else{
            $transaction_md_filter=" AND pp.manual_offline IN($transaction_mode)";  
       }
   }
}

$filter="AND cm.transaction_date_time BETWEEN '$start_date' AND '$end_date' $Registration_ID_filter AND pr.Patient_Name LIKE '%$Patient_Name%' $receipt_number_filter $reference_number_filter $transaction_md_filter";
$query_out = "SELECT mp.receiptNumber,manual_offline,pp.auth_code,pr.Registration_ID, pr.Patient_Name, cm.patient_phone, cm.payment_amount, cm.bill_payment_code, itl.Patient_Payment_ID,pp.Payment_Date_And_Time,mp.Operator_name,cm.transaction_status FROM tbl_patient_registration pr,tbl_card_and_mobile_payment_transaction cm,tbl_patient_payments pp,tbl_item_list_cache itl,tbl_mobile_payemts mp WHERE pr.Registration_ID=cm.Registration_ID AND itl.card_and_mobile_payment_transaction_id = cm.bill_payment_code and itl.Patient_Payment_ID = pp.Patient_Payment_ID AND cm.bill_payment_code=mp.Payment_Number AND cm.transaction_status='completed' $filter GROUP by pp.Patient_Payment_ID order by pp.Patient_Payment_ID DESC";

$sql_select_all_payment_receipt_result=mysqli_query($conn,$query_out) or die(mysqli_error($conn));
if(mysqli_num_rows($sql_select_all_payment_receipt_result)>0){
    $count_sn=1;
    $total_receipt_amount=0;
    while($receipt_rows=mysqli_fetch_assoc($sql_select_all_payment_receipt_result)){
        $Registration_ID=$receipt_rows['Registration_ID'];
        $Patient_Name=$receipt_rows['Patient_Name'];
        $Patient_Payment_ID=$receipt_rows['Patient_Payment_ID'];
        $auth_code=$receipt_rows['receiptNumber'];
        $Payment_Date_And_Time=$receipt_rows['Payment_Date_And_Time'];
        $manual_offline=$receipt_rows['manual_offline'];
        //select payment receipt amount
        // $sql_select_payment_receipt_amount=mysqli_query($conn,"SELECT SUM((Price-Discount)*Quantity) AS receipt_amount FROM tbl_patient_payment_item_list WHERE Patient_Payment_ID='$Patient_Payment_ID'") or die(mysqli_error($conn));
        // $receipt_amount=mysqli_fetch_assoc($sql_select_payment_receipt_amount)['receipt_amount'];
        // if($receipt_amount==0)continue;
        // $total_receipt_amount+=$receipt_amount;
        //select phone number used to pay this transaction
        // $in_query = "SELECT tbl_card_and_mobile_payment_transaction.patient_phone,tbl_card_and_mobile_payment_transaction.payment_amount,tbl_card_and_mobile_payment_transaction.bill_payment_code,tbl_card_and_mobile_payment_transaction.Operator_name FROM tbl_card_and_mobile_payment_transaction,tbl_mobile_payemts,tbl_item_list_cache WHERE  tbl_card_and_mobile_payment_transaction.bill_payment_code = tbl_mobile_payemts.Payment_Number and transaction_status='completed' AND tbl_card_and_mobile_payment_transaction.Payment_Cache_ID = tbl_item_list_cache.Payment_Cache_ID and (card_and_mobile_payment_transaction_id IN(SELECT card_and_mobile_payment_transaction_id FROM tbl_item_list_cache WHERE Patient_Payment_ID='$Patient_Payment_ID') OR bill_payment_code IN(SELECT card_and_mobile_payment_transaction_id FROM tbl_item_list_cache WHERE Patient_Payment_ID='$Patient_Payment_ID'))";
      
        // $sql_select_phone_number_per_this_transaction_result=mysqli_query($conn,$in_query) or die(mysqli_error($conn));
        // $det_rows=mysqli_fetch_assoc($sql_select_phone_number_per_this_transaction_result);

        $patient_phone=$receipt_rows['patient_phone'];
        $bill_payment_code=$receipt_rows['bill_payment_code'];
        $Operator_name=$receipt_rows['Operator_name'];;
        $receipt_amount=$receipt_rows['payment_amount'];
        $total_receipt_amount+=$receipt_amount;
        // $sql_select_payment_transaction_operator_result=mysqli_query($conn,"SELECT Operator_name FROM tbl_mobile_payemts WHERE Payment_Number='$bill_payment_code' limit 1") or die(mysqli_error($conn));
        // if(mysqli_num_rows($sql_select_payment_transaction_operator_result)>0){
        //     $Operator_name=mysqli_fetch_assoc($sql_select_payment_transaction_operator_result)['Operator_name'];
        // }
        
        ///for gepg receipt details
        if(empty($Operator_name)){
            $new_query = "SELECT PayCtrNum,PyrCellNum,PspReceiptNumber,PspName FROM tbl_billpymentinfo bpi,tbl_item_list_cache ilc WHERE bpi.BillId=ilc.gepg_bill_id AND Patient_Payment_ID='$Patient_Payment_ID' LIMIT 1";
            $sql_fetch_gepg_receipt_details_result=mysqli_query($conn,$new_query) or die(mysqli_error($conn));
            if(mysqli_num_rows($sql_fetch_gepg_receipt_details_result)>0){
                $gepg_fedbck_detail_rows=mysqli_fetch_assoc($sql_fetch_gepg_receipt_details_result);
                $bill_payment_code=$gepg_fedbck_detail_rows['PayCtrNum'];
                $patient_phone=$gepg_fedbck_detail_rows['PyrCellNum'];
                $auth_code=$gepg_fedbck_detail_rows['PspReceiptNumber'];
                $Operator_name=$gepg_fedbck_detail_rows['PspName'];
            }
        }
        echo "<tr>
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
    echo "<tr><td colspan='8'><b>TOTAL</b></td><td><b>".number_format($total_receipt_amount)."</b></td></tr>";
}
?>
