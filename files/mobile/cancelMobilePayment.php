<?php
	include("../includes/connection.php");

	if(isset($_GET['payment_code'])){
		$payment_code = $_GET['payment_code'];
	}else{
		$payment_code = '000000';
	}

	$qr = "DELETE FROM tbl_mobile_payment WHERE payment_code = '$payment_code'";
	mysqli_query($conn,$qr) or die(mysqli_error($conn));
?>