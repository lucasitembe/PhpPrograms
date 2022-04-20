<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_SESSION['userinfo']['Employee_ID'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
        $Employee_ID = 0;
    }

	if(isset($_GET['Payment_Item_Cache_List_ID'])){
		$Payment_Item_Cache_List_ID = $_GET['Payment_Item_Cache_List_ID'];
	}else{
		$Payment_Item_Cache_List_ID = '';
	}

	if(isset($_GET['consultation_ID'])){
		$consultation_ID = $_GET['consultation_ID'];
	}else{
		$consultation_ID = '';
	}

	if(isset($_GET['External_Participant'])){
		$External_Participant = $_GET['External_Participant'];
	}else{
		$External_Participant = '';
	}

	if(isset($_GET['Participant_Type'])){
		$Participant_Type = $_GET['Participant_Type'];
	}else{
		$Participant_Type = '';
	}

	if(isset($_GET['Registration_ID'])){
		$Registration_ID = $_GET['Registration_ID'];
	}else{
		$Registration_ID = '';
	}

	//get Post_operative_ID
	$select = mysqli_query($conn,"select Post_operative_ID from tbl_post_operative_notes where Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	if($num > 0){
		while ($data = mysqli_fetch_array($select)) {
			$Post_operative_ID = $data['Post_operative_ID'];
		}
	}else{
		$insert = mysqli_query($conn,"insert into tbl_post_operative_notes(
    							Payment_Item_Cache_List_ID, Surgery_Date, Surgery_Date_Time, 
    							consultation_ID, Registration_ID, Employee_ID)
	    							
	    						values('$Payment_Item_Cache_List_ID',(select now()),(select now()),
	    							'$consultation_ID','$Registration_ID','$Employee_ID')") or die(mysqli_error($conn));

		if($insert){
			$select = mysqli_query($conn,"select Post_operative_ID from tbl_post_operative_notes where Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
			$num = mysqli_num_rows($select);
			if($num > 0){
				while ($data = mysqli_fetch_array($select)) {
					$Post_operative_ID = $data['Post_operative_ID'];
				}
			}else{
				$Post_operative_ID = '';
			}
		}else{
			$Post_operative_ID = '';
		}
	}
echo $Participant_Type;
	//check if data available 
	$select = mysqli_query($conn,"select External_Participant_ID from tbl_post_operative_external_participant where Post_operative_ID = '$Post_operative_ID'") or die(mysqli_error($conn));
	$no = mysqli_num_rows($select);
	if($no < 1){
		$insert = mysqli_query($conn,"insert into tbl_post_operative_external_participant(Post_operative_ID) values('$Post_operative_ID')") or die(mysqli_error($conn));
	}

	//generate suppotive sql
	if($Participant_Type == 'Surgeon'){
		$sql = "update tbl_post_operative_external_participant set External_Surgeons = '$External_Participant' where Post_operative_ID = '$Post_operative_ID'";
	}else if($Participant_Type == 'Assistant_Surgeon'){
		$sql = "update tbl_post_operative_external_participant set External_Assistant_Surgeon = '$External_Participant' where Post_operative_ID = '$Post_operative_ID'";
	}else if($Participant_Type == 'Scrub_Nurse'){
		$sql = "update tbl_post_operative_external_participant set External_Scrub_Nurse = '$External_Participant' where Post_operative_ID = '$Post_operative_ID'";
	}else if($Participant_Type == 'Anaesthetic'){
		$sql = "update tbl_post_operative_external_participant set External_Anaesthetic = '$External_Participant' where Post_operative_ID = '$Post_operative_ID'";
	}else{
		$sql = 'select now()';
	}
	//update selected external
	$update = mysqli_query($conn,$sql) or die(mysqli_error($conn));
?>