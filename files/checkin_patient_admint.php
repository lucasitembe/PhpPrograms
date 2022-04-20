<?php
@session_start();
include("./includes/connection.php");
$filter = '';
$Registration_ID = '';
if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
}
$select = mysqli_query($conn,"SELECT Check_In_ID FROM tbl_check_in where Registration_ID = '$Registration_ID' and Visit_Date = DATE(NOW())") or die(mysqli_error($conn));
$num_check_in = mysqli_num_rows($select);

if ($num_check_in == 0) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
    $Type_Of_Check_In = 'Afresh';
    $Check_In_Process = "insert into tbl_check_in(
                                Registration_ID,Check_In_Date_And_Time,Visit_Date,
                                Employee_ID,Branch_ID,Check_In_Date,Type_Of_Check_In)                                
                                values(
                                    '$Registration_ID',NOW(),DATE(NOW()),
                                    '$Employee_ID','$Branch_ID',NOW(),'$Type_Of_Check_In'
                                )";

    if (mysqli_query($conn,$Check_In_Process)) {
        $insert_bill_info = mysqli_query($conn,"INSERT INTO tbl_patient_bill(
                                            Registration_ID,Date_Time) VALUES ('$Registration_ID',(select now()))") or die(mysqli_error($conn));
        echo 1;
    } else {
        die(mysqli_error($conn));
    }
} else {
    echo 0;
}

