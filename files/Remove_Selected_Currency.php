<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_GET['Currency_ID'])){
		$Currency_ID = $_GET['Currency_ID'];
	}else{
		$Currency_ID = 0;
	}
	$delete = mysqli_query($conn,"delete from tbl_multi_currency where Currency_ID = '$Currency_ID'") or die(mysqli_error($conn));
?>