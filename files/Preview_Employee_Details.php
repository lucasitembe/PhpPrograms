<?php
	include("./includes/connection.php");
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

	$delete = mysqli_query($conn,"delete from tbl_approval_employee where Approval_ID = '$temp'") or die(mysqli_error($conn));
	if($delete){
		$update = mysqli_query($conn,"update tbl_approval_level set Approval_Title = '$Approval_Title' where Approval_ID = '$temp'") or die(mysqli_error($conn));
		if($update){
			echo 'yes';
		}else{
			echo "no";
		}
	}
?>