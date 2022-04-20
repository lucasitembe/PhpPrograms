<?php

session_start();
include("./includes/connection.php");

if (isset($_POST['Supervisor_Username'])) {
    $Supervisor_Username = mysqli_real_escape_string($conn,$_POST['Supervisor_Username']);
}

if (isset($_POST['Supervisor_Password'])) {
    $Supervisor_Password = mysqli_real_escape_string($conn,md5($_POST['Supervisor_Password']));
}


if (isset($_POST['IssueManual_ID'])) {
    $IssueManual_ID = $_POST['IssueManual_ID'];
}

$select = mysqli_query($conn,"select emp.Employee_ID from tbl_employee emp, tbl_privileges p where
                                emp.Employee_ID = p.Employee_ID and
                                    p.Given_Username = '$Supervisor_Username' and
                                        Given_Password = '$Supervisor_Password'") or die(mysqli_error($conn));

if (mysqli_num_rows($select) > 0) {

    $getItems = mysqli_query($conn,"SELECT Quantity_Issued,Item_ID,Store_Issuing FROM tbl_issuemanual_items isi JOIN tbl_issuesmanual iss
                                ON isi.Issue_ID=iss.Issue_ID
                            WHERE isi.Issue_ID='$IssueManual_ID'
           " ) or die(mysqli_error($conn));

    while ($row = mysqli_fetch_array($getItems)) {
        $qty = $row['Quantity_Issued'];
        $Item_ID = $row['Item_ID'];
        $Sub_Department_ID = $row['Store_Issuing'];

        $updatestore = mysqli_query($conn,"update tbl_items_balance set Item_Balance = Item_Balance - '$qty' where
                                            Item_ID = '$Item_ID' and
                                            Sub_Department_ID = '$Sub_Department_ID' ") or die(mysqli_error($conn));
    }
    
    $updateIssu = mysqli_query($conn,"update tbl_issuesmanual set  status='saved'  where
                                            Issue_ID='$IssueManual_ID' ") or die(mysqli_error($conn));
    if (isset($_SESSION['IssueManual_ID'])) {
        unset($_SESSION['IssueManual_ID']);
    }
    echo 1;
} else {
    echo 0;
}
