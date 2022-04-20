<?php 
	require_once('./includes/connection.php');
	$Service_ID = $_GET['Service_ID'];
	$Registration_ID = $_GET['Reg_ID'];
	$consultation_ID = $_GET['consultation_ID'];
	//echo $Registration_ID.",".$Service_ID;
	
	//exit();
	$select_service = "
		SELECT * FROM tbl_inpatient_services_given 
			WHERE Service_ID = '$Service_ID' AND 
                              Registration_ID = '$Registration_ID' AND 
                              consultation_ID = '$consultation_ID'
			ORDER BY Time_Given desc LIMIT 1";
        
       // echo $select_service;
	$selected_service = mysqli_query($conn,$select_service) or die(mysqli_error($conn));
	if(mysqli_num_rows($selected_service) > 0){
		while($services = mysqli_fetch_assoc($selected_service)){
			$time_given = $services['Time_Given'];
			//$time_given = date("M j, Y. g:i a", strtotime($services['Time_Given']));;
		}
	} else {
		$time_given = "Never Given";	
	}
		echo $time_given;
?>