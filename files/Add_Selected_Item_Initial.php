<?php
	session_start();
	include("./includes/connection.php");
	if(isset($_GET['Item_ID'])){
		$Item_ID = $_GET['Item_ID'];
	}else{
		$Item_ID = 0;
	}

	if($Item_ID != 0){
		//check if item available
		$select = mysqli_query($conn,"select Item_ID from tbl_initial_items where Item_ID = '$Item_ID'") or die(mysqli_error($conn));
		$num = mysqli_num_rows($select);
		if($num < 1){
			$insert = mysqli_query($conn,"insert into tbl_initial_items(Item_ID) values('$Item_ID')") or die(mysqli_error($conn));
			if($insert){
				echo 'yes';
			}else{
				echo 'no';
			}
		}else{
			echo 'no';
		}
	}else{
		echo 'nop';
	}
?>