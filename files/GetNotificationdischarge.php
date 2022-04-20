<?php 
	require_once('includes/connection.php');
	
	//Count Patients to be DISCHARGE
		$count_patients = "SELECT COUNT(*) as patients FROM tbl_admission WHERE Discharge_Clearance_Status = 'pending'";
		$counted_patients = mysqli_query($conn,$count_patients) or die(mysqli_error($conn));	
		while($patientscount = mysqli_fetch_assoc($counted_patients)){
			$patients = $patientscount['patients']; 
		}
		
	echo $patients;
?>