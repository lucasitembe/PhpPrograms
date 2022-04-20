<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_SESSION['userinfo']['Employee_ID'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
        $Employee_ID = 0;
    }
	
	if(isset($_GET['Employee_ID'])){
		$Nurse_ID = $_GET['Employee_ID']; //selected Nurse
	}else{
		$Nurse_ID = '';
	}

	if(isset($_GET['Payment_Item_Cache_List_ID'])){
		$Payment_Item_Cache_List_ID = $_GET['Payment_Item_Cache_List_ID'];
	}else{
		$Payment_Item_Cache_List_ID = '';
	}

	if(isset($_GET['Patient_Payment_Item_List_ID'])){
		$Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];
	}else{
		$Patient_Payment_Item_List_ID = 0;
	}

	if(isset($_GET['Registration_ID'])){
		$Registration_ID = $_GET['Registration_ID'];
	}else{
		$Registration_ID = '';
	}

	if(isset($_GET['consultation_ID'])){
		$consultation_ID = $_GET['consultation_ID'];
	}else{
		$consultation_ID = 0;
	}


    if($consultation_ID != 0 && $Registration_ID != 0 && $Employee_ID != '' && $Payment_Item_Cache_List_ID != ''){
	    //check if data available into tbl_post_operative_notes
	    $select = mysqli_query($conn,"select Post_operative_ID from tbl_post_operative_notes where Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
	    $num = mysqli_num_rows($select);
	    if($num > 0){
	    	while ($data = mysqli_fetch_array($select)) {
	    		$Post_operative_ID = $data['Post_operative_ID'];
	    	}
	    }else{
	    	$insert = mysqli_query($conn,"insert into tbl_post_operative_notes(
	    							Payment_Item_Cache_List_ID, Surgery_Date, Surgery_Date_Time, 
	    							consultation_ID, Registration_ID, Employee_ID)
	    							
	    						values('$Payment_Item_Cache_List_ID',(select now()),(select now()),
	    							'$consultation_ID','$Registration_ID','$Employee_ID')") or die(mysqli_error($conn));
	    }

	    //Select Post_operative_ID
	    $select = mysqli_query($conn,"select Post_operative_ID from tbl_post_operative_notes where Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
	    $num = mysqli_num_rows($select);
	    if($num > 0){
	    	while ($data = mysqli_fetch_array($select)) {
	    		$Post_operative_ID = $data['Post_operative_ID'];
	    	}
	    }else{
	    	$Post_operative_ID = 0;
	    }

	    //get External_Scrub_Nurse if available
	    //get external participants
	    $select_external = mysqli_query($conn,"select External_Scrub_Nurse from tbl_post_operative_external_participant where Post_operative_ID = '$Post_operative_ID'") or die(mysqli_error($conn));
	    $num_external = mysqli_num_rows($select_external);
	    if($num_external > 0){
	        while ($ext = mysqli_fetch_array($select_external)) {
	            $External_Scrub_Nurse_Value = $ext['External_Scrub_Nurse'];
	        }
	    }else{
	        $External_Scrub_Nurse_Value = '';
	    }

	    if($External_Scrub_Nurse_Value != '' && $External_Scrub_Nurse_Value != ''){
	    	$Externals = '('.$External_Scrub_Nurse_Value.')**External';
	    }else{
	    	$Externals = '';
	    }

	    //check if selected Nurse already added
	    $check = mysqli_query($conn,"SELECT Employee_ID, Post_operative_ID, Employee_Type FROM tbl_post_operative_participant WHERE
	    						Post_operative_ID = '$Post_operative_ID' and
	    						Employee_ID = '$Nurse_ID'") or die(mysqli_error($conn));
	    $num_check = mysqli_num_rows($check);
	    if($num_check < 1){
	    	$add = mysqli_query($conn,"INSERT INTO tbl_post_operative_participant(Post_operative_ID, Employee_ID, Employee_Type)
	    						values('$Post_operative_ID','$Nurse_ID','Nurse')") or die(mysqli_error($conn));
	    	if($add){
	    		$select = mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee emp, tbl_post_operative_participant pop WHERE
	    								emp.Employee_ID = pop.Employee_ID and
	    								pop.Post_operative_ID = '$Post_operative_ID' and
	    								pop.Employee_Type = 'Nurse'") or die(mysqli_error($conn));
	    		$nm = mysqli_num_rows($select);
	    		if($nm > 0){
	    			$values = '';
	    			while ($row = mysqli_fetch_array($select)) {
	    				$values.= ucwords(strtolower($row['Employee_Name'])).';    ';
	    			}
	    			echo $values.$Externals;
	    		}else{
	    			echo $Externals;
	    		}
	    	}
	    }else{
			$select = mysqli_query($conn,"select Employee_Name from tbl_employee emp, tbl_post_operative_participant pop where
    								emp.Employee_ID = pop.Employee_ID and
    								pop.Post_operative_ID = '$Post_operative_ID' and
    								pop.Employee_Type = 'Nurse'") or die(mysqli_error($conn));
    		$nm = mysqli_num_rows($select);
    		if($nm > 0){
    			$values = '';
    			while ($row = mysqli_fetch_array($select)) {
    				$values.= ucwords(strtolower($row['Employee_Name'])).';    ';
    			}
    			echo $values.$Externals;
    		}else{
    			echo $Externals;
    		}
	    }
	}
?>