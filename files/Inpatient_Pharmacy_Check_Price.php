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

	//check zero price
	$select = mysqli_query($conn,"select Price from tbl_pharmacy_inpatient_items_list_cache where Registration_ID ='$Registration_ID'  and Employee_ID = '$Employee_ID' and Price < 1") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	if($num > 0){
		echo 'no';
	}else{
		echo 'yes';
	}
?>