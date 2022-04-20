<?php
	include("./includes/connection.php");

	if(isset($_GET['Dispense_Cache_ID'])){
		$Dispense_Cache_ID = $_GET['Dispense_Cache_ID'];
	}else{
		$Dispense_Cache_ID = 0;
	}

	//delete selected record
	mysqli_query($conn,"delete from tbl_multi_dispense_cache where Dispense_Cache_ID = '$Dispense_Cache_ID'") or die(mysqli_error($conn));
?>