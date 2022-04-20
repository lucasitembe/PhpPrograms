<?php
include("../includes/connection.php");
$newfile = "/var/www/html/ehms/mobile_processing_payfolder/logs/Crdb_Transactions-".date("Y-m-d").".log";
$Control = 1;
$sql_date_time = mysqli_query($conn,"select now() as Date_Time ") or die(mysql_error($conn));
while($date = mysqli_fetch_array($sql_date_time)){
    $Current_Date_Time = $date['Date_Time'];
}

$response = file_get_contents('php://input');
$html .="\n[".$Current_Date_Time."]: REC INFO ".$response;
$result  = json_decode($response,true);

$responseID = $result['responseID'];
$Transaction_ID = $result['requestTransactionID'];
$responseTerminalID = $result['responseTerminalID'];
$responseMerchantID = $result['responseMerchantID'];
$responseBatchNumber = $result['responseBatchNumber'];
$responseCardNumber = $result['responseCardNumber'];
$P_Code = $result['authorizationCode'];
$A_Paid = $result['transactionAmount'];
$Transaction_type = "Direct cash";

if(!empty($P_Code) && (int)$A_Paid > 0 && !empty($Transaction_ID)){
$query1 = "SELECT Payment_Code,Registration_ID FROM tbl_bank_transaction_cache WHERE Payment_Code = '$Transaction_ID'";
$result1 = mysqli_query($conn, $query1) or die(mysqli_error($conn));
$objects = mysqli_fetch_assoc($result1);
$R_ID = $objects['Registration_ID'];
$Payment_Code = $objects['Payment_Code'];

$query = "INSERT INTO tbl_bank_api_payments_details (Registration_ID, Amount_Paid, Payment_Code,"
        . "Transaction_Date,Terminal_ID,Merchant_ID,Batch_No,"
        . "Auth_No,trans_type,P_ID,cardNumber) VALUES ('$R_ID','$A_Paid','$Payment_Code','$Current_Date_Time','$responseTerminalID','$responseMerchantID','$responseBatchNumber','$P_Code','$Transaction_type','$responseID','$responseCardNumber')";

$result = mysqli_query($conn, $query) or die(mysqli_error($conn));
if($result){
    $id = mysqli_insert_id($conn);
    $upq = mysqli_query($conn, "update tbl_bank_transaction_cache set Transaction_Status = 'Completed' where Payment_Code = '$Transaction_ID'") or die(mysqli_error($conn));
    if($upq){
        print_r(json_encode(array(
        "responseCode" => 0,
        "responseStatus" => "ACK",
        "responseReference" => $id,
        "responseMessage" => "SUCCESS"
    )));
    }
    
}else{
    print_r(json_encode(array(
        "responseCode" => 1,
        "responseStatus" => "NACK",
        "responseReference" => 0,
        "responseMessage" => "FAIL"
    )));
}
}else{
    print_r(json_encode(array(
        "responseCode" => 1,
        "responseStatus" => "NACK",
        "responseReference" => 0,
        "responseMessage" => "FAIL"
    )));
}
