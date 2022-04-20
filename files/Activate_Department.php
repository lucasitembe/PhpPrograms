<?php
	session_start();
	include("./includes/connection.php");
	if(isset($_SESSION['userinfo'])){
		if(isset($_GET['Department_ID'])){
			$Department_ID = $_GET['Department_ID'];
			mysqli_query($conn,"update tbl_department set Department_Status = 'active' where Department_ID = '$Department_ID'");
		}
	}
?>