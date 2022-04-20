<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    $Title_Control = "False";
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ./index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Patients_Billing_Works'])){
	    if($_SESSION['userinfo']['Patients_Billing_Works'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ./index.php?InvalidPrivilege=yes");
    }
    $Registration_ID = '';
    if(isset($_GET['Registration_ID'])){
	$Registration_ID = $_GET['Registration_ID'];
    }else{
	@session_destroy();
	    header("Location: ./index.php?InvalidPrivilege=yes");
    }
    $update_query = "UPDATE tbl_admission SET Discharge_Clearance_Status='cleared'
                     WHERE Registration_ID=$Registration_ID AND Admission_Status='Admitted'";
    mysqli_query($conn,$update_query);
    header("Location: cashbill.php?Registration_ID=$Registration_ID&InPatientsBillingWorks=InPatientsBillingWorks");
?>