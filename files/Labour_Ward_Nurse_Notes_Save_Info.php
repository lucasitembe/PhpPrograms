<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_SESSION['userinfo']['Employee_ID'])){
		$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
	}else{
		$Employee_ID = NULL;
	}

	if(isset($_GET['Registration_ID'])){
		$Registration_ID = $_GET['Registration_ID'];
	}else{
		$Registration_ID = '';
	}

	if(isset($_GET['Consultation_ID'])){
		$Consultation_ID = $_GET['Consultation_ID'];
	}else{
		$Consultation_ID = '';
	}

	if(isset($_GET['Admision_ID'])){
		$Admision_ID = $_GET['Admision_ID'];
	}else{
		$Admision_ID = '';
	}

	if(isset($_GET['Palpation'])){
		$Palpation = $_GET['Palpation'];
	}else{
		$Palpation = '';
	}

	if(isset($_GET['Presentation'])){
		$Presentation = $_GET['Presentation'];
	}else{
		$Presentation = '';
	}

	if(isset($_GET['Position'])){
		$Position = $_GET['Position'];
	}else{
		$Position = '';
	}

	if(isset($_GET['Contraction'])){
		$Contraction = $_GET['Contraction'];
	}else{
		$Contraction = '';
	}

	if(isset($_GET['Liquir'])){
		$Liquir = $_GET['Liquir'];
	}else{
		$Liquir = '';
	}

	if(isset($_GET['Colour'])){
		$Colour = $_GET['Colour'];
	}else{
		$Colour = '';
	}

	if(isset($_GET['Pv_Examination'])){
		$Pv_Examination = $_GET['Pv_Examination'];
	}else{
		$Pv_Examination = '';
	}

	if(isset($_GET['OS'])){
		$OS = $_GET['OS'];
	}else{
		$OS = '';
	}

	if(isset($_GET['Membrane'])){
		$Membrane = $_GET['Membrane'];
	}else{
		$Membrane = '';
	}

	if(isset($_GET['Temperature'])){
		$Temperature = $_GET['Temperature'];
	}else{
		$Temperature = '';
	}

	if(isset($_GET['Purse'])){
		$Purse = $_GET['Purse'];
	}else{
		$Purse = '';
	}

	if(isset($_GET['Respiration'])){
		$Respiration = $_GET['Respiration'];
	}else{
		$Respiration = '';
	}

	if(isset($_GET['BP'])){
		$BP = $_GET['BP'];
	}else{
		$BP = '';
	}

	if(isset($_GET['Exp'])){
		$Exp = $_GET['Exp'];
	}else{
		$Exp = '';
	}

	if(isset($_GET['FHR'])){
		$FHR = $_GET['FHR'];
	}else{
		$FHR = '';
	}

	if(isset($_GET['BMI'])){
		$BMI = $_GET['BMI'];
	}else{
		$BMI = '';
	}

	if(isset($_GET['Remarks'])){
		$Remarks = $_GET['Remarks'];
	}else{
		$Remarks = '';
	}

	if($Employee_ID > 0){
		//check if information already inserted
		$select = mysqli_query($conn,"select Note_ID from tbl_labour_ward_notes where
								Admision_ID = '$Admision_ID' and
								Registration_ID = '$Registration_ID' and
								Consultation_ID = '$Consultation_ID'") or die(mysqli_error($conn));
		$num = mysqli_num_rows($select);
		if($num > 0){
			while ($data = mysqli_fetch_array($select)) {
				$Note_ID = $data['Note_ID'];
			}
			
			//get history
			$history = mysqli_query($conn,"insert into tbl_labour_ward_notes_history(
									Note_ID, Employee_ID, Palpation, Note_Date_Time, Presentation,
									Position, Contraction, Liquir, Colour,
									Pv_Examination, OS, Membrane,
									Temperature, Purse, Respiration,
									BP, FHR, Remarks, Exp, BMI)
								select '$Note_ID', Employee_ID, Palpation, Note_Date_Time, Presentation,
									Position, Contraction, Liquir, Colour,
									Pv_Examination, OS, Membrane,
									Temperature, Purse, Respiration,
									BP, FHR, Remarks, Exp, BMI from tbl_labour_ward_notes where Note_ID = '$Note_ID'") or die(mysqli_error($conn));
			if($history){
				//update
				$update = mysqli_query($conn,"update tbl_labour_ward_notes set Employee_ID = '$Employee_ID',
										Palpation = '$Palpation', Note_Date_Time = (select now()), Presentation = '$Presentation',
										Position = '$Position', Contraction = '$Contraction', Liquir = '$Liquir', Colour = '$Colour',
										Pv_Examination = '$Pv_Examination', OS = '$OS', Membrane = '$Membrane',
										Temperature = '$Temperature', Purse = '$Purse', Respiration = '$Respiration',
										BP = '$BP', FHR = '$FHR', Remarks = '$Remarks', Exp = '$Exp', BMI = '$BMI' where Note_ID = '$Note_ID'") or die(mysqli_error($conn));
				if($update){
					echo "yes";
				}else{
					echo "no";
				}
			}else{
				echo "no";
			}
		}else{
			$insert = mysqli_query($conn,"insert into tbl_labour_ward_notes(
									Employee_ID, Consultation_ID, Admision_ID, Registration_ID,
									Palpation, Note_Date_Time, Presentation,
									Position, Contraction, Liquir, Colour,
									Pv_Examination, OS, Membrane,
									Temperature, Purse, Respiration,
									BP, FHR, Remarks, Exp, BMI) 
								values('$Employee_ID', '$Consultation_ID', '$Admision_ID', '$Registration_ID',
									'$Palpation', (select now()), '$Presentation',
									'$Position', '$Contraction', '$Liquir', '$Colour',
									'$Pv_Examination', '$OS', '$Membrane',
									'$Temperature', '$Purse', '$Respiration',
									'$BP', '$FHR', '$Remarks', '$Exp', '$BMI')") or die(mysqli_error($conn));
			if($insert){
				echo "yes";
			}else{
				echo "no";
			}
		}
	}else{
		echo "no";
	}
?>