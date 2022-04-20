<?php
	include("../includes/connection.php");
	include('codeGeneRator.php');

	if(isset($_GET['code'])){
		$code = $_GET['code'];
	}else{
		//check free codes
		
		//get last code
		$select_qr = "SELECT * FROM tbl_mobile_payment_code";
		$result = mysqli_query($conn,$select_qr);
		$row = mysqli_fetch_assoc($result);

		$code = $row['patient_code'];
	}

	$code = getCode($code);

	mysqli_query($conn,"UPDATE tbl_mobile_payment_code SET patient_code='$code'") or die(mysqli_error($conn));
	echo $code;
?>