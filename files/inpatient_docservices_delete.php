<?php 
	require_once('includes/connection.php');
	$DS_Cache_ID = $_GET['cid'];
	
	$delete_service = "DELETE FROM tbl_inpatient_doctorservices_cache WHERE DS_Cache_ID = '$DS_Cache_ID'";
	$delete_service_qry = mysqli_query($conn,$delete_service) or die(mysqli_error($conn));
	
	if($delete_service_qry){
		echo $DS_Cache_ID;
	}
?>