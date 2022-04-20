<?php
	
	require_once('includes/connection.php');
	
	$id = $_POST['appoint_id'];

	$update_status = "UPDATE tbl_appointment SET status = 'approved' WHERE appointment_id = '$id'";
	$approved = mysqli_query($conn,$update_status) or die(mysqli_error($conn));
	
	if ($approved){
		echo 'approved';
	}
	
?>