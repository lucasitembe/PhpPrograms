<?php
	include("../includes/connection.php");
	if(isset($_GET['payment_code'])){
		$payment_code = $_GET['payment_code'];
	}else{
		$payment_code = '000000';
	}
	
	$updatePayment = "UPDATE tbl_mobile_payment SET 
											payment_status = 'seen' 
											WHERE payment_code = '$payment_code'";

	mysqli_query($conn,$updatePayment) or die(mysqli_error($conn));
?>