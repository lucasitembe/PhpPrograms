<?php
	session_start();
	include("./includes/connection.php");
	include_once("./functions/items.php");

	if(isset($_GET['Item_ID'])){
		$Item_ID = $_GET['Item_ID'];
	}else{
		$Item_ID = 0;
	}

	$Last_Buying_Price = Get_Last_Buy_Price($Item_ID);

	if(isset($_GET['Quantity_Issued'])){
		$Quantity_Issued = $_GET['Quantity_Issued'];
	}else{
		$Quantity_Issued = 0;
	}

	if(isset($_GET['Requisition_Item_ID'])){
		$Requisition_Item_ID = $_GET['Requisition_Item_ID'];
	}else{
		$Requisition_Item_ID = 0;
	}
 	
 	mysqli_query($conn,"update tbl_issuemanual_items set Buying_Price = '$Last_Buying_Price' where Requisition_Item_ID = '$Requisition_Item_ID'") or die(mysqli_error($conn));
 	echo number_format($Quantity_Issued * $Last_Buying_Price);
?>