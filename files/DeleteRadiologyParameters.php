<?php 

	require_once('includes/connection.php');
	
	isset($_GET['Parameter_ID']) ? $DeleteParam = $_GET['Parameter_ID'] : $DeleteParam = 0;

	$DeleteRow = "DELETE FROM tbl_radiology_parameter WHERE Parameter_ID = '$DeleteParam'";
	$DeleteParam_qry = mysqli_query($conn,$DeleteRow) or die(mysqli_error($conn));
	if($DeleteParam_qry){
		echo 'OK';
	} else {
		echo 'error';
	}
	
?>