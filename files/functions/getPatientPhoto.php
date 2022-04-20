<?php
include_once("../includes/connection.php");
$patient_id = mysqli_real_escape_string($conn,$_GET['patient_id']);

$query = "SELECT Patient_Picture from tbl_patient_registration where Registration_ID='$patient_id' limit 1";
$result = mysqli_query($conn,$query) or die(mysqli_error($conn));

$Patient_Picture = mysqli_fetch_assoc($result)['Patient_Picture'];
if(!empty($Patient_Picture)){
	echo "<img <img src='./patientImages/{$Patient_Picture}' id='Patient_Picture' style='max-width:370px;min-width:370px;min-height:310px;max-height:310px;'>";
} else {
	// echo "No Patient image uploaded";
	echo "<img <img src='./patientImages/index.png' id='Patient_Picture' title='THIS PATIENT&#39;S IMAGE NOT UPLOADED' style='max-width:370px;min-width:370px;min-height:310px;max-height:310px;'>";

}
//'.$data->Patient_Picture.'