<?php

session_start();
include("../includes/connection.php");
if (isset($_POST['action'])) {
    if ($_POST['action'] == 'save') { 
		$tt=$_POST['tt'];
		$Branch_ID=$_POST['Branch_ID'];
        $insert = mysqli_query($conn,"UPDATE tbl_system_configuration SET Can_admit_before_discharge='$tt' WHERE Branch_ID='$Branch_ID'");
        
         if ($insert) {
            echo 'Data saved successfully';
            
        } else {
            echo 'Data saving error';
        } 
        

    }
}