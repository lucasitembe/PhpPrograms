<?php
include("includes/connection.php");
$Radiotherapy_ID = $_GET['Radiotherapy_ID'];
$number_phases = $_GET['number_phases'];
$key_value = $_GET['key_value'];

    if(!empty($Radiotherapy_ID) && !empty($number_phases) && !empty($key_value)){
        
        $Select_Details = mysqli_query($conn, "SELECT $key_value AS Results FROM tbl_radiotherapy_phases WHERE Radiotherapy_ID = $Radiotherapy_ID AND Treatment_Phase = '$number_phases'") or die(mysqli_error($conn));
        while($row = mysqli_fetch_assoc($Select_Details)){
            $Results = $row['Results'];
            echo $Results;
        }
    }
?>