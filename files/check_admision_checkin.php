<?php 
session_start();
include("./includes/connection.php");
$Employee_ID = $_SESSION['userinfo']['Employee_ID'];

$consultation_ID = $_GET['consultation_ID'];
$Registration_ID = $_GET['Registration_ID'];
$Check_In_ID = $_GET['Check_In_ID'];

        $Amelazwa = mysqli_query($conn, "SELECT Check_In_ID FROM tbl_check_in_details WHERE Check_In_ID ='$Check_In_ID' AND ToBe_Admitted = 'yes' AND Registration_ID='$Registration_ID' AND consultation_ID = '$consultation_ID'");

            if (mysqli_num_rows($Amelazwa) > 0){
                echo 200;
            }else{
                echo 201;
            }

mysqli_close($conn);

?>