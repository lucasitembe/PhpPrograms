<?php
 include("./includes/connection.php");
 
if (isset($_POST['number'])) {
    $number = $_POST['number'];
}
if (isset($_POST['patient_ID'])) {
    $patient_ID = $_POST['patient_ID'];
}

//number:number,patient_ID:patient_ID

  $update_phone_number = mysqli_query($conn,"UPDATE tbl_patient_registration SET Phone_Number='$number' WHERE Registration_ID='$patient_ID'");