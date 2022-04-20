<?php
	include("../includes/connection.php");

	if(isset($_GET['payment_code'])){
		$payment_code = $_GET['payment_code'];
		$code = $_GET['payment_code'];
	}else{
		$payment_code = '000000';
		$code = '000000';
	}

	$facility_qr = "SELECT 	hospital_code FROM tbl_mobile_payment_code";
	$facility_result = mysqli_query($conn,$facility_qr);
	$facility_row = mysqli_fetch_assoc($facility_result);
	$hospital_code = $facility_row['hospital_code'];
	$payment_code =$hospital_code.$payment_code;

	$amount = 0;

	$qr = "SELECT * FROM tbl_mobile_payment mp, tbl_items i WHERE i.Item_ID = mp.item_ID AND mp.payment_code = '$code'";
	$result = mysqli_query($conn,$qr) or die(mysqli_error($conn));
	$amount = 0;
	while($row = mysqli_fetch_assoc($result)){
		$amount+= (($row['Price']*$row['Quantity'])-$row['Discount']);
	}
	
	$handle = fopen("http://127.0.0.1/EHMS_10_12_2014_1730_MOBILE/ehms_ndogo/sendCode.php?code=$payment_code&ammount=$amount",'r');
	
	//update status in local mobile payment table
	if($handle){
		$update_qr = "UPDATE tbl_mobile_payment SET payment_status ='sent' WHERE payment_code = '$code'";
		mysqli_query($conn,$update_qr) or die(mysqli_error($conn));
	}
?>