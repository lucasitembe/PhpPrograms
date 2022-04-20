<?php
include '../includes/connection.php';

if(isset($_POST['terminal_name']) && isset($_POST['terminal_id'])){
	$name = mysql_real_escape_string($_POST['terminal_name']);
	$id = mysql_real_escape_string($_POST['terminal_id']);

	$query = "INSERT INTO tbl_epay_offline_terminals_config (terminal_name,terminal_id) values ('$name','$id')";
	$result = mysqli_query($conn,$query) or die("Error Occured! ".mysqli_error($conn));

	echo "Terminal  Added Successfully";
}