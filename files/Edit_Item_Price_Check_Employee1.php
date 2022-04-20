<?php
	session_start();
	include("./includes/connection.php");

	//get employee id
    if(isset($_SESSION['userinfo']['Given_Username'])){
        $Given_Username = $_SESSION['userinfo']['Given_Username'];
    }else{
        $Given_Username = 0;
    }

	//get employee id
    if(isset($_SESSION['userinfo']['Given_Password'])){
        $Given_Password = $_SESSION['userinfo']['Given_Password'];
    }else{
        $Given_Password = 0;
    }

    if(isset($_GET['Username1'])){
    	$Username1 = $_GET['Username1'];
    }else{
    	$Username1 = '';
    }

    if(isset($_GET['Password1'])){
    	$Password1 = md5($_GET['Password1']);
    }else{
    	$Password1 = md5('');
    }
    if(strtolower($Given_Username) == strtolower($Username1) && $Given_Password = $Password1){
    	echo "yes";
    }else{
    	echo "no";
    }
?>