<?php
	include("./includes/connection.php");
	if(isset($_GET['Employee_ID'])){
		$Employee_ID = $_GET['Employee_ID'];
	}else{
		$Employee_ID = 0;
	}

	if($Employee_ID != 0){
		$update = mysqli_query($conn,"delete from tbl_approval_employee where Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
		if($update){
			echo "yes";
		}else{
			echo "no";
		}
	}else{
		echo "no";
	}
?>