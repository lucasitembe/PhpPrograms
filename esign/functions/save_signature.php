<?php
require_once("functions.php");
require_once('../../includes/connection.php');
if(isset($_POST['person_type']) && $_POST['person_type']=="employee"){
	//creating an image version of the employee signature
		base64_to_jpeg($_POST['signature'],"../employee_signatures/".trim($_POST['reg_no']).'.png');
		$signature_path = trim($_POST['reg_no']).'.png';
		$reg_no = trim($_POST['reg_no']);

		//updating the patient signature
		$query = "update tbl_employee set employee_signature='$signature_path' where Employee_ID='$reg_no'";
		$result = mysqli_query($conn,$query) or die(mysqli_error($conn));

		if($result){
			$response = array(
			'status' => '200',
			'message' => 'Employee Signature Has Been Saved Sucessfully',
			);
			echo json_encode($response);
		} else {
			//unlink("../patients_signature/".$_POST['reg_no'].'.png');
			$response = array(
			'status' => '500',
			'message' => 'Failed to save Employee Signature',
			);
			echo json_encode($response);
		}


} else {
	//creating an image version of the patient signature
base64_to_jpeg($_POST['signature'],"../patients_signature/".trim($_POST['reg_no']).'.png');
$signature_path = trim($_POST['reg_no']).'.png';
$reg_no = trim($_POST['reg_no']);
$Check_In_ID = trim($_POST['Check_In_ID']);

//updating the patient signature
$query = "UPDATE tbl_patient_registration set patient_signature='$signature_path' where Registration_ID='$reg_no'";
$result = mysqli_query($conn, $query) or die(mysqli_error($conn));

//die("UPDATE tbl_check_in set signature='$signature_path' where Registration_ID='$reg_no' AND Check_In_ID = '$Check_In_ID'");
$query = "UPDATE tbl_check_in set signature='$signature_path' where Registration_ID='$reg_no' AND Check_In_ID = '$Check_In_ID'";
$result = mysqli_query($conn, $query) or die(mysqli_error($conn));

if($result){
	$response = array(
	'status' => '200',
	'message' => 'Patient Signature Has Been Saved Sucessfully ',
	);
	echo json_encode($response);
} else {
	unlink("../patients_signature/".$_POST['reg_no'].'.png');
	$response = array(
	'status' => '500',
	'message' => 'Failed to save Patient Signature',
	);
	echo json_encode($response);
}

}
