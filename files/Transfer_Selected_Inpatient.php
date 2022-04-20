<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_SESSION['userinfo']['Employee_ID'])){
		$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
	}else{
		$Employee_ID = 0;
	}

	if(isset($_GET['consultation_ID'])){
		$consultation_ID = $_GET['consultation_ID'];
	}else{
		$consultation_ID = '';
	}

	if(isset($_GET['Hospital_Ward_ID'])){
		$Hospital_Ward_ID = $_GET['Hospital_Ward_ID'];
	}else{
		$Hospital_Ward_ID = '';
	}

	if(isset($_GET['Bed_ID'])){
		$Bed_ID = $_GET['Bed_ID'];
	}else{
		$Bed_ID = '';
	}


	//get previous details
	$get_details = mysqli_query($conn,"select ad.Hospital_Ward_ID, ad.Bed_ID, ad.Admision_ID from
                                        tbl_admission ad, tbl_check_in_details cd where
                                        cd.Admission_ID = ad.Admision_ID and
                                        cd.consultation_ID = '$consultation_ID'") or die(mysqli_error($conn));
	$nm = mysqli_num_rows($get_details);
	if($nm > 0){
		while ($data = mysqli_fetch_array($get_details)) {
			$Current_Hospital_Ward_ID = $data['Hospital_Ward_ID'];
			$Current_Bed_ID = $data['Bed_ID'];
			$Admision_ID = $data['Admision_ID'];
		}
	}else{
		$Current_Hospital_Ward_ID = NULL;
		$Current_Bed_ID = NULL;
		$Admision_ID = NULL;
	}

	//keep current info before update
	$keep = mysqli_query($conn,"insert into tbl_ward_transfer_history(
							Source_Ward, Source_Bed, Destination_Ward,
    						Destination_Bed, Employee_ID, Transfer_Date_Time, Admision_ID)
    					values('$Current_Hospital_Ward_ID','$Current_Bed_ID','$Hospital_Ward_ID',
    						'$Bed_ID','$Employee_ID',(select now()),'$Admision_ID')") or die(mysqli_error($conn));
	if($keep){
		//perform transfer
		$transfer = mysqli_query($conn,"update tbl_admission set Hospital_Ward_ID = '$Hospital_Ward_ID', Bed_ID = '$Bed_ID' where Admision_ID = '$Admision_ID'") or die(mysqli_error($conn));
		if($transfer){
			echo "yes";
		}else{
			echo "no";
		}
	}else{
		echo "no";
	}
 ?>