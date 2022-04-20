<?php 

	require_once('includes/connection.php');
	
	isset($_GET['Item_ID']) ? $Item_ID = $_GET['Item_ID'] : $Item_ID = 0;
	isset($_GET['Parameter_Name']) ? $Parameter_Name = mysqli_real_escape_string($conn,$_GET['Parameter_Name']) : $Parameter_Name = '';
	isset($_GET['Delete']) ? $Delete = $_GET['Delete'] : $Delete = '';
	isset($_GET['Parameter_ID']) ? $DeleteParam = $_GET['Parameter_ID'] : $DeleteParam = 0;
	$response = '';
	
	if($Delete == 'yes'){
		$DeleteRow = "DELETE FROM tbl_radiology_parameter_cache WHERE Parameter_ID = '$DeleteParam'";
		$DeleteParam_qry = mysqli_query($conn,$DeleteRow) or die(mysqli_error($conn));
		if($DeleteParam_qry){
			$response .= 'OK';
		} else {
			$response .= 'error';
		}
		echo $response;
	}
	
?>