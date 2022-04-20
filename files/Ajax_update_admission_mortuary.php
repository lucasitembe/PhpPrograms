<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_POST['admitted_from'])){
		$admitted_from = $_POST['admitted_from'];
	}else{
		$admitted_from = 0;
	}
	if(isset($_POST['Date_Of_Death'])){
		$Date_Of_Death = $_POST['Date_Of_Death'];
	}else{
		$Date_Of_Death = 0;
	}
	if(isset($_POST['Police_Name'])){
		$Police_Name = $_POST['Police_Name'];
	}else{
		$Police_Name = 0;
	}
	if(isset($_POST['Case_Type'])){
		$Case_Type = $_POST['Case_Type'];
	}else{
		$Case_Type = 0;
	}
	if(isset($_POST['Corpse_Brought_By'])){
		$Corpse_Brought_By = $_POST['Corpse_Brought_By'];
	}else{
		$Corpse_Brought_By = 0;
	}
	if(isset($_POST['Police_Title'])){
		$Police_Title = $_POST['Police_Title'];
	}else{
		$Police_Title = 0;
	}
	if(isset($_POST['Corpse_Kin_Relationship'])){
		$Corpse_Kin_Relationship = $_POST['Corpse_Kin_Relationship'];
	}else{
		$Corpse_Kin_Relationship= 0;
	}
	if(isset($_POST['Place_Of_Death'])){
		$Place_Of_Death = $_POST['Place_Of_Death'];
	}else{
		$Place_Of_Death = 0;
	}
	if(isset($_POST['Police_Station'])){
		$Police_Station = $_POST['Police_Station'];
	}else{
		$Police_Station = 0;
	}
	if(isset($_POST['inalala_bilakulala'])){
		$inalala_bilakulala = $_POST['inalala_bilakulala'];
	}else{
		$inalala_bilakulala = 0;
	}
	if(isset($_POST['Vehicle_No_In'])){
		$Vehicle_No_In = $_POST['Vehicle_No_In'];
	}else{
		$Vehicle_No_In= 0;
	}
	if(isset($_POST['Police_No'])){
		$Police_No = $_POST['Police_No'];
	}else{
		$Police_No= 0;
	}
	if(isset($_POST['Nurse_On_Duty'])){
		$Nurse_On_Duty = $_POST['Nurse_On_Duty'];
	}else{
		$Nurse_On_Duty= 0;
	}
	if(isset($_POST['Kin_Phone'])){
		$Kin_Phone = $_POST['Kin_Phone'];
	}else{
		$Kin_Phone= 0;
	}
	if(isset($_POST['Police_Phone'])){
		$Police_Phone = $_POST['Police_Phone'];
	}else{
		$Police_Phone= 0;
	}
	if(isset($_POST['Corpse_Kin_Address'])){
		$Corpse_Kin_Address = $_POST['Corpse_Kin_Address'];
	}else{
		$Corpse_Kin_Address= 0;
	}
	if(isset($_POST['course_of_death'])){
		$course_of_death = $_POST['course_of_death'];
	}else{
		$course_of_death= 0;
	}
	if(isset($_POST['Police_Place'])){
		$Police_Place = $_POST['Police_Place'];
	}else{
		$Police_Place= 0;
	}
	if(isset($_POST['body_Condition'])){
		$course_of_death = $_POST['body_Condition'];
	}else{
		$body_Condition= 0;
	}
	if(isset($_POST['name_of_doctor_confirm_death'])){
		$name_of_doctor_confirm_death = $_POST['name_of_doctor_confirm_death'];
	}else{
		$name_of_doctor_confirm_death= 0;
	}
	if(isset($_POST['Postmortem_Done_By'])){
		$Postmortem_Done_By = $_POST['Postmortem_Done_By'];
	}else{
		$Postmortem_Done_By= 0;
	}
	if(isset($_POST['Postmortem_No'])){
		$Postmortem_No = $_POST['Postmortem_No'];
	}else{
		$Postmortem_No= 0;
	}
	if(isset($_POST['Other_Details'])){
		$Other_Details = $_POST['Other_Details'];
	}else{
		$Other_Details= 0;
	}
	if(isset($_POST['Registration_ID'])){
		$Registration_ID = $_POST['Registration_ID'];
	}else{
		$Registration_ID= 0;
	}

	mysqli_query($conn,"update tbl_mortuary_admission set admitted_from='$admitted_from',inalala_bilakulala='$inalala_bilakulala',"
                . "case_type='$Case_Type' WHERE Corpse_ID='$Registration_ID'") or die(mysqli_error($conn));
?>