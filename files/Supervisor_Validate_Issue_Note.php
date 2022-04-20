<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_SESSION['userinfo']['Given_Username'])){
		$current_username = $_SESSION['userinfo']['Given_Username'];
	}else{
		$current_username = '';
	}
	
	if(isset($_GET['Supervisor_Username'])){
		$Supervisor_Username = $_GET['Supervisor_Username'];
	}else{
		$Supervisor_Username = '';
	}

	if(isset($_GET['Supervisor_Password'])){
		$Supervisor_Password = md5($_GET['Supervisor_Password']);
	}else{
		$Supervisor_Password = md5('');
	}

	if(isset($_GET['Requisition_ID'])){
		$Requisition_ID = $_GET['Requisition_ID'];
	}else{
		$Requisition_ID = '';
	}
 

	if(($current_username == $Supervisor_Username)){
		echo "same user";
	}else{
		//validate
		$validate = mysqli_query($conn,"select Employee_ID from tbl_privileges where
									Given_Password = '$Supervisor_Password' and
									Given_Username = '$Supervisor_Username' and
									Session_Master_Priveleges = 'yes'") or die(mysqli_error($conn));
		$num = mysqli_num_rows($validate);
		if($num > 0){
			while ($data = mysqli_fetch_array($validate)) {
				$Employee_ID = $data['Employee_ID'];
			}
			mysqli_query($conn,"update tbl_requisition set Supervisor_ID = '$Employee_ID' where Requisition_ID = '$Requisition_ID'") or die(mysqli_error($conn));
			echo "yes";
		}else{
			echo "no";
		}
	}
?>