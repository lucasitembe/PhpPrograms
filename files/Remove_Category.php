<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_SESSION['userinfo'])){
		if(isset($_GET['Item_Category_ID'])){
			$Item_Category_ID = $_GET['Item_Category_ID'];
		}else{
			$Item_Category_ID = 0;
		}
		mysqli_query($conn,"delete from tbl_item_category where Item_Category_ID = '$Item_Category_ID'");
	}
?>