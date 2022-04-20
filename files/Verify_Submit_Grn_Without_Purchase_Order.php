<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_SESSION['userinfo']['Employee_ID'])){
		$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
	}else{
		$Employee_ID = 0;
	}
	$Sub_Department_ID = $_SESSION['Storage_Info']['Sub_Department_ID'];
	$Supplier_ID = (!empty(isset($_GET['Supplier_ID']))) ? $_GET['Supplier_ID'] : '';


	//check if items available
	$select = mysqli_query($conn,"SELECT Purchase_Cache_ID from tbl_grn_purchase_cache where Employee_ID = '$Employee_ID' and Sub_Department_ID='$Sub_Department_ID' AND Supplier_ID = '$Supplier_ID' ") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	if($num > 0){
            echo "continue";
	}else{
            echo "stop_no_item_found";
	}
?>
