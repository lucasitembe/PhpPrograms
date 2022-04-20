<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_GET['Buying_Price'])){
		$Buying_Price = $_GET['Buying_Price'];
	}else{
		$Buying_Price = '';
	}

	if(isset($_GET['Order_Item_ID'])){
		$Order_Item_ID =$_GET['Order_Item_ID'];
	}else{
		$Order_Item_ID = 0;
	}
	$update = mysqli_query($conn,"update tbl_pending_purchase_order_items set Buying_Price = '$Buying_Price' where pending_purchase_id = '$Order_Item_ID'") or die(mysqli_error($conn));
?>