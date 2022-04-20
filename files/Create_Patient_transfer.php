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

	if(isset($_GET['reason'])){
		$reason = $_GET['reason'];
	}else{
		$reason = '';
	}
        
         $ward_out_ID = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Hospital_Ward_ID FROM tbl_admission WHERE Admision_ID='$Admision_ID'"))['Hospital_Ward_ID'];

	if($Employee_ID != 0 && $Registration_ID != null && $Admision_ID != ''){
		//check if no pending  process
		$select = mysqli_query($conn,"SELECT Transfer_Detail_ID from tbl_patient_transfer_details where Admision_ID = '$Admision_ID' and Transfer_Status = 'pending'") or die(mysqli_error($conn));
		$num = mysqli_num_rows($select);
		if($num < 1){
			$insert = mysqli_query($conn,"INSERT into tbl_patient_transfer_details(	Registration_ID, Admision_ID, Hospital_Ward_ID, Bed_ID,room_id, Employee_ID, Created_Date_Time,reasson_for_tranfer) values('$Registration_ID','$Admision_ID','$Hospital_Ward_ID','$Bed_ID','$room_id','$Employee_ID',NOW(),'$reason')") or die(mysqli_error($conn));
              if($insert){
				  $insert_transfer_detais = mysqli_query($conn,"INSERT INTO tbl_transfer_out_in(Admision_ID,out_ward_id,in_ward_id,transfer_out_date,transfer_status)VALUES($Admision_ID,$Hospital_Ward_ID,$ward_out_ID,(select now()),'pending')");
					if($insert_transfer_detais){
						echo "Transfer Pending";
					}
			  }          
            
		}
	}
?>