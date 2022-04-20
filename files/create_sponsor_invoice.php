<?php

@session_start();
include("./includes/connection.php");

$employee_id = $_SESSION['userinfo']['Employee_ID'];

$amount = $_GET['total'];
$sponsor = $_GET['Sponsor_ID'];
$start_date = $_GET['Date_From'];
$end_date = $_GET['Date_To'];
$msg = '';

$yrdata = strtotime($start_date);
$invoice_year= date('Y', $yrdata);
$invoice_month= date('F', $yrdata);

$sql = "INSERT INTO tbl_invoice (invoice_date,amount,sponsor_id,employee_id,invoice_month,invoice_year) VALUES ( CURDATE(),'$amount', '$sponsor', '$employee_id','$invoice_month','$invoice_year')";
$query = mysqli_query($conn,$sql) or die(mysqli_error($conn));
if ($query) {

    $Invoice_ID = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Invoice_ID FROM tbl_invoice WHERE employee_id='$employee_id' ORDER BY Invoice_ID DESC LIMIT 1"))['Invoice_ID'];

    if ($Invoice_ID > 0) {
        //echo "UPDATE tbl_invoice SET Invoice_Number='$inv_no' WHERE Invoice_ID='$Invoice_ID' \n\n";
        $inv_no = "GI-" . str_pad($Invoice_ID, 7, "0", STR_PAD_LEFT); // it will produce 
        $update = mysqli_query($conn,"UPDATE tbl_invoice SET Invoice_Number='$inv_no' WHERE Invoice_ID='$Invoice_ID'") or die(mysqli_error($conn));


        if ($update) {
            // echo "UPDATE tbl_bills SET invoice_status='1',invoice_id='$Invoice_ID' WHERE  Bill_Date between '$start_date' and '$end_date'";
            $update = mysqli_query($conn,"UPDATE tbl_bills SET invoice_status='1',invoice_id='$Invoice_ID' WHERE  Bill_Date between '$start_date' and '$end_date'") or die(mysqli_error($conn));
        }
    }

    $msg = 'Invoice Created';

    echo json_encode($msg);
} else {


    $msg = 'Error';

    echo json_encode($msg);
}