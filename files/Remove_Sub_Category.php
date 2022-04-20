<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_SESSION['userinfo'])){
		if(isset($_GET['Item_Subcategory_ID'])){
			$Item_Subcategory_ID = $_GET['Item_Subcategory_ID'];
		}else{
			$Item_Subcategory_ID = 0;
		}
		mysqli_query($conn,"delete from tbl_item_subcategory where Item_Subcategory_ID = '$Item_Subcategory_ID'");
	}
?>