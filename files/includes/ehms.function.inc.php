<?php
function getPaymentMethod($sponsor_id) {
    global $conn;
    $Sponsor_qr = "SELECT payment_method FROM tbl_sponsor WHERE Sponsor_ID= '$sponsor_id'";
    $sponsor_result = mysqli_query($conn,$Sponsor_qr) or die(mysqli_error($conn));
    $sponsor_row = mysqli_fetch_assoc($sponsor_result);
    $payment_method = $sponsor_row['payment_method'];

    return $payment_method;
}