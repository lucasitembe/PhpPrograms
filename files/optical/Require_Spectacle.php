<?php
	session_start();
	include("./includes/connection.php");
	
	if(isset($_GET['Section'])){
		$Section = $_GET['Section'];
	}else{
		$Section = 'Outpatient';
	}

	if(isset($_GET['Registration_ID'])){
		$Registration_ID = $_GET['Registration_ID'];
	}else{
		$Registration_ID = 0;
	}

	if(isset($_GET['Employee_ID'])){
		$Employee_ID = $_GET['Employee_ID'];
	}else{
		$Employee_ID = NULL;
	}

	if(isset($_GET['Status'])){
		$Status = $_GET['Status'];
	}else{
		$Status = '';
	}

	if(isset($_GET['consultation_ID'])){
		$consultation_ID = $_GET['consultation_ID'];
	}else{
		$consultation_ID = '';
	}

	if($Registration_ID != 0 && $consultation_ID != ''){
            
            
		//check if patient is pending
		$slct = mysqli_query($conn,"select Spectacle_ID from tbl_spectacles where Registration_ID = '$Registration_ID' and Spectacle_Status = 'pending' and consultation_ID = '$consultation_ID'") or die(mysqli_error($conn));
		$nm = mysqli_num_rows($slct);
		
		if($nm == 0 && $Status == 'Checked'){
			$insert = mysqli_query($conn,"insert into tbl_spectacles(Registration_ID,consultation_ID,Date_Time,Patient_Type,Employee_ID) values('$Registration_ID','$consultation_ID',(select now()),'$Section','$Employee_ID')") or die(mysqli_error($conn));
			if($insert){
				echo "inserted";
			}else{
				echo "not";
			}
		}else if($Status == 'Not_checked'){
			$update = mysqli_query($conn,"delete from tbl_spectacles where Registration_ID = '$Registration_ID' and Spectacle_Status = 'pending' and consultation_ID = '$consultation_ID'") or die(mysqli_error($conn));
		}
	}
?>

