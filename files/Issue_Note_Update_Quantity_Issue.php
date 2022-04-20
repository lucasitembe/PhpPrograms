<?php
	include("./includes/connection.php");
	if(isset($_GET['Requisition_Item_ID'])){
		$Requisition_Item_ID = $_GET['Requisition_Item_ID'];
	}else{
		$Requisition_Item_ID = 0;
	}

	if(isset($_GET['Quantity'])){
		$Quantity = $_GET['Quantity'];
	}else{
		$Quantity = '';
	}

	$update = mysqli_query($conn,"update tbl_requisition_items set 
							Quantity_Issued = '$Quantity',
							Items_Per_Container = '$Quantity',
							Container_Issued = 1 where Requisition_Item_ID = '$Requisition_Item_ID'") or die(mysqli_error($conn));
?>