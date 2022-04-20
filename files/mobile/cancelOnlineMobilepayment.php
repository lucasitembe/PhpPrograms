<?php
	include("../includes/connection.php");
	session_start();

	if(isset($_GET['payment_code'])){
		$payment_code = $_GET['payment_code'];
		$code = $_GET['payment_code'];
	}else{
		$payment_code = '000000';
		$code = '000000';
	}
	$Supervisor_ID = $_SESSION['supervisor']['Employee_ID'];
	$Employee_ID = $_SESSION['supervisor']['Employee_ID'];
	$facility_qr = "SELECT 	hospital_code FROM tbl_mobile_payment_code";
	$facility_result = mysqli_query($conn,$facility_qr);
	$facility_row = mysqli_fetch_assoc($facility_result);
	$hospital_code = $facility_row['hospital_code'];
	$payment_code =$hospital_code.$payment_code;

	$amount = 0;

	$qr = "SELECT * FROM tbl_mobile_payment mp, tbl_items i,tbl_patient_registration pr,tbl_sponsor s WHERE 
											i.Item_ID = mp.item_ID AND mp.payment_code = '$code'
											AND mp.Registration_ID = pr.Registration_ID
											AND s.Sponsor_ID = pr.Sponsor_ID";

	$result = mysqli_query($conn,$qr) or die(mysqli_error($conn));
	$amount = 0;
	while($row = mysqli_fetch_assoc($result)){
		$amount+= (($row['Selling_Price_Cash']*$row['Quantity'])-$row['Discount']);
		$Registration_ID = $row['Registration_ID'];
		$Sponsor_ID = $row['Sponsor_ID'];
		$Sponsor_Name = $row['Guarantor_Name'];
		$visit_status = $row['visit_status'];
		$Supervisor_ID = $row['Supervisor_ID'];
	}
	
	$handle = file_get_contents("http://127.0.0.1/ehms_ndogo/cancelOnlinePayment.php?code=$payment_code&ammount=$amount",'r');
	if($handle == 'paid' || $handle == 'mismatch'){
		echo $handle;
	}else{
		//delete all with the selected code
		$qr = "DELETE FROM tbl_mobile_payment WHERE payment_code = '$code'";
		mysqli_query($conn,$qr) or die(mysqli_error($conn));
		echo 'Payment Process Canceled!';
	}
?>