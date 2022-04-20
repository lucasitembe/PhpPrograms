<?php
	session_start();
	include("./includes/connection.php");
	if(isset($_SESSION['userinfo'])){
		if(isset($_GET['Item_Subcategory_ID'])){
			$Item_Subcategory_ID = $_GET['Item_Subcategory_ID'];
		}else{
			$Item_Subcategory_ID = 0;
		}
		if($Item_Subcategory_ID != 0 && $Item_Subcategory_ID != null && $Item_Subcategory_ID != ''){
			$check = mysqli_query($conn,"select Item_ID from tbl_items where Item_Subcategory_ID = '$Item_Subcategory_ID'") or die(mysqli_error($conn));
			$num = mysqli_num_rows($check);
			if($num < 1){
				echo "yes";
			}else{
				echo "no";
			}
		}else{
			echo "no";
		}
	}else{
		echo "no";
	}
?>