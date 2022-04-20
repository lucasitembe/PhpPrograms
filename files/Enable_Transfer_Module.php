<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_SESSION['userinfo'])){
		$Branch_ID = $_SESSION['userinfo']['Branch_ID'];
	}else{
		$Branch_ID = 0;
	}

	if(isset($_GET['Status'])){
		$Status = $_GET['Status'];
		$result = mysqli_query($conn,"update tbl_system_configuration set Transfer_Patient_Module_Status = '$Status' where Branch_ID = '$Branch_ID'") or die(mysqli_error($conn));
		if($result){
			$_SESSION['systeminfo']['Transfer_Patient_Module_Status'] = $Status;
			header("Location: ./admissionconfiguration.php?AdmissionConfiguration=AdmissionConfigurationThisForm");
		}
	}
?>