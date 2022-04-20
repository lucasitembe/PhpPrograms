<?php
session_start();
include("./includes/connection.php");
$Employee_ID=$_SESSION['userinfo']['Employee_ID'];

$Patient_Payment_Test_ID = filter_input(INPUT_GET, 'Patient_Payment_Test_ID');
$Payment_ID = filter_input(INPUT_GET, 'payment_id');
$Laboratory_Test_specimen_ID = filter_input(INPUT_GET, 'Laboratory_Test_specimen_ID');

if(isset($_GET['Status_From']))
    if(filter_input(INPUT_GET, 'Status_From') == 'payment'){

        $sql1 =mysqli_query($conn,"DELETE FROM tbl_patient_payment_test_specimen WHERE Laboratory_Test_Specimen_ID='$Laboratory_Test_specimen_ID'");
}else if (filter_input(INPUT_GET, 'Status_From') == 'cache') {
        $sql1 =mysqli_query($conn,"DELETE FROM tbl_patient_cache_test_specimen Laboratory_Test_specimen_ID='$Laboratory_Test_specimen_ID'");
}else{
        echo "Failed to delete data";
    }

?>