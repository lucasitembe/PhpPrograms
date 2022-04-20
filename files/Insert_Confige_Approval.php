<?php
	include("./includes/connection.php");

	if(isset($_GET['Approval_Level'])){
		$Approval_Level = $_GET['Approval_Level'];
	}else{
		$Approval_Level = 0;
	}

	if($Approval_Level != 0){
		$update = mysqli_query($conn,"update tbl_system_configuration set Approval_Levels = '$Approval_Level'") or die(mysqli_error($conn));
		if($update){
			for($i = 1; $i <= $Approval_Level; $i++){
				mysqli_query($conn,"insert into tbl_approval_level(Approval_ID) values('$i')") or die(mysqli_error($conn));
			}
			echo 'yes';
		}else{
			echo 'no';
		}
	}else{
		echo 'no';
	}
?>