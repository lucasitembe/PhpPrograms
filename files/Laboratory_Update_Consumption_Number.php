<?php
    session_start();
    include("./includes/connection.php");

    if(isset($_SESSION['Laboratory_Consumption_ID'])){
        $Consumption_ID = $_SESSION['Laboratory_Consumption_ID'];
    }else{
        $Consumption_ID = 'New Consumption';
    }
    echo $Consumption_ID;
?>