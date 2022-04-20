<?php 
	require_once('includes/connection.php'); 
	isset($_GET['Message_Department']) ? $Message_Department = $_GET['Message_Department'] : $Message_Department = '';
	isset($_GET['Message']) ? $Message = mysqli_real_escape_string($conn,$_GET['Message']) : $Message = '';
	
	$message_data = '';
	
	$select_message = "SELECT * FROM tbl_smsalert_templates WHERE Department_Name = '$Message_Department'";
	$select_message_qry = mysqli_query($conn,$select_message) or die(mysqli_error($conn));
	$count_message = mysqli_num_rows($select_message_qry);
	if($count_message > 0){
		$update_message = "UPDATE tbl_smsalert_templates SET Message = '$Message' WHERE Department_Name = '$Message_Department'";
		$update_message_qry = mysqli_query($conn,$update_message) or die(mysqli_error($conn));
		if($update_message_qry){
			$message_data .= "Message Updated";
		}
	} else {
		
		$insert_message = "INSERT INTO tbl_smsalert_templates(Department_Name, Message) VALUES('$Message_Department', '$Message')";
		$insert_message_qry = mysqli_query($conn,$insert_message) or die(mysqli_error($conn));
		
		if($insert_message_qry){
			$message_data .= "Message Saved";
		} else {
			$message_data .= "Message not Saved";			
		}
		
	}
	
	echo $message_data;
?>

