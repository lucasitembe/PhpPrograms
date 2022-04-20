<?php
include("includes/connection.php");

$Admision_ID = $_GET['Admision_ID'];
$Registration_ID = $_GET['Registration_ID'];

if(!empty($Registration_ID) && !empty($Admision_ID)){
    $Select_causes = mysqli_query($conn, "SELECT death_reason FROM tbl_diceased_patients WHERE Patient_ID = '$Registration_ID'") or die(mysqli_error($conn));
        if(mysqli_num_rows($Select_causes)){
            echo 200;
        }else{
            echo 201;
        }
}

mysqli_close($conn);
?>