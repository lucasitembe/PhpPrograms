<?php
require_once("functions2.php");
require_once('../../includes/connection.php');
if(isset($_POST['person_type']) && $_POST['person_type']=="employee"){
	//creating an image version of the employee signature
		base64_to_jpeg($_POST['signature'],"../employee_signatures/".trim($_POST['reg_no']).'.png');
		$signature_path = trim($_POST['reg_no']).'.png';
		$Patient_Payment_ID = $_POST['Patient_Payment_ID'];
		$Patient_Payment_Item_List_ID = $_POST['Patient_Payment_Item_List_ID'];
		$reg_no = $_POST['reg_no'];

		//updating the patient signature
		$query = "INSERT into optical_image(Patient_Payment_ID,Patient_Payment_Item_List_ID,reg_no,optical_img) values('$Patient_Payment_ID','$Patient_Payment_Item_List_ID','$reg_no','$signature_path')";
		$result = mysqli_query($conn,$query) or die(mysqli_error($conn));

		if($result){
			$response = array(
			'status' => '200',
			'message' => 'patient image Has Been Saved Sucessfully',
			);
			echo json_encode($response);
			
		} else {
			//unlink("../patients_signature/".$_POST['reg_no'].'.png');
			$response = array(
			'status' => '500',
			'message' => 'Failed to save patient image',
			);
			echo json_encode($response);
		}


}
 else {
	//creating an image version of EYE IMAGE
	$Patient_Payment_Item_List_ID = $_POST['Patient_Payment_Item_List_ID'];
	$get_date=date("y-m-d h:m:s");
    $dateleo = strtotime($get_date);
	base64_to_jpeg($_POST['signature'],"../patients_signature/".$dateleo.'-'.$Patient_Payment_Item_List_ID.'.png');
	$signature_path = $dateleo.'-'.$Patient_Payment_Item_List_ID.'.png';
	$reg_no = trim($_POST['reg_no']);
	$Patient_Payment_Item_List_ID = $_POST['Patient_Payment_Item_List_ID'];
	$Patient_Payment_ID = $_POST['Patient_Payment_ID'];
	

	//INSERTING EYE IMAGE IN DATABASE
	$query = "INSERT into optical_image(Patient_Payment_ID,Patient_Payment_Item_List_ID,reg_no,optical_img) values('$Patient_Payment_ID','$Patient_Payment_Item_List_ID','$$reg_no','$signature_path')";
		$result = mysqli_query($conn,$query) or die(mysqli_error($conn));
	if($result){
		$response = array(
		'status' => '200',
		'message' => 'Patient Eye Image Has Been Saved Sucessfully',
		);
		echo json_encode($response);
	} else {
		unlink("../patients_signature/".$_POST['reg_no'].'.png');
		$response = array(
		'status' => '500',
		'message' => 'Failed to save Patient Eye Image',
		);
		echo json_encode($response);
	}

}
