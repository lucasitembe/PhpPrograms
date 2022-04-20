<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_GET['Item_ID'])){
		$Item_ID = $_GET['Item_ID'];
	}else{
		$Item_ID = 0;
	}

	if(isset($_GET['Sponsor_ID'])){
		$Sponsor_ID = $_GET['Sponsor_ID'];
	}else{
		$Sponsor_ID = 0;
	}

	$select = mysqli_query($conn,"select item_ID from tbl_sponsor_non_supported_items where sponsor_id = '$Sponsor_ID' and item_ID = '$Item_ID'") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	if($num > 0){
		echo "no";
	}else{
		echo "yes";
	}
?>