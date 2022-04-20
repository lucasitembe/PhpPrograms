<?php
	include("../includes/connection.php");
	session_start();
	

	$Employee_ID= $_SESSION['userinfo']['Employee_ID'];

	$Supervisor_ID = $_SESSION['supervisor']['Employee_ID'];

	$Registration_ID= $_GET['Registration_ID'];

	$item_ID= $_GET['item_ID'];

	$Check_In_Type= $_GET['Check_In_Type'];

	$Patient_Direction= $_GET['Patient_Direction'];

	$Consultant_ID= $_GET['Consultant_ID'];

	$Quantity= $_GET['Quantity'];

	$Discount= $_GET['Discount'];

	$bill_type= $_GET['bill_type'];

	$payment_code= $_GET['payment_code'];

	if(isset($_GET['visit_status'])){
		$visit_status = $_GET['visit_status'];
	}else{
		$visit_status = 'new';
	}

	if($Patient_Direction == 'Direct To Doctor' || $Patient_Direction == 'Direct To Doctor Via Nurse Station'){
		$select_consultant_qr = "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = '$Consultant_ID'";
		$consultantResult = mysqli_query($conn,$select_consultant_qr) or die(mysqli_error($conn));
		$Consultant = mysqli_fetch_assoc($consultantResult)['Employee_Name'];
	}else{
		$select_consultant_qr = "SELECT Clinic_Name FROM tbl_clinic WHERE Clinic_ID = '$Consultant_ID'";
		$consultantResult = mysqli_query($conn,$select_consultant_qr) or die(mysqli_error($conn));
		$Consultant = mysqli_fetch_assoc($consultantResult)['Clinic_Name'];
	}

	$price_qr = "SELECT Selling_Price_Cash FROM tbl_items WHERE item_ID = '$item_ID'";
	$price_result = mysqli_query($conn,$price_qr) or die(mysqli_error($conn));
	$Price = mysqli_fetch_assoc($price_result)['Selling_Price_Cash'];

	$sql = "INSERT INTO tbl_mobile_payment(Employee_ID,
											Supervisor_ID,
											Registration_ID,
											item_ID, 
											Check_In_Type, 
											Patient_Direction, 
											Consultant_ID,
											Consultant, 
											Quantity, 
											Discount,
											Price, 
											bill_type,
											payment_code, 
											payment_date_and_time,
											visit_status)
						
						VALUES('$Employee_ID',
								'$Supervisor_ID',
								'$Registration_ID', 
								'$item_ID', 
								'$Check_In_Type',
								'$Patient_Direction', 
								'$Consultant_ID',
								'$Consultant',
								'$Quantity', 
								'$Discount',
								'$Price',
								'$bill_type', 
								'$payment_code', 
								(SELECT NOW()),
								'$visit_status' )";
if(!mysqli_query($conn,$sql)){
	die(mysqli_error($conn));
}
?>