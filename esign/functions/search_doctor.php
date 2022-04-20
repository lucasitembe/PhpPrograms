<?php
require_once('../../includes/connection.php');

//getting the reg_no
$Employee_ID = mysqli_real_escape_string($conn,$_GET['Employee_ID']);
$query = "Select * from tbl_employee where Employee_ID='$Employee_ID' limit 1";
$result = mysqli_query($conn,$query) or die(mysqli_error($conn));
$row = mysqli_fetch_object($result);
//print_r($row);


if($row){
	$response = array(
		'status' => '200',
		'Employee_Name' => $row->Employee_Name,
		'Employee_ID' => $row->Employee_ID,
		);
	echo json_encode($response);
} else {
	$response = array(
		'status' => '404',
		'message' => "No Employee found with Employee ID : ".$reg_no,
		);
	echo json_encode($response);
}