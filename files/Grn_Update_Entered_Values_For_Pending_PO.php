<?php
	session_start();
	include("./includes/connection.php");
	if(isset($_GET['Quantity'])){
		$Quantity = $_GET['Quantity'];
	}else{
		$Quantity = 0;
	}

	
	if(isset($_GET['Order_Item_ID'])){
		$Order_Item_ID = $_GET['Order_Item_ID'];
	}else{
		$Order_Item_ID = 0;
	}
	$update = mysqli_query($conn,"update tbl_pending_purchase_order_items set
							Quantity_Received = '$Quantity', Containers_Received = 1, Items_Per_Container = '$Quantity' where 
							Order_Item_ID = '$Order_Item_ID'") or die(mysqli_error($conn));	
?>