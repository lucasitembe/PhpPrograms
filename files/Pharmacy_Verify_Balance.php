<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_GET['Item_ID'])){
		$Item_ID = $_GET['Item_ID'];
	}else{
		$Item_ID = 0;
	}

	if(isset($_GET['Quantity'])){
		$Quantity = $_GET['Quantity'];
	}else{
		$Quantity = 0;
	}

	//Get sub Department ID
	if(isset($_SESSION['Pharmacy_ID'])){
		$Sub_Department_ID = $_SESSION['Pharmacy_ID'];
	}else{
		$Sub_Department_ID = 0;
	}

	$select = mysqli_query($conn,"select Item_Balance from tbl_items_balance where Item_ID = '$Item_ID' and Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	if($num > 0){
		while ($data = mysqli_fetch_array($select)) {
			$Item_Balance = $data['Item_Balance'];
		}
	}else{
		if($Item_ID != 0 && $Sub_Department_ID != 0 && $Item_ID != null && $Item_ID != ''){
			mysqli_query($conn,"insert into tbl_items_balance(Item_ID,Sub_Department_ID) values('$Item_ID','$Sub_Department_ID'");
			$Item_Balance = 0;
		}else{
			$Item_Balance = 0;
		}
	}
	if (isset($_SESSION['systeminfo']['Allow_Pharmaceutical_Dispensing_Above_Actual_Balance']) && strtolower($_SESSION['systeminfo']['Allow_Pharmaceutical_Dispensing_Above_Actual_Balance']) == 'no' && ($Item_Balance < $Quantity) && $Quantity != 0 && $Quantity != null) {
		echo "no";
	}else if($Quantity < 0){
		echo "no";
	}else if($Item_ID == null || $Item_ID == ''){
		echo "no";
	}else{
		echo "yes";
	}
?>