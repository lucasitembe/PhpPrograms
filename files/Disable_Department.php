<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_GET['Department_ID'])){
		if(isset($_SESSION['userinfo'])){
			if(isset($_GET['Department_ID'])){
				$Department_ID = $_GET['Department_ID'];
			}else{
				$Department_ID = 0;
			}
			
			mysqli_query($conn,"update tbl_department set Department_Status = 'not active' where Department_ID = '$Department_ID'");
		}
	}
?>