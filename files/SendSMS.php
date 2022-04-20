<?php
	require_once('includes/sms/sms.php');
	require_once('includes/connection.php'); 
	
	isset($_GET['Department']) ? $Department = $_GET['Department'] : $Department = '';
	isset($_GET['Receiver']) ? $Receiver = $_GET['Receiver'] : $Receiver = '';
	isset($_GET['Registration_ID']) ? $Registration_ID = $_GET['Registration_ID'] : $Registration_ID = 0;

	$select_message = "SELECT * FROM tbl_smsalert_templates WHERE Department_Name = '$Department'";
	$select_message_qry = mysqli_query($conn,$select_message) or die(mysqli_error($conn));
	$count_message = mysqli_num_rows($select_message_qry);
	if($count_message > 0){
		while($msg = mysqli_fetch_assoc($select_message_qry)){
			$Message = $msg['Message'];
		}
		if($Department == 'Registration'){
			$Message .= $Registration_ID;
		}
	} elseif($Department == 'Radiology') {
		$Message = "Your Radiology Results are ready.";		
	} elseif($Department == 'Lab'){
		$Message = "Your Laboratory Results are ready.";		
	} else {
		$Message = "Thanks for Registering at Our Hospital, your Registration number is: " . $Registration_ID;		
	}
	
	$send = SendSMS($Receiver, $Message);

	
?>