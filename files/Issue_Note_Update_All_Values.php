<?php
	include("./includes/connection.php");
	if(isset($_GET['Requisition_Item_ID'])){
		$Requisition_Item_ID = $_GET['Requisition_Item_ID'];
	}else{
		$Requisition_Item_ID = 0;
	}

	if(isset($_GET['Quantity_Issued'])){
		$Quantity_Issued = $_GET['Quantity_Issued'];
	}else{
		$Quantity_Issued = 0;
	}
	
	if(isset($_GET['Items'])){
		$Items = $_GET['Items'];
	}else{
		$Items = 0;
	}
	
	if(isset($_GET['Containers'])){
		$Containers = $_GET['Containers'];
	}else{
		$Containers = 0;
	}

	$update = mysqli_query($conn,"update tbl_requisition_items set 
							Quantity_Issued = '$Quantity_Issued',
							Items_Per_Container = '$Items',
							Container_Issued = '$Containers' where Requisition_Item_ID = '$Requisition_Item_ID'") or die(mysqli_error($conn));
?>