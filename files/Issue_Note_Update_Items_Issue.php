<?php
	include("./includes/connection.php");
	if(isset($_GET['Requisition_Item_ID'])){
		$Requisition_Item_ID = $_GET['Requisition_Item_ID'];
	}else{
		$Requisition_Item_ID = 0;
	}

	if(isset($_GET['Items'])){
		$Items = $_GET['Items'];
	}else{
		$Items = '';
	}

	$update = mysqli_query($conn,"update tbl_requisition_items set Items_Per_Container = '$Items' where Requisition_Item_ID = '$Requisition_Item_ID'") or die(mysqli_error($conn));
?>