<?php
	include("./includes/connection.php");
	
	if(isset($_GET['temp'])){
		$temp = $_GET['temp'];
	}else{
		$temp = '';
	}

	$check = mysqli_query($conn,"select Employee_ID from tbl_approval_employee where Approval_ID = '$temp'") or die(mysqli_error($conn));
	$num = mysqli_num_rows($check);
	if($num > 0){
		echo "yes";
	}else{
		echo "no";
	}
?>