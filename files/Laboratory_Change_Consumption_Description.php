<?php
    session_start();
    include("./includes/connection.php");

    if(isset($_SESSION['Laboratory_Consumption_ID'])){
        $Consumption_ID = $_SESSION['Laboratory_Consumption_ID'];
    }else{
        $Consumption_ID = 0;
    }

    if(isset($_GET['Consumption_Description'])){
        $Consumption_Description = $_GET['Consumption_Description'];
    }else{
        $Consumption_Description = '';
    }

    if($Consumption_ID != 0 && $Consumption_ID != '' && $Consumption_ID != null){
        //update Consumption description automatically
        $update_details = mysqli_query($conn,"update tbl_Consumption set Consumption_Description = '$Consumption_Description' where Consumption_ID = '$Consumption_ID'") or die(mysqli_error($conn));
        if(!$update_details){
            die(mysqli_error($conn));
        }
    }
?>