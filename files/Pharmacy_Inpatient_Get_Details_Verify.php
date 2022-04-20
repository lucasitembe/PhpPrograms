<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_SESSION['userinfo']['Employee_ID'])){
		$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
	}else{
		$Employee_ID = 0;
	}

	if(isset($_GET['Registration_ID'])){
		$Registration_ID = $_GET['Registration_ID'];
	}else{
		$Registration_ID = 0;
	}

	if(isset($_GET['Item_ID'])){
		$Item_ID = $_GET['Item_ID'];
	}else{
		$Item_ID = 0;
	}

	//check if available
	$select = mysqli_query($conn,"select Item_ID from tbl_pharmacy_inpatient_items_list_cache where
							Employee_ID = '$Employee_ID' and
							Registration_ID = '$Registration_ID' and
							Item_ID = '$Item_ID'") or die(mysqli_error($conn));
	$no = mysqli_num_rows($select);
	if($no > 0){
		echo "no";
	}else{
		echo "yes";
	}
?>