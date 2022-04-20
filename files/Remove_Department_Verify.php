<?php
	include("./includes/connection.php");
	$Val1 = ''; $Val2 = '';

	if(isset($_GET['Department_ID'])){
		$Department_ID = $_GET['Department_ID'];
	}else{
		$Department_ID = 0;
	}

	//Check if sub departments available
	$check = mysqli_query($conn,"select Sub_Department_ID from tbl_sub_department where Department_ID = '$Department_ID' and Sub_Department_Status = 'active' limit 1") or die(mysqli_error($conn));
	$num = mysqli_num_rows($check);
	if($num > 0){
		$Val1 = "no";
	}else{
		$Val1 = "yes";
	}


	//check if employees available based on selected department
	$check = mysqli_query($conn,"select Employee_ID from tbl_employee where Department_ID = '$Department_ID' and Account_Status = 'active' limit 1") or die(mysqli_error($conn));
	$num = mysqli_num_rows($check);
	if($num > 0){
		$Val2 = "no";
	}else{
		$Val2 = "yes";
	}

	if($Val1 == 'no' && $Val2 == 'no'){
		echo "both"; //Both Employees & Su Departments
	}else if($Val1 == 'no'){
		echo "sub"; //Sub Departments only
	}else if($Val2 == 'no'){
		echo "emp"; //Employees only
	}else{
		echo "none"; //Neither Employees nor Sub Departments
	}
?>