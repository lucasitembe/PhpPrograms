<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_GET['Item_ID'])){
		$Item_ID = $_GET['Item_ID'];
	}else{
		$Item_ID = 0;
	}

	//Get sub Department ID
	if(isset($_SESSION['Pharmacy_ID'])){
		$Sub_Department_ID = $_SESSION['Pharmacy_ID'];
	}else{
		$Sub_Department_ID = 0;
	}

	$select = mysqli_query($conn,"SELECT Item_Balance from tbl_items_balance where Item_ID = '$Item_ID' and Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	if($num > 0){
		while ($data = mysqli_fetch_array($select)) {
			$Item_Balance = $data['Item_Balance'];
		}
	}else{
		if($Item_ID != 0 && $Sub_Department_ID != 0){
			mysqli_query($conn,"INSERT into tbl_items_balance(Item_ID,Sub_Department_ID) values('$Item_ID','$Sub_Department_ID'");
			$Item_Balance = 0;
		}
	}
	echo $Item_Balance;
?>