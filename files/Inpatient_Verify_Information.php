<?php
	session_start();
	include("./includes/connection.php");
	$Control = 'yes';

	if(isset($_GET['Registration_ID'])){
		$Registration_ID = $_GET['Registration_ID'];
	}else{
		$Registration_ID = 0;
	}

	if(isset($_SESSION['userinfo']['Employee_ID'])){
		$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
	}else{
		$Employee_ID = 0;
	}

	$select = mysqli_query($conn,"select Price, Quantity from tbl_inpatient_items_cache where 
							Registration_ID = '$Registration_ID' and Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	if($num > 0){
		while ($data = mysqli_fetch_array($select)) {
			if($data['Price'] == "0" || $data['Price'] == null || $data['Price'] == '' || $data['Quantity'] == "0" || $data['Quantity'] == null || $data['Quantity'] == ''){
				$Control = 'not';
			}
		}
	}else{
		$Control = 'no';
	}
	echo $Control;
?>