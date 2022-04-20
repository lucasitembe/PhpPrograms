<?php
include("./includes/connection.php");
session_start();
if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
} else {
    $Employee_ID = 0;
}

if(isset($_POST['finger_print_details'])){
    $finger_print_details=$_POST['finger_print_details'];
}
if(isset($_POST['Registration_ID'])){
    $Registration_ID=$_POST['Registration_ID'];
}
$result=mysqli_query($conn,"INSERT INTO
			tbl_finger_print_details(Registration_ID,finger_data,capture_location,Employee)
			VALUES($Registration_ID,'$finger_print_details',(SELECT Hospital_Name FROM tbl_system_configuration),$Employee_ID)");
if($result){
    echo "Finger Print Saved";
}else{
    echo "Process Fail...please try again";
}