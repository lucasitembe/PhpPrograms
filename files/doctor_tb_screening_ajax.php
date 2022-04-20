<?php
include("./includes/connection.php");
	if(isset($_POST['tb_screenig']) && $_POST['tb_screenig']=='yes'){
		$Registration_ID=mysqli_real_escape_string($conn,$_POST['Registration_ID']);
		$consultation_ID=mysqli_real_escape_string($conn,$_POST['consultation_ID']);
		$cough=mysqli_real_escape_string($conn,$_POST['cough']);
		$household_history=mysqli_real_escape_string($conn,$_POST['household_history']);
		$fever=mysqli_real_escape_string($conn,$_POST['fever']);
		$irritability=mysqli_real_escape_string($conn,$_POST['irritability']);
		$weight_change=mysqli_real_escape_string($conn,$_POST['weight_change']);
		$past_treatment=mysqli_real_escape_string($conn,$_POST['past_treatment']);
		$excessive_sweat=mysqli_real_escape_string($conn,$_POST['excessive_sweat']);
		$other_symptoms=mysqli_real_escape_string($conn,$_POST['other_symptoms']);
		$cough_less=mysqli_real_escape_string($conn,$_POST['cough_less']);
        $sputum_yes=mysqli_real_escape_string($conn,$_POST['sputum_yes']);
        $cough_blood=mysqli_real_escape_string($conn,$_POST['cough_blood']);
		$total_score = mysqli_real_escape_string($conn,$_POST['total_score']);
		$check_existance=mysqli_query($conn,"SELECT consultation_ID FROM tbl_bt_diagnosis WHERE consultation_ID = $consultation_ID AND Registration_ID=$Registration_ID");
		if(mysqli_num_rows($check_existance) < 1){
		$query=mysqli_query($conn,"INSERT INTO tbl_bt_diagnosis (Registration_ID,consultation_ID,cough,household_history,						fever,irritability,weight_change,past_treatment,excessive_sweat,other_symptoms,cough_less,					sputum_yes,cough_blood,total_score,date_time) 
							VALUES ($Registration_ID,$consultation_ID,'$cough','$household_history','$fever','$irritability','$weight_change','$past_treatment','$excessive_sweat','$other_symptoms','$cough_less','$sputum_yes','$cough_blood','$total_score',NOW())");
		if($query){
				echo "Data Saved Successifully";
			}else {
				echo mysqli_error($conn);
			}
		}else{
			echo "Patient already exist";
		}
	}
?>
