<?php
	include("./includes/connection.php");
	if(isset($_GET['Requisition_Item_ID'])){
		$Requisition_Item_ID = $_GET['Requisition_Item_ID'];
	}else{
		$Requisition_Item_ID = 0;
	}

	if(isset($_GET['Containers'])){
		$Containers = $_GET['Containers'];
	}else{
		$Containers = '';
	}

	//get items per containers
	$select = mysqli_query($conn,"select Items_Per_Container from tbl_requisition_items where Requisition_Item_ID = '$Requisition_Item_ID'") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	if($num > 0){
		while ($data = mysqli_fetch_array($select)) {
			$Items_Per_Container = $data['Items_Per_Container'];
		}
	}else{
		$Items_Per_Container = 0;
	}

	$update = mysqli_query($conn,"update tbl_requisition_items set 
							Container_Issued = '$Containers', 
							Items_Per_Container = '$Items_Per_Container' 
							where Requisition_Item_ID = '$Requisition_Item_ID'") or die(mysqli_error($conn));
?>