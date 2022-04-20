<?php
require_once('../../includes/connection.php');

//getting the reg_no
$reg_no = mysqli_real_escape_string($conn,$_GET['reg_no']);
$query = "Select * from tbl_patient_registration where Registration_ID='$reg_no' limit 1";
$result = mysqli_query($conn,$query) or die(mysqli_error($conn));
$row = mysqli_fetch_object($result);
//print_r($row);


if($row){
	$Sponsor_ID = $row->Sponsor_ID;
	$Sponsor = mysqli_fetch_object(mysqli_query($conn,"Select Guarantor_Name from tbl_sponsor where Sponsor_ID='$Sponsor_ID' limit 1"));
	$response = array(
		'status' => '200',
		'Patient_Name' => $row->Patient_Name,
		'Registration_ID' => $row->Registration_ID,
		'Sponsor' => $Sponsor->Guarantor_Name,
		);
	echo json_encode($response);
} else {
	$response = array(
		'status' => '404',
		'message' => "No patient found with Reg # : ".$reg_no,
		);
	echo json_encode($response);
}