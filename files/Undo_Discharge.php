<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_GET['Admision_ID'])){
		$Admision_ID = $_GET['Admision_ID'];
	}else{
		$Admision_ID = 0;
	}

	mysqli_query($conn,"update tbl_admission set Admission_Status = 'Admitted' where 
					Admision_ID = '$Admision_ID' and 
					Credit_Bill_Status = 'pending' and
					Cash_Bill_Status = 'pending'") or die(mysqli_error($conn));
?>