<?php
	include("./includes/connection.php");
	if(isset($_GET['Date_Value'])){
		$Date_Value = $_GET['Date_Value'];
	}else{
		$Date_Value = '';
	}

	if(isset($_GET['Order_Item_ID'])){
		$Order_Item_ID = $_GET['Order_Item_ID'];
	}else{
		$Order_Item_ID = 0;
	}
	$update = mysqli_query($conn,"update tbl_pending_purchase_order_items set Expire_Date = '$Date_Value' where Order_Item_ID = '$Order_Item_ID'") or die(mysqli_error($conn));
?>