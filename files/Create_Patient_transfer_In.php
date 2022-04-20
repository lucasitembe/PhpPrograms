<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_SESSION['userinfo'])){
		$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
	}else{
		$Employee_ID = 0;
	}

	if(isset($_GET['Registration_ID'])){
		$Registration_ID = $_GET['Registration_ID'];
	}else{
		$Registration_ID = '';
	}

	if(isset($_GET['Admision_ID'])){
		$Admision_ID = $_GET['Admision_ID'];
	}else{
		$Admision_ID = '';
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

	if(isset($_GET['room_id'])){
		$room_id = $_GET['room_id'];
	}else{
		$room_id = '';
	}

	

	if($Employee_ID != 0 && $Registration_ID != null && $Admision_ID != '' && $Hospital_Ward_ID != null && $Hospital_Ward_ID != '' && isset($_SESSION['userinfo'])){
		$select = mysqli_query($conn,"select Transfer_Detail_ID from tbl_patient_transfer_details where Admision_ID = '$Admision_ID' and Transfer_Status = 'pending'") or die(mysqli_error($conn));
		$num = mysqli_num_rows($select);
		if($num > 0){
			$update1 = mysqli_query($conn,"UPDATE tbl_admission set Hospital_Ward_ID = '$Hospital_Ward_ID', Bed_ID = '$Bed_ID', ward_room_id='$room_id' where Admision_ID = '$Admision_ID'") or die(mysqli_error($conn));
			if($update1){
				$update2 = mysqli_query($conn,"UPDATE tbl_patient_transfer_details set Transfer_Status = 'received', Received_By = '$Employee_ID', Received_Date = (select now()) where Admision_ID = '$Admision_ID' and Transfer_Status = 'pending'") or die(mysqli_error($conn));
				$update_patient_info = mysqli_query($conn,"UPDATE tbl_transfer_out_in set transfer_status = 'received',transfer_in_date=(select now()) where Admision_ID = '$Admision_ID' and transfer_status = 'pending'") or die(mysqli_error($conn));
				echo "Received";
			}
		}
	}
?>