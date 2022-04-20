<?php
	include("../includes/connection.php");
	session_start();
	

	$Employee_ID= $_SESSION['userinfo']['Employee_ID'];

	$Supervisor_ID = $_SESSION['supervisor']['Employee_ID'];

	$Registration_ID= $_GET['Registration_ID'];

	$Check_In_Type= $_GET['Check_In_Type'];

	$Patient_Direction= $_GET['Patient_Direction'];

	$Consultant_ID= $_GET['Consultant_ID'];

	$Quantity= $_GET['Quantity'];

	$Discount= $_GET['Discount'];

	$bill_type= $_GET['bill_type'];

	$payment_code= $_GET['payment_code'];

	$Price = $_GET['Price'];

	$Item_Name = $_GET['Item_Name'];

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

	$item_qr = "SELECT Item_ID FROM tbl_items WHERE Product_Name LIKE '%direct cash%' ORDER BY Item_ID ASC LIMIT 1";
	$item_result = mysqli_query($conn,$item_qr) or die(mysqli_error($conn));
	$Item_ID = mysqli_fetch_assoc($item_result)['Item_ID'];

	$sql = "INSERT INTO tbl_mobile_payment(Employee_ID,
											Supervisor_ID,
											Registration_ID,
											Item_Name,
											item_ID,
											Category,
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
								'$Item_Name',
								'$Item_ID',
								'direct cash',
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