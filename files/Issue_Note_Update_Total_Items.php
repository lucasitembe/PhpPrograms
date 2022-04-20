<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_GET['Container_Issued'])){
		$Container_Issued = $_GET['Container_Issued'];
	}else{
		$Container_Issued = 0;
	}

	if(isset($_GET['Items_Issued'])){
		$Items_Issued = $_GET['Items_Issued'];
	}else{
		$Items_Issued = 0;
	}

	if(isset($_GET['Requisition_Item_ID'])){
		$Requisition_Item_ID = $_GET['Requisition_Item_ID'];
	}else{
		$Requisition_Item_ID = 0;
	}

	if($Container_Issued != null && $Container_Issued != '' && $Items_Issued != null && $Items_Issued != '' && isset($_GET['Requisition_Item_ID'])){
		$update = mysqli_query($conn,"update tbl_requisition_items set Quantity_Issued = ($Container_Issued * $Items_Issued), Items_Per_Container = '$Items_Issued', Container_Issued = '$Container_Issued' where Requisition_Item_ID = '$Requisition_Item_ID'") or die(mysqli_error($conn));
	}
?>