<?php
include("./includes/connection.php");
session_start();
if(!isset($_SESSION['userinfo'])){
	exit;
}

if(isset($_GET['disease_group_id'])){
	$disease_group_id = $_GET['disease_group_id'];
}else{
	$disease_group_id = 0;
}

if(isset($_GET['disease_Id'])){
	$disease_Id = $_GET['disease_Id'];
}else{
	$disease_Id = 0;
}

$delete_qr = "DELETE FROM tbl_disease_group_mapping WHERE disease_group_id=$disease_group_id AND disease_id=$disease_Id";

mysqli_query($conn,$delete_qr) or die(mysqli_error($conn));
?>