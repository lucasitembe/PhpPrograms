<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_SESSION['userinfo']['Employee_ID'])){
		$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
	}else{
		$Employee_ID = NULL;
	}

	if(isset($_GET['Consultation_ID'])){
		$Consultation_ID = mysqli_real_escape_string($conn,$_GET['Consultation_ID']);
	}else{
		$Consultation_ID = '';
	}

	if(isset($_GET['Admision_ID'])){
		$Admision_ID = mysqli_real_escape_string($conn,$_GET['Admision_ID']);
	}else{
		$Admision_ID = '';
	}

	if(isset($_GET['Notes'])){
		$Notes = mysqli_real_escape_string($conn,$_GET['Notes']);
	}else{
		$Notes = '';
	}

	if(isset($_GET['Registration_ID'])){
		$Registration_ID = mysqli_real_escape_string($conn,$_GET['Registration_ID']);
	}else{
		$Registration_ID = '';
	}

	$insert = mysqli_query($conn,"insert into tbl_nurse_notes(
							Admision_ID, Notes_Date_Time, Employee_ID, 
							Notes, Registration_ID, Consultation_ID)
						values('$Admision_ID',(select now()),'$Employee_ID',
							'$Notes','$Registration_ID','$Consultation_ID')") or die(mysqli_error($conn));
	if($insert){
		echo "yes";
	}else{
		echo "no";
	}

?>
