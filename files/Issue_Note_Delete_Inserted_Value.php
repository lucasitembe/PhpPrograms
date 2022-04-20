<?php
	include("./includes/connection.php");
	if(isset($_GET['Requisition_Item_ID'])){
		$Requisition_Item_ID = $_GET['Requisition_Item_ID'];
	}else{
		$Requisition_Item_ID = 0;
	}

	$update = mysqli_query($conn,"update tbl_requisition_items set 
							Quantity_Issued = '',
							Items_Per_Container = '',
							Container_Issued = '' where Requisition_Item_ID = '$Requisition_Item_ID'") or die(mysqli_error($conn));
?>