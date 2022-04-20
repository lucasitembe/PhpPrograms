<?php
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day
    }
include("connection.php");
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

///fuction to process feedback url

Start_Transaction();
$clientId = "888800";
$timestamp = "20181228143755";
$passdata = $clientId."Crdb@2019".$timestamp;
$password = base64_encode(hash("sha256", $passdata,True));

$an_error_occured=FALSE;
$sql_date_time = mysqli_query($conn,"SELECT `Last_Updated_Date`,Mobile_Payment_ID FROM `tbl_mobile_payemts` WHERE `Mobile_Payment_ID`in (SELECT max(`Mobile_Payment_ID`) FROM `tbl_mobile_payemts`) LIMIT 1") or die(mysqli_error($conn));
while($date = mysqli_fetch_array($sql_date_time)){
    $CurrentDateTime = $date['Last_Updated_Date'];
    $Mobile_Payment_ID = $date['Mobile_Payment_ID'];
}
// if (trim($CurrentDateTime) == "") {
//   $date = strtotime(date("Y-m-d H:i:s"));
// }else {
//   $date = strtotime($CurrentDateTime);
// }
$sql_date_time = mysqli_query($conn,"select now() as Date_Time ") or die(mysqli_error($conn));
while($date = mysqli_fetch_array($sql_date_time)){
    $Current_Date_Time = $date['Date_Time'];
}
$startTime = date("Y-m-d H:i:s");
$cenvertedTimestart = date('Y-m-d H:i:s',strtotime('-2 hour',strtotime($startTime)));
$cenvertedTime = date('Y-m-d H:i:s',strtotime('+2 hour',strtotime($startTime)));
$start_date = substr($cenvertedTimestart,0,16);
$start_date = '2020-10-07 00:00:00';
$end_date =  substr($cenvertedTime,0,16);

mysqli_query($conn,"UPDATE `tbl_mobile_payemts` SET `Last_Updated_Date`='$start_date' WHERE `Mobile_Payment_ID` = '$Mobile_Payment_ID'") or die(mysqli_error($conn));
//if ($date >= strtotime(date("y-m-d H:i:s",(3600*1 + $date)))){
//    $start_date = date("Y-m-d H:i:s");
//}if (date("Y-m-d H:i:s") == $end_date){
//    $start_date = $end_date;
//}
//select all pending bill item
$xml_data='<EpsGateway xmlns="http://www.thehub.co.tz/" version="2.0">
    <header>
        <username>10004</username>
        <password>zYpPaasg4ddE8uI7M/u6XxMMeQrzd4fkYQtCxHi9rAU=</password>
        <timestamp>20191228143755</timestamp>
    </header>
    <body>
        <request>
            <command>QueryBill</command>
            <startDate>'.$start_date.'</startDate>
            <endDate>'.$end_date.'</endDate>
        </request>
    </body>
</EpsGateway>';
echo "[".date("Y-m-d h:i:s")."]Query:=> ".$xml_data;
$header = [
    'Content-Type: application/xml',
    'Accept: application/json'
];
$url = 'http://157.230.84.237:3003/gateway/services/v1/collection';
$ch = curl_init();
curl_setopt( $ch, CURLOPT_URL, $url );
curl_setopt( $ch, CURLOPT_POST, true );
curl_setopt( $ch, CURLOPT_HTTPHEADER, $header);
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
curl_setopt( $ch, CURLOPT_POSTFIELDS, $xml_data );
$result = curl_exec($ch);
echo "[".date("Y-m-d h:i:s")."]Query Response:=> ".$result;
if($result == ""){
die("Empty Response");
}

curl_close($ch);
$json = json_decode($result, true);

