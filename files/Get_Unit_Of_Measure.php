<?php
	include("./includes/connection.php");

	if(isset($_GET['Item_ID'])){
		$Item_ID = $_GET['Item_ID'];
	}else{
		$Item_ID = 0;
	}

	$select = mysqli_query($conn,"SELECT Unit_Of_Measure from tbl_items where Item_ID = '$Item_ID'") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	if($num > 0){
		while ($data = mysqli_fetch_array($select)) {
			echo $data['Unit_Of_Measure'];
		}
	}
?>
