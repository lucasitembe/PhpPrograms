<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_GET['Items_Per_Container'])){
		$Items_Per_Container = $_GET['Items_Per_Container'];
	}else{
		$Items_Per_Container = 0;
	}

	if(isset($_GET['Items_ID'])){
		$Items_ID = $_GET['Items_ID'];
	}else{
		$Items_ID = 0;
	}
	$update = mysqli_query($conn,"update tbl_pending_purchase_order_items set Items_Per_Container = '$Items_Per_Container' where pending_purchase_id = '$Items_ID'") or die(mysqli_error($conn));
?>