<?php 
//Item_ID=' + Item_ID + '&Reg_ID=' + Registration_ID+'&consultation_ID=' + consultation_ID
	require_once('./includes/connection.php');
	$consultation_ID = $_GET['consultation_ID'];
	$Registration_ID = $_GET['Reg_ID'];
        $Item_ID = $_GET['Item_ID'];
        
	$select_service = "
		SELECT * FROM tbl_inpatient_medicines_given 
			WHERE Item_ID = '$Item_ID' AND 
                              Registration_ID = '$Registration_ID' AND
                              consultation_ID = '$consultation_ID'
			ORDER BY Time_Given desc LIMIT 1";
        
	$selected_service = mysqli_query($conn,$select_service) or die(mysqli_error($conn));
        
        //ECHO $select_service;EXIT;
	if(mysqli_num_rows($selected_service) > 0){
		$services = mysqli_fetch_assoc($selected_service);
			$time_given = $services['Time_Given'];
			
	} else {
		$time_given = "Never Given";	
	}
		echo $time_given;
?>