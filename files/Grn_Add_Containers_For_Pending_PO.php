<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_GET['Containers_Received'])){
		$Containers_Received = $_GET['Containers_Received'];
	}else{
		$Containers_Received = 0;
	}

	if(isset($_GET['Containers_ID'])){
		$Containers_ID = $_GET['Containers_ID'];
	}else{
		$Containers_ID = 0;
	}
	$update = mysqli_query($conn,"update tbl_pending_purchase_order_items set Containers_Received = '$Containers_Received' where Order_Item_ID = '$Containers_ID'") or die(mysqli_error($conn));
?>