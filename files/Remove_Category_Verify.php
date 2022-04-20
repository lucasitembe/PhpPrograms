<?php
	session_start();
	include("./includes/connection.php");
	if(isset($_SESSION['userinfo'])){
		if(isset($_GET['Item_Category_ID'])){
			$Item_Category_ID = $_GET['Item_Category_ID'];
		}else{
			$Item_Category_ID = 0;
		}
		if($Item_Category_ID != 0 && $Item_Category_ID != null && $Item_Category_ID != ''){
			$check = mysqli_query($conn,"select Item_Category_ID from tbl_item_subcategory where Item_Category_ID = '$Item_Category_ID'") or die(mysqli_error($conn));
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