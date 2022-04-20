<?php
	session_start();
	include("./includes/connection.php");
	
	if(isset($_GET['Patient_Bill_ID'])){
		$Patient_Bill_ID = $_GET['Patient_Bill_ID'];
	}else{
		$Patient_Bill_ID = '';
	}

	if(isset($_GET['Registration_ID'])){
		$Registration_ID = $_GET['Registration_ID'];
	}else{
		$Registration_ID ='';
	}

	if(isset($_GET['Prepaid_ID'])){
		$Prepaid_ID = $_GET['Prepaid_ID'];
	}else{
		$Prepaid_ID ='';
	}

	//check if status allows bill to be approved
	$select = mysqli_query($conn,"select Status from tbl_prepaid_details where Prepaid_ID = '$Prepaid_ID'") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	if($num > 0){
		while ($data = mysqli_fetch_array($select)) {
			$Status = $data['Status'];
		}
	}else{
		$Status = '';
	}

	if(strtolower($Status) == 'pending'){
		echo "yes"; //ready to be cleared
	}else if(strtolower($Status) == 'cleared'){
		echo "true"; //already cleared
	}else{
		echo "no"; //other status
	}
?>