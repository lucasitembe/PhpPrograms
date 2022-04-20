<?php
	session_start();
	include("./includes/connection.php");

    if(isset($_SESSION['userinfo']['Employee_ID'])){
    	$Employee_ID =$_SESSION['userinfo']['Employee_ID'];
    }else{
    	$Employee_ID = NULL;
    }

    if(isset($_GET['Receipt_Setting'])){
    	$Receipt_Setting = $_GET['Receipt_Setting'];
    }else{
    	$Receipt_Setting = 'Receipt';
    }

    if(isset($_GET['Include_Sponsor_Name_On_Printed_Receipts'])){
    	$Include_Sponsor_Name_On_Printed_Receipts = $_GET['Include_Sponsor_Name_On_Printed_Receipts'];
    }else{
		$Include_Sponsor_Name_On_Printed_Receipts = 'no';
    }

    $update = mysqli_query($conn,"update tbl_printer_settings set 
    						Paper_Type = '$Receipt_Setting', 
    						Include_Sponsor_Name_On_Printed_Receipts = '$Include_Sponsor_Name_On_Printed_Receipts', 
    						Date_Created = (select now()), Created_By = '$Employee_ID'") or die(mysqli_error($conn));
?>