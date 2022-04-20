<?php

session_start();
include("./includes/connection.php");
$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
$index = 0;
if (isset($_POST['delete'])) {
    $billID = $_POST['billID'];
    $resultcheck = mysqli_query($conn, "SELECT BillID,Customer,BillAmount, AmountPaid,AuthorizationCode,TerminalID FROM billdetails WHERE  BillID = '$billID' AND UserID= '$Employee_ID'") or die(mysqli_error($conn));
    while ($resultdata = mysqli_fetch_assoc($resultcheck)) {
        $Customer = $resultdata['Customer'];
        $BillAmount = $resultdata['BillAmount'];
        $AmountPaid = $resultdata['AmountPaid'];
        $AuthorizationCode = $resultdata['AuthorizationCode'];
        $terminal_id = $resultdata['TerminalID'];
        $BillID = $resultdata['BillID'];

        $delete = mysqli_query($conn, "INSERT INTO Cancelbilldetails(`BillID`,`Customer`,`CurrencyCode`,`BillAmount`,`UserID`) VALUES('$BillID','$Customer','TZS','$BillAmount','$Employee_ID')") or die(mysqli_error($conn));
        if ($delete) {
            mysqli_query($conn, "DELETE FROM billdetails WHERE  BillID = '$billID' AND UserID= '$Employee_ID'") or die(mysqli_error($conn));
        }
    }
}
$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
$resultcheck = mysqli_query($conn, "SELECT BillID,Customer,BillAmount, AmountPaid,AuthorizationCode,TerminalID FROM billdetails WHERE  UserID= '$Employee_ID' and AuthorizationCode is null") or die(mysqli_error($conn));
while ($resultdata = mysqli_fetch_assoc($resultcheck)) {
    $Customer = $resultdata['Customer'];
    $BillAmount = $resultdata['BillAmount'];
    $AmountPaid = $resultdata['AmountPaid'];
    $AuthorizationCode = $resultdata['AuthorizationCode'];
    $terminal_id = $resultdata['TerminalID'];
    $BillID = $resultdata['BillID'];

    if (empty($AuthorizationCode)) {
        echo '<tr><td>' . ++$index . '</td><td>' . $Customer . '</td><td>' . $BillAmount . '</td><td>' . $AuthorizationCode . '</td><td>' . $terminal_id . '</td><td><a href="#" onclick="cancel_crdb_transaction(' . $BillID . ');" style="background: red;font-weight: bold;" class="art-button-green">Cancel</a></td></tr>';
    } else {
        $qly = mysqli_query($conn, "SELECT * FROM tbl_patient_payments WHERE auth_code = '$AuthorizationCode'") or die(mysqli_error($conn));
        if (mysqli_num_rows($qly) > 0) {
            continue;
        } else {
            echo '<tr><td>' . ++$index . '</td><td>' . $Customer . '</td><td>' . $BillAmount . '</td><td>' . $AuthorizationCode . '</td><td>' . $terminal_id . '</td><td><a href="#" class="art-button-green" onclick="confirmTransaction(' . $BillID . ');">Print Receipt</a></td></tr>';
        }
    }
}