foreach($json['body']['genericResult']['parameters'] as $item) {
    $thirdPartyId =  $item['thirdPartyId'];
    $paymentId = $item['epsID'];
    $receiptNumber = $item['referenceNumber'];
    $invoiceNumber = $item['referenceNumber'];
    $amount = (int)$item['amount'];
    $date = $item['date'];
    $operator = $item['operator'];
    $resultCode = '0';
    $resultStatus = '';

  if($amount > 0){

      if ($resultCode == 0) {
        mysqli_query($conn,"INSERT INTO `tbl_mobile_payemts`(`Payment_Number`, `Operator_name`, `epsID`, `Amount`, `thirdPartyId`, `Payment_Date`,`Last_Updated_Date`,`receiptNumber`)
         VALUES ('$invoiceNumber','$operator','$paymentId','$amount','$thirdPartyId','$date','$end_date','$receiptNumber')") or die(mysqli_error($conn));
        $sql_select_all_pending_transaction_result=mysqli_query($conn,"SELECT Registration_ID,patient_phone,payment_direction,Registration_ID,Employee_ID,card_and_mobile_payment_transaction_id,Payment_Cache_ID,bill_payment_code FROM tbl_card_and_mobile_payment_transaction WHERE transaction_status='pending' and bill_payment_code='$invoiceNumber'") or die(mysqli_error($conn));// AND DATE(transaction_date_time)=CURDATE()
        if(mysqli_num_rows($sql_select_all_pending_transaction_result)>0){
            while($transaction_id_rows=mysqli_fetch_assoc($sql_select_all_pending_transaction_result)){
                $card_and_mobile_payment_transaction_id="10001".$transaction_id_rows['card_and_mobile_payment_transaction_id'];
                $Payment_Cache_ID=$transaction_id_rows['Payment_Cache_ID'];
                $Employee_ID=$transaction_id_rows['Employee_ID'];
                $Registration_ID=$transaction_id_rows['Registration_ID'];
		$payment_direction=$transaction_id_rows['payment_direction'];
                $patient_phone =(int)$transaction_id_rows['patient_phone'];
                $receiptNumber =$transaction_id_rows['bill_payment_code'];

                $namequery = mysqli_query($conn, "SELECT Patient_Name FROM tbl_patient_registration WHERE Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
                $name = mysqli_fetch_assoc($namequery)['Patient_Name'];

                //select detail for creating receipt
                $sql_select_receipt_details_result = mysqli_query($conn,"SELECT Check_In_ID FROM tbl_check_in WHERE Registration_ID='$Registration_ID' ORDER BY Check_In_ID DESC LIMIT 1") or die(mysqli_error($conn));
                if (mysqli_num_rows($sql_select_receipt_details_result) > 0) {
                    $check_in_id_rows = mysqli_fetch_assoc($sql_select_receipt_details_result);
                    $Check_In_ID = $check_in_id_rows['Check_In_ID'];
              echo "===>1";
                    $sql_other_payment_detail_result = mysqli_query($conn,"SELECT Folio_Number,Sponsor_ID,Billing_Type FROM tbl_payment_cache WHERE Payment_Cache_ID='$Payment_Cache_ID'") or die(mysqli_error($conn));
                    if (mysqli_num_rows($sql_other_payment_detail_result) > 0) {
                        $other_payment_deta_rows = mysqli_fetch_assoc($sql_other_payment_detail_result);
                        $Folio_Number = $other_payment_deta_rows['Folio_Number'];
                        $Sponsor_ID = $other_payment_deta_rows['Sponsor_ID'];
                        $Billing_Type = $other_payment_deta_rows['Billing_Type'];

                   echo "===>2";
                        //get bill id
                        $sql_get_bill_id_result = mysqli_query($conn,"SELECT Patient_Bill_ID FROM tbl_patient_bill WHERE Registration_ID='$Registration_ID' AND Status='active'") or die(mysqli_error($conn));
                        if (mysqli_num_rows($sql_get_bill_id_result) > 0) {
                            $Patient_Bill_ID = mysqli_fetch_assoc($sql_get_bill_id_result)['Patient_Bill_ID'];
                            ///create receipt id
                            $chq = mysqli_query($conn,"SELECT Check_In_Type FROM tbl_item_list_cache WHERE card_and_mobile_payment_transaction_id='$invoiceNumber'") or die(mysqli_error($conn));
                            $checkintype = mysqli_fetch_assoc($chq)['Check_In_Type'];
                            if($checkintype == "Direct Cash"){
                                $sql_create_receipt_result = mysqli_query($conn,"INSERT INTO tbl_patient_payments(Registration_ID,Supervisor_ID,Employee_ID,Folio_Number,Check_In_ID,Sponsor_ID,Billing_Type,payment_type,Patient_Bill_ID,auth_code,manual_offline,Payment_Date_And_Time,Receipt_Date,payment_direction,Transaction_type) VALUES('$Registration_ID','$Employee_ID','$Employee_ID','$Folio_Number','$Check_In_ID','$Sponsor_ID','$Billing_Type','pre','$Patient_Bill_ID','$receiptNumber','Mobile Online',NOW(),CURDATE(),'$payment_direction','Direct Cash')") or die(mysqli_error($conn));
                            }else{
                                $sql_create_receipt_result = mysqli_query($conn,"INSERT INTO tbl_patient_payments(Registration_ID,Supervisor_ID,Employee_ID,Folio_Number,Check_In_ID,Sponsor_ID,Billing_Type,payment_type,Patient_Bill_ID,auth_code,manual_offline,Payment_Date_And_Time,Receipt_Date,payment_direction) VALUES('$Registration_ID','$Employee_ID','$Employee_ID','$Folio_Number','$Check_In_ID','$Sponsor_ID','$Billing_Type','pre','$Patient_Bill_ID','$receiptNumber','Mobile Online',NOW(),CURDATE(),'$payment_direction')") or die(mysqli_error($conn));
                            }
                            if (!$sql_create_receipt_result) {
                                $an_error_occured = TRUE;
				echo "yajuu~~!ERROR";
                            }
//                       echo "===>3";
                          $Patient_Payment_ID = mysqli_insert_id($conn);
                            echo "===>$card_and_mobile_payment_transaction_id<<<====!";
//                       echo "pay_id=>$Patient_Payment_ID";
//                       if($card_and_mobile_payment_transaction_id=="55"){echo "breaking_point";break;}
                            //select receipt item
                            $sql_select_receipt_items_result = mysqli_query($conn,"SELECT Edited_Quantity,Check_In_Type,Category,Item_ID,Discount,Price,Quantity,Patient_Direction,Consultant,Consultant_ID,Sub_Department_ID,Clinic_ID,finance_department_id,clinic_location_id FROM tbl_item_list_cache WHERE card_and_mobile_payment_transaction_id='$invoiceNumber'") or die(mysqli_error($conn));
echo "~~returned".mysqli_num_rows($sql_select_receipt_items_result)."~returnedrowse~";
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
                                    echo "===>4~~!ERROR";
                                    if ($Edited_Quantity > 0) {
                                        $Quantity = $Edited_Quantity;
                                    }


                                    //create receipt item
                                    $sql_select_receipt_item_result = mysqli_query($conn,"INSERT INTO tbl_patient_payment_item_list(Check_In_Type,Category,Item_ID,Discount,Price,Quantity,Patient_Direction,Consultant,Consultant_ID,Clinic_ID,Patient_Payment_ID,Sub_Department_ID,finance_department_id,clinic_location_id,Transaction_Date_And_Time) VALUES('$Check_In_Type','$Category','$Item_ID','$Discount','$Price','$Quantity','$Patient_Direction','$Consultant','$Consultant_ID','$Clinic_ID','$Patient_Payment_ID','$Sub_Department_ID','$finance_department_id','$clinic_location_id',NOW())") or die("concultant=========================>>".mysqli_error($conn));
                                    if (!$sql_select_receipt_item_result) {
                                        $an_error_occured = TRUE;
                                        echo "===>5~~!ERROR";
                                    }

                                    $api_key = '35EF8711741433';
                                    $contacts = '255'.$patient_phone;
                                    $from = 'KCMC';
                                    $sms_text = urlencode('Ndugu '.$name.', malipo yako ya hospitali (KCMC) yamekamili kiasi cha TZS. '. number_format($amount).'. kumbukumbu#: '.$receiptNumber.'. Tarehe '.$Current_Date_Time);

                                    $ch = curl_init();
                                    curl_setopt($ch,CURLOPT_URL, "https://siltechtz.com/app/smsapi/index.php");
                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                    curl_setopt($ch, CURLOPT_POST, 1);

                                   // curl_setopt($ch, CURLOPT_POSTFIELDS, "key=".			$api_key."&campaign=1&routeid=10&type=text&contacts=".$contacts."&senderid=".$from."&msg=".$sms_text);
                                    $response = curl_exec($ch);
                                    curl_close($ch);
                                    echo "<=>".$response."<=>".$contacts;


                                    //update all paid item under this transaction
//                               $sql_update_transaction_result=mysqli_query("UPDATE tbl_card_and_mobile_payment_transaction SET transaction_status='completed' WHERE card_and_mobile_payment_transaction_id='$card_and_mobile_payment_transaction_id'") or die(mysql_error());
                                    $sql_update_transaction_result = mysqli_query($conn,"UPDATE tbl_card_and_mobile_payment_transaction SET transaction_status='completed' WHERE bill_payment_code='$invoiceNumber'") or die(mysqli_error($conn));
                                    if (!$sql_update_transaction_result) {
                                        $an_error_occured = TRUE;
                                        echo "===>8~~!ERROR";
                                    }
                                    //update paid item
                                    $sql_update_paid_item_result = mysqli_query($conn,"UPDATE tbl_item_list_cache SET Patient_Payment_ID='$Patient_Payment_ID',card_and_mobile_payment_status='completed',Status='paid' WHERE card_and_mobile_payment_transaction_id='$invoiceNumber'") or die(mysqli_error($conn));
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
    }
}
//comit or rol back transaction
if(!$an_error_occured){
    Commit_Transaction();
    echo "success";
}else{
    Rollback_Transaction();
    echo "faild";
}

}
