<?php
	include("./includes/connection.php");

	if(isset($_GET['Region_ID'])){
		$Region_ID = $_GET['Region_ID'];
	}else{
		$Region_ID = 0;
	}

	if($Region_ID != 0){
		//update selected region status
		$update = mysqli_query($conn,"update tbl_regions set Region_Status = 'Selected' where Region_ID = '$Region_ID'") or die(mysqli_error($conn));
		$update = mysqli_query($conn,"update tbl_regions set Region_Status = 'Not Selected' where Region_ID <> '$Region_ID'") or die(mysqli_error($conn));
	}

	//get selected region
	$select = mysqli_query($conn,"select * from tbl_regions where Region_Status = 'Selected'") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	if($num > 0){
		while ($data = mysqli_fetch_array($select)) {
			$Selected_Region = $data['Region_Name'];
		}
	}else{
		$Selected_Region = 'No Region Selected';
	}
	echo '&nbsp;&nbsp;'.ucwords(strtolower($Selected_Region));
?>