<?php
session_start();
include("./includes/connection.php");
$Transaction_ID = mysqli_real_escape_string($conn, $_POST['transactionID']);
$submitCode = mysqli_real_escape_string($conn, $_POST['submitCode']);
$submitStatus = mysqli_real_escape_string($conn, $_POST['submitStatus']);
$submitReference = mysqli_real_escape_string($conn, $_POST['submitReference']);
$submitMessage = mysqli_real_escape_string($conn, $_POST['submitMessage']);

$pay_code = '';
$epaycode = '';
$select = mysqli_query($conn,"select pr.Registration_ID, bt.Payment_Code, bt.Amount_Required,Guarantor_Name, bt.Employee_ID, bt.Transaction_Status, bt.Transaction_ID,
							bt.Transaction_Date_Time, bt.Transaction_Date, pr.Patient_Name, pr.Date_Of_Birth, pr.Gender, pr.Phone_Number, pr.Registration_Date_And_Time
							from tbl_bank_transaction_cache bt,  tbl_sponsor sp, tbl_patient_registration pr where
							pr.Registration_ID = bt.Registration_ID and
                            pr.Sponsor_ID = sp.Sponsor_ID and
							Transaction_ID = '$Transaction_ID' ") or die(mysqli_error($conn));
$num = mysqli_num_rows($select);

$remoteTransID = 0;
$Payment_Code = 0;
$Registration_ID = 0;

if ($num > 0) {
    $results = mysqli_query($conn,"update tbl_bank_transaction_cache set Transaction_Status = 'uploaded',submitCode ='$submitCode',"
            . "submitStatus='$submitStatus',submitReference='$submitReference',submitMessage='$submitMessage' where Transaction_ID = '$Transaction_ID'") or die(mysqli_error($conn));
    if($results){
        echo  "200";
    }else{
        echo '300';
    }
}else{
    echo "404";
}