<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_SESSION['userinfo']['Employee_ID'])){
		$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
	}else{
		$Employee_ID = NULL;
	}

	if(isset($_GET['Consultation_ID'])){
		$Consultation_ID = mysqli_real_escape_string($conn,$_GET['Consultation_ID']);
	}else{
		$Consultation_ID = '';
	}

	if(isset($_GET['Admision_ID'])){
		$Admision_ID = mysqli_real_escape_string($conn,$_GET['Admision_ID']);
	}else{
		$Admision_ID = '';
	}

	if(isset($_GET['Assessment'])){
		$Assessment = mysqli_real_escape_string($conn,$_GET['Assessment']);
	}else{
		$Assessment = '';
	}

	if(isset($_GET['Nursing_Diagnosis'])){
		$Nursing_Diagnosis = mysqli_real_escape_string($conn,$_GET['Nursing_Diagnosis']);
	}else{
		$Nursing_Diagnosis = '';
	}

	if(isset($_GET['Goal'])){
		$Goal = mysqli_real_escape_string($conn,$_GET['Goal']);
	}else{
		$Goal = '';
	}

	if(isset($_GET['Nursing_Interverntion'])){
		$Nursing_Interverntion = mysqli_real_escape_string($conn,$_GET['Nursing_Interverntion']);
	}else{
		$Nursing_Interverntion = '';
	}

	if(isset($_GET['Evaluation'])){
		$Evaluation = mysqli_real_escape_string($conn,$_GET['Evaluation']);
	}else{
		$Evaluation = '';
	}

	if(isset($_GET['Registration_ID'])){
		$Registration_ID = mysqli_real_escape_string($conn,$_GET['Registration_ID']);
	}else{
		$Registration_ID = '';
	}

        if($Admision_ID ==''||$Admision_ID ==0){
            
            $insert = mysqli_query($conn,"insert into tbl_Patient_Progress(
							 Checked_Date_Time, Employee_ID, 
							Assessment, Nursing_Diagnosis, Goal, 
							Nursing_Interverntion, Evaluation, Registration_ID, Consultation_ID)
						values((select now()),'$Employee_ID',
							'$Assessment','$Nursing_Diagnosis','$Goal',
							'$Nursing_Interverntion','$Evaluation','$Registration_ID','$Consultation_ID')") or die(mysqli_error($conn));
        }else if($Consultation_ID ==''||$Consultation_ID ==0){
            
            $insert = mysqli_query($conn,"insert into tbl_Patient_Progress(
							Admision_ID, Checked_Date_Time, Employee_ID, 
							Assessment, Nursing_Diagnosis, Goal, 
							Nursing_Interverntion, Evaluation, Registration_ID)
						values('$Admision_ID',(select now()),'$Employee_ID',
							'$Assessment','$Nursing_Diagnosis','$Goal',
							'$Nursing_Interverntion','$Evaluation','$Registration_ID')") or die(mysqli_error($conn));
        }else{
            
          $insert = mysqli_query($conn,"insert into tbl_Patient_Progress(
							Admision_ID, Checked_Date_Time, Employee_ID, 
							Assessment, Nursing_Diagnosis, Goal, 
							Nursing_Interverntion, Evaluation, Registration_ID, Consultation_ID)
						values('$Admision_ID',(select now()),'$Employee_ID',
							'$Assessment','$Nursing_Diagnosis','$Goal',
							'$Nursing_Interverntion','$Evaluation','$Registration_ID','$Consultation_ID')") or die(mysqli_error($conn));  
        }
	if($insert){
		echo "yes";
	}else{
		echo "no";
	}

?>
