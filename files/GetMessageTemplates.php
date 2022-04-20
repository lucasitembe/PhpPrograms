<?php 
	require_once('includes/connection.php');
	isset($_GET['Dept']) ? $Message_Department = $_GET['Dept'] : $Message_Department = "";
	
	$select_message = "SELECT * FROM tbl_smsalert_templates WHERE Department_Name = '$Message_Department'";
	$select_message_qry = mysqli_query($conn,$select_message) or die(mysqli_error($conn));
	$count_message = mysqli_num_rows($select_message_qry);
	if($count_message > 0){
		while($msg = mysqli_fetch_assoc($select_message_qry)){
			$Message = $msg['Message'];
			echo $Message;
		}
	}
?>

