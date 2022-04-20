<?php
	include("./includes/connection.php");

	if(isset($_GET['Item_Category_ID'])){
		$Item_Category_ID = $_GET['Item_Category_ID'];
	}else{
		$Item_Category_ID = 0;
	}

	if($Item_Category_ID != 0 && $Item_Category_ID != null && $Item_Category_ID != ''){
		$check = mysqli_query($conn,"SELECT Item_Category_ID from tbl_exemption_categories
		ategories where Item_Category_ID = '$Item_Category_ID'") or die(mysqli_error($conn));
		$num = mysqli_num_rows($check);
		if($num < 1){
			//insert
			$insert = mysqli_query($conn,"INSERT into tbl_exemption_categories(Item_Category_ID) values('$Item_Category_ID')") or die(mysqli_error($conn));
		}
	}
?>