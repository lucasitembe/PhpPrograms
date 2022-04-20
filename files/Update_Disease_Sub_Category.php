<?php
	include("./includes/connection.php");
	
	if(isset($_GET['subcategory_ID'])){
		$subcategory_ID = $_GET['subcategory_ID'];
	}else{
		$subcategory_ID = 0;
	}

	if(isset($_GET['disease_ID'])){
		$disease_ID = $_GET['disease_ID'];
	}else{
		$disease_ID = 0;
	}
	mysqli_query($conn,"update tbl_disease set subcategory_ID = '$subcategory_ID' where disease_ID = '$disease_ID'") or die(mysqli_error($conn));
?>