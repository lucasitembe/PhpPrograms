<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_GET['Total_Quantity'])){
		$Total_Quantity = $_GET['Total_Quantity'];
	}else{
		$Total_Quantity = '';
	}

	if(isset($_GET['Order_Item_ID'])){
		$Order_Item_ID = $_GET['Order_Item_ID'];
	}else{
		$Order_Item_ID = 0;
	}
	$update = mysqli_query($conn,"update tbl_pending_purchase_order_items set Quantity_Received = '$Total_Quantity' where Order_Item_ID = '$Order_Item_ID'") or die(mysqli_error($conn));
?>