<?php

include("./includes/connection.php");
//require_once './functions/functions.php';

if (isset($_POST['action']) && $_POST['action'] == 'returntobilling') {
  $admision_ID=  mysqli_real_escape_string($conn,$_POST['admision_ID']);
    $update = "UPDATE tbl_admission  SET Admission_Status = 'Pending',Discharge_Clearance_Status = 'not cleared',Cash_Bill_Status='pending',Credit_Bill_Status='pending' WHERE Admision_ID='$admision_ID' ";


    if (mysqli_query($conn,$update)) {
        echo "1";
    } else {
        die(mysqli_error($conn));
    }
} 
?>