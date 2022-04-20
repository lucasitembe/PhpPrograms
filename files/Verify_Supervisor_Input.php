<?php
	include("./includes/connection.php");
	if(isset($_GET['Username'])){
		$Username = $_GET['Username'];
	}else{
		$Username = '';
	}
	if(isset($_GET['Password'])){
		$Password = md5($_GET['Password']);
	}else{
		$Password = md5('');
	}

	//check supervisor validity
	$select = mysqli_query($conn,"select Employee_ID from tbl_privileges where
							Given_Username = '$Username' and
							Given_Password = '$Password' and
							Session_Master_Priveleges = 'yes'") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	if($num > 0){
		echo "yes";
	}else{
		echo "no";
	}
?>