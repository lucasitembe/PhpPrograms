<?php

@session_start();
include("../includes/connection.php");
include("../includes/cleaninput.php");
include("./database.php");
$employee_id = $_SESSION['userinfo']['Employee_ID'];
            
if (isset($_POST['check_id'])) {
    $_POST = sanitize_input($_POST);
    $check_id = $_POST['check_id'];
    $checkType = $_POST['checkType'];
    $Type_of_patient_case = $_POST['Type_of_patient_case'];
    

    if (is_numeric($check_id) && !empty($Type_of_patient_case)) { //Process_Status,p.Check_In_ID,Type_Of_Check_In
        mysqli_query($conn,"UPDATE tbl_check_in SET Type_of_patient_case='$Type_of_patient_case' WHERE Check_In_ID='$check_id'") or die(mysqli_error($conn));
        echo 1;
    } else {
        echo 0;
    }


}










