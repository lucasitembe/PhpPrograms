<?php 
	require_once('includes/connection.php');
	
	//$tbl = $_GET['tbl'];
	$count_patients = "SELECT COUNT(*) as patients FROM tbl_check_in_details WHERE ToBe_Admitted = 'yes' AND Admit_Status = 'not admitted'";
	$counted_patients = mysqli_query($conn,$count_patients) or die(mysqli_error($conn));	
	while($patientscount = mysqli_fetch_assoc($counted_patients)){
		$patients = $patientscount['patients'];
	}
	echo $patients;
?>