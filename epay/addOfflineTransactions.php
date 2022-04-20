<?php
/**
* @author Nassor Nassor at GPITG 
* created date: january 7 2017
* modified date: -- march 2017
*/



include '../files/includes/constants.php';
define('DBNAME_REM', EPAY_SERVER_DB);
define('HOST_REM', EPAY_SERVER_HOST);
define('USER_REM', EPAY_SERVER_USER);
define('PASSWORD_REM', EPAY_SERVER_PASS);
define('PORT_REM', EPAY_SERVER_PORT);
include 'dbconfig_remote.php';
//$con = new dbconfig;
$con_remote = new dbconfig_remote();

if(isset($_POST['bill_num'])){
	//print_r($_POST);
	
	$bill_number = mysql_escape_string($_POST['bill_num']);
	$terminal_id = encrypt(mysql_escape_string($_POST['terminal_id']));
	$amount_Paid = mysql_escape_string($_POST['amount']);
	$auth_no = encrypt(mysql_escape_string($_POST['auth_code']));
	
	$patient_Name =encrypt(mysql_escape_string($_POST['patient_name']));
	$registration_ID = encrypt(mysql_escape_string($_POST['registration_id']));
	$invoice_Number = encrypt('');
	$transfer_Ref = encrypt('');
	$transfer_Date = encrypt(date('Y-m-d'));
	$merchant_ID = encrypt('');
	$batch_No = encrypt('');
	$trans_type = encrypt(mysql_escape_string($_POST['trans_type']));


	$transSql[0] = "INSERT INTO tbl_process_logs (Patient_Name, Registration_ID, Amount_Paid, Invoice_Number, transfer_Ref, transfer_Date,Terminal_ID, Merchant_ID, Batch_No, Auth_No) VALUES ('" . $patient_Name . "', '" . $registration_ID . "', '" . encrypt($amount_Paid) . "', '" . $invoice_Number . "', '" . $transfer_Ref . "', '" . $transfer_Date . "','" . $terminal_id . "','" . $merchant_ID . "' , '" . $batch_No . "', '" . $auth_no . "')";

    $transSql[1] = "UPDATE tbl_bank_transaction_cache SET Amount_Required=Amount_Required-" . $amount_Paid  . ", 
        Transaction_Status= Case
                            When Amount_Required <= '$amount_Paid' Then 'Completed' 
                            When Amount_Required >  '$amount_Paid' Then 'pending'
                            End 
        WHERE Payment_Code='".$bill_number."'";

    $transSql[2] = "INSERT INTO tbl_payments (Patient_Name, Registration_ID, Amount_Paid, Payment_Code, Payment_Receipt, Transaction_Ref, Transaction_Date, Process_Status, Status_Code,Terminal_ID, Merchant_ID, Batch_No, Auth_No,trans_type) VALUES ('" . $patient_Name . "', '" . $registration_ID . "', '" . encrypt($amount_Paid) . "', '" . encrypt($bill_number) . "', '" . $invoice_Number . "', '" . $transfer_Ref . "', '" . $transfer_Date . "', '".encrypt('Completed')."', '".encrypt('200')."','" . $terminal_id . "','" . $merchant_ID . "' , '" . $batch_No . "', '" . $auth_no . "','$trans_type')";
    if ($con_remote->transactionalQueryExcute($transSql)) {
        //$sqlArrayLocal = array("UPDATE tbl_process_logs SET process_status='success' WHERE Transaction_ID='$transID' AND Payment_Code='$payment_code'");
        /*if ($con->transactionalQueryExcute($sqlArrayLocal)) {
            echo 1;
        } else {
            echo 0;
        }*/
        echo 'Payments Completed Successfully';
    } else {
        echo 0;
    }
}


function encrypt($text) {
    $block = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
    $padding = $block - (strlen($text) % $block);
    $text .= str_repeat(chr($padding), $padding);
    $crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, SALT_KEY, $text, MCRYPT_MODE_CBC, SALT_IV);

    return base64_encode($crypttext);
}

function decrypt($input) {
    $dectext = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, SALT_KEY, base64_decode($input), MCRYPT_MODE_CBC, SALT_IV);
    
    $strPad = ord($dectext[strlen($dectext) - 1]);
    $dectext = substr($dectext, 0, -$strPad);
    return $dectext;
}