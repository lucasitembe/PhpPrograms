<?php
include("./includes/connection.php");
$Payment_ID = $_POST['Payment_ID'];
mysqli_query($conn, "UPDATE  tbl_patient_payments SET Bill_ID = NULL, Billing_Process_Status = 'excluded' WHERE `Patient_Payment_ID` = '$Payment_ID'");
?>
