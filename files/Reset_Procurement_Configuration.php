<?php
	include("./includes/connection.php");

	//delete data from tbl_approval_employee
	$delete1 = mysqli_query($conn,"delete from tbl_approval_employee");

	//delete data from tbl_approval_level
	if($delete1){
		$delete2 = mysqli_query($conn,"delete from tbl_approval_level");

		//update system configuration table
		if($delete2){
			mysqli_query($conn,"update tbl_system_configuration set Approval_Levels = '0'") or die(mysqli_error($conn));
		}
	}
?>