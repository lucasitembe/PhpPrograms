<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_GET['Grn_Status'])){
        $Grn_Status = $_GET['Grn_Status'];
	}else{
        $Grn_Status = '';
	}

	if(isset($_GET['Order_Item_ID'])){
		$Order_Item_ID = $_GET['Order_Item_ID'];
	}else{
		$Order_Item_ID = 0;
	}

	$update = mysqli_query($conn,"update tbl_purchase_order_items set Grn_Status = '$Grn_Status' where Order_Item_ID = '$Order_Item_ID'") or die(mysqli_error($conn));
?>