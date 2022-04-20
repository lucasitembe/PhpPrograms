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

$insert_qr = "INSERT INTO tbl_disease_group_mapping(disease_group_id, disease_id) VALUES ('$disease_group_id', '$disease_Id')";

if(!mysqli_query($conn,$insert_qr)){
	if(mysql_errno()=='1062'){
		echo "Disease Already Exists !";
	}
}else{
	echo "added";
}

?>