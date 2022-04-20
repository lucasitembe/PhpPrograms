<?php
	include("./includes/connection.php");
	$Control = 'yes';
	if(isset($_GET['Approval_Title'])){
		$Approval_Title = $_GET['Approval_Title'];
	}else{
		$Approval_Title = '';
	}

	if(isset($_GET['temp'])){
		$temp = $_GET['temp'];
	}else{
		$temp = '';
	}

	//check if approval title already inserted
	$check = mysqli_query($conn,"select Approval_Title from tbl_approval_level where Approval_Title = '$Approval_Title'") or die(mysqli_error($conn));
	$num = mysqli_num_rows($check);
	if($num > 0){
		$Control = 'Available';
	}else{
		$update = mysqli_query($conn,"update tbl_approval_level set Approval_Title = '$Approval_Title' where Approval_ID = '$temp'") or die(mysqli_error($conn));
		if(!$update){
			$Control = 'no';
		}
	}
	echo $Control;
?